<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthInsuranceResource;
use App\Models\Feature;
use Illuminate\Http\Request;
use App\Models\InsuranceProduct;
use App\Models\Provider;
use Illuminate\Support\Facades\DB;

class HealthInsuranceController extends Controller
{
    public function index(Request $request)
    {
        DB::enableQueryLog();

        $postalCode = $request->postal_code;
        $features = $request->features;
        $provider = $request->current_supplier;
        $ownRiskRange = $request->voluntary_deductible;

        $products = InsuranceProduct::where('sub_category',13)->with('postFeatures', 'categoryDetail');
          
        $products->when($postalCode, function ($query) use ($postalCode) {
             $query->whereJsonContains('pin_codes', $postalCode);
        })
        ->when($features, function ($query) use ($features) {
             $query->whereHas('postFeatures', function ($query) use ($features) {
                 $query->whereIn('feature_id', $features);
            });
        })
        ->when($provider, function ($query) use ($provider) {
                $query->where('provider', $provider);
        })
        ->when($ownRiskRange, function ($query) use ($ownRiskRange) {
               $query->whereBetween('own_risk', $ownRiskRange);
        });
  
      
        
       $objFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
           ->from('features as f1')
           ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
           ->where('f1.category',5)
           ->where('f1.is_preferred', 1)
           ->get()
           ->groupBy('parent');
  

   // Initialize an empty array to store the grouped filters
   $filters = [];

   // Loop through the grouped features and convert them to the desired structure
   foreach ($objFeatures as $parent => $items) {
       $filters[] = [
           $parent => $items->map(function ($item) {
               return (object) $item->toArray();
           })->toArray()
       ];
   }

 
        $providers = $products->count() > 0  ? Provider::where('category',$products->first('category')->category)->get(): []  ;
       
        $filteredProducts = $products->get();
           $mergedData = [];

            foreach ($filteredProducts as $product) {
                $formattedProduct = (new HealthInsuranceResource($product))->toArray($request);                
                $mergedData[] = $formattedProduct;
            }

        $message= $products->count() > 0 ? 'Products retrieved successfully.' : 'Products not found.';

            return response()->json([
                'success' => true,
                'data' => $mergedData,
                'providers' => $providers,
                'message' => $message
            ]);
    }
    function healthInsuranceStore(Request $request) {
        try {
            $user_id = $request->input('user_id');
            $user_type = $request->input('user_type');
            $category_id = $request->input('category');
            $sub_category_id = $request->input('sub_category');
            $service_id = $request->input('service_id');
            $postal_code = $request->input('postal_code');
            $service_type = $request->input('service_type');
            $combos = json_encode($request->input('combos'));
            $total_price = $request->input('total_price');
            $discounted_price = $request->input('discounted_price');
            $discount_prct = $request->input('discount_prct');
            $commission_prct = $request->input('commission_prct');
            $commission_amt = $request->input('commission_amt');
            $request_status = $request->input('request_status');
            $advantages = $request->input('advantages');
            
            $data = new UserRequest();
            
            $data->user_id = $user_id;
            $data->user_type = $user_type;
            $data->category = $category_id;
            $data->sub_category = $sub_category_id;
            $data->service_id = $service_id;
            $data->service_type = $service_type;
            $data->combos = $combos;
            $data->postal_code = $postal_code;
            $data->advantages = json_encode($advantages);
            $data->total_price = $total_price;
            $data->discounted_price = $discounted_price;
            $data->discount_prct = $discount_prct;
            $data->commission_prct = $commission_prct;
            $data->commission_amt = $commission_amt;
            $data->request_status = $request_status;
            $data->provider_id = $request->provider_id;
            $data->solar_panels = $request->solar_panels;
            $data->no_gas = $request->no_gas;
            $data->shipping_address = json_encode($request->shipping_address);
            $data->billing_address = json_encode($request->billing_address);
            if ($data->save()) {
                $data->load('userDetails'); 
                $orderNo = $data->id + 1000;
                $data->order_no = $orderNo;
                $name = $data->userDetails?$data->userDetails->name:'';
                
                $data->save();
                if($request->has('advantages')){
                    foreach($request->advantages as $key => $value){
                        PostRequest::updateOrCreate(['request_id' => $data->id, 'key' => $key],
                            [
                                'value' => $value
                            ]);
                    }
                }
                return response()->json(['success' => true, 'message' => 'User request saved successfully'], 200);
            }
            
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
}
