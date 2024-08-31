<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\TvInternetProduct;
use App\Models\Feature;
use App\Models\FeeSetting;
use Dotenv\Validator;
use App\Http\Resources\InternetTvResource;
use App\Models\Provider;
use Illuminate\Support\Facades\DB;

class InternetTvController extends BaseController
{
    public function index(Request $request)
    {
        DB::enableQueryLog();
        $products = TvInternetProduct::with('postFeatures', 'documents', 'providerDetails');

        // Clone the query for calculating min and max prices
        $priceQuery = clone $products;
        $minPrice = $priceQuery->min('price');
        $maxPrice = $priceQuery->max('price');

        $pageno = $request->input('pageNo', 1);
        $postsPerPage = $request->input('postsPerPage', 10);
        $toSkip = $postsPerPage * ($pageno - 1);

        // Apply filters based on the request parameters
        if ($request->has('postal_code')) {
            $postalCode = json_encode($request->input('postal_code'));
            $products->whereRaw('JSON_CONTAINS(pin_codes, ?)', [$postalCode]);
        }

        if ($request->has('network_type')) {
            $networkType = json_encode($request->input('network_type'));
            $products->whereRaw('JSON_CONTAINS(network_type, ?)', [$networkType]);
        }

        if ($request->has('tv_packages')) {
            $tvPackages = json_encode($request->input('tv_packages'));
            $products->whereRaw('JSON_CONTAINS(tv_packages, ?)', [$tvPackages]);
        }

        if ($request->has('no_of_person')) {
            $products->where('no_of_person', '>=', $request->input('no_of_person'));
        }

        if ($request->has('house_type')) {
            $products->where('house_type', $request->input('house_type'));
        }

        if ($request->has('current_supplier')) {
            $products->where('provider', $request->input('current_supplier'));
        }

        if ($request->has('no_of_receivers')) {
            $products->where('no_of_receivers', '>=', $request->input('no_of_receivers'));
        }

        if ($request->has('features') && !is_null($request->features)) {
            $features = $request->input('features');
            $products->whereHas('postFeatures', function ($query) use ($features) {
                $query->whereIn('feature_id', $features);
            });
        }

        $filteredProducts = $products->skip($toSkip)->take($postsPerPage)->get();
        $recordsCount = $products->count();

        if ($filteredProducts->isNotEmpty()) {
            $objFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
                ->from('features as f1')
                ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
                ->where('f1.category', $filteredProducts[0]->category)
                ->where('f1.is_preferred', 1)
                ->get()
                ->groupBy('parent');
        } else {
            $objFeatures = collect();
        }

        $mergedData = $filteredProducts->map(function ($product) use ($request) {
            return (new InternetTvResource($product))->toArray($request);
        });

        if ($request->has('callFromExclusiveDeal')) {
            return [$mergedData, $objFeatures];
        }

        $nominalFee = FeeSetting::where('category_id', config('constant.category.Internet & Tv'))->first()->amount;

        return response()->json([
            'success' => true,
            'data' => $mergedData,
            'filters' => $objFeatures,
            'recordsCount' => $recordsCount,
            'nominalFees' => $nominalFee,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'message' => 'Products retrieved successfully.'
        ]);
    }

