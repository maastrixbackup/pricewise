<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DealProduct;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductPromotion;
use App\Models\ProductRating;
use App\Models\ProductRequest;
use App\Models\ShopProduct;
use App\Models\ShopSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shopProducts = ShopProduct::with('categoryDetails', 'brandDetails')->orderBy('id', 'desc')->get();
        $objBrand = ProductBrand::latest()->get();
        $objColor  = ProductColor::latest()->get();
        $objCategory  = ProductCategory::latest()->get();
        // dd($shopProducts);
        return view('admin.products.index', compact('shopProducts', 'objBrand', 'objColor', 'objCategory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $objBrand = ProductBrand::all();
        $objCategory = ProductCategory::all();
        $objColor = ProductColor::all();
        return view('admin.products.add', compact('objBrand', 'objCategory', 'objColor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        // dd($request->all());

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        DB::beginTransaction();
        try {
            $shopProduct = new ShopProduct();
            $shopProduct->title = trim($request->title);
            $shopProduct->slug = $slug;
            $shopProduct->long_title = $request->long_title;
            $shopProduct->model = $request->model;
            $shopProduct->sku = $request->sku;
            $shopProduct->size = $request->size;
            $shopProduct->brand_id = $request->brand;
            $shopProduct->category_id = $request->category;
            $shopProduct->color_id = $request->color;
            $shopProduct->actual_price = $request->actual_price;
            $shopProduct->exp_delivery = $request->exp_delivery;
            $shopProduct->sell_price = $request->selling_price;
            $shopProduct->delivery_cost = $request->delivery_cost;
            $shopProduct->qty = $request->qty;
            $shopProduct->about = $request->about;
            $shopProduct->p_status = $request->p_status;
            $shopProduct->is_featured = $request->is_featured;
            $shopProduct->new_arrival = $request->new_arrival;
            $shopProduct->pin_codes = json_encode($request->pin_codes ? explode(",", $request->pin_codes) : []);
            $shopProduct->is_publish = $request->status;


            if ($request->image) {
                // Handle the image file upload
                $filename = 'banner_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/shops/'), $filename);
            }
            // Save the filename in the database
            $shopProduct->banner_image = $filename ?? '';

            if ($shopProduct->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Product Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
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

        // $id1 = encrypt($id);
        // dd($id1);
        // $id1 = decrypt($id2);
        $objBrand = ProductBrand::all();
        $objCategory = ProductCategory::all();
        $objColor = ProductColor::all();
        $objImages = ProductImage::where('product_id', $id)->get();
        $objProduct = ShopProduct::find($id);

        $rating = ProductRating::where('product_id', $id)->get();
        $review = $rating->count();
        $rate = 0.0;

        if ($review > 0) {
            $totalRating = $rating->sum('rating');
            $rate = $totalRating / $review;
        }

        $ratingCount  = [

            '5 Star' => $rating->where('rating', 5)->count(),
            '4 Star' => $rating->where('rating', 4)->count(),
            '3 Star' => $rating->where('rating', 3)->count(),
            '2 Star' => $rating->where('rating', 2)->count(),
            '1 Star' => $rating->where('rating', 1)->count(),
        ];


        return view('admin.products.edit', compact('objProduct', 'objBrand', 'objCategory', 'objColor', 'objImages', 'ratingCount'));
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
        $request->validate([
            'title' => 'required'
        ]);


        // if ($shopProduct) {
        //     $slug = $shopProduct->slug;
        // }

        // // Convert to lowercase
        // $slug = strtolower($request->title);

        // // Remove special characters
        // $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // // Replace spaces and multiple hyphens with a single hyphen
        // $slug = preg_replace('/[\s-]+/', '-', $slug);

        // // Trim hyphens from the beginning and end of the string
        // $slug = trim($slug, '-');

        DB::beginTransaction();
        try {
            $shopProduct = ShopProduct::find($id);
            $shopProduct->title = trim($request->title);
            // $shopProduct->slug = $slug;
            $shopProduct->model = $request->model;
            $shopProduct->long_title = $request->long_title;
            $shopProduct->sku = $request->sku;
            $shopProduct->size = $request->size;
            $shopProduct->brand_id = $request->brand;
            $shopProduct->category_id = $request->category;
            $shopProduct->color_id = $request->color;
            $shopProduct->actual_price = $request->actual_price;
            $shopProduct->exp_delivery = $request->exp_delivery;
            $shopProduct->sell_price = $request->selling_price;
            $shopProduct->delivery_cost = $request->delivery_cost;
            $shopProduct->qty = $request->qty;
            $shopProduct->about = $request->about;
            $shopProduct->p_status = $request->p_status;
            $shopProduct->is_featured = $request->is_featured;
            $shopProduct->new_arrival = $request->new_arrival;
            $shopProduct->pin_codes = json_encode($request->pin_codes ? explode(",", $request->pin_codes) : []);
            $shopProduct->is_publish = $request->status;


            if ($request->image) {
                // Handle the image file upload
                $filename = 'banner_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/shops/'), $filename);

                // Check if the dealProduct has an existing image
                if (!empty($shopProduct->banner_image)) {
                    $existingFilePath = public_path('storage/images/shops/') . $shopProduct->banner_image;
                    if (file_exists($existingFilePath)) {
                        // Delete the file if it exists
                        unlink($existingFilePath);
                    }
                }
            }

            // Save the filename in the database
            $shopProduct->banner_image = $filename ?? $shopProduct->banner_image;

            if ($shopProduct->save()) {
                DB::commit();
                return redirect()->route('admin.products.index')->with(Toastr::success('Product Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function add_product_images(Request $request, $id)
    {
        $objProduct = ShopProduct::find($request->id);

        if ($request->hasfile('image')) {
            $insert = []; // Initialize the insert array outside the loop

            foreach ($request->file('image') as $key => $file) {
                // Generate a unique filename for each file
                $filename = 'productI_' . time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/images/shops/'), $filename);

                $insert[] = [
                    'product_id' => $objProduct->id,
                    'category_id' => $objProduct->category_id,
                    'image' => $filename,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            DB::beginTransaction();
            try {
                ProductImage::insert($insert);
                DB::commit();
                return redirect()->back()->with(Toastr::success('Image Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
            }
        }
    }

    public function delete_product_images(Request $request)
    {
        $productImage = ProductImage::where('id', $request->id)->first();
        // return response()->json($productImage);
        if ($productImage) {
            // Check if the productImage has an existing image
            if (!empty($productImage->image)) {
                $existingFilePath = public_path('storage/images/shops/') . $productImage->image;

                // Delete the file if it exists
                if (file_exists($existingFilePath)) {
                    unlink($existingFilePath);
                }
            }

            // Delete the image record
            $productImage->delete();

            // Fetch updated image data for the product
            $imgData = ProductImage::where('product_id', $request->product_id)->get();
            $imgData = $imgData->map(function ($img) {
                $img->image = asset('storage/images/shops/' . $img->image);
                return $img;
            });

            // Prepare the success response
            $message = ['message' => 'Image deleted successfully.', 'title' => ''];
            return response()->json([
                'status' => true,
                'imgdata' => $imgData,
                'message' => $message
            ]);
        } else {
            // Product image not found message
            $message = ['message' => 'Product image not found.', 'title' => ''];
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }
    }
    public function storeProductHighlight(Request $request, $id)
    {
        $shopP = ShopProduct::find($id);

        // Decode existing highlights JSON into an array
        $hData = json_decode($shopP->heighlights, true) ?: [];

        // Get the new highlight from the request
        $newHighlight = $request->input('highlight');

        // Ensure $newHighlight is not empty
        if (empty($newHighlight)) {
            return redirect()->back()->with(Toastr::error('Highlight cannot be empty', '', ["positionClass" => "toast-top-right"]));
        }

        // Check if the new highlight already exists in the array
        if (array_intersect($newHighlight, $hData)) {
            return redirect()->back()->with(Toastr::error('Highlight already exists', '', ["positionClass" => "toast-top-right"]));
        }

        // If $hData is null (no highlights yet), initialize it as an empty array
        if (!is_array($hData)) {
            $hData = [];
        }

        // Check if we have less than 3 highlights
        if (count($hData) < 3) {
            if ($request->has('highlight')) {
                // Get the new highlights from the request
                $newHighlights = $request->input('highlight');

                // Append the new highlights to the existing array
                $hData = array_merge($hData, (array)$newHighlights);

                // Ensure we do not exceed the maximum of 3 highlights
                $hData = array_slice($hData, 0, 3);

                // Encode highlights back to JSON and save
                $shopP->heighlights = json_encode($hData);
            }

            if ($shopP->save()) {
                return redirect()->back()->with(Toastr::success('Highlights Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } else {
            return redirect()->back()->with(Toastr::error('You have reached Max limit 3', '', ["positionClass" => "toast-top-right"]));
        }
    }


    public function delete_p_highlight(Request $request)
    {
        $productH = ShopProduct::find($request->id);

        // Decode the highlights JSON field
        $highlights = json_decode($productH->heighlights, true);

        // Check if the highlight exists in the array and remove it
        if (($key = array_search($request->key, $highlights)) !== false) {
            unset($highlights[$key]);
        }

        // Re-index the array (optional, but keeps the array tidy)
        $highlights = array_values($highlights);

        // Encode the modified array back to JSON
        $productH->heighlights = json_encode($highlights);

        // Save the updated product
        $productH->save();
        $pData = json_decode($productH->heighlights, true);

        return response()->json(['status' => true, 'message' => 'Highlight removed successfully', 'pData' => $pData]);
    }

    public function update_product_description(Request $request, $id)
    {
        $pDescription = ShopProduct::where('id', $request->id)->first();
        $pDescription->description = $request->p_description;
        DB::beginTransaction();
        try {
            if ($pDescription->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Description Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }


    public function update_product_features(Request $request, $id)
    {
        // Retrieve the existing record from the database
        $pFeatures = ShopProduct::find($request->id); // Ensure you replace YourModel with the actual model name and $id with the relevant identifier

        // Check if the record exists
        if (!$pFeatures) {
            return redirect()->back()->with(Toastr::error('Record not found.', '', ["positionClass" => "toast-top-right"]));
        }

        // Fetch the current specification from the record
        $currentData = json_decode($pFeatures->specification, true) ?? [];

        // Retrieve and handle the request inputs
        $key = $request->key ?? [];
        $value = $request->value ?? [];
        $key1 = $request->key1 ?? [];
        $value1 = $request->value1 ?? [];

        // Combine arrays into associative arrays
        $merged_array = array_combine($key, $value) ?: [];
        $merged_array1 = array_combine($key1, $value1) ?: [];

        // Initialize $data array
        $data = $currentData; // Start with existing data

        // Update or add new entries in the 'General' section
        if (!empty($merged_array)) {
            $data['General'] = array_merge($currentData['General'] ?? [], $merged_array);
        }

        // Update or add new entries in the 'Product Details' section
        if (!empty($merged_array1)) {
            $data['Product_Details'] = array_merge($currentData['Product_Details'] ?? [], $merged_array1);
        }

        // Debug output
        // dd(json_encode($data, true));

        // Save to database
        $pFeatures->specification = json_encode($data, true);

        DB::beginTransaction();

        try {
            if ($pFeatures->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Specification Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function delete_p_specification(Request $request)
    {
        $sProduct = ShopProduct::find($request->id);
        $specification = json_decode($sProduct->specification, true);

        foreach ($specification as $key => $value) {
            if (array_key_exists($request->key, $value)) {
                unset($specification[$key][$request->key]);
                // if (count($specification[$key]) == 0) {
                //     unset($specification[$key]);
                // }
            }
        }

        $sProduct->specification = json_encode($specification);
        $sProduct->save();

        $product = json_decode($sProduct->specification, true);
        return response()->json(["status" => true, "product" => $product, "pid" => $request->id, 'message' => 'Specification Deleted']);
    }

    public function update_new_arrival(Request $request)
    {
        $newArrival = ShopProduct::find($request->id);

        if ($newArrival) {
            $key = $request->key;

            if ($newArrival->new_arrival == 1) {
                $newArrival->new_arrival = null;
            } else {
                $newArrival->new_arrival = 1;
            }

            $newArrival->save();

            return response()->json([
                "status" => true,
                "product" => $newArrival,
                "message" => 'New Arrival Status Updated'
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => 'Product not found'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            ShopProduct::find($id)->delete();
            return redirect()->back()->with(Toastr::error(__('Product deleted successfully!')));
        } catch (\Exception $e) {
            $error_msg = Toastr::error(__($e->getMessage()));
            return redirect()->back()->with($error_msg);
        }
    }



    // Brands
    public function brand_index(Request $request)
    {
        $brands = ProductBrand::with('categoryDetails')->get();
        return view('admin.product_brand.index', compact('brands'));
    }
    public function brand_create(Request $request)
    {
        return view('admin.product_brand.add');
    }
    public function brand_store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category' => 'required'
        ]);

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        DB::beginTransaction();
        try {
            $productBrand = new ProductBrand();
            $productBrand->title = $request->title;
            $productBrand->slug = $slug;
            $productBrand->category = $request->category;
            $productBrand->status = $request->status;
            if ($productBrand->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Brand Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::success($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
    public function brand_edit(Request $request, $id)
    {
        $brand = ProductBrand::find($id);
        return view('admin.product_brand.edit', compact('brand'));
    }
    public function brand_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'category' => 'required'
        ]);
        // dd($request->all());

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        DB::beginTransaction();
        try {
            $productBrand = ProductBrand::find($id);
            $productBrand->title = $request->title;
            $productBrand->slug = $slug;
            $productBrand->category = $request->category;
            $productBrand->status = $request->status;
            if ($productBrand->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Brand Upodated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::success($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
    public function brand_destroy(Request $request)
    {
        try {
            ProductBrand::where('id', $request->id)->delete();
            return redirect()->back()->with(Toastr::success('Deal Deleted Successfully', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }


    // Product Category
    public function pCategory_index(Request $request)
    {
        $categories = ProductCategory::latest()->get();
        return view('admin.product_category.index', compact('categories'));
    }

    public function pCategory_create(Request $request)
    {
        return view('admin.product_category.add');
    }

    public function pCategory_store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:product_categories,title'
        ]);

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');
        DB::beginTransaction();
        try {
            $category = new ProductCategory();
            $category->title = trim($request->title);
            $category->slug = $slug;
            $category->status = $request->status;

            if ($request->image) {
                // Handle the image file upload
                $filename = 'category_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/shops/'), $filename);
            }
            // Save the filename in the database
            $category->image = $filename ?? '';

            if ($category->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Data Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
    public function pCategory_edit(Request $request, $id)
    {
        $category = ProductCategory::find($id);
        return view('admin.product_category.edit', compact('category'));
    }

    public function pCategory_update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required'
        ]);
        // dd($request->all());

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        DB::beginTransaction();
        try {
            $category = ProductCategory::find($id);
            $category->title = trim($request->title);
            $category->slug = $slug;
            $category->status = $request->status;

            if ($request->image) {
                // Handle the image file upload
                $filename = 'category_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/shops/'), $filename);

                // Check if the dealProduct has an existing image
                if (!empty($category->image)) {
                    $existingFilePath = public_path('storage/images/shops/') . $category->image;
                    if (file_exists($existingFilePath)) {
                        // Delete the file if it exists
                        unlink($existingFilePath);
                    }
                }
            }
            // Save the filename in the database
            $category->image = $filename ?? $category->image;

            if ($category->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Data Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
    public function pCategory_destroy(Request $request)
    {
        try {
            ProductCategory::where('id', $request->id)->delete();
            return redirect()->back()->with(Toastr::success('Data Deleted Successfully', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    // Product Promotion
    public function promotion_index(Request $request)
    {
        $promotions = ProductPromotion::latest()->get();
        return view('admin.product_promotion.index', compact('promotions'));
    }
    public function promotion_create(Request $request)
    {
        return view('admin.product_promotion.add');
    }
    public function promotion_store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:product_promotions,title',
            'sub_title' => 'required',
            'btn_text' => 'required',
            'sub_title' => 'required',
            'image' => 'required',
        ]);
        // dd($request->all());

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        DB::beginTransaction();
        try {

            $promotion = new ProductPromotion();
            $promotion->title = trim($request->title);
            $promotion->slug = $slug;
            $promotion->sub_title = $request->sub_title;
            $promotion->description = $request->description;
            $promotion->btn_text = $request->btn_text;
            $promotion->btn_url = $request->btn_url;
            $promotion->status = $request->status;

            if ($request->image) {
                // Handle the image file upload
                $filename = 'promotion_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/shops/'), $filename);
            }
            // Save the filename in the database
            $promotion->image = $filename ?? '';
            if ($promotion->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Promotion Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
    public function promotion_edit(Request $request, $id)
    {
        $promotion = ProductPromotion::find($id);
        return view('admin.product_promotion.edit', compact('promotion'));
    }
    public function promotion_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'sub_title' => 'required',
            'btn_text' => 'required',
            'sub_title' => 'required',
        ]);

        // dd($request->all());

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        DB::beginTransaction();
        try {

            $promotion = ProductPromotion::find($id);
            $promotion->title = trim($request->title);
            $promotion->slug = $slug;
            $promotion->sub_title = $request->sub_title;
            $promotion->description = $request->description;
            $promotion->btn_text = $request->btn_text;
            $promotion->btn_url = $request->btn_url;
            $promotion->status = $request->status;

            if ($request->image) {
                // Handle the image file upload
                $filename = 'promotion_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/shops/'), $filename);

                // Check if the dealProduct has an existing image
                if (!empty($promotion->image)) {
                    $existingFilePath = public_path('storage/images/shops/') . $promotion->image;
                    if (file_exists($existingFilePath)) {
                        // Delete the file if it exists
                        unlink($existingFilePath);
                    }
                }
            }
            // Save the filename in the database
            $promotion->image = $filename ?? $promotion->image;
            if ($promotion->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Promotion Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
    public function promotion_destroy(Request $request)
    {
        try {
            ProductPromotion::where('id', $request->id)->delete();
            return redirect()->back()->with(Toastr::success('Promotion Deleted Successfully', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    // Deals Products
    public function deals_index(Request $request)
    {
        $dealsProduct = DealProduct::with('categoryDetails')->get();
        // dd($dealsProduct);
        return view('admin.deals_product.index', compact('dealsProduct'));
    }
    public function deals_create(Request $request)
    {
        return view('admin.deals_product.add');
    }
    public function deals_store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:deal_products,title',
            'valid_till' => 'required',
            'category' => 'required',
            // 'image' => 'required'
        ]);

        // dd($request->all());

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        DB::beginTransaction();
        try {
            $dealProduct = new DealProduct();
            $dealProduct->title = $request->title;
            $dealProduct->slug = $slug;
            $dealProduct->valid_till = $request->valid_till;
            $dealProduct->category = $request->category;
            $dealProduct->status = $request->status;

            if ($request->image) {
                // Handle the image file upload
                $filename = 'deals_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/shops/'), $filename);
            }
            // Save the filename in the database
            $dealProduct->image = $filename ?? '';

            if ($dealProduct->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Deal Created Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
    public function deals_edit(Request $request, $id)
    {
        $deal = DealProduct::find($id);
        return view('admin.deals_product.edit', compact('deal'));
    }
    public function deals_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'valid_till' => 'required',
            'category' => 'required',
            // 'image' => 'required'
        ]);

        // dd($request->all());

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        DB::beginTransaction();
        try {
            $dealProduct = DealProduct::find($id);
            $dealProduct->title = $request->title;
            $dealProduct->slug = $slug;
            $dealProduct->valid_till = $request->valid_till;
            $dealProduct->category = $request->category;
            $dealProduct->status = $request->status;

            if ($request->image) {
                // Handle the image file upload
                $filename = 'deals_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/shops/'), $filename);

                // Check if the dealProduct has an existing image
                if (!empty($dealProduct->image)) {
                    $existingFilePath = public_path('storage/images/shops/') . $dealProduct->image;
                    if (file_exists($existingFilePath)) {
                        // Delete the file if it exists
                        unlink($existingFilePath);
                    }
                }
            }
            // Save the filename in the database
            $dealProduct->image = $filename ?? $dealProduct->image;

            if ($dealProduct->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Deal Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
    public function deals_destroy(Request $request)
    {
        try {
            DealProduct::where('id', $request->id)->delete();
            return redirect()->back()->with(Toastr::success('Deal Deleted Successfully', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    // Colors
    public function color_index(Request $request)
    {
        $colors = ProductColor::latest()->get();
        return view('admin.product_color.index', compact('colors'));
    }
    public function color_create(Request $request)
    {
        return view('admin.product_color.add');
    }
    public function color_store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:product_colors,title',
        ]);

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        DB::beginTransaction();
        try {
            $color = new ProductColor();
            $color->title = $request->title;
            $color->slug = $slug;
            $color->status = $request->status;
            if ($color->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Color Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::success($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
    public function color_edit(Request $request, $id)
    {
        $color = ProductColor::find($id);
        return view('admin.product_color.edit', compact('color'));
    }
    public function color_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
        ]);

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');
        // dd($slug);
        DB::beginTransaction();
        try {
            $color = ProductColor::find($id);
            $color->title = $request->title;
            $color->slug = $slug;
            $color->status = $request->status;
            if ($color->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Color Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::success($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
    public function color_destroy(Request $request)
    {
        try {
            ProductColor::where('id', $request->id)->delete();
            return redirect()->back()->with(Toastr::success('Color Deleted Successfully', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::success($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function shopEdit(Request $request)
    {
        $shopSetting = ShopSetting::find(1);
        return view('admin.shop_setting.edit', compact('shopSetting'));
    }
    public function shopStore(Request $request)
    {
        // dd($request->all());
        try {
            $shopSetting = ShopSetting::find(1);

            $shopSetting->order_above = $request->order_above;
            $shopSetting->order_time = $request->order_time;
            $shopSetting->period = $request->period;
            $shopSetting->limited_stock = $request->limited_stock;


            if ($request->image) {
                // Handle the image file upload
                $filename = 'payment_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/shops/'), $filename);

                // Check if the dealProduct has an existing image
                if (!empty($shopSetting->image)) {
                    $existingFilePath = public_path('storage/images/shops/') . $shopSetting->image;
                    if (file_exists($existingFilePath)) {
                        // Delete the file if it exists
                        unlink($existingFilePath);
                    }
                }
            }
            // Save the filename in the database
            $shopSetting->image = $filename ?? $shopSetting->image;

            if ($shopSetting->save()) {
                return redirect()->back()->with(Toastr::success('Shop Setting Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            } else {
                return redirect()->back()->with(Toastr::warning('Unable to Update the setting !', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function ratingsView(Request $request)
    {
        $ratings = ProductRating::with('productDetails', 'userDetails')->get();
        return view('admin.products.ratings', compact('ratings'));
    }

    public function viewRatings(Request $request)
    {
        $id = $request->id;
        $rating = ProductRating::where('product_id', $id)->get();
        $reviewCount = $rating->count();
        $averageRating = 0.0;

        if ($reviewCount > 0) {
            $totalRating = $rating->sum('rating');
            $averageRating = $totalRating / $reviewCount;

            $averageRating = round($averageRating, 1); // This will round it to 3.6
        }

        $ratingCount = [
            '5 Star' => $rating->where('rating', 5)->count(),
            '4 Star' => $rating->where('rating', 4)->count(),
            '3 Star' => $rating->where('rating', 3)->count(),
            '2 Star' => $rating->where('rating', 2)->count(),
            '1 Star' => $rating->where('rating', 1)->count(),
        ];

        $totalRatings = array_sum($ratingCount);

        $rateData = '';  // Initialize an empty string to hold the HTML output

        foreach ($ratingCount as $key => $count) {
            $percentage = $totalRatings > 0 ? ($count / $totalRatings) * 100 : 0;
            $rateData .= '<tr class="rating-bar">
                            <td style="width: 10%;">' . $key . '</td>
                            <td style="width: 80%;">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" role="progressbar"
                                        aria-valuenow="' . round($percentage) . '" aria-valuemin="0"
                                        aria-valuemax="100"
                                        style="width: ' . round($percentage) . '%; background-color: orange;">
                                    </div>
                                </div>
                            </td>
                            <td style="width: 10%;">(' . $count . ')</td>
                        </tr>';
        }

        return response()->json([
            'success' => true,
            'averageRating' => $averageRating,
            'totalRatings' => $totalRatings,
            'totalReview' => $reviewCount,
            'rateData' => $rateData,
            'message' => 'Showing..'
        ]);
    }

    public function reviewDetails(Request $request)
    {
        // Retrieve the single ProductRating record with related user and product details
        $reviewDetails = ProductRating::with('userDetails', 'productDetails')->find($request->id);

        // Check if the record exists
        if (!$reviewDetails) {
            return response()->json(['error' => 'Review not found'], 404);
        }

        // Transform the data to the desired format
        $reviewDetails = [
            'id' => $reviewDetails->id,
            'rating' => $reviewDetails->rating,
            'review' => $reviewDetails->review,
            'product' => [
                'id' => $reviewDetails->productDetails->id,
                'title' => $reviewDetails->productDetails->title,
                'sku' => $reviewDetails->productDetails->sku,
                'size' => $reviewDetails->productDetails->size,
                'actual_price' => $reviewDetails->productDetails->actual_price,
                'sell_price' => $reviewDetails->productDetails->sell_price,
            ],
            'user' => [
                'id' => $reviewDetails->userDetails->id,
                'name' => $reviewDetails->userDetails->name,
                'email' => $reviewDetails->userDetails->email,
                'mobile' => $reviewDetails->userDetails->mobile,
                'photo' => asset('storage/images/customers/' . $reviewDetails->userDetails->photo)
            ],
        ];

        return response()->json($reviewDetails);
    }

    public function duplicateProduct(Request $request)
    {
        $objBrand = ProductBrand::latest()->get();
        $objColor = ProductColor::latest()->get();
        $objCategory = ProductCategory::latest()->get();
        $id = $request->product_id;
        $objProduct = ShopProduct::find($id);

        $productData = view('admin.partials.product-form', compact('objBrand', 'objColor', 'objCategory', 'objProduct'))->render();

        return response()->json(['success' => true, 'html' => $productData], 200);
    }

    public function storeDuplicateProduct(Request $request)
    {
        if ($request->has('sku')) {
            $product = ShopProduct::where('sku', $request->input('sku'))->first();
            if ($product) { // Check if $product is not null
                return redirect()->back()->with(Toastr::error('Product with Same SKU Already exists', '', ["positionClass" => "toast-top-right"]));
            }
        }

        $exitProduct = ShopProduct::find($request->input('id'));
        // dd($request->all());
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        // Check if the slug already exists in the database
        $originalSlug = $slug;
        $count = 1;

        while (ShopProduct::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        DB::beginTransaction();
        try {
            $shopProduct = new ShopProduct();
            $shopProduct->title = trim($request->title);
            $shopProduct->slug = $slug;
            $shopProduct->model = $request->model;
            $shopProduct->sku = $request->sku;
            $shopProduct->size = $request->size;
            $shopProduct->brand_id = $request->brand;
            $shopProduct->category_id = $request->category;
            $shopProduct->color_id = $request->color;
            $shopProduct->actual_price = $request->actual_price;
            $shopProduct->exp_delivery = $request->exp_delivery;
            $shopProduct->sell_price = $request->selling_price;
            $shopProduct->delivery_cost = $request->delivery_cost;
            $shopProduct->qty = $request->qty;
            $shopProduct->about = $request->about;
            $shopProduct->description = $exitProduct->description;
            $shopProduct->specification = $exitProduct->specification;
            $shopProduct->heighlights = $exitProduct->heighlights;
            $shopProduct->p_status = $request->p_status;
            $shopProduct->is_featured = $request->is_featured;
            $shopProduct->new_arrival = $request->new_arrival;
            $shopProduct->pin_codes = json_encode($request->pin_codes ? explode(",", $request->pin_codes) : []);
            $shopProduct->is_publish = $request->status;


            if ($request->image) {
                // Handle the image file upload
                $filename = 'banner_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/shops/'), $filename);
            }
            // Save the filename in the database
            $shopProduct->banner_image = $filename ?? '';

            if ($shopProduct->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Product Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function requestedProduct(Request $req)
    {
        if ($req->has('id')) {
            $rp = ProductRequest::latest()->take(10)->where('status', '0')->with('userDetails', 'productDetails')->get();
            $count = $rp->count();
            $notify = '';

            if ($count > 0) {
                foreach ($rp as $v) {
                    $specificTime = Carbon::parse($v->curr_time); // Adjust if needed
                    // Format the notification item
                    $notify .= '
                    <li>
                        <a class="d-flex text-dark py-2" href="javascript:void(0)">
                            <div class="flex-shrink-0 mx-3">
                                <i class="fa fa-fw fa-bell text-success"></i>
                            </div>
                            <div class="flex-grow-1 fs-sm pe-2">
                                <div class="fw-semibold"> New request Added</div>
                                <div class="text-muted">' . $specificTime->diffForHumans() . '</div>
                            </div>
                        </a>
                    </li>';
                }

                return response()->json(['count' => $count, 'notify' => $notify]);
            } else {
                $notify = '
                    <li>
                        <a class="d-flex text-center text-dark py-2" href="javascript:void(0)">

                            <div class="flex-grow-1 fs-sm pe-2">
                                <i class="fa fa-fw fa-bell-o text-danger"></i>
                                <div class="fw-semibold">There is No Notifications</div>
                                <div class="text-muted"></div>
                            </div>
                        </a>
                    </li>';
                return response()->json(['count' => $count, 'notify' => $notify]);
            }
        }

        // Update the status of all relevant requests
        ProductRequest::where('status', '0')->update(['status' => '1']);
        $requestP = ProductRequest::latest()->with('userDetails', 'productDetails')->get();
        return view('admin.products.request_products', compact('requestP'));
    }

    public function checkRequestDetails(Request $request)
    {
        return $request->all();
    }
}
