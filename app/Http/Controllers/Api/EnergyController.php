<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\TvInternetProduct;
use App\Models\EnergyProduct;
use App\Models\Provider;
use App\Models\Feature;
use Validator;
use App\Http\Resources\EnergyResource;
use DB;
use Illuminate\Http\Response;

class EnergyController extends BaseController
{
   public function index(Request $request)
    {
        $pageno = $request->pageNo ?? 1;
        $postsPerPage = $request->postsPerPage ?? 10;
        $toSkip = (int)$postsPerPage * (int)$pageno - (int)$postsPerPage;

        $products = EnergyProduct::with('postFeatures'
        , 'prices', 'feedInCost', 'documents', 'providerDetails', 'govtTaxes');
        
        // Filter by postal code
        if ($request->has('postal_code')) {
           $postalCode = json_encode($request->input('postal_code'));    
            // Use whereRaw with JSON_CONTAINS to check if the postal code is present in the pin_codes array
            $products->whereRaw('JSON_CONTAINS(pin_codes, ?)', [$postalCode]);
        }

        // Filter by number of persons
        if ($request->has('no_of_person')) {
            $products->where('no_of_person', '>=', $request->input('no_of_person'));
        }

        // Filter by house type
        // if ($request->has('house_type')) {
        //     $products->where('house_type', $request->input('house_type'));
        // }

        // Filter by current supplier
        if ($request->has('current_supplier')) {
            $products->where('provider', $request->input('current_supplier'));
        }

        // Filter by meter type
        if ($request->has('meter_type')) {
            $products->where('meter_type', $request->input('meter_type'));
        }

        // Filter by gas availability
        if ($request->has('no_gas') && $request->no_gas == 1) {
            $products->where('no_gas', $request->input('no_gas'));
        }

        // Filter by energy label
        if ($request->has('energy_label')) {
            
            $energy_label = json_encode($request->input('energy_label'));    
            // Use whereRaw with JSON_CONTAINS to check if the energy_label is present in the energy_label array
            $products->whereRaw('JSON_CONTAINS(energy_label, ?)', [$energy_label]);
        }     

        if ($request->has('features') && $request->features !== null) {
            $features = $request->input('features');
            $products->whereHas('postFeatures', function ($query) use ($features) {
                $query->whereIn('feature_id', $features);
            });
        }  
        
  
        $filteredProducts = $products->skip($toSkip)->take($postsPerPage)->get();
        $recordsCount = $products->count();
        
            $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
                ->from('features as f1')
                ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
                ->where('f1.category', 16)
                ->where('f1.is_preferred', 1)
                ->get()
                ->groupBy('parent');
       

        // Initialize an empty array to store the grouped filters
        $filters = [];

        // Loop through the grouped features and convert them to the desired structure
        foreach ($objEnergyFeatures as $parent => $items) {
            $filters[] = [
                $parent => $items->map(function ($item) {
                    return (object) $item->toArray();
                })->toArray()
            ];
        }
        //dd($request->advantages);
        $mergedData = [];
        $businessGeneralSettings = getSettings('business_general');
        $totalFeedIn = ($request->advantages['feed_in_normal']??1) + ($request->advantages['feed_in_peak']??1);
        $normal_electric_consume = $request->advantages['normal_electric_consume']??1;
        $peak_electric_consume = $request->advantages['peak_electric_consume']??1;
        $feed_in_normal = $request->advantages['feed_in_normal']??1;
        $feed_in_peak = $request->advantages['feed_in_peak']??1;
        $gas_consume = $request->advantages['gas_consume']??1;
        
        foreach ($filteredProducts as $product) {
            $formattedProduct = (new EnergyResource($product))->toArray($request);                
            if($product->feedInCost){
            $feedInCostRange = json_decode($product->feedInCost->feed_in_cost, true);
            $feedInCostValue = array_filter($feedInCostRange, function($item) use ($totalFeedIn) {
                return $totalFeedIn >= $item['from_range'] && $totalFeedIn <= $item['to_range'];
            });
            }
            if (!empty($feedInCostValue)) {
                $feedInCostValue = reset($feedInCostValue);
                
            }
            $feedInCostRangeValue = $feedInCostValue['amount']??0;
            $reductionCostElectric = $product->reduction_of_energy_tax??$businessGeneralSettings['reduction_of_energy_tax'];
            $govt_levies_gas = $product->government_levies_gas??$businessGeneralSettings['governement_levies_gas'];

            if($request->no_gas === 1){
                $gas_consume = 0;
                $del_gas = 0;
                $govt_levies_gas = 0;
                $net_gas = 0;
            }else{
                $gas_consume = $request->advantages['gas_consume']??1;
                $del_gas = 1;                
                $net_gas = 1;
            }
            if($request->solar_panels > 0){
                

            }else{
                $feed_in_normal = 0;
                $feed_in_peak = 0;
                $feedInCostRangeValue = 0;
            }
            
            $total = (($product->normal_electric_price??$product->prices->electric_rate) * $normal_electric_consume)
            + (($product->peak_electric_price??$product->prices->off_peak_electric_rate) * $peak_electric_consume)
            + $product->delivery_cost_electric
            + ($product->network_cost_electric??0)
            + $feedInCostRangeValue
            - ($product->feedInCost ? $product->feedInCost->normal_return_delivery * $feed_in_normal : 0)
            - ($product->feedInCost ? $product->feedInCost->off_peak_return_delivery * $feed_in_peak : 0)
            - $reductionCostElectric
            + ($gas_consume * $product->prices->gas_rate)          
            + ($product->delivery_cost_gas * $del_gas)
            + ($govt_levies_gas * $govt_levies_gas)
            + (($product->network_cost_gas??0) * $net_gas)
            - $product->cashback;
            $advantages = ['gas_consume' => $gas_consume,
             'normal_electric_consume' => $normal_electric_consume,
             'peak_electric_consume' => $peak_electric_consume,
             'feed_in_peak' => $feed_in_peak,
             'feed_in_normal' => $feed_in_normal,
            ];
            $formattedProduct['total'] = $total;
            $formattedProduct['advantages'] = $advantages;
            
            $mergedData[] = $formattedProduct;
        }
        // Sort the merged data based on the total values
        usort($mergedData, function ($a, $b) {
            return $a['total'] <=> $b['total'];
        });
        if($request->has('callFromExclusiveDeal')){
            return [$mergedData , $filters];
        }
 
        // Return the merged data
        return response()->json([
            'success' => true,
            'data' => $mergedData,
            'filters' => $filters,
            //'advantages' => $advantages,
            'recordsCount'=> $recordsCount,
            'message' => 'Products retrieved successfully.'
        ]);

    }

