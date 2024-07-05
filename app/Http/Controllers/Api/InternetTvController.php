<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\TvInternetProduct;
use App\Models\Feature;
use Validator;
use App\Http\Resources\InternetTvResource;
use DB;

class InternetTvController extends BaseController
{
   public function index(Request $request)
    {
        \DB::enableQueryLog();
        $products = TvInternetProduct::with('postFeatures', 'documents', 'providerDetails');

        $pageno = $request->pageNo ?? 1;
        $postsPerPage = $request->postsPerPage ?? 10;
        $toSkip = (int)$postsPerPage * (int)$pageno - (int)$postsPerPage;

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
        if ($request->has('house_type')) {
            $products->where('house_type', $request->input('house_type'));
        }

        // Filter by current supplier
        // if ($request->has('current_supplier')) {
        //     $products->where('provider', $request->input('current_supplier'));
        // }


        if ($request->has('features') && $request->feature !== null) {
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

            $objFeatures = collect(); // Or any other default value or action
        }

        $mergedData = [];

            foreach ($filteredProducts as $product) {
                $formattedProduct = (new InternetTvResource($product))->toArray($request);   
                $mergedData[] = $formattedProduct;
            }
            if($request->has('callFromExclusiveDeal')){
                return [$mergedData , $objFeatures];
            }
            return response()->json([
                'success' => true,
                'data' => $mergedData,
                'filters' => $objFeatures,
                'recordsCount'=> $recordsCount,
                'message' => 'Products retrieved successfully.'
            ]);
    }

    public function internetCompare(Request $request)
    {
        $compareIds = $request->compare_ids;

        if (!empty($compareIds)) {
            $products = TvInternetProduct::with('postFeatures', 'documents', 'providerDetails');
            $filteredProducts = $products->whereIn('id', $compareIds)->get();

            if ($filteredProducts->isNotEmpty()) {
                $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
                    ->from('features as f1')
                    ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
                    ->where('f1.category', $filteredProducts[0]->category)
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

                $filteredProductsFormatted = InternetTvResource::collection($filteredProducts);

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



                return response()->json([
                    'success' => true,
                    'data'    => $filteredProductsFormatted,
                    'filters' =>  $filters,
                    'message' => 'Products retrieved successfully.',
                ], 200);


            } else {
                return $this->sendError('No products found -for comparison.', [], 404);
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
}
