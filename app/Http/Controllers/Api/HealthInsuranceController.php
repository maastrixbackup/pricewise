<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\InsuranceProduct;
class HealthInsuranceController extends Controller
{
    public function index(Request $request)
    {
        \DB::enableQueryLog();
        $products = InsuranceProduct::where('sub_category',$request->sub_category)->with('postFeatures', 'categoryDetail','providerDetails');
        return $products->get();
        //Filter by postal code
        if ($request->has('postal_code')) {
           $postalCode = json_encode($request->input('postal_code'));    
            // Use whereRaw with JSON_CONTAINS to check if the postal code is present in the pin_codes array
            $products->whereRaw('JSON_CONTAINS(pin_codes, ?)', [$postalCode]);
        }
        
        //dd($products->get());
        $filteredProducts = $products->get();
        if ($filteredProducts->isNotEmpty()) {
        $objFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
            ->from('features as f1')
            ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
            ->where('f1.category', $filteredProducts[0]->category)
            ->where('f1.is_preferred', 1)
            ->get()
            ->groupBy('parent');
            } else {
            
            $objFeatures = collect(); // Or any other default value or action
        }

        // $mergedData = [];

            foreach ($filteredProducts as $product) {
                $formattedProduct = (new InternetTvResource($product))->toArray($request);                
                $mergedData[] = $formattedProduct;
            }
  
            return response()->json([
                'success' => true,
                'data' => $mergedData,
                'filters' => $objFeatures,
                'message' => 'Products retrieved successfully.'
            ]);
    }
}