    public function internetCompare(Request $request)
    {
        $compareIds = $request->compare_ids;

        if (!empty($compareIds)) {
            $products = TvInternetProduct::with('postFeatures', 'documents', 'providerDetails')
                ->whereIn('id', $compareIds)
                ->get();

            if ($products->isNotEmpty()) {
                $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
                    ->from('features as f1')
                    ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
                    ->where('f1.category', $products[0]->category)
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

                $filteredProductsFormatted = InternetTvResource::collection($products);

                $noParentFilter = null;
                foreach ($filters as $index => $filter) {
                    if (isset($filter['No_Parent'])) {
                        $noParentFilter = $filter;
                        unset($filters[$index]);
                        break;
                    }
                }

                // Insert the "No_Parent" filter at the beginning of the array of filters
                if ($noParentFilter !== null) {
                    array_unshift($filters, $noParentFilter);
                }

                $nominalFee = FeeSetting::where('category_id', config('constant.category.Internet & Tv'))->first()->amount;

                return response()->json([
                    'success' => true,
                    'data'    => $filteredProductsFormatted,
                    'filters' => $filters,
                    'nominalFees' => $nominalFee,
                    'message' => 'Products retrieved successfully.',
                ]);
            } else {
                return $this->sendError('No products found for comparison.', []);
            }
        } else {
            return $this->sendError('No comparison IDs provided.', []);
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

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = TvInternetProduct::create($input);

        return $this->sendResponse(new InternetTvResource($product), 'Product created successfully.');
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

        return $this->sendResponse(new InternetTvResource($product), 'Product retrieved successfully.');
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

        $request->validate([
            'name' => 'required',
            'detail' => 'required'
        ]);

        // if ($validator->fails()) {
        //     return $this->sendError('Validation Error.', $validator->errors());
        // }

        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();

        return $this->sendResponse(new InternetTvResource($product), 'Product updated successfully.');
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


    // public function index(Request $request)
    // {
    //     DB::enableQueryLog();
    //     $products = TvInternetProduct::with('postFeatures', 'documents', 'providerDetails');

    //     // Clone the query for calculating min and max prices
    //     $priceQuery = clone $products;
    //     $minPrice = $priceQuery->min('price');
    //     $maxPrice = $priceQuery->max('price');

    //     $pageno = $request->pageNo ?? 1;
    //     $postsPerPage = $request->postsPerPage ?? 10;
    //     $toSkip = (int)$postsPerPage * ((int)$pageno - 1);

    //     // Apply filters based on the request parameters
    //     if ($request->has('postal_code')) {
    //         $postalCode = json_encode($request->input('postal_code'));
    //         $products->whereRaw('JSON_CONTAINS(pin_codes, ?)', [$postalCode]);
    //     }

    //     if ($request->has('network_type')) {
    //         $networkType = json_encode($request->input('network_type'));
    //         $products->whereRaw('JSON_CONTAINS(network_type, ?)', [$networkType]);
    //     }

    //     if ($request->has('tv_packages')) {
    //         $tvPackages = json_encode($request->input('tv_packages'));
    //         $products->whereRaw('JSON_CONTAINS(tv_packages, ?)', [$tvPackages]);
    //     }

    //     if ($request->has('no_of_person')) {
    //         $products->where('no_of_person', '>=', $request->input('no_of_person'));
    //     }

    //     if ($request->has('house_type')) {
    //         $products->where('house_type', $request->input('house_type'));
    //     }

    //     if ($request->has('current_supplier')) {
    //         $products->where('provider', $request->input('current_supplier'));
    //     }

    //     if ($request->has('no_of_receivers')) {
    //         $products->where('no_of_receivers', '>=', $request->input('no_of_receivers'));
    //     }

    //     if ($request->has('features') && !is_null($request->features)) {
    //         $features = $request->input('features');
    //         $products->whereHas('postFeatures', function ($query) use ($features) {
    //             $query->whereIn('feature_id', $features);
    //         });
    //     }




    //     $filteredProducts = $products->skip($toSkip)->take($postsPerPage)->get();
    //     $recordsCount = $products->count();

    //     if ($filteredProducts->isNotEmpty()) {
    //         $objFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
    //             ->from('features as f1')
    //             ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
    //             ->where('f1.category', $filteredProducts[0]->category)
    //             ->where('f1.is_preferred', 1)
    //             ->get()
    //             ->groupBy('parent');
    //     } else {
    //         $objFeatures = collect();
    //     }

    //     $mergedData = $filteredProducts->map(function ($product) use ($request) {
    //         return (new InternetTvResource($product))->toArray($request);
    //     });

    //     if ($request->has('callFromExclusiveDeal')) {
    //         return [$mergedData, $objFeatures];
    //     }
    //     $nominalFee = FeeSetting::where('category_id', config('constant.category.Internet & Tv'))->first()->amount;

    //     return response()->json([
    //         'success' => true,
    //         'data' => $mergedData,
    //         'filters' => $objFeatures,
    //         'recordsCount' => $recordsCount,
    //         'nominalFees' => $nominalFee,
    //         'minPrice' => $minPrice,
    //         'maxPrice' => $maxPrice,
    //         'message' => 'Products retrieved successfully.'
    //     ]);
    // }


    // public function internetCompare(Request $request)
    // {
    //     $compareIds = $request->compare_ids;

    //     if (!empty($compareIds)) {
    //         $products = TvInternetProduct::with('postFeatures', 'documents', 'providerDetails');
    //         $filteredProducts = $products->whereIn('id', $compareIds)->get();

    //         if ($filteredProducts->isNotEmpty()) {
    //             $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
    //                 ->from('features as f1')
    //                 ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
    //                 ->where('f1.category', $filteredProducts[0]->category)
    //                 ->where('f1.is_preferred', 1)
    //                 ->get()
    //                 ->groupBy('parent');

    //             // Initialize an empty array to store the grouped filters
    //             $filters = [];

    //             // Loop through the grouped features and convert them to the desired structure
    //             foreach ($objEnergyFeatures as $parent => $items) {
    //                 $filters[] = [
    //                     $parent => $items->map(function ($item) {
    //                         return (object) $item->toArray();
    //                     })->toArray()
    //                 ];
    //             }

    //             $filteredProductsFormatted = InternetTvResource::collection($filteredProducts);

    //             $noParentFilter = null;
    //             foreach ($filters as $index => $filter) {
    //                 if (isset($filter['No_Parent'])) {
    //                     $noParentFilter = $filter;
    //                     unset($filters[$index]);
    //                     break;
    //                 }
    //             }

    //             // Insert the "No_Parent" filter at the beginning of the array of filters
    //             if ($noParentFilter !== null) {
    //                 array_unshift($filters, $noParentFilter);
    //             }


    //             $nominalFee = FeeSetting::where('category_id', config('constant.category.Internet & Tv'))->first()->amount;

    //             return response()->json([
    //                 'success' => true,
    //                 'data'    => $filteredProductsFormatted,
    //                 'filters' =>  $filters,
    //                 'nominalFees' => $nominalFee,
    //                 'message' => 'Products retrieved successfully.',
    //             ], 200);
    //         } else {
    //             return $this->sendError('No products found -for comparison.', [], 404);
    //         }
    //     } else {
    //         return $this->sendError('No comparison IDs provided.', [], 400);
    //     }
    // }
}
