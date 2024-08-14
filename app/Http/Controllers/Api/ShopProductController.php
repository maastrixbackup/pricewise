<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductDetailsResource;
use App\Http\Resources\ShopProductResource;
use App\Models\DealProduct;
use App\Models\ProductCart;
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

        // Initialize the min and max price
        $minPrice = 0;
        $maxPrice = 0;

        // Calculate min and max prices
        if ($productsQuery->exists()) {
            $filteredProductsQuery = clone $productsQuery;
            $minPrice = $filteredProductsQuery->min('sell_price');
            $maxPrice = $filteredProductsQuery->max('sell_price');
        }

        // Handle category filtering and data retrieval
        $categories = [];
        if ($request->has('category_id')) {
            $categoryDetails = ProductCategory::find($request->input('category_id'));
            if ($categoryDetails) {
                $categories = [$categoryDetails->toArray()];
            } else {
                return response()->json(['error' => 'Category not found'], 404);
            }
        } else {
            $categories = ProductCategory::all()->toArray();
        }

        // Prepare the product data grouped by category with filters applied
        $finalProducts = [];
        foreach ($categories as $category) {
            $categoryProductsQuery = ShopProduct::where('category_id', $category['id']);

            // Apply additional filters within the category
            if ($request->has('color_id')) {
                $categoryProductsQuery->where('color_id', $request->input('color_id'));
            }
            if ($request->has('product_type')) {
                $categoryProductsQuery->where('product_type', $request->input('product_type'));
            }
            if ($request->has('sell_price')) {
                $categoryProductsQuery->where('sell_price', '>=', $request->input('sell_price'));
            }

            // Get the filtered products for the category
            $products = $categoryProductsQuery->get();
            $productData = $products->map(function ($product) use ($request) {
                return (new ShopProductResource($product))->toArray($request);
            });

            $finalProducts[$category['title']] = $productData;
        }

        // Prepare new arrivals data
        $newArrivalProducts = ShopProduct::with('categoryDetails', 'brandDetails', 'colorDetails', 'images')
            ->where('new_arrival', 1)
            ->get();
        $filteredNewArrivals = $newArrivalProducts->map(function ($product) use ($request) {
            return (new ShopProductResource($product))->toArray($request);
        });

        // Prepare product category data
        $productCategory = ProductCategory::orderBy('id', 'asc')->get();
        $filteredCat = $productCategory->map(function ($category) use ($request) {
            return (new ProductCategoryResource($category))->toArray($request);
        });

        // Prepare promotion products data
        $promotionProduct = ProductPromotion::where('status', 'active')->get();
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

        // Prepare deals products data
        $dealsProduct = DealProduct::where('status', 'active')->get();
        $dealsProduct = $dealsProduct->map(function ($deal) {
            return [
                'id' => $deal->id,
                'title' => $deal->title,
                'slug' => $deal->slug,
                'valid_till' => $deal->valid_till,
                'image' => asset('storage/images/shops/' . $deal->image),
                'category' => $deal->categoryDetails,
            ];
        });

        // Final response
        return response()->json([
            'status' => true,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'finalProducts' => $finalProducts,
            'newArrivals' => $filteredNewArrivals,
            'deals' => $dealsProduct,
            'promotion' => $promotionProduct,
            'catWithProduct' => $filteredCat,
        ], 200);
    }

    public function shopFilter(Request $request)
    {
        // Retrieve all product categories
        $productCat = ProductCategory::all();

        // Map product categories to the desired format
        $filterCat = $productCat->map(function ($pc) {
            return [
                'id' => $pc->id,
                'title' => $pc->title,
                'slug' => $pc->slug
            ];
        });

        $filterProduct = [];

        // Check if the 'category_id' is present in the request
        if ($request->has('category_id')) {
            // Retrieve products that belong to the specified category
            $products = ShopProduct::where('category_id', $request->input('category_id'))->get();

            // Group products by title
            $groupedProducts = $products->groupBy('title');

            // Merge products with the same title
            $filterProduct = $groupedProducts->map(function ($group) {
                if ($group->count() > 1) {
                    $mergedProduct = $group->first();
                } else {
                    $mergedProduct = $group->first();
                }

                return [
                    'id' => $mergedProduct->id,
                    'title' => $mergedProduct->title,
                    'slug' => $mergedProduct->slug
                ];
            })->values(); // Use values() to reset the keys
        }

        // Define the available product types
        $productType = [
            'personal' => 'Personal',
            'business' => 'Business',
            'large-business' => 'Large Business'
        ];

        // Return a JSON response with the filter data
        return response()->json([
            'success' => true,
            'productTypes' => $productType,
            'categories' => $filterCat,
            'products' => $filterProduct
        ]);
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


    public function addToCart(Request $request)
    {
        // Check if the product already exists in the cart for the user
        $cartExist = ProductCart::where([
            'user_id' => $request->input('user_id'),
            'product_id' => $request->input('product_id')
        ])->with('productDetails')->first();

        if ($cartExist) {
            // If the product is already in the cart, increase the quantity and update the amount
            $addQty = $cartExist->qty + 1;
            // $price = $request->input('price'); // assuming 'price' is the price of one unit
            $price = $cartExist->productDetails->sell_price;
            $cartExist->qty = $addQty;
            $finalPrice = $addQty * $price; // calculate the total price for the updated quantity
            $cartExist->amount = $finalPrice;

            if ($cartExist->save()) {
                $viewCartProduct = ProductCart::where([
                    'user_id' => $request->input('user_id'),
                ])->with('productDetails')->get();


                $cartAmount = $viewCartProduct->sum('amount'); // Calculate the total cart amount
                $cartItems = $viewCartProduct->map(function ($cp) {
                    return [
                        'id' => $cp->id,
                        'user_id' => $cp->user_id,
                        'product_id' => $cp->product_id,
                        'qty' => $cp->qty,
                        'amount' => $cp->amount,
                        'product_details' => [
                            'pid' => $cp->productDetails->id,
                            'title' => $cp->productDetails->title,
                            'slug' => $cp->productDetails->slug,
                            'sku' => $cp->productDetails->sku,
                            'size' => $cp->productDetails->size,
                            'model' => $cp->productDetails->model,
                            'sell_price' => $cp->productDetails->sell_price,
                            'actual_price' => $cp->productDetails->actual_price,
                            'ratings' => $cp->productDetails->ratings,
                            'image' => !empty($cp->productDetails->images) && isset($cp->productDetails->images[0])
                                ? asset('storage/images/shops/' . $cp->productDetails->images[0]->image)
                                : ''
                        ],
                        // 'pd' => $cp->productDetails,
                    ];
                });
                // Return success response or any other appropriate action
                return response()->json([
                    'success' =>  true,
                    'total_amount' => $cartAmount,
                    'existData' => $cartItems,
                    'message' => 'Cart updated successfully!'
                ], 200);
            }
        } else {
            // If the product is not in the cart, create a new cart entry
            $cartProduct = new ProductCart();
            $cartProduct->user_id = $request->input('user_id');
            $cartProduct->product_id = $request->input('product_id');
            $cartProduct->qty = 1; // initialize quantity as 1
            $price = ShopProduct::find($request->input('product_id'))->sell_price; // Fetch the price from the product details
            $cartProduct->amount = $price; // set the amount to the price of one unit

            if ($cartProduct->save()) {
                $viewCartProduct = ProductCart::where([
                    'user_id' => $request->input('user_id'),
                ])->with('productDetails')->get();


                $cartAmount = $viewCartProduct->sum('amount'); // Calculate the total cart amount
                $cartItems = $viewCartProduct->map(function ($cp) {
                    return [
                        'id' => $cp->id,
                        'user_id' => $cp->user_id,
                        'product_id' => $cp->product_id,
                        'qty' => $cp->qty,
                        'amount' => $cp->amount,
                        'product_details' => [
                            'pid' => $cp->productDetails->id,
                            'title' => $cp->productDetails->title,
                            'slug' => $cp->productDetails->slug,
                            'sku' => $cp->productDetails->sku,
                            'size' => $cp->productDetails->size,
                            'model' => $cp->productDetails->model,
                            'sell_price' => $cp->productDetails->sell_price,
                            'actual_price' => $cp->productDetails->actual_price,
                            'ratings' => $cp->productDetails->ratings,
                            'image' => !empty($cp->productDetails->images) && isset($cp->productDetails->images[0])
                                ? asset('storage/images/shops/' . $cp->productDetails->images[0]->image)
                                : ''
                        ],
                        // 'pd' => $cp->productDetails,
                    ];
                });
                // Return success response or any other appropriate action
                return response()->json([
                    'success' => true,
                    'total_amount' => $cartAmount,
                    'existData' => $cartItems,
                    'message' => 'Product added to cart successfully!'
                ], 200);
            }
        }

        // If something goes wrong, return an error response
        return response()->json([
            'error' => false,
            'message' => 'Failed to update cart!'
        ], 500);
    }


    public function viewCart(Request $request)
    {
        // Retrieve the products in the cart along with their details for the specified user
        $viewCartProduct = ProductCart::with('productDetails')
            ->where('user_id', $request->input('user_id'))
            ->get();

        if ($viewCartProduct->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No products in the cart.'
            ], 404);
        }


        $cartAmount = $viewCartProduct->sum('amount'); // Calculate the total cart amount
        // Map the cart products to a simplified structure
        $cartProducts = $viewCartProduct->map(function ($cp) {
            return [
                'id' => $cp->id,
                'user_id' => $cp->user_id,
                'product_id' => $cp->product_id,
                'qty' => $cp->qty,
                'amount' => $cp->amount,
                'product_details' => [
                    'pid' => $cp->productDetails->id,
                    'title' => $cp->productDetails->title,
                    'slug' => $cp->productDetails->slug,
                    'sku' => $cp->productDetails->sku,
                    'size' => $cp->productDetails->size,
                    'model' => $cp->productDetails->model,
                    'sell_price' => $cp->productDetails->sell_price,
                    'actual_price' => $cp->productDetails->actual_price,
                    'ratings' => $cp->productDetails->ratings,
                    'image' => !empty($cp->productDetails->images) && isset($cp->productDetails->images[0])
                        ? asset('storage/images/shops/' . $cp->productDetails->images[0]->image)
                        : ''
                ],
                // 'pd' => $cp->productDetails,
            ];
        });

        return response()->json([
            'success' => true,
            'total_amount' => $cartAmount,
            'cart_products' => $cartProducts,
            'message' => 'Cart viewed successfully.'
        ], 200);
    }


    public function removeCart(Request $request)
    {

        if ($request->has('c_id') && $request->has('user_id')) {
            // Retrieve the cart item by its ID
            $cartItem = ProductCart::find($request->input('c_id'));

            if ($cartItem) {
                if ($cartItem->delete()) {
                    // Retrieve the remaining cart products for the user
                    $cartProducts = ProductCart::where('user_id', $request->input('user_id'))
                        ->with('productDetails')
                        ->get();

                    $cartAmount = $cartProducts->sum('amount'); // Calculate the total cart amount

                    if ($cartProducts->isNotEmpty()) {
                        // Map the remaining cart products to a simplified structure
                        $cartItems = $cartProducts->map(function ($cp) {
                            return [
                                'id' => $cp->id,
                                'user_id' => $cp->user_id,
                                'product_id' => $cp->product_id,
                                'qty' => $cp->qty,
                                'amount' => $cp->amount,
                                'product_details' => [
                                    'pid' => $cp->productDetails->id,
                                    'title' => $cp->productDetails->title,
                                    'slug' => $cp->productDetails->slug,
                                    'sku' => $cp->productDetails->sku,
                                    'size' => $cp->productDetails->size,
                                    'model' => $cp->productDetails->model,
                                    'sell_price' => $cp->productDetails->sell_price,
                                    'actual_price' => $cp->productDetails->actual_price,
                                    'ratings' => $cp->productDetails->ratings,
                                    'image' => !empty($cp->productDetails->images) && isset($cp->productDetails->images[0])
                                        ? asset('storage/images/shops/' . $cp->productDetails->images[0]->image)
                                        : ''
                                ],
                            ];
                        });

                        // Return success response with the updated cart items
                        return response()->json([
                            'success' => true,
                            'total_amount' => $cartAmount,
                            'existData' => $cartItems,
                            'message' => 'Product removed from cart successfully!'
                        ], 200);
                    } else {
                        // If the cart is empty after deletion, return a message
                        return response()->json([
                            'success' => true,
                            'existData' => [],
                            'message' => 'Product removed from cart successfully, no items left in the cart.'
                        ], 200);
                    }
                }
            } else {
                // If the cart item is not found, return an error
                return response()->json([
                    'error' => 'Cart item not found!'
                ], 404);
            }
        } else {
            // If required parameters are missing, return an error
            return response()->json([
                'error' => 'Invalid request! Missing required parameters.'
            ], 400);
        }
    }

    public function addMoreQty(Request $request)
    {
        $cartItems = [];

        if ($request->has('cart_id')) {
            $cartP = ProductCart::with('productDetails')->find($request->input('cart_id'));
            if ($cartP) {
                $eQty = $cartP->qty + 1;
                $price = $eQty * $cartP->productDetails->sell_price;
                $cartP->qty = $eQty;
                $cartP->amount = $price;
                $cartP->save(); // Save the changes to the database
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found in cart'
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'cart_id is required'
            ], 400);
        }

        if ($request->has('user_id')) {
            $cartPs = ProductCart::with('productDetails')
                ->where('user_id', $request->input('user_id'))
                ->get();

            $cartAmount = $cartPs->sum('amount'); // Calculate the total cart amount

            if ($cartPs->isNotEmpty()) {
                $cartItems = $cartPs->map(function ($cp) {
                    return [
                        'id' => $cp->id,
                        'user_id' => $cp->user_id,
                        'product_id' => $cp->product_id,
                        'qty' => $cp->qty,
                        'amount' => $cp->amount,
                        'product_details' => [
                            'pid' => $cp->productDetails->id,
                            'title' => $cp->productDetails->title,
                            'slug' => $cp->productDetails->slug,
                            'sku' => $cp->productDetails->sku,
                            'size' => $cp->productDetails->size,
                            'model' => $cp->productDetails->model,
                            'sell_price' => $cp->productDetails->sell_price,
                            'actual_price' => $cp->productDetails->actual_price,
                            'ratings' => $cp->productDetails->ratings,
                            'image' => !empty($cp->productDetails->banner_image)
                                ? asset('storage/images/shops/' . $cp->productDetails->banner_image)
                                : ''
                        ],
                    ];
                });
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'user_id is required'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Quantity updated successfully',
            'total_amount' => $cartAmount,
            'data' => $cartItems
        ]);
    }


    public function removeQty(Request $request)
    {
        $cartItems = [];

        if ($request->has('cart_id')) {
            $cartP = ProductCart::with('productDetails')->find($request->input('cart_id'));
            if ($cartP) {
                $eQty = $cartP->qty - 1;

                // Prevent the quantity from going below 1
                if ($eQty < 1) {
                    $eQty = 1;
                }

                $price = $eQty * $cartP->productDetails->sell_price;
                $cartP->qty = $eQty;
                $cartP->amount = $price;
                $cartP->save(); // Save the changes to the database
            }
        }

        $cartPs = ProductCart::with('productDetails')
            ->where('user_id', $request->input('user_id'))
            ->get();

        $cartAmount = $cartPs->sum('amount'); // Calculate the total cart amount

        if ($cartPs->isNotEmpty()) {
            $cartItems = $cartPs->map(function ($cp) {
                return [
                    'id' => $cp->id,
                    'user_id' => $cp->user_id,
                    'product_id' => $cp->product_id,
                    'qty' => $cp->qty,
                    'amount' => $cp->amount,
                    'product_details' => [
                        'pid' => $cp->productDetails->id,
                        'title' => $cp->productDetails->title,
                        'slug' => $cp->productDetails->slug,
                        'sku' => $cp->productDetails->sku,
                        'size' => $cp->productDetails->size,
                        'model' => $cp->productDetails->model,
                        'sell_price' => $cp->productDetails->sell_price,
                        'actual_price' => $cp->productDetails->actual_price,
                        'ratings' => $cp->productDetails->ratings,
                        'image' => !empty($cp->productDetails->banner_image)
                            ? asset('storage/images/shops/' . $cp->productDetails->banner_image)
                            : ''
                    ],
                ];
            });
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Quantity updated successfully',
            'total_amount' => $cartAmount, // Include the total cart amount
            'data' => $cartItems
        ]);
    }





















    // public function index(Request $request)
    // {
    //     // Initialize the product query with necessary relationships
    //     $productsQuery = ShopProduct::with('categoryDetails', 'brandDetails', 'images', 'colorDetails');

    //     $minPrice = 0;
    //     $maxPrice = 0;

    //     if ($productsQuery) {
    //         // Calculate min and max prices only if filters are applied
    //         $filteredProductsQuery = clone $productsQuery;
    //         $minPrice = $filteredProductsQuery->min('sell_price');
    //         $maxPrice = $filteredProductsQuery->max('sell_price');
    //     }


    //     if ($request->has('category_id')) {
    //         // Retrieve the category details for the given category_id
    //         $categoryDetails = ProductCategory::find($request->input('category_id'));

    //         if ($categoryDetails) {
    //             $categoryDetails = $categoryDetails->toArray();
    //             $categories = [$categoryDetails]; // Convert to an array to handle single category uniformly
    //         } else {
    //             // Handle the case where the category_id does not exist
    //             return response()->json(['error' => 'Category not found'], 404);
    //         }
    //     } else {
    //         // Retrieve all category details if no category_id is provided
    //         $categories = ProductCategory::all()->toArray();
    //     }

    //     $a = [];
    //     foreach ($categories as $category) {
    //         $products = ShopProduct::where('category_id', $category['id'])->get();
    //         $productData = $products->map(function ($product) {
    //             return (new ShopProductResource($product))->toArray(request());
    //         });

    //         $a[$category['title']] = $productData;
    //     }

    //     return response()->json($a);


    //     // Filter By Color
    //     if ($request->has('color_id')) {
    //         $productsQuery->where('color_id', $request->input('color_id'));
    //     }

    //     // Filter By Product Type
    //     if ($request->has('product_type')) {
    //         $productsQuery->where('product_type', $request->input('product_type'));
    //     }

    //     // Filter By Selling Price
    //     if ($request->has('sell_price')) {
    //         $productsQuery->where('sell_price', '>=', $request->input('sell_price'));
    //     }


    //     // Retrieve the final filtered products
    //     $products = $productsQuery->get();

    //     // $filteredProducts = [];
    //     // if ($products->isNotEmpty()) {
    //     //     foreach ($products as $product) {
    //     //         $filterData = (new ShopProductResource($product))->toArray($request);
    //     //         $filteredProducts[] = $filterData;
    //     //     }
    //     // }


    //     $assArry = [];
    //     $categoryProduct = ProductCategory::all();

    //     if ($categoryProduct) {
    //         foreach ($categoryProduct as $v) { // Simplified the loop by removing $k
    //             $products = ShopProduct::where('category_id', $v->id)->get();
    //             foreach ($products as $product) {
    //                 $filterData = (new ShopProductResource($product))->toArray($request);
    //                 $filteredProducts[] = $filterData;
    //             }
    //             $assArry[$v->id] = $filteredProducts; // Corrected array assignment syntax
    //         }
    //     }
    //     return $assArry;

    //     $newArrivalProducts = ShopProduct::with('categoryDetails', 'brandDetails', 'colorDetails', 'images')
    //         ->where('new_arrival', 1)
    //         ->get();

    //     $filteredNewArrivals = [];
    //     if ($newArrivalProducts->isNotEmpty()) {
    //         foreach ($newArrivalProducts as $product) {
    //             $newArrivalData = (new ShopProductResource($product))->toArray($request);
    //             $filteredNewArrivals[] = $newArrivalData;
    //         }
    //     }

    //     $productCategory = ProductCategory::orderBy('id', 'asc')->get();

    //     $filteredCat = [];
    //     if ($productCategory->isNotEmpty()) {
    //         foreach ($productCategory as $category) {
    //             $categoryWithProduct = (new ProductCategoryResource($category))->toArray($request);
    //             $filteredCat[] = $categoryWithProduct;
    //         }
    //     }

    //     $promotionProduct = ProductPromotion::where('status', 'active')->get();
    //     if ($promotionProduct->isNotEmpty()) {
    //         $promotionProduct = $promotionProduct->map(function ($promotion) {
    //             return [
    //                 'id' => $promotion->id,
    //                 'title' => $promotion->title,
    //                 'sub_title' => $promotion->sub_title,
    //                 'description' => $promotion->description,
    //                 'image' => asset('storage/images/shops/' . $promotion->image),
    //                 'btn_text' => $promotion->btn_text,
    //                 'btn_url' => $promotion->btn_url,
    //             ];
    //         });
    //     }

    //     $dealsProduct = DealProduct::where('status', 'active')->get();
    //     if ($dealsProduct->isNotEmpty()) {
    //         $dealsProduct = $dealsProduct->map(function ($deals) {
    //             return [
    //                 'id' => $deals->id,
    //                 'title' => $deals->title,
    //                 'slug' => $deals->slug,
    //                 'valid_till' => $deals->valid_till,
    //                 'image' => asset('storage/images/shops/' . $deals->image),
    //                 'category' => $deals->categoryDetails
    //             ];
    //         });
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'minPrice' => $minPrice,
    //         'maxPrice' => $maxPrice,
    //         'finalProducts' => $filteredProducts,
    //         'newArrivals' => $filteredNewArrivals,
    //         'deals' => $dealsProduct,
    //         'promotion' => $promotionProduct,
    //         'catWithProduct' => $filteredCat,
    //     ], 200);
    // }

}
