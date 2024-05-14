<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthInsuranceResource;
use Illuminate\Http\Request;
use DB;
use App\Models\InsuranceProduct;
use App\Models\Provider;

class HealthInsuranceController extends Controller
{
    public function index(Request $request)
    {
        \DB::enableQueryLog();
        $postalCode = $request->postal_code;
        $provider = 
        $products = InsuranceProduct::where('sub_category',$request->sub_category)->with('postFeatures', 'categoryDetail');
          //Filter by postal code
          $products->when($postalCode, function ($query) use ($postalCode) {
             $query->whereJsonContains('pin_codes', $postalCode);
        });
      
        //  $category_id = $products->first('category')->category;
        //  $providers = Provider::where('category',$category_id)->get();

    
        
        
        $filteredProducts = $products->get();
           $mergedData = [];

            foreach ($filteredProducts as $product) {
                $formattedProduct = (new HealthInsuranceResource($product))->toArray($request);                
                $mergedData[] = $formattedProduct;
            }
       
            return response()->json([
                'success' => true,
                'data' => $mergedData,
                'filters' => [],
                'message' => 'Products retrieved successfully.'
            ]);
    }
}
