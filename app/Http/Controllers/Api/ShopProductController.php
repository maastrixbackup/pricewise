<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductDetailsResource;
use App\Http\Resources\ShopProductResource;
use App\Models\DealProduct;
use App\Models\ProductCategory;
use App\Models\ProductPromotion;
use App\Models\ProductRating;
use App\Models\ShopProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ShopProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Initialize the product query with necessary relationships
        $productsQuery = ShopProduct::with('categoryDetails', 'brandDetails', 'images', 'colorDetails');

        $minPrice = 0;
        $maxPrice = 0;
        if ($productsQuery) {
            // Calculate min and max prices only if filters are applied
            $filteredProductsQuery = clone $productsQuery;
            $minPrice = $filteredProductsQuery->min('sell_price');
            $maxPrice = $filteredProductsQuery->max('sell_price');
        }

        // Filter By Category
        if ($request->has('category_id')) {
            $productsQuery->where('category_id', $request->input('category_id'));
        }


        // Filter By Color
        if ($request->has('color_id')) {
            $productsQuery->where('color_id', $request->input('color_id'));
        }

        // Filter By Product Type
        if ($request->has('product_type')) {
            $productsQuery->where('product_type', $request->input('product_type'));
        }

        // Filter By Selling Price
        if ($request->has('sell_price')) {
            $productsQuery->where('sell_price', '>=', $request->input('sell_price'));
        }


        // Retrieve the final filtered products
        $products = $productsQuery->get();

        $filteredProducts = [];
        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                $filterData = (new ShopProductResource($product))->toArray($request);
                $filteredProducts[] = $filterData;
            }
        }

        $newArrivalProducts = ShopProduct::with('categoryDetails', 'brandDetails', 'colorDetails', 'images')
            ->where('new_arrival', 1)
            ->get();

        $filteredNewArrivals = [];
        if ($newArrivalProducts->isNotEmpty()) {
            foreach ($newArrivalProducts as $product) {
                $newArrivalData = (new ShopProductResource($product))->toArray($request);
                $filteredNewArrivals[] = $newArrivalData;
            }
        }

        $productCategory = ProductCategory::orderBy('id', 'asc')->get();

        $filteredCat = [];
        if ($productCategory->isNotEmpty()) {
            foreach ($productCategory as $category) {
                $categoryWithProduct = (new ProductCategoryResource($category))->toArray($request);
                $filteredCat[] = $categoryWithProduct;
            }
        }

        $promotionProduct = ProductPromotion::where('status', 'active')->get();
        if ($promotionProduct->isNotEmpty()) {
            $promotionProduct = $promotionProduct->map(function ($promotion) {
                return [
                    'id' => $promotion->id,
                    'title' => $promotion->title,
                    'sub_title' => $promotion->sub_title,
                    'description' => $promotion->description,
                    'image' => asset('storage/images/shops/' . $promotion->image),
                    'btn_text' => $promotion->btn_text,
                    'btn_url' => $promotion->btn_url,
                ];
            });
        }

        $dealsProduct = DealProduct::where('status', 'active')->get();
        if ($dealsProduct->isNotEmpty()) {
            $dealsProduct = $dealsProduct->map(function ($deals) {
                return [
                    'id' => $deals->id,
                    'title' => $deals->title,
                    'slug' => $deals->slug,
                    'valid_till' => $deals->valid_till,
                    'image' => asset('storage/images/shops/' . $deals->image),
                    'category' => $deals->categoryDetails
                ];
            });
        }

        return response()->json([
            'status' => true,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'finalProducts' => $filteredProducts,
            'newArrivals' => $filteredNewArrivals,
            'deals' => $dealsProduct,
            'promotion' => $promotionProduct,
            'catWithProduct' => $filteredCat,
        ], 200);
    }


    public function productDetails(Request $request, $id)
    {

        // $id = encrypt($id);
        // $id = decrypt($id);
        $product = ShopProduct::with('categoryDetails', 'brandDetails', 'images', 'colorDetails')->find($id);

        $similarProducts = ShopProduct::with('categoryDetails', 'brandDetails', 'images', 'colorDetails')->take(10)->get();

        $featuredProduct = [];
        if ($similarProducts->isNotEmpty()) {
            foreach ($similarProducts as $pdt) {
                $filterData = (new ShopProductResource($pdt))->toArray($request);
                $featuredProduct[] = $filterData;
            }
        }

        if (!$product) {
            // Handle the case when the product is not found, e.g., throw an exception or return a 404 response.
            abort(404, 'Product not found');
        }
        $filteredProduct = new ProductDetailsResource($product);

        $rating = ProductRating::where('product_id', $id)->get();
        $ratingCount = [
            '5 Star' => $rating->where('rating', 5)->count(),
            '4 Star' => $rating->where('rating', 4)->count(),
            '3 Star' => $rating->where('rating', 3)->count(),
            '2 Star' => $rating->where('rating', 2)->count(),
            '1 Star' => $rating->where('rating', 1)->count(),
        ];


        return response()->json([
            'status' => true,
            'productDetails' => $filteredProduct,
            'featuredProduct' => $featuredProduct,
            'ratingCountData' => $ratingCount,
        ], 200);
    }


    public function storeProductReviews(Request $request)
    {

        $chkRating = ProductRating::where(['product_id' => $request->product_id, 'user_id' => $request->user_id])->first();
        if ($chkRating) {
            return response()->json([
                'status' => false,
                'message' => 'You Have already rated the product.',
            ], 400);
        }


        DB::beginTransaction();
        try {
            $rating = new ProductRating();
            $rating->product_id = $request->product_id;
            $rating->user_id = $request->user_id;
            $rating->name = $request->name;
            $rating->email = $request->email;
            $rating->rating = $request->rating;
            $rating->review = $request->review;

            if ($rating->save()) {
                // Calculate ratings after saving the new rating
                $ratings = ProductRating::where('product_id', $request->product_id)
                    // ->with('productDetails', 'userDetails')
                    ->get();

                $review = $ratings->count();
                $rate = 0.0;

                if ($review > 0) {
                    $totalRating = $ratings->sum('rating');
                    $rate = $totalRating / $review;
                }

                $ratingCount = [
                    '5 Star' => $ratings->where('rating', 5)->count(),
                    '4 Star' => $ratings->where('rating', 4)->count(),
                    '3 Star' => $ratings->where('rating', 3)->count(),
                    '2 Star' => $ratings->where('rating', 2)->count(),
                    '1 Star' => $ratings->where('rating', 1)->count(),
                ];

                $ratings = $ratings->map(function ($rating) {
                    return [
                        'id' => $rating->id,
                        'user_id' => $rating->user_id,
                        'product_id' => $rating->product_id,
                        'name' => $rating->name,
                        'email' => $rating->email,
                        'rating' => $rating->rating,
                        'review' => $rating->review,
                    ];
                });

                $productRating = ShopProduct::find($request->product_id);
                $productRating->ratings = $rate;
                $productRating->review_count = $review;

                if ($productRating->save()) {
                    DB::commit();

                    return response()->json([
                        'status' => true,
                        'message' => 'Review Saved Successfully',
                        'ratingDetails' => $ratings,
                        'ratingsCount' => $ratingCount,
                        'reviews' => $review,
                        'rating' => $rate
                    ], 200);
                }
            }
        } catch (\Exception $e) {
            DB::rollback(); // Rollback the transaction
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function filteredReviews(Request $request)
    {
        if ($request->has('rating') && $request->has('product_id')) {
            $reviews = ProductRating::where([
                'product_id' => $request->product_id,
                'rating' => $request->rating
            ])->get();

            $reviews = $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user_id' => $review->user_id,
                    'product_id' => $review->product_id,
                    'name' => $review->name,
                    'review' => $review->review,
                    'rating' => $review->rating,
                ];
            });

            if ($reviews->isNotEmpty()) {
                return response()->json([
                    'status' => true,
                    'ratingsData' => $reviews,
                    'message' => 'Reviews Retrieved Successfully.'
                ], 200);
            }
        }

        return response()->json([
            'status' => false,
            'ratingsData' => [],
            'message' => 'Reviews Not Found.'
        ], 404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