    public function topEnergyDeals(Request $request)
    {
        $products = EnergyProduct::with('postFeatures', 'prices', 'feedInCost', 'documents', 'providerDetails', 'govtTaxes');
        
        // Filter by postal code
        if ($request->has('postal_code')) {
           $postalCode = json_encode($request->input('postal_code'));    
            // Use whereRaw with JSON_CONTAINS to check if the postal code is present in the pin_codes array
            $products->whereRaw('JSON_CONTAINS(pin_codes, ?)', [$postalCode]);
        }

        // Filter by number of persons
        if ($request->has('no_of_person')) {
            $products->where('no_of_person', '<=', $request->input('no_of_person'));
        }

        // Filter by house type
        // if ($request->has('house_type')) {
        //     $products->where('house_type', $request->input('house_type'));
        // }

        // Filter by current supplier
        if ($request->has('current_supplier')) {
            $products->where('provider', $request->input('current_supplier'));
        }

        // Filter by meter type
        if ($request->has('meter_type')) {
            $products->where('meter_type', $request->input('meter_type'));
        }

        // Filter by gas availability
        if ($request->has('no_gas') && $request->no_gas == 1) {
            $products->where('no_gas', $request->input('no_gas'));
        }

        // Filter by energy label
        if ($request->has('energy_label')) {
            
            $energy_label = json_encode($request->input('energy_label'));    
            // Use whereRaw with JSON_CONTAINS to check if the energy_label is present in the energy_label array
            $products->whereRaw('JSON_CONTAINS(energy_label, ?)', [$energy_label]);
        }     

        if ($request->has('features')) {
            $features = $request->input('features');
            $products->whereHas('postFeatures', function ($query) use ($features) {
                $query->whereIn('feature_id', $features);
            });
        }    
        // Retrieve filtered products and return response
        $filteredProducts = $products->get();
        
            $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
                ->from('features as f1')
                ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
                ->where('f1.category', 16)
                ->where('f1.is_preferred', 1)
                ->get()
                ->groupBy('parent');
       
        $filters = [];
        foreach ($objEnergyFeatures as $parent => $items) {
            $filters[] = [
                $parent => $items->map(function ($item) {
                    return (object) $item->toArray();
                })->toArray()
            ];
        }

        $mergedData = [];
        $businessGeneralSettings = getSettings('business_general');
        $totalFeedIn = ($request->advantages['feed_in_normal']??500) + ($request->advantages['feed_in_peak']??300);
        $normal_electric_consume = $request->advantages['normal_electric_consume']??500;
        $peak_electric_consume = $request->advantages['peak_electric_consume']??300;
        $feed_in_normal = $request->advantages['feed_in_normal']??500;
        $feed_in_peak = $request->advantages['feed_in_peak']??300;
        $gas_consume = $request->advantages['gas_consume']??200;

            foreach ($filteredProducts as $product) {
                $formattedProduct = (new EnergyResource($product))->toArray($request);
                
                if($product->feedInCost){
                $feedInCostRange = json_decode($product->feedInCost->feed_in_cost, true);
                $feedInCostValue = array_filter($feedInCostRange, function($item) use ($totalFeedIn) {
                    return $totalFeedIn >= $item['from_range'] && $totalFeedIn <= $item['to_range'];
                });
                }
                if (!empty($feedInCostValue)) {
                    $feedInCostValue = reset($feedInCostValue);
                    
                }
                $feedInCostRangeValue = $feedInCostValue['amount']??0;
                $reductionCostElectric = $product->reduction_of_energy_tax??$businessGeneralSettings['reduction_of_energy_tax'];
                $govt_levies_gas = $product->government_levies_gas??$businessGeneralSettings['governement_levies_gas'];
                if($request->no_gas == 1){
                    $gas_consume = 0;
                    $del_gas = 0;
                    $govt_levies_gas = 0;
                    $net_gas = 0;
                }else{
                    $gas_consume = $request->advantages['gas_consume']??200;
                    $del_gas = 1;                    
                    $net_gas = 1;
                }
                if($request->solar_panels > 0){
                    

                }else{
                    $feed_in_normal = 0;
                    $feed_in_peak = 0;
                    $feedInCostRangeValue = 0;
                }
                
                
                $total = ($product->normal_electric_price??$product->prices->electric_rate) * $normal_electric_consume
                + ($product->peak_electric_price??$product->prices->off_peak_electric_rate) * $peak_electric_consume
                + ($product->delivery_cost_electric??0)
                + ($product->network_cost_electric??0)
                + $feedInCostRangeValue
                - ($product->feedInCost ? $product->feedInCost->normal_return_delivery * $feed_in_normal : 0)
                - ($product->feedInCost ? $product->feedInCost->off_peak_return_delivery * $feed_in_peak : 0)
                - $reductionCostElectric
                + ($gas_consume * ($product->prices?$product->prices->gas_rate:0))           
                + ($product->delivery_cost_gas * $del_gas)
                + ($govt_levies_gas * $govt_levies_gas)
                + (($product->network_cost_gas??0) * $net_gas)
                - $product->cashback;
                $advantages = ['gas_consume' => $gas_consume,
                 'normal_electric_consume' => $normal_electric_consume,
                 'peak_electric_consume' => $peak_electric_consume,
                 'feed_in_peak' => $feed_in_peak,
                 'feed_in_normal' => $feed_in_normal,
                ];
                $formattedProduct['total'] = $total;
                $formattedProduct['advantages'] = $advantages;
                $mergedData[] = $formattedProduct;
            }
            // Sort the merged data based on the total values
            usort($mergedData, function ($a, $b) {
                return $a['total'] <=> $b['total'];
            });
            
            // Return the merged data
            return response()->json([
                'success' => true,
                'data' => $mergedData,
                'filters' => $filters,
                'message' => 'Products retrieved successfully.'
            ]);

    }

