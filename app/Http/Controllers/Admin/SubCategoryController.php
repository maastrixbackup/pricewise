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

        $sub_category = new SubCategory();
        $sub_category->title = $request->title;
        $sub_category->slug = $request->slug;
        $sub_category->status = $request->status;
        $sub_category->category_id = $request->category_id;

        if ($sub_category->save()) {
            return redirect()->back()->with(Toastr::success('Sub Category Added Succcessfully','', ["positionClass" => "toast-top-right"]));
        } else {
            return redirect()->back()->with(Toastr::error('Unable To Add Sub Category','', ["positionClass" => "toast-top-right"]));
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
        return view('admin.sub_categories.edit');
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
