<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DealProduct;
use App\Models\ComboProduct;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductPromotion;
use App\Models\ProductRating;
use App\Models\ProductRequest;
use App\Models\ShopProduct;
use App\Models\NotifyProduct;
use App\Models\ShopSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\Mail\AvailableProductNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


use App\Http\Controllers\Api\ShopProductController;

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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Product Added successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
    public function update(Request $request, $id, \App\Http\Controllers\Api\ShopProductController $shopProductController)
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

                // Notify users about product availability
                if ($shopProduct->qty > 0) {
                    $notifyProducts = NotifyProduct::where(['product_id' => $shopProduct->id, 'notified' => '0'])->get();

                    $shopProductController = new \App\Http\Controllers\Api\ShopProductController();

                    foreach ($notifyProducts as $notifyProduct) {
                        $params = [
                            'user_name' => $notifyProduct->userDetails->name,
                            'product_name' => $shopProduct->title,
                            'url' => config('frontend.url') . 'shop/' . $shopProduct->slug,
                            'email' => $notifyProduct->email,
                            'notify_id' => $notifyProduct->id
                        ];

                        // Call the method in the ShopProductController
                        $response = $shopProductController->productNotifySend($params);
                        if ($response['status'] == 'success') {
                            // Update the notification status to 'notified'
                            NotifyProduct::where('id', $notifyProduct->id)->update(['notified' => '1']);
                        }
                    }
                }

                DB::commit();
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Product updated successfully.',
                    'title' => ''
                ]);
                return redirect()->route('admin.products.index');
            }

            throw new \Exception('Failed to update product');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Image updated successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('toastr', [
                    'type' => 'error',
                    'message' => $e->getMessage(),
                    'title' => ''
                ]);
                return redirect()->back();
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
            session()->flash('toastr', [
                'type' => 'error',
                'message' => 'Highlights Cannot be Empty.',
                'title' => ''
            ]);
            return redirect()->back();
        }

        // Check if the new highlight already exists in the array
        if (array_intersect($newHighlight, $hData)) {
            session()->flash('toastr', [
                'type' => 'error',
                'message' => 'Highlights Already Exists.',
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Highlights updated successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } else {
            session()->flash('toastr', [
                'type' => 'error',
                'message' => 'You have reached Max Limit 3.',
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Description updated successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }


    public function update_product_features(Request $request, $id)
    {
        // Retrieve the existing record from the database
        $pFeatures = ShopProduct::find($request->id); // Ensure you replace YourModel with the actual model name and $id with the relevant identifier

        // Check if the record exists
        if (!$pFeatures) {
            session()->flash('toastr', [
                'type' => 'error',
                'message' => 'Record Not Found.',
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Specification updated successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
            ]);
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
            session()->flash('toastr', [
                'type' => 'success',
                'message' => 'Product deleted successfully.',
                'title' => ''
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Brand Added successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Brand updated successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }
    public function brand_destroy(Request $request)
    {
        try {
            ProductBrand::where('id', $request->id)->delete();
            session()->flash('toastr', [
                'type' => 'success',
                'message' => 'Deal Deleted successfully.',
                'title' => ''
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Data Added successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Data updated successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }
    public function pCategory_destroy(Request $request)
    {
        try {
            ProductCategory::where('id', $request->id)->delete();
            session()->flash('toastr', [
                'type' => 'success',
                'message' => 'Data Deleted successfully.',
                'title' => ''
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Promotion Added successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Promotion Updated successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }
    public function promotion_destroy(Request $request)
    {
        try {
            ProductPromotion::where('id', $request->id)->delete();
            session()->flash('toastr', [
                'type' => 'success',
                'message' => 'Promotion Deleted successfully.',
                'title' => ''
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('toastr', [
                'type' => 'success',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Deal Created successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Deal Updated successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }
    public function deals_destroy(Request $request)
    {
        try {
            DealProduct::where('id', $request->id)->delete();
            session()->flash('toastr', [
                'type' => 'success',
                'message' => 'Deal Deleted successfully.',
                'title' => ''
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Color Added successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Color Updated successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }
    public function color_destroy(Request $request)
    {
        try {
            ProductColor::where('id', $request->id)->delete();
            session()->flash('toastr', [
                'type' => 'success',
                'message' => 'Color Deleted successfully.',
                'title' => ''
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Shop Setting Updated successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            } else {
                session()->flash('toastr', [
                    'type' => 'warning',
                    'message' => 'Unable to update the setting',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
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
            return response()->json(['error' => 'Review not found']);
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
                session()->flash('toastr', [
                    'type' => 'warning',
                    'message' => 'Product with n same SKU Already Exists.',
                    'title' => ''
                ]);
                return redirect()->back();
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
                session()->flash('toastr', [
                    'type' => 'success',
                    'message' => 'Product Added successfully.',
                    'title' => ''
                ]);
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }

    public function checkRequestDetails(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $checkRequest = ProductRequest::with('userDetails', 'productDetails')->find($id);
            $htmlData = '';
            if ($checkRequest) {
                $formattedCallbackDate = Carbon::parse($checkRequest->callback_date_time)->format('F j, Y g:i A'); // Format example: August 27, 2024 2:30 PM

                $htmlData = '
                <div class="card">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="' . asset('storage/images/shops/' . $checkRequest->productDetails->banner_image) . '"
                                    alt="" width="200px">
                            </div>
                            <div class="col-md-6 pt-3">
                                <div><b>' . $checkRequest->productDetails->title . '</b></div>
                                <small>SKU: ' . $checkRequest->productDetails->sku . '</small>
                                <div><b ><span style="font-size: 20px;">' . '€' . $checkRequest->productDetails->sell_price . ' </span><span style="text-decoration: line-through; font-size: 15px;">' . '€' . $checkRequest->productDetails->actual_price . '</span></b></div>
                            </div>
                                <hr/>
                            <div class="col-md-12">
                                <b>Customer Name:</b> ' . $checkRequest->user_name . '
                            </div>
                                <hr/>
                            <div class="col-md-12">
                                <b>Email:</b> ' . $checkRequest->email . '
                            </div>
                                <hr/>
                            <div class="col-md-12">
                                <b>Phone Number:</b> ' . $checkRequest->phone_number . '
                            </div>
                                <hr/>
                            <div class="col-md-12">
                                <b>Requested Quantity:</b> ' . $checkRequest->qty . '
                            </div>
                                <hr/>
                            <div class="col-md-12">
                                <b>Callback Date & Time:</b> ' . $formattedCallbackDate . '
                            </div>
                                <hr/>
                            <div class="col-md-12">
                                <b>Delivery Address:</b> ' . $checkRequest->delivery_address . '
                            </div>
                                <hr/>
                            <div class="col-md-12">
                                <b>Additional Message:</b> ' . $checkRequest->additional_info . '
                            </div>
                        </div>
                    </div>
                </div>';
            }
            return response()->json(['status' => true, 'htmlData' => $htmlData]);
        }
        return response()->json(['status' => false, 'htmlData' => '']);
    }

    public function requestedProduct(Request $req)
    {
        // Update the status of all relevant requests
        ProductRequest::where('status', '0')->update(['status' => '1']);
        $requestP = ProductRequest::latest()->with('userDetails', 'productDetails')->get();
        return view('admin.products.request_products', compact('requestP'));
    }

    public function notificationProduct(Request $request)
    {
        // Update the status of all relevant requests
        NotifyProduct::where('viewed', 'unread')->update(['viewed' => 'read']);
        $notifyP = NotifyProduct::latest()->with('userDetails', 'productDetails')->get();
        return view('admin.products.notify_products', compact('notifyP'));
    }

    public function checkNotification(Request $req)
    {
        if ($req->has('id')) {
            // Fetch the latest 5 product requests with status '0' and related data
            $rp = ProductRequest::latest()->where('status', '0')->with('userDetails', 'productDetails')->get();
            // Fetch the latest 5 notifications for products
            $np = NotifyProduct::latest()->where('viewed', 'unread')->get();
            // Initialize the notification output and count variables
            $notify = '';
            $count = 0;

            if ($rp->isNotEmpty()) {
                $count += $rp->count();
                foreach ($rp as $v) {
                    $specificTime = Carbon::parse($v->created_at);
                    // Format the notification item
                    $notify .= '
                                <li>
                                    <a class="d-flex text-dark py-2" href="' . route('admin.request_products') . '">
                                        <div class="flex-shrink-0 mx-3">
                                            <i class="fa fa-fw fa-bell text-success"></i>
                                        </div>
                                        <div class="flex-grow-1 fs-sm pe-2">
                                            <div class="fw-semibold">New request Added</div>
                                            <div class="text-muted">' . $specificTime->diffForHumans() . '</div>
                                        </div>
                                    </a>
                                </li>';
                }
            }

            if ($np->isNotEmpty()) {
                $count += $np->count();
                foreach ($np as $v) {
                    $specificTime = Carbon::parse($v->created_at);
                    // Format the notification item
                    $notify .= '
                            <li>
                                <a class="d-flex text-dark py-2" href="' . route('admin.notify_products') . '">
                                    <div class="flex-shrink-0 mx-3">
                                        <i class="fa fa-fw fa-bell text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 fs-sm pe-2">
                                        <div class="fw-semibold">New request for notification</div>
                                        <div class="text-muted">' . $specificTime->diffForHumans() . '</div>
                                    </div>
                                </a>
                            </li>';
                }
            }

            // If no notifications found, display a default message
            if ($count === 0) {
                $notify = '
            <li>
                <a class="d-flex text-center text-dark py-2" href="javascript:void(0)">
                    <div class="flex-grow-1 fs-sm pe-2">
                        <i class="fa fa-fw fa-bell-o text-danger"></i>
                        <div class="fw-semibold">There are No Notifications</div>
                    </div>
                </a>
            </li>';
            }

            return response()->json(['count' => $count, 'notify' => $notify]);
        }

        // Return default response if 'id' is not present in the request
        return response()->json(['count' => 0, 'notify' => $this->noNotificationsMessage()]);
    }

    private function noNotificationsMessage()
    {
        return '
            <li>
                <a class="d-flex text-center text-dark py-2" href="javascript:void(0)">
                    <div class="flex-grow-1 fs-sm pe-2">
                        <i class="fa fa-fw fa-bell-o text-danger"></i>
                        <div class="fw-semibold">There are No Notifications</div>
                    </div>
                </a>
            </li>';
    }

    public function comboDealsCreate(Request $request, $id)
    {
        $shopProduct = ShopProduct::where('id', '!=', $id)
            ->whereNotIn('p_status', ['0', '3'])
            ->get();
        $comboProducts = ComboProduct::where('product_id', $id)->with('comboProductDetails')->get();
        return view('admin.products.bulk_combos', compact('id', 'comboProducts', 'shopProduct'));
    }

    public function comboDealsStore(Request $request)
    {
        $dealNew = new ComboProduct();
        $dealNew->product_id = $request->product_id;
        $dealNew->deal_id = $request->deal_id;
        $dealNew->deal_price = $request->deal_price;
        $dealNew->status = $request->status;

        DB::beginTransaction();
        try {
            $dealNew->save();
            DB::commit();
            session()->flash('toastr', [
                'type' => 'success',
                'message' => 'Product Deal Added successfully.',
                'title' => ''
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }

    public function comboStatusUpdate(Request $request)
    {
        $chkDeal = ComboProduct::find($request->id);

        if ($chkDeal) {
            // Update the status based on the received value
            $chkDeal->status = $request->status;
            $chkDeal->save();

            return response()->json([
                "status" => true,
                "message" => 'Deal Product Status Updated'
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => 'Deal Product not found.'
            ]);
        }
    }


    public function comboDealsDelete(Request $request)
    {
        try {
            ComboProduct::find($request->id)->delete();
            session()->flash('toastr', [
                'type' => 'success',
                'message' => 'Deal Deleted successfully.',
                'title' => ''
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('toastr', [
                'type' => 'error',
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }
}