    public function energyCompare(Request $request)
    {
        $compareIds = $request->compare_ids;

        if (!empty($compareIds)) {
            $products = EnergyProduct::with('postFeatures', 'prices', 'feedInCost', 'documents', 'providerDetails');
            $filteredProducts = $products->whereIn('id', $compareIds)->get();
        
            $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
                ->from('features as f1')
                ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
                ->where('f1.category', 16)
                ->where('f1.is_preferred', 1)
                ->get()
                ->groupBy('parent');
       

        // Initialize an empty array to store the grouped filters
        $filters = [];

        // Loop through the grouped features and convert them to the desired structure
        foreach ($objEnergyFeatures as $parent => $items) {
            $filters[] = [
                $parent => $items->map(function ($item) {
                    return (object) $item->toArray();
                })->toArray()
            ];
        }
        //dd($request->advantages);
        $mergedData = [];
        $businessGeneralSettings = getSettings('business_general');
        $totalFeedIn = ($request->advantages['feed_in_normal']??1) + ($request->advantages['feed_in_peak']??1);
        $normal_electric_consume = $request->advantages['normal_electric_consume']??1;
        $peak_electric_consume = $request->advantages['peak_electric_consume']??1;
        $feed_in_normal = $request->advantages['feed_in_normal']??1;
        $feed_in_peak = $request->advantages['feed_in_peak']??1;
        $gas_consume = $request->advantages['gas_consume']??1;
        
        foreach ($filteredProducts as $product) {
            $formattedProduct = (new EnergyResource($product))->toArray($request);                
            if($product->feedInCost){
            $feedInCostRange = json_decode($product->feedInCost->feed_in_cost, true);
            $feedInCostValue = array_filter($feedInCostRange, function($item) use ($totalFeedIn) {
                return $totalFeedIn >= $item['from_range'] && $totalFeedIn <= $item['to_range'];
            });
            }
            if (!empty($feedInCostValue)) {
                $feedInCostValue = reset($feedInCostValue);
                
            }
            $feedInCostRangeValue = $feedInCostValue['amount']??0;
            $reductionCostElectric = $product->reduction_of_energy_tax??$businessGeneralSettings['reduction_of_energy_tax'];
            $govt_levies_gas = $product->government_levies_gas??$businessGeneralSettings['governement_levies_gas'];

            if($request->no_gas === 1){
                $gas_consume = 0;
                $del_gas = 0;
                $govt_levies_gas = 0;
                $net_gas = 0;
            }else{
                $gas_consume = $request->advantages['gas_consume']??1;
                $del_gas = 1;                
                $net_gas = 1;
            }
            if($request->solar_panels > 0){
                

            }else{
                $feed_in_normal = 0;
                $feed_in_peak = 0;
                $feedInCostRangeValue = 0;
            }
            
            $total = (($product->normal_electric_price??$product->prices->electric_rate) * $normal_electric_consume)
            + (($product->peak_electric_price??$product->prices->off_peak_electric_rate) * $peak_electric_consume)
            + $product->delivery_cost_electric
            + ($product->network_cost_electric??0)
            + $feedInCostRangeValue
            - ($product->feedInCost ? $product->feedInCost->normal_return_delivery * $feed_in_normal : 0)
            - ($product->feedInCost ? $product->feedInCost->off_peak_return_delivery * $feed_in_peak : 0)
            - $reductionCostElectric
            + ($gas_consume * $product->prices->gas_rate)          
            + ($product->delivery_cost_gas * $del_gas)
            + ($govt_levies_gas * $govt_levies_gas)
            + (($product->network_cost_gas??0) * $net_gas)
            - $product->cashback;
            $advantages = ['gas_consume' => $gas_consume,
             'normal_electric_consume' => $normal_electric_consume,
             'peak_electric_consume' => $peak_electric_consume,
             'feed_in_peak' => $feed_in_peak,
             'feed_in_normal' => $feed_in_normal,
            ];
            $formattedProduct['total'] = $total;
            $formattedProduct['advantages'] = $advantages;
            
            $mergedData[] = $formattedProduct;
        }
        
            usort($mergedData, function ($a, $b) {
                return $a['total'] <=> $b['total'];
            });
         return response()->json([
            'success' => true,
            'data' => $mergedData,
            'filters' => $filters,
            //'advantages' => $advantages,
            'message' => 'Products retrieved successfully.'
        ]);
        }else {
            return $this->sendError('No comparison IDs provided.', [], 400);
        }
    }


    public function getSupliers()
    {
        $providers = Provider::get();
    
        return $this->sendResponse($providers, 'Suppliers retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $product = TvInternetProduct::create($input);
   
        return $this->sendResponse(new EnergyResource($product), 'Product created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = TvInternetProduct::find($id);
  
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
   
        return $this->sendResponse(new EnergyResource($product), 'Product retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TvInternetProduct $product)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();
   
        return $this->sendResponse(new EnergyResource($product), 'Product updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TvInternetProduct $product)
    {
        $product->delete();
   
        return $this->sendResponse([], 'Product deleted successfully.');
    }
}