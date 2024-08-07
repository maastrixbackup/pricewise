<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\TvInternetProduct;
use App\Models\EnergyProduct;
use App\Models\Provider;
use App\Models\Feature;
use Dotenv\Validator;
use App\Http\Resources\EnergyResource;
use Illuminate\Support\Facades\DB;

class InsuranceController extends BaseController
{
   public function index(Request $request)
    {
        $products = EnergyProduct::with('postFeatures', 'prices', 'feedInCost');

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
        if ($request->has('house_type')) {
            $products->where('house_type', $request->input('house_type'));
        }

        // Filter by current supplier
        if ($request->has('current_supplier')) {
            $products->where('provider', $request->input('current_supplier'));
        }

        // Filter by meter type
        if ($request->has('meter_type')) {
            $products->where('meter_type', $request->input('meter_type'));
        }

        // Filter by gas availability
        if ($request->has('no_gas')) {
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
        $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No Parent") as parent'))
            ->from('features as f1')
            ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
            ->where('f1.category', $filteredProducts[0]->category)
            ->where('f1.is_preferred', 1)
            ->get()
            ->groupBy('parent');
            $filteredProductsFormatted = EnergyResource::collection($filteredProducts);

            // Merge filteredProductsFormatted and objEnergyFeatures
            $mergedData = $filteredProductsFormatted->merge(['energy_filters' => $objEnergyFeatures]);

            // Return the merged data
            return $this->sendResponse($mergedData, 'Products and filters retrieved successfully.');
    }

    public function energyCompare(Request $request)
    {
        $compareIds = $request->compare_ids;

        if (!empty($compareIds)) {
            $products = EnergyProduct::with('postFeatures', 'prices', 'feedInCost');
            $filteredProducts = $products->whereIn('id', $compareIds)->get();

            if ($filteredProducts->isNotEmpty()) {
                $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No Parent") as parent'))
                    ->from('features as f1')
                    ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
                    ->where('f1.category', $filteredProducts[0]->category)
                    ->where('f1.is_preferred', 1)
                    ->get()
                    ->groupBy('parent');

                $filteredProductsFormatted = EnergyResource::collection($filteredProducts);

                // Merge filteredProductsFormatted and objEnergyFeatures
                $mergedData = $filteredProductsFormatted->merge(['energy_filters' => $objEnergyFeatures]);

                // Return the merged data
                return $this->sendResponse($mergedData, 'Products retrieved successfully.');
            } else {
                return $this->sendError('No products found for comparison.', [], 404);
            }
        } else {
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
