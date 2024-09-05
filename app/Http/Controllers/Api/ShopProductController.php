<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductDetailsResource;
use App\Http\Resources\ShopProductResource;
use App\Models\DealProduct;
use App\Models\ProductCart;
use App\Models\ProductCategory;
use App\Models\ProductColor;
use App\Models\ProductPromotion;
use App\Models\ProductRating;
use App\Models\ProductRequest;
use App\Models\ShopProduct;
use App\Models\ShopSetting;
use App\Models\WishlistProduct;
use App\Models\NotifyProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\AdminNewRequestNotification;
use Illuminate\Support\Facades\Log;
use App\Mail\UserRequestConfirmation;
use App\Mail\UserProductNotification;
use App\Mail\AdminProductNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Resources\CompareProductResource;
use App\Models\EmailTemplate;
use App\Mail\AvailableProductNotification;
use App\Mail\WelcomeEmail;
use App\Models\ShopOrder;
use App\Models\ComboProduct;
use App\Models\ShopOrderDetail;
use App\Models\ExtraProduct;
use Illuminate\Support\Str;

class ShopProductController extends Controller
{

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
        if ($request->filled('category_id')) {
            $categoryDetails = ProductCategory::find($request->input('category_id'));
            $categories = [$categoryDetails->toArray()];
            // if ($categoryDetails) {
            // } else {
            //     return response()->json(['error' => 'Category not found']);
            // }
        } else {
            $categories = ProductCategory::take(5)->get();
        }

        // Prepare the product data grouped by category with filters applied
        $finalProducts = [];
        foreach ($categories as $category) {
            $categoryProductsQuery = ShopProduct::where('category_id', $category['id']);

            // Apply additional filters within the category
            if ($request->filled('search')) {
                $categoryProductsQuery->where('title', 'like', '%' . $request->input('search') . '%');
            }

            if ($request->filled('color_id')) {
                $categoryProductsQuery->where('color_id', $request->input('color_id'));
            }

            if ($request->filled('product_type')) {
                $categoryProductsQuery->where('product_type', $request->input('product_type'));
            }

            if ($request->filled('sell_price')) {
                $categoryProductsQuery->where('sell_price', '>=', $request->input('sell_price'));
            }

            if ($request->filled('filter')) {
                switch ($request->input('filter')) {
                    case 1:
                        $categoryProductsQuery->orderBy('sell_price', 'desc');
                        break;
                    case 2:
                        $categoryProductsQuery->orderBy('sell_price', 'asc');
                        break;
                    case 3:
                        $categoryProductsQuery->orderBy('sell_count', 'desc');
                        break;
                }
            }

            // Get the filtered products for the category
            $products = $categoryProductsQuery->get();
            $productData = $products->map(function ($product) use ($request) {
                return (new ShopProductResource($product))->toArray($request);
            });

            $finalProducts[$category['title']] = $productData;
        }


        // Prepare new arrivals data
        if ($request->filled('category_id')) {
            $newArrivalProducts = ShopProduct::with('categoryDetails', 'brandDetails', 'colorDetails', 'images')
                ->where('new_arrival', 1)
                ->where('category_id', $request->input('category_id'))
                ->get();
        } else {
            $newArrivalProducts = ShopProduct::with('categoryDetails', 'brandDetails', 'colorDetails', 'images')
                ->where('new_arrival', 1)
                ->get();
        }

        $filteredNewArrivals = $newArrivalProducts->map(function ($product) use ($request) {
            return (new ShopProductResource($product))->toArray($request);
        });


        // Prepare product category data
        $productCategory = ProductCategory::orderBy('id', 'asc')->where('status', 'active')->get();
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
        if ($request->filled('category_id')) {
            $dealsProduct = DealProduct::where([
                'status' => 'active',
                'category' => $request->input('category_id')
            ])->get();
        } else {
            $dealsProduct = DealProduct::where('status', 'active')->get();
        }
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

        $shopCommon = ShopSetting::find(1);

