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
}
