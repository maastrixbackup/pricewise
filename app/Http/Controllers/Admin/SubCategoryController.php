<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmartPhone;
use App\Models\SmartPhoneFaq;
use App\Models\Provider;
use App\Models\ProviderDiscount;
use App\Models\Document;
use App\Models\DefaultProduct;
use App\Models\AdditionalInfo;
use App\Models\ShopProduct;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\PostFeature;
use App\Models\TvPackage;
use App\Models\Combo;
use App\Models\Affiliate;
use App\Models\Feature;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub_cat = SubCategory::with('categoryDetails')->orderBy('id', 'desc')->get();
        // dd($sub_cat);
        return view('admin.sub_categories.index', compact('sub_cat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::orderBy('id', 'desc')->whereNull('parent')->get();
        return view('admin.sub_categories.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'title' => 'required|unique:sub_categories,title',
            'slug' => 'required',
            'category_id' => 'required',
            'status' => 'required',
        ]);


        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $sub_category = new SubCategory();
        $sub_category->title = $request->title;
        $sub_category->slug = $slug;
        $sub_category->status = $request->status;
        $sub_category->category_id = $request->category_id;

        if ($request->image) {
            // Generate a unique file name for the image
            $imageName = 'sub_category_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

            $destinationDirectory = public_path('storage/images/sub_categories');

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);

            $sub_category->image = $imageName;
        }

        if ($sub_category->save()) {
            return redirect()->back()->with(Toastr::success('Sub Category Added Succcessfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            return redirect()->back()->with(Toastr::error('Unable To Add Sub Category', '', ["positionClass" => "toast-top-right"]));
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
        $subCat = SubCategory::find($id);
        $category = Category::orderBy('id', 'desc')->whereNull('parent')->get();
        return view('admin.sub_categories.edit', compact('subCat', 'category'));
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
            'title' => 'required',
            'slug' => 'required',
            'category_id' => 'required',
            'status' => 'required',
        ]);


        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $sub_category = SubCategory::find($id);
        $sub_category->title = $request->title;
        $sub_category->slug = $slug;
        $sub_category->status = $request->status;
        $sub_category->category_id = $request->category_id;

        if ($request->image) {
            // Generate a unique file name for the image
            $imageName = 'sub_category_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

            $destinationDirectory = public_path('storage/images/sub_categories');

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);


            $existingFilePath = public_path('storage/images/sub_categories/') . $sub_category->image;

            if (file_exists($existingFilePath)) {
                // Delete the file
                unlink($existingFilePath);
            }

            $sub_category->image = $imageName;
        }

        try {
            if ($sub_category->save()) {
                return redirect()->back()->with(Toastr::success('Sub Category Updated', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
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
            SubCategory::find($id)->delete();
            return back()->with(Toastr::success('Sub Category deleted !', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
}