        $shopCommon = [
            'id' => $shopCommon->id,
            'order_above' => $shopCommon->order_above,
            'order_time' => $shopCommon->order_time,
            'reflection_period' => $shopCommon->period,
            'limited_stock' => $shopCommon->limited_stock,
            'payment_image' => asset('storage/images/shops/' . $shopCommon->image)
        ];

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
            'commonSetting' => $shopCommon
        ]);
    }

    public function shopProductCompare(Request $request)
    {
        $productIds = $request->input('product_ids');
        // $productIds = json_decode($request->input('product_ids'), true);
        if ($productIds) {

            $shopProducts = ShopProduct::with('categoryDetails', 'brandDetails', 'images', 'colorDetails')
                ->whereIn('id', $productIds)->get();

            if ($shopProducts->isNotEmpty()) {

                $shopProducts = $shopProducts->map(function ($product) use ($request) {
                    return (new CompareProductResource($product))->toArray($request);
                });

                return response()->json([
                    'status' => true,
                    'productData' => $shopProducts,
                    'message' => 'Products retrieved successfully.'
                ]);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'No Products found for Comparison.'
                ]);
            }
        } else {

            return response()->json([
                'status' => false,
                'message' => 'No Product IDs found.'
            ]);
        }
    }

    public function categoryFilter(Request $request)
    {
        $productCategories = ProductCategory::orderBy('id', 'asc')->where('status', 'active')->get();
        $productCategories = $productCategories->map(function ($cat) {
            return [
                'id' => $cat->id,
                'title' => $cat->title,
                'slug' => $cat->slug
            ];
        });

        return response()->json([
            'status' => true,
            'categories' => $productCategories,
            'message' => 'Data retrieved successfully.'
        ]);
    }


    public function categoryWiseProducts(Request $request, $slug)
    {
        // Check if slug is empty
        if (empty($slug)) {
            return response()->json(['status' => false, 'message' => 'Slug cannot be empty']);
        }

        $pageNo = $request->pageNo ?? 1;
        $postsPerPage = $request->postsPerPage ?? 12;
        $toSkip = ($postsPerPage * $pageNo) - $postsPerPage;

        $category = ProductCategory::where('slug', $slug)->first();

        // Fetch similar products
        $similarProducts = ShopProduct::with('categoryDetails', 'brandDetails', 'images', 'colorDetails')
            ->orderBy('id', 'DESC')
            ->take(10)
            ->get();

        $featuredProduct = [];
        if ($similarProducts->isNotEmpty()) {
            foreach ($similarProducts as $pdt) {
                $filterData = (new ShopProductResource($pdt))->toArray($request);
                $featuredProduct[] = $filterData;
            }
        }

        if ($category) {
            // Fetch products by category
            $catProducts = ShopProduct::where('category_id', $category->id)
                ->with('categoryDetails', 'brandDetails', 'colorDetails', 'images')
                ->skip($toSkip)
                ->take($postsPerPage)
                ->get();

            // Check if catProducts is empty
            if ($catProducts->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'featuredProduct' => $featuredProduct,
                    'message' => 'No products found in this category'
                ]);
            }

            // Filter the products
            $filteredProducts = $catProducts->map(function ($product) use ($request) {
                return (new ShopProductResource($product))->toArray($request);
            });

            return response()->json([
                'status' => true,
                'productsData' => $filteredProducts,
                'featuredProduct' => $featuredProduct,
                'message' => 'Products retrieved successfully'
            ]);
        } else {
            return response()->json(['status' => false, 'message' => 'Category not found']);
        }
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

        // Product Color
        $productColors = ProductColor::orderBy('id', 'asc')->where('status', 'active')->get();
        $productColors = $productColors->map(function ($clr) {
            return [
                'id' => $clr->id,
                'title' => $clr->title,

            ];
        });
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
            'productColors' => $productColors,
            'products' => $filterProduct
        ]);
    }


    public function productDetails(Request $request, $slug)
    {

        // $id = encrypt($id);
        $product = ShopProduct::with('categoryDetails', 'brandDetails', 'images', 'colorDetails')->where('slug', $slug)->first();

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
            // abort(404, 'Product not found');
            return response()->json([
                'status' => false,
                'message' => 'Product not found'

            ]);
        }
        $filteredProduct = new ProductDetailsResource($product);

        $rating = ProductRating::where('product_id', $product->id)->get();
        $ratingCount = [
            'fiveStar' => $rating->where('rating', 5)->count(),
            'fourStar' => $rating->where('rating', 4)->count(),
            'threeStar' => $rating->where('rating', 3)->count(),
            'twoStar' => $rating->where('rating', 2)->count(),
            'oneStar' => $rating->where('rating', 1)->count(),
        ];

        $totalRatings = $rating->count();

        $shopCommon = ShopSetting::find(1);

        $shopCommon = [
            'id' => $shopCommon->id,
            'order_above' => $shopCommon->order_above,
            'order_time' => $shopCommon->order_time,
            'reflection_period' => $shopCommon->period,
            'limited_stock' => $shopCommon->limited_stock,
            'payment_image' => asset('storage/images/shops/' . $shopCommon->image)
        ];


        return response()->json([
            'status' => true,
            'productDetails' => $filteredProduct,
            'featuredProduct' => $featuredProduct,
            'ratingCountData' => $ratingCount,
            'totalRatings' => $totalRatings,
            'reviews' => $rating,
            'commonData' => $shopCommon,
        ]);
    }


    public function storeProductReviews(Request $request)
    {

        $chkRating = ProductRating::where(['product_id' => $request->product_id, 'user_id' => $request->user_id])->first();
        if ($chkRating) {
            return response()->json([
                'status' => false,
                'message' => 'You Have already rated the product.',
            ]);
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
                    ]);
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
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'ratingsData' => [],
            'message' => 'Reviews Not Found.'
        ]);
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

                $cartCount = $viewCartProduct->count();
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
                        'combo_product' => ComboProduct::where('product_id', $cp->product_id)->with('comboProductDetails')->get(),
                        // 'pd' => $cp->productDetails,
                    ];
                });
                // Return success response or any other appropriate action
                return response()->json([
                    'success' =>  true,
                    'total_amount' => $cartAmount,
                    'existData' => $cartItems,
                    'cartCount' => $cartCount,
                    'message' => 'Cart updated successfully!'
                ]);
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


                $cartCount = $viewCartProduct->count();
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
                        'combo_product' => ComboProduct::where('product_id', $cp->product_id)->with('comboProductDetails')->get(),
                    ];
                });
                // Return success response or any other appropriate action
                return response()->json([
                    'success' => true,
                    'total_amount' => $cartAmount,
                    'existData' => $cartItems,
                    'cartCount' => $cartCount,
                    'message' => 'Product added to cart successfully!'
                ]);
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
            ]);
        }


        $cartCount = $viewCartProduct->count();
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
                    'highlights' => $cp->productDetails->heighlights,
                    'banner_image' => asset('storage/images/shops/' . $cp->productDetails->banner_image),
                    'image' => !empty($cp->productDetails->images) && isset($cp->productDetails->images[0])
                        ? asset('storage/images/shops/' . $cp->productDetails->images[0]->image)
                        : ''
                ],
                'combo_product' => ComboProduct::where('product_id', $cp->product_id)->with('comboProductDetails')->get(),
                // 'pd' => $cp->productDetails,
            ];
        });

        return response()->json([
            'success' => true,
            'total_amount' => $cartAmount,
            'cart_products' => $cartProducts,
            'cartCount' => $cartCount,
            'message' => 'Cart viewed successfully.'
        ]);
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

                    $cartCount = $cartProducts->count();
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
                                'combo_product' => ComboProduct::where('product_id', $cp->product_id)->with('comboProductDetails')->get(),
                            ];
                        });

                        // Return success response with the updated cart items
                        return response()->json([
                            'success' => true,
                            'total_amount' => $cartAmount,
                            'existData' => $cartItems,
                            'cartCount' => $cartCount,
                            'message' => 'Product removed from cart successfully!'
                        ]);
                    } else {
                        // If the cart is empty after deletion, return a message
                        return response()->json([
                            'success' => true,
                            'existData' => [],
                            'message' => 'Product removed from cart successfully, no items left in the cart.'
                        ]);
                    }
                }
            } else {
                // If the cart item is not found, return an error
                return response()->json([
                    'error' => 'Cart item not found!'
                ]);
            }
        } else {
            // If required parameters are missing, return an error
            return response()->json([
                'error' => 'Invalid request! Missing required parameters.'
            ]);
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
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'cart_id is required'
            ]);
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
                        'combo_product' => ComboProduct::where('product_id', $cp->product_id)->with('comboProductDetails')->get(),
                    ];
                });
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'user_id is required'
            ]);
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
                    'combo_product' => ComboProduct::where('product_id', $cp->product_id)->with('comboProductDetails')->get(),
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

    public function addToWishList(Request $request)
    {
        $userId = $request->input('user_id');
        $productId = $request->input('product_id');

        if ($userId && $productId) {
            // Check if the product is already in the wishlist
            $existingWishlist = WishlistProduct::where([
                'user_id' => $userId,
                'product_id' => $productId
            ])->first();

            if ($existingWishlist) {
                return $this->removeFromWishList($request);
            }

            // Add the product to the wishlist
            $wishlistAdd = new WishlistProduct();
            $wishlistAdd->user_id = $userId;
            $wishlistAdd->product_id = $productId;

            if ($wishlistAdd->save()) {
                $wishlistItems = WishlistProduct::where('user_id', $userId)->get();
                $wishlistCount = $wishlistItems->count();
                return response()->json([
                    'status' => true,
                    'listData' => $wishlistAdd,
                    'wishlistCount' => $wishlistCount,
                    'message' => 'Product added to your wishlist.',
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to add product to wishlist. Please provide valid user_id and product_id.',
        ]);
    }

    public function removeFromWishList(Request $request)
    {
        $userId = $request->input('user_id');
        $productId = $request->input('product_id');

        if ($userId && $productId) {
            // Find the wishlist entry
            $wishlistItem = WishlistProduct::where([
                'user_id' => $userId,
                'product_id' => $productId
            ])->first();

            // If the item exists, delete it
            if ($wishlistItem) {
                $wishlistItem->delete();

                $wishlistItems = WishlistProduct::where('user_id', $userId)->get();
                $wishlistCount = $wishlistItems->count();
                return response()->json([
                    'status' => true,
                    'wishlistCount' => $wishlistCount,
                    'message' => 'Product removed from wishlist.'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Product not found in your wishlist.'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to remove product from wishlist. Please provide valid user_id and product_id.',
        ]);
    }

    // public function addToWishList(Request $request)
    // {

    //     if ($request->has('user_id') && $request->has('product_id')) {
    //         // Check if the product is already in the wishlist
    //         $existingWishlist = WishlistProduct::where([
    //             'user_id' => $request->input('user_id'),
    //             'product_id' => $request->input('product_id')
    //         ])->first();

    //         if ($existingWishlist) {
    //             return $this->removeFromWishList($request);
    //             // return response()->json([
    //             //     'status' => false,
    //             //     'message' => 'Product is already in your wishlist.',
    //             // ]);
    //         }

    //         // Add the product to the wishlist
    //         $wishlistAdd = new WishlistProduct();
    //         $wishlistAdd->user_id = $request->input('user_id');
    //         $wishlistAdd->product_id = $request->input('product_id');

    //         if ($wishlistAdd->save()) {
    //             return response()->json([
    //                 'status' => true,
    //                 'listData' => $wishlistAdd,
    //                 'message' => 'Product Added in your wishlist.'
    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Failed to add product to wishlist. Please provide valid user_id and product_id.',
    //     ]);
    // }

    // public function removeFromWishList(Request $request)
    // {
    //     if ($request->has('user_id') && $request->has('product_id')) {
    //         // Find the wishlist entry
    //         $wishlistItem = WishlistProduct::where([
    //             'user_id' => $request->input('user_id'),
    //             'product_id' => $request->input('product_id')
    //         ])->first();

    //         // If the item exists, delete it
    //         if ($wishlistItem) {
    //             $wishlistItem->delete();

    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Product removed from wishlist.'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Product not found in your wishlist.'
    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Failed to remove product from wishlist. Please provide valid user_id and product_id.',
    //     ]);
    // }

    public function productWishLists(Request $request)
    {
        $userId = $request->input('user_id');
        $productId = $request->input('product_id');

        if ($userId && $productId) {
            $wishlistProduct = WishlistProduct::where([
                'user_id' => $userId,
                'product_id' => $productId
            ])->first();

            if ($wishlistProduct) {
                $wItems = WishlistProduct::where('user_id', $request->input('user_id'))->get();
                $wCount = $wItems->count();
                return response()->json([
                    'status' => true,
                    'wishlistProduct' => $wishlistProduct,
                    'wishlistCount' => $wCount,
                    'message' => 'Product found in wishlist.'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'wishlistProduct' => null,
                    'message' => 'Product not found in wishlist.'
                ]);
            }
        }

        if ($userId) {
            $wishlistProducts = WishlistProduct::where('user_id', $userId)->get();
            $wishListCount = $wishlistProducts->count();
            return response()->json([
                'status' => true,
                'wishlistProducts' => $wishlistProducts,
                'wishlistCount' => $wishListCount,
                'message' => 'Successfully retrieved all products.'
            ]);
        }

        return response()->json([
            'status' => false,
            'wishlistProducts' => null,
            'message' => 'User ID is required.'
        ]);
    }


    // public function storeProductRequest(Request $request)
    // {
    //     // Set the default timezone
    //     // date_default_timezone_set('GMT');
    //     $current_time = date('H:i:s');

    //     // Create a DateTime object from the string
    //     $date = $request->input('callback_date');
    //     $dateTime = $date->datetime('Y-m-d H:i:s');
    //     return $dateTime;
    //     if ($request->has('user_id') && $request->has('product_id')) {
    //         try {

    //             // Format the date as 'Y-m-d H:i:s'
    //             $formattedDate = $date->format('Y-m-d H:i:s');

    //             // Create a new ProductRequest instance
    //             $productReqs = new ProductRequest();
    //             $productReqs->user_id = $request->input('user_id');
    //             $productReqs->product_id = $request->input('product_id');
    //             $productReqs->user_name = $request->input('user_name');
    //             $productReqs->email = $request->input('email');
    //             $productReqs->phone_number = $request->input('phone_number');
    //             $productReqs->qty = $request->input('qty');
    //             $productReqs->delivery_address = $request->input('delivery_address');
    //             $productReqs->curr_time = $current_time;
    //             $productReqs->callback_date = $request->input('callback_date');
    //             $productReqs->additional_info = $request->input('additional_info');
    //             $productReqs->terms_condition = $request->input('terms_condition'); // corrected field

    //             // Save the product request to the database
    //             $productReqs->save();

    //             // Fetch product name
    //             $product = ShopProduct::find($request->input('product_id'));
    //             $product_name = $product->title;

    //             // Prepare email data
    //             $userName = $request->input('user_name');
    //             $userEmail = $request->input('email');
    //             $userNumber = $request->input('phone_number');
    //             $quantity = $request->input('qty');
    //             $deliveryAddress = $request->input('delivery_address');
    //             $callbackDate = $request->input('callback_date');
    //             $additionalInfo = $request->input('additional_info');

    //             // Log email data
    //             Log::info('Sending email with the following details:', [
    //                 'user_name' => $userName,
    //                 'product_name' => $product_name,
    //                 'quantity' => $quantity,
    //                 'delivery_address' => $deliveryAddress,
    //                 'callback_date' => $callbackDate,
    //                 'additional_info' => $additionalInfo,
    //             ]);

    //             // // Send confirmation email to the user
    //             // Mail::to($userEmail)->send(new UserRequestConfirmation(
    //             //     $userName,
    //             //     $product_name,
    //             //     $quantity,
    //             //     $deliveryAddress,
    //             //     $callbackDate,
    //             //     $additionalInfo
    //             // ));

    //             // // Send notification email to the admin
    //             // Mail::to('bibhuprasad.maastrix@gmail.com')->send(new AdminNewRequestNotification(
    //             //     $userName,
    //             //     $userEmail,
    //             //     $userNumber,
    //             //     $product_name,
    //             //     $quantity,
    //             //     $deliveryAddress,
    //             //     $callbackDate,
    //             //     $additionalInfo
    //             // ));

    //             // Prepare the HTML message
    //             $message = "Hi <b>{$userName}</b>, your request has been received, and a confirmation email has been sent to this email address: <i>{$userEmail}</i>";

    //             // Return a success response with the HTML message
    //             return response()->json(['status' => true, 'message' => $message]);
    //         } catch (\Exception $e) {
    //             // Log the error
    //             Log::error('Failed to process request or send email: ' . $e->getMessage());

    //             // Return an error response
    //             return response()->json(['status' => false, 'message' => $e->getMessage()]);
    //         }
    //     } else {
    //         return response()->json(['status' => false, 'message' => 'Missing required user_id or product_id']);
    //     }
    // }

    public function storeProductRequest(Request $request)
    {
        // Validate required fields
        $validatedData = $request->validate([
            'user_id'          => 'required|integer|exists:users,id',
            'product_id'       => 'required|integer|exists:shop_products,id',
            'user_name'        => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'phone_number'     => 'required|string|max:20',
            'qty'              => 'required|integer|min:1',
            'delivery_address' => 'required|string|max:500',
            'callback_date'    => 'required|date_format:Y-m-d\TH:i',
            'additional_info'  => 'nullable|string|max:1000',
            'terms_condition'  => 'required|boolean',
        ]);

        try {
            // Parse and format callback_date using Carbon
            $callbackDate = Carbon::parse($validatedData['callback_date'])->format('Y-m-d H:i:s');

            // Get current time in 'H:i:s' format
            $currentTime = Carbon::now()->format('H:i:s');

            // Create a new ProductRequest instance and fill data
            $productRequest = new ProductRequest();
            $productRequest->user_id          = $validatedData['user_id'];
            $productRequest->product_id       = $validatedData['product_id'];
            $productRequest->user_name        = $validatedData['user_name'];
            $productRequest->email            = trim($validatedData['email']);
            $productRequest->phone_number     = $validatedData['phone_number'];
            $productRequest->qty              = $validatedData['qty'];
            $productRequest->delivery_address = $validatedData['delivery_address'];
            $productRequest->curr_time        = $currentTime;
            $productRequest->callback_date    = $callbackDate;
            $productRequest->additional_info  = $validatedData['additional_info'] ?? null;
            $productRequest->terms_condition  = $validatedData['terms_condition'];

            // Save the product request to the database
            $productRequest->save();

            // Fetch product details
            $product = ShopProduct::findOrFail($validatedData['product_id']);

            // Prepare email data
            $emailData = [
                'user_name'        => $validatedData['user_name'],
                'user_email'       => $validatedData['email'],
                'user_number'      => $validatedData['phone_number'],
                'product_name'     => $product->title,
                'quantity'         => $validatedData['qty'],
                'delivery_address' => $validatedData['delivery_address'],
                'callback_date'    => $callbackDate,
                'additional_info'  => $validatedData['additional_info'] ?? 'N/A',
            ];

            // Log email data
            Log::info('Sending email with the following details:', $emailData);

            // Uncomment and configure these lines if email functionality is set up
            // Mail::to($emailData['user_email'])->send(new UserRequestConfirmation($emailData));
            // Mail::to('admin@example.com')->send(new AdminNewRequestNotification($emailData));

            // Prepare the success message
            $message = "Hi <b>{$emailData['user_name']}</b>, your request has been received, and a confirmation email has been sent to: <i>{$emailData['user_email']}</i>";

            // Return a success response
            return response()->json(['status' => true, 'message' => $message], 200);
        } catch (\Exception $e) {
            // Log the error details
            Log::error('Error in storeProductRequest:', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            // Return an error response
            return response()->json(['status' => false, 'message' => 'An error occurred while processing your request. Please try again later.'], 500);
        }
    }

    public function saveProductNotifyRequest(Request $req)
    {
        $userId = $req->input('user_id');
        $productId = $req->input('product_id');
        $email = $req->input('email');
        $current_time = date('H:i:s');

        if ($userId && $productId && $email) {

            // Check if the notification request already exists and is not notified
            $existNotify = NotifyProduct::where([
                'user_id' => $userId,
                'product_id' => $productId,
                'notified' => '0'
            ])->first();

            if ($existNotify) {
                return response()->json(['status' => false, 'message' => 'Already requested for notification.']);
            }

            $productNotify = new NotifyProduct();
            $productNotify->user_id = $userId;
            $productNotify->product_id = $productId;
            $productNotify->curr_time = $current_time;
            $productNotify->notify_email = trim($email);

            if ($productNotify->save()) {
                // Fetch user details
                $userDetails = User::find($userId);
                $product = ShopProduct::find($productId);

                if ($userDetails) {
                    $userName = $userDetails->name;
                    $p_name = $product->title;

                    // Log the email details
                    Log::info('Sending email with the following details:', [
                        'user_name' => $userName,
                        'product_name' => $p_name,
                        'email' => $email,
                    ]);

                    // Send confirmation emails to the user and the admin
                    // Mail::to($email)->send(new UserProductNotification($userName, $p_name, $email));
                    // Mail::to('bibhuprasad.maastrix@gmail.com')->send(new AdminProductNotification($userName, $p_name, $email));

                    return response()->json(['status' => true, 'message' => 'You will be notified']);
                }
            }
        }

        return response()->json(['status' => false, 'message' => 'Failed to process the request']);
    }

    public function productNotifySend($params)
    {
        $userName = $params['user_name'];
        $productName = $params['product_name'];
        $productUrl = $params['url'];
        $email = $params['email'];
        $notifyId = $params['notify_id'];

        try {
            // Send the email
            Mail::to($email)->send(new AvailableProductNotification($userName, $productName, $productUrl));
            // Return the params or any other relevant data
            return [
                'status' => 'success',
                'message' => 'Notification sent successfully',
                'params' => $params
            ];
        } catch (\Exception $e) {
            Log::error('Error sending product availability notification: ' . $e->getMessage());

            // Return error data
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'params' => $params
            ];
        }
    }

    public function placeOrderCreate(Request $request)
    {
        // Input data retrieval
        $userId = $request->input('user_id');
        $fName = $request->input('f_name');
        $lName = $request->input('l_name');
        $email = trim($request->input('email'));
        $phoneNumber = $request->input('phone_number');
        $userType = $request->input('user_type');
        $salutation = $request->input('salutation');
        $diffBilling = $request->input('diff_billing');
        $deliveredBy = $request->input('exp_delivery');
        $totalAmount = $request->input('total_amount');
        $orderDate = now()->format('Y-m-d H:i:s');
        $orderNumber = 'ORD-' . strtoupper(Str::random(5)) . '-' . time();
        $productData = $request->input('product_data');

        // $productData = [
        //     [
        //         "product_id" => "1",
        //         "qty" => "5",
        //         "price" => "506",
        //         "extras" => [
        //             [
        //                 "ext_id" => "5",
        //                 "amount" => "205"
        //             ],
        //             [
        //                 "ext_id" => "6",
        //                 "amount" => "305"
        //             ]
        //         ]
        //     ],
        //     // Add more products here if needed
        // ];

        // Company details (if applicable)
        $companyDetails = ($userType == 2 || $userType == 3) ? [
            'company_name' => $request->input('company_name'),
            'vat_no' => $request->input('vat_no'),
        ] : null;

        // Billing address (if different)
        $billingAddress = ($diffBilling == '1') ? [
            'postal_code' => $request->input('billing_post_code'),
            'place' => $request->input('billing_place'),
            'house_no' => $request->input('billing_house'),
            'street' => $request->input('billing_street'),
            'shipping_address' => $request->input('billing_address'),
        ] : null;

        // Shipping address
        $shippingAddress = [
            'postal_code' => $request->input('post_code'),
            'place' => $request->input('place'),
            'house_no' => $request->input('house_no'),
            'street' => $request->input('street'),
            'shipping_address' => $request->input('shipping_address'),
        ];

        // Initialize order object
        $orderNew = new ShopOrder();
        $orderNew->fill([
            'user_id' => $userId,
            'order_number' => $orderNumber,
            'user_type' => $userType,
            'salutation' => $salutation,
            'guest_fname' => $fName,
            'guest_lname' => $lName,
            'guest_phone' => $phoneNumber,
            'guest_email' => $email,
            'total_amount' => $totalAmount,
            'order_date' => $orderDate,
            'exp_delivery' => $deliveredBy,
            'bill_different' => $diffBilling,
            'shipping_address' => json_encode($shippingAddress),
            'company_details' => json_encode($companyDetails),
            'billing_address' => json_encode($billingAddress),
        ]);

        DB::beginTransaction();
        try {
            $orderNew->save();
            $orderId = $orderNew->id;

            // Prepare product details and extras data
            $orderDetails = [];
            $extras = [];

            if (!empty($productData) && is_array($productData)) {
                foreach ($productData as $product) {
                    $orderDetails[] = [
                        'order_id' => $orderId,
                        'product_id' => $product['product_id'],
                        'quantity' => $product['qty'],
                        'price' => $product['price'],
                        'total' => $product['qty'] * $product['price'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (!empty($product['extras']) && is_array($product['extras'])) {
                        foreach ($product['extras'] as $extra) {
                            $extras[] = [
                                'order_id' => $orderId,
                                'product_id' => $product['product_id'],
                                'extra_id' => $extra['ext_id'],
                                'extra_price' => $extra['amount'],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }

                // Insert product details and extras
                ShopOrderDetail::insert($orderDetails);
                if (!empty($extras)) {
                    ExtraProduct::insert($extras);
                }

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Order Created Successfully.',
                    'order_number' => $orderNumber,
                    'order_id' => $orderId
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Product data is empty or invalid.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false,'message' => $e->getMessage()]);
        }
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
    //             return response()->json(['error' => 'Category not found'] );
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
    //     ]);
    // }

}
