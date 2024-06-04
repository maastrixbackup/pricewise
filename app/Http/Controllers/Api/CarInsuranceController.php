<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarInsuranceResource;
use App\Models\Feature;
use App\Models\insuranceCoverage;
use App\Models\InsuranceProduct;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarInsuranceController extends Controller
{
    public function index(Request $request)
    {

        $pageno = $request->pageNo ?? 1;
        $postsPerPage = $request->postsPerPage ?? 10;
        $toSkip = (int)$postsPerPage * (int)$pageno - (int)$postsPerPage;

        DB::enableQueryLog();

        $postalCode = $request->postal_code;
        $features = $request->features;
        $provider = $request->current_supplier;
        $ownRiskRange = $request->voluntary_deductible;
        $coverages = $request->coverages;
        $damageFreeYear = $request->damage_free_year;
        $numberOfKilometer = $request->number_of_kilometer;

        $products = InsuranceProduct::where('sub_category', config('constant.subcategory.CarInsurance'))->with('postFeatures', 'categoryDetail', 'coverages.coverageDetails');

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
            ->when($coverages, function ($query) use ($coverages) {
                $query->whereHas('coverages', function ($query) use ($coverages) {
                    $query->whereIn('insurance_coverage_id', $coverages);
                });
            })
            ->when($ownRiskRange, function ($query) use ($ownRiskRange) {
                $query->whereBetween('own_risk', $ownRiskRange);
            })
            ->when($damageFreeYear, function ($query) use ($damageFreeYear) {
                $query->whereBetween('damage_free_year', $damageFreeYear);
            })
            ->when($numberOfKilometer, function ($query) use ($numberOfKilometer) {
                $query->whereBetween('number_of_kilometers', $numberOfKilometer);
            });



        $objFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
            ->from('features as f1')
            ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
            ->where('f1.category', 5)
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



        $filteredProducts = $products->skip($toSkip)->take($postsPerPage)->get();

        $recordsCount = $products->count();

        $providers = $products->count() > 0  ? Provider::where('category', $products->first('category')->category)->get() : [];


        $mergedData = [];

        foreach ($filteredProducts as $product) {
            $formattedProduct = (new CarInsuranceResource($product))->toArray($request);
            $mergedData[] = $formattedProduct;
        }

        $message = $products->count() > 0 ? 'Products retrieved successfully.' : 'Products not found.';


        $coverages = insuranceCoverage::where('subcategory_id', config('constant.subcategory.HomeInsurance'))->get();

        $coverages = $coverages->map(function ($coverage) {
            $coverage->image = asset('storage/images/insurance_coverages/' . $coverage->image);
            return $coverage;
        });

        return response()->json([
            'success' => true,
            'data' => $mergedData,
            'providers' => $providers,
            'coverages' => $coverages,
            'recordsCount' => $recordsCount,
            'filters' => $filters,
            'message' => $message
        ], 200);
    }
}
