<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvProduct;
use App\Models\CommissionType;
use App\Models\TvContractLength;
use App\Models\AdditionalCategory;
use App\Models\ShopCategory;
use App\Models\DefaultProduct;
use App\Models\AdditionalInfo;
use App\Models\ShopProduct;
use App\Models\Category;
use App\Models\Affiliate;
use App\Models\TvFeature;
use Brian2694\Toastr\Facades\Toastr;

use Illuminate\Support\Facades\Auth;

class TvProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objTv = TvProduct::latest()->get();
        return view('admin.tvproducts.index', compact('objTv'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $objContract = TvContractLength::latest()->get();
        // $objCommission = CommissionType::latest()->get();
        // $objAdditionalCategories = AdditionalCategory::latest()->get();
         $objRelatedProducts = TvProduct::orderBy('id', 'asc')->get();
         $objCategory = Category::latest()->get();
        // $objAffiliates = Affiliate::latest()->get();
         //$objFeature = TvFeature::latest()->get();
        return view('admin.tvproducts.add', compact('objCategory', 'objRelatedProducts'));
        //, compact('objContract', 'objCommission', 'objAdditionalCategories', 'objRelatedProducts', 'objCategory', 'objAffiliates', 'objFeature')
    }

    public function gettvproducts(Request $request)
    {

        ## Read value
        $draw = $request->get('draw');
        $row = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value
        ## Read value
        $data = array();
        $totalRecords = TvProduct::select('count(*) as allcount')->count();
        if ($searchValue) {
            $totalRecordswithFilter = TvProduct::select('count(*) as allcount')->where('title', 'like', '%' . $searchValue . '%');
        } else {
            $totalRecordswithFilter = TvProduct::select('count(*) as allcount');
        }
        if (isset($request->product_name)) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('title', 'like', '%' . $request->product_name . '%');
        }
        if (isset($request->product_type)) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('product_type', 'like', '%' . $request->product_type . '%');
        }
        if (isset($request->status)) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('status', $request->status);
        }
        $totalRecordswithFilter = $totalRecordswithFilter->count();

        // Fetch records
        if ($searchValue) {
            $productRecords = TvProduct::orderBy($columnName, $columnSortOrder)
                ->where('title', 'like', '%' . $searchValue . '%')
                ->select('tv_products.*');
        } else {
            $productRecords = TvProduct::orderBy($columnName, $columnSortOrder)
                ->select('tv_products.*');
        }
        if (isset($request->status)) {
            $productRecords = $productRecords->where('status',  $request->status);
        }
        if (isset($request->product_name)) {
            $productRecords = $productRecords->where('title', 'like', '%' . $request->product_name . '%');
        }
        if (isset($request->product_type)) {
            $productRecords = $productRecords->where('product_type', 'like', '%' . $request->product_type . '%');
        }
        $productRecords = $productRecords->skip($row)->take($rowperpage)->get();
        $i = 1;
        foreach ($productRecords as $key => $record) {

            $edit_btn = route('admin.tv-products.edit', $record->id);
            $delete_btn = route('admin.tv-products.destroy', $record->id);
            $default_btn = route('admin.tv-default', $record->id);
            $duplicate_btn = route('admin.duplicate-tv', $record->id);

            if ($record->product_type == 'consumer') {
                $pro_type =  '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Consumer</div>';
            } else if ($record->product_type == 'upgrade') {
                $pro_type =  '<div class="badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Upgrade</div>';
            } else if ($record->product_type == 'cln') {
                $pro_type =  '<div class="badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>CLN</div>';
            } else if ($record->product_type == 'affiliate') {
                $pro_type =  '<div class="badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Affiliate</div>';
            } else {
                $pro_type = '<div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Business</div>';
            }


            if ($record->status == 1) {
                $status =  '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Published</div>';
            } else if ($record->status == 2) {
                $status =  '<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Legacy</div>';
            } else {
                $status =  '<div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Draft</div>';
            }

            $edit_link = $delete_link = $default_link = $duplicate_link = '';
            if (Auth::guard('admin')->user()->can('tv-product-edit')) {
                $edit_link =   '<a title="Edit" href="' . $edit_btn . '" class="btn1 btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>';
            }
            if (Auth::guard('admin')->user()->can('tv-product-delete')) {
                $delete_link = ' <a title="Delete" class="btn1 btn-outline-danger trash remove-tv" data-id="' . $record->id . '" data-action="' . $delete_btn . '"><i class="bx bx-trash me-0"></i></a>';
            }
            if (Auth::guard('admin')->user()->can('tv-product-default')) {
                $default_link = '  <a title="Default" class="btn1 btn-outline-success" href="' . $default_btn . '"><i class="bx bx-star me-0"></i></a>';
            }
            if (Auth::guard('admin')->user()->can('tv-products-duplicate')) {
                $duplicate_link = ' <a title="Duplicate" class="btn1 btn-outline-warning" href="' . $duplicate_btn . '"><i class="bx bx-copy me-0"></i></a>';
            }

            if ($record->add_extras || $record->related_products) {
                //         $action =  '<div class="col">
                //    <a title="Edit" href="' . $edit_btn . '" class="btn1 btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>
                //    <a title="Delete" class="btn1 btn-outline-danger trash remove-tv" data-id="' . $record->id . '" data-action="' . $delete_btn . '"><i class="bx bx-trash me-0"></i></a>
                //    <a title="Default" class="btn1 btn-outline-success" href="' . $default_btn . '"><i class="bx bx-star me-0"></i></a>
                //    </div>';
                $action =  '<div class="col">' . $edit_link . $delete_link . $default_link  . $duplicate_link . '</div>';
            } else {
                //             $action =  '<div class="col">
                //        <a title="Edit" href="' . $edit_btn . '" class="btn1 btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>
                //        <a title="Delete" class="btn1 btn-outline-danger trash remove-tv" data-id="' . $record->id . '" data-action="' . $delete_btn . '"><i class="bx bx-trash me-0"></i></a>

                //    </div>';

                $action =  '<div class="col">' . $edit_link . $delete_link . $duplicate_link . '</div>';
            }
            $data[] = array(
                "id" => $i++,
                "title" => $record->title,
                "product_type" => $pro_type,
                "status" => $status,
                "action" =>  $action,


            );
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "data" => $data
        );

        echo json_encode($response);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = TvProduct::where('title', $request->title)->first();
        if (isset($product) && $product->count() > 0) {
            $message = array('message' => 'Already Exists! Please enter unique title', 'title' => 'Already Exists!');
            return response()->json(["status" => false, 'message' => $message, 'title' => 'Already Exists!']);
        } else {

            // Convert to lowercase
            $slug = strtolower($request->title);

            // Remove special characters
            $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

            // Replace spaces and multiple hyphens with a single hyphen
            $slug = preg_replace('/[\s-]+/', '-', $slug);

            // Trim hyphens from the beginning and end of the string
            $slug = trim($slug, '-');

            $objTv = new TvProduct();
            $objTv->title = $request->title;
            $objTv->content = $request->description;
            //$objTv->avg_speed = $request->avg_speed;
            $objTv->avg_download_speed = $request->avg_download_speed;
            $objTv->avg_upload_speed = $request->avg_upload_speed;
            $objTv->price = $request->price;
            $objTv->contract_length_id = $request->contract_length_id;
            $objTv->commission = $request->commission;
            $objTv->commission_type = $request->commission_type;
            $objTv->feature_id = $request->features ? implode(",", $request->features) : "";
            $objTv->add_extras = $request->additional_extras ? implode(",", $request->additional_extras) : "";
            $objTv->related_products =  $request->related_products ? implode(",", $request->related_products) : "";
            $objTv->status = $request->online_status;
            $objTv->akj_product_id =  $request->akj_product_id;
            $objTv->akj_discount_id =  $request->akj_discount_id;
            $objTv->product_type = $request->product_type;
            $objTv->order_type = $request->order_types;
            $objTv->is_featured = $request->is_featured;
            $objTv->catalogue_name = $request->catalogue_name;
            $objTv->product_name_api = $request->product_name_api;
            $objTv->category_id =  $request->category;
            $objTv->url = $slug;
            $objTv->affiliate = $request->affiliate_name;
            $objTv->template = $request->template;
            $objTv->is_page = isset($request->is_page) ? $request->is_page : 0;
            if ($request->file('image') == null || $request->file('image') == '') {
                $input['image'] = $objTv->product_image;
            } else {
                $destinationPath = '/images';
                $imgfile = $request->file('image');
                $imgFilename = $imgfile->getClientOriginalName();
                $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
                $image = $imgFilename;
                $objTv->product_image = $image;
            }
            if ($objTv->save()) {
                Toastr::success('Tv Product Added Successfully', '', ["positionClass" => "toast-top-right"]);
                return response()->json(["status" => true, "redirect_location" => route("admin.tv-products.index")]);
            } else {
                $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
                return response()->json(["status" => false, 'message' => $message]);
            }
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
        $objTv = TvProduct::find($id);
        $objContract = TvContractLength::latest()->get();
        $objCommission = CommissionType::latest()->get();
        $objAdditionalCategories = AdditionalCategory::latest()->get();
        $objRelatedProducts = ShopCategory::orderBy('id', 'asc')->get();
        $objCategory = Category::latest()->where("product_type", "broadband")->get();
        $objAffiliates = Affiliate::latest()->get();
        $objFeature = TvFeature::latest()->get();
        return view('admin.tvproducts.edit', compact('objTv', 'objContract', 'objCommission', 'objAdditionalCategories', 'objRelatedProducts', 'objCategory', 'objAffiliates', 'objFeature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function tv_update(Request $request, $id)
    {

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $objTv = TvProduct::where('id', $id)->first();
        $objTv->title = $request->title;
        //$objTv->avg_speed = $request->avg_speed;
        $objTv->avg_download_speed = $request->avg_download_speed;
        $objTv->avg_upload_speed = $request->avg_upload_speed;
        $objTv->price = $request->price;
        $objTv->contract_length_id = $request->contract_length_id;
        $objTv->commission = $request->commission;
        $objTv->commission_type = $request->commission_type;
        $objTv->feature_id = $request->features ? implode(",", $request->features) : "";
        $objTv->add_extras = $request->additional_extras ? implode(",", $request->additional_extras) : "";
        $objTv->related_products =  $request->related_products ? implode(",", $request->related_products) : "";
        $objTv->status = $request->online_status;
        $objTv->akj_product_id =  $request->akj_product_id;
        $objTv->akj_discount_id =  $request->akj_discount_id;
        $objTv->product_type = $request->product_type;
        $objTv->order_type = $request->order_types;
        $objTv->is_featured = $request->is_featured;
        $objTv->catalogue_name = $request->catalogue_name;
        $objTv->product_name_api = $request->product_name_api;
        $objTv->category_id =  $request->category;
        $objTv->mpf_product = $request->mpf_product;
        $objTv->url = $slug;
        $objTv->affiliate = $request->affiliate_name;
        $objTv->template = $request->template;

        $objTv->content = $request->input('description');
        $objTv->is_page = isset($request->is_page) ? $request->is_page : 0;
        // dd($request->all());
        if ($request->file('image') == null || $request->file('image') == '') {
            $input['image'] = $objTv->product_image;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $objTv->product_image = $image;
        }
        if ($objTv->save()) {
            Toastr::success('Tv Product Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, "redirect_location" => route("admin.tv-products.index")]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
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
        $objTv = TvProduct::find($id);
        if ($objTv->delete()) {
            return back()->with(Toastr::error(__('Tv Data deleted successfully!')));
        } else {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.tv-products.index')->with($error_msg);
        }
    }

    public function default(Request $request, $id)
    {
        //dd($id);
        $product = TvProduct::find($id);
        $default = DefaultProduct::latest()->get();
        $responseData = array();
        $data = DefaultProduct::where('product_id', $id)->where('product_type', 'tv')->where('is_default', '1')->pluck('default_product_id')->toArray();
        $manda_data = DefaultProduct::where('product_id', $id)->where('product_type', 'tv')->where('is_mandatory', '1')->pluck('default_product_id')->toArray();

        return view('admin.tvproducts.default', compact('product', 'data', 'manda_data'));
    }

    public function default_update(Request $request)
    {

        if (isset($request->related_product_ids)) {
            foreach ($request->related_product_ids as $val) {
                $addinfo = ShopProduct::where('id', $val)->first();
                $category = ShopCategory::where('id',  $addinfo->category_id)->first();

                $default = DefaultProduct::where('default_product_category_id', $category->id)->where('product_id', $request->product_id)->where('product_type', 'tv')->where('default_product_type', 'related_product')->first();

                if ($default) {
                    $objDefault = DefaultProduct::find($default->id);
                    $objDefault->product_id = $request->product_id;
                    $objDefault->product_type = 'tv';
                    $objDefault->default_product_id = $val;
                    $objDefault->default_product_category_id = $category->id;
                    $objDefault->default_product_type = 'related_product';
                    $objDefault->is_default = 1;
                    $objDefault->save();
                } else {
                    $objDefault = new DefaultProduct();
                    $objDefault->product_id = $request->product_id;
                    $objDefault->product_type = 'tv';
                    $objDefault->default_product_id =  $val;
                    $objDefault->default_product_type = $request->add;
                    $objDefault->default_product_category_id = $category->id;
                    $objDefault->default_product_type = 'related_product';
                    $objDefault->is_default = 1;
                    $objDefault->save();
                }
            }
        }

        if (isset($request->mandatory_related_product_ids)) {
            foreach ($request->mandatory_related_product_ids as $val) {
                $addinfo = ShopProduct::where('id', $val)->first();
                $addinfo->is_mandatory = 1;
                $addinfo->save();
                $category = ShopCategory::where('id',  $addinfo->category_id)->first();
                $default = DefaultProduct::where('default_product_category_id', $category->id)->where('product_id', $request->product_id)->where('product_type', 'tv')->where('default_product_type', 'related_product')->where('is_mandatory', "1")->first();

                if ($default) {
                    $objDefault = DefaultProduct::find($default->id);
                    $objDefault->product_id = $request->product_id;
                    $objDefault->product_type = 'tv';
                    $objDefault->default_product_id = $val;
                    $objDefault->default_product_category_id = $category->id;
                    $objDefault->default_product_type = 'related_product';
                    $objDefault->is_mandatory = 1;
                    $objDefault->save();
                } else {
                    $objDefault = new DefaultProduct();
                    $objDefault->product_id = $request->product_id;
                    $objDefault->product_id = $request->product_id;
                    $objDefault->product_type = 'tv';
                    $objDefault->default_product_id =  $val;
                    $objDefault->default_product_type = $request->add;
                    $objDefault->default_product_category_id = $category->id;
                    $objDefault->default_product_type = 'related_product';
                    $objDefault->is_mandatory = 1;
                    $objDefault->save();
                }
            }
        }
        if (isset($request->addon_ids)) {
            foreach ($request->addon_ids as $val) {
                $addinfo = AdditionalInfo::where('id', $val)->first();
                $category = AdditionalCategory::where('id',  $addinfo->add_cat_id)->first();

                $default = DefaultProduct::where('default_product_category_id', $category->id)->where('product_id', $request->product_id)->where('product_type', 'tv')->where('default_product_type', 'addon')->first();
                if ($default) {
                    $objDefault = DefaultProduct::find($default->id);
                    $objDefault->product_id = $request->product_id;
                    $objDefault->product_type = 'tv';
                    $objDefault->default_product_id = $val;
                    $objDefault->default_product_category_id = $category->id;
                    $objDefault->default_product_type = 'addon';
                    $objDefault->is_default = 1;
                    $objDefault->save();
                } else {
                    $objDefault = new DefaultProduct();
                    $objDefault->product_id = $request->product_id;
                    $objDefault->product_type = 'tv';
                    $objDefault->default_product_id =  $val;
                    $objDefault->default_product_type = $request->add;
                    $objDefault->default_product_category_id = $category->id;
                    $objDefault->default_product_type = 'addon';
                    $objDefault->is_default = 1;
                    $objDefault->save();
                }
            }
        }
        if (isset($request->mandatory_addon_ids)) {
            foreach ($request->mandatory_addon_ids as $val) {
                $addinfo = AdditionalInfo::where('id', $val)->first();
                $category = AdditionalCategory::where('id',  $addinfo->add_cat_id)->first();
                $addinfo->is_mandatory = 1;
                $addinfo->save();
                $default = DefaultProduct::where('default_product_category_id', $category->id)->where('product_id', $request->product_id)->where('product_type', 'tv')->where('default_product_type', 'addon')->where('is_mandatory', "1")->first();

                if ($default) {
                    $objDefault = DefaultProduct::find($default->id);
                    $objDefault->product_id = $request->product_id;
                    $objDefault->product_type = 'tv';
                    $objDefault->default_product_id = $val;
                    $objDefault->default_product_category_id = $category->id;
                    $objDefault->default_product_type = 'addon';
                    $objDefault->is_mandatory = 1;
                    $objDefault->save();
                } else {
                    $objDefault = new DefaultProduct();
                    $objDefault->product_id = $request->product_id;
                    $objDefault->product_id = $request->product_id;
                    $objDefault->product_type = 'tv';
                    $objDefault->default_product_id =  $val;
                    $objDefault->default_product_type = $request->add;
                    $objDefault->default_product_category_id = $category->id;
                    $objDefault->default_product_type = 'addon';
                    $objDefault->is_mandatory = 1;
                    $objDefault->save();
                }
            }
        }
        return redirect()->back()->with(["status" => true, Toastr::success('Success', '', ["positionClass" => "toast-top-right"])]);
    }

    public function duplicate(Request $request, $id)
    {
        $objExtProduct = TvProduct::where('id', $id)->first();
        $objProduct = new TvProduct();
        $last_product = TvProduct::where('id', $id)->max('duplicate_count');
        dd($last_product);
        if ($last_product == 0) {
            $dup_no = '--' . 'duplicate';
        } else {
            $dup_no = '--' . 'duplicate-' . $last_product;
        }

        $objProduct->title = $objExtProduct->title . $dup_no;
        $objProduct->url = $objExtProduct->url . $dup_no;
        // $objProduct->avg_speed = $objExtProduct->avg_speed;
        $objProduct->avg_download_speed = $objExtProduct->avg_download_speed;
        $objProduct->avg_upload_speed = $objExtProduct->avg_upload_speed;
        $objProduct->price = $objExtProduct->price;
        $objProduct->contract_length_id = $objExtProduct->contract_length_id;
        $objProduct->commission = $objExtProduct->commission;
        $objProduct->commission_type = $objExtProduct->commission_type;
        $objProduct->feature_id = $objExtProduct->feature_id;
        $objProduct->add_extras = $objExtProduct->add_extras;
        $objProduct->related_products =  $objExtProduct->related_products;
        $objProduct->status = $objExtProduct->status;
        $objProduct->akj_product_id =  $objExtProduct->akj_product_id;
        $objProduct->akj_discount_id =  $objExtProduct->akj_discount_id;
        $objProduct->product_type = $objExtProduct->product_type;
        $objProduct->order_type = $objExtProduct->order_type;
        $objProduct->is_featured = $objExtProduct->is_featured;
        $objProduct->catalogue_name = $objExtProduct->catalogue_name;
        $objProduct->product_name_api = $objExtProduct->product_name_api;
        $objProduct->category_id =  $objExtProduct->category_id;
        $objProduct->affiliate = $objExtProduct->affiliate;
        $objProduct->template = $objExtProduct->template;
        $objProduct->is_page = $objExtProduct->is_page;
        $objProduct->content = $objExtProduct->content;
        $objProduct->product_image =  $objExtProduct->product_image;


        if ($objProduct->save()) {
            $objExtProduct->duplicate_count = $last_product + 1;
            $objExtProduct->save();

            return redirect()->route("admin.tv-products.index")->with(Toastr::success(__('Tv Duplicated Successfully')));
        } else {

            return redirect()->route("admin.tv-products.index")->with(Toastr::error(__('Something went wrong !! Please Try again later')));
        }
    }
}
