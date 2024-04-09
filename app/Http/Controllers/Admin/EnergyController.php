<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnergyProduct;
use App\Models\Provider;
use App\Models\DefaultProduct;
use App\Models\AdditionalInfo;
use App\Models\ShopProduct;
use App\Models\Category;
use App\Models\PostFeature;
use App\Models\Affiliate;
use App\Models\Feature;
use Brian2694\Toastr\Facades\Toastr;

use Illuminate\Support\Facades\Auth;

class EnergyController extends Controller
{
    protected string $guard = 'admin';
    public function guard()
    {
        return Auth::guard($this->guard);
    }
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:energy-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:energy-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:energy-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:energy-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$objTv = EnergyProduct::latest()->get();
        $objTvFeatures = Feature::select('id','features','input_type')->where('category', 9)->get();
        $objInternetFeatures = Feature::select('id','features','input_type')->where('category', 8)->get();
        $objTeleFeatures = Feature::select('id','features','input_type')->where('category', 2)->get();
        return view('admin.energy.index', compact('objTv', 'objTvFeatures', 'objInternetFeatures', 'objTeleFeatures'));
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
         $objRelatedProducts = EnergyProduct::orderBy('id', 'asc')->get();
         $objCategory = Category::latest()->get();
         $providers = Provider::latest()->get();
         //$objFeature = TvFeature::latest()->get();
        return view('admin.energy.add', compact('objCategory', 'objRelatedProducts', 'providers'));
        //, compact('objContract', 'objCommission', 'objAdditionalCategories', 'objRelatedProducts', 'objCategory', 'objAffiliates', 'objFeature')
    }

    public function getenergyproducts(Request $request)
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

        $totalRecords = EnergyProduct::select('count(*) as allcount')->count();
        if ($searchValue) {
            $totalRecordswithFilter = EnergyProduct::with('postFeatures')->select('count(*) as allcount')->where('title', 'like', '%' . $searchValue . '%');
        } else {
            $totalRecordswithFilter = EnergyProduct::with('postFeatures')->select('count(*) as allcount');
        }
        if (isset($request->product_name)) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('title', 'like', '%' . $request->product_name . '%');
        }
        if (isset($request->product_type)) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('product_type', 'like', '%' . $request->product_type . '%');
        }
        if (isset($request->internet)) {
            $totalRecordswithFilter = $totalRecordswithFilter->whereHas('postFeatures', function ($query) use ($request) {
                $query->where('feature_id', $request->internet);
            });
        }
        if (isset($request->status)) {
            $totalRecordswithFilter = $totalRecordswithFilter->where('status', $request->status);
        }
        $totalRecordswithFilter = $totalRecordswithFilter->count();

        // Fetch records
        if ($searchValue) {
            $productRecords = EnergyProduct::with('postFeatures')->orderBy($columnName, $columnSortOrder)
                ->where('title', 'like', '%' . $searchValue . '%')
                ->select('energy_products.*');
        } else {
            $productRecords = EnergyProduct::with('postFeatures')->orderBy($columnName, $columnSortOrder)
                ->select('energy_products.*');
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
        if (isset($request->internet)) {
            $productRecords = $productRecords->whereHas('postFeatures', function ($query) use ($request) {
                $query->where('feature_id', $request->internet);
            });
        }
        $productRecords = $productRecords->skip($row)->take($rowperpage)->get();
        $i = 1;
        foreach ($productRecords as $key => $record) {

            $edit_btn = route('admin.energy.edit', $record->id);
            $delete_btn = route('admin.energy.destroy', $record->id);
            $default_btn = route('admin.tv-default', $record->id);
            $duplicate_btn = route('admin.duplicate-tv', $record->id);

            if ($record->product_type == 'personal') {
                $pro_type =  '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Personal</div>';
            } else if ($record->product_type == 'business') {
                $pro_type =  '<div class="badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Business</div>';
            } else if ($record->product_type == 'cln') {
                $pro_type =  '<div class="badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Large Business</div>';
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
            // if (Auth::guard('admin')->user()->can('tv-product-edit')) {
                $edit_link =   '<a title="Edit" href="' . $edit_btn . '" class="btn1 btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>';
            //}
            //if (Auth::guard('admin')->user()->can('tv-product-delete')) {
                $delete_link = ' <a title="Delete" class="btn1 btn-outline-danger trash remove-tv" data-id="' . $record->id . '" data-action="' . $delete_btn . '"><i class="bx bx-trash me-0"></i></a>';
            //}
            //if (Auth::guard('admin')->user()->can('tv-product-default')) {
                $default_link = '  <a title="Default" class="btn1 btn-outline-success" href="' . $default_btn . '"><i class="bx bx-star me-0"></i></a>';
            //}
            //if (Auth::guard('admin')->user()->can('energy-duplicate')) {
                $duplicate_link = ' <a title="Duplicate" class="btn1 btn-outline-warning" href="' . $duplicate_btn . '"><i class="bx bx-copy me-0"></i></a>';
            //}

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
        $product = EnergyProduct::where('title', $request->title)->first();
        if (isset($product) && $product->count() > 0) {
            $message = array('message' => 'Already Exists! Please enter unique title', 'title' => 'Already Exists!');
            return response()->json(["status" => false, 'message' => $message, 'title' => 'Already Exists!']);
        } else {
            $objTv = new EnergyProduct();
            $objTv->title = $request->title;
            $objTv->content = $request->description;
            $objTv->commission = $request->commission;
            $objTv->commission_type = $request->commission_type;
            $objTv->avg_delivery_time = $request->avg_delivery_time;
            $objTv->price = $request->price;
            $objTv->contract_length = $request->contract_length;
            $objTv->contract_type = $request->contract_type;
            $objTv->transfer_service = $request->transfer_service;
            $objTv->pin_codes = json_encode($request->pin_codes ? explode(",", $request->pin_codes) : []);
            $objTv->combos = json_encode($request->combos ? explode(",", $request->combos) : []);            
            $objTv->status = $request->status?$request->status:0;
            $objTv->valid_till =  $request->valid_till;
            $objTv->category =  $request->category;
            $objTv->product_type = $request->product_type;
            $objTv->manual_install = $request->manual_install;
            $objTv->is_featured = $request->is_featured;
            $objTv->mechanic_install = $request->mechanic_install;
            $objTv->mechanic_charge = $request->mechanic_charge;            
            $objTv->slug = $request->link;
            $objTv->provider = $request->provider;
            
            //$objTv->is_page = isset($request->is_page) ? $request->is_page : 0;
            if ($request->file('image') == null || $request->file('image') == '') {
                $input['image'] = $objTv->image;
            } else {
                $destinationPath = '/images';
                $imgfile = $request->file('image');
                $imgFilename = $imgfile->getClientOriginalName();
                $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
                $image = $imgFilename;
                $objTv->image = $image;
            }
            if ($objTv->save()) {
                return redirect()->route('admin.energy.index')->with(Toastr::success('Energy Product Added Successfully', '', ["positionClass" => "toast-top-right"]));
                //Toastr::success('Tv Product Added Successfully', '', ["positionClass" => "toast-top-right"]);
                //return response()->json(["status" => true, "redirect_location" => route("admin.energy.index")]);
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $objTv = EnergyProduct::find($id);
        $objTvFeatures = Feature::select('id','features','input_type')->where('category', 9)->get();
        $postTvFeatures = PostFeature::where('post_id', $id)->where('category_id', 9)->pluck('feature_value', 'feature_id')->toArray();
        $objInternetFeatures = Feature::select('id','features','input_type')->where('category', 8)->get();
        $postInternetFeatures = PostFeature::where('post_id', $id)->where('category_id', 8)->pluck('feature_value', 'feature_id')->toArray();       
        $objTeleFeatures = Feature::select('id','features','input_type')->where('category', 2)->get();
        $postTeleFeatures = PostFeature::where('post_id', $id)->where('category_id', 2)->pluck('feature_value', 'feature_id')->toArray();
        $serviceInfo = PostFeature::where('post_id', $id)->where('type', 'info')->get();
        $objRelatedProducts = EnergyProduct::orderBy('id', 'asc')->get();
        $objCategory = Category::latest()->get();
        //$objAffiliates = Affiliate::latest()->get();
        //$objFeature = TvFeature::latest()->get();
        return view('admin.energy.edit', compact('objTv', 'objRelatedProducts', 'objCategory', 'objInternetFeatures', 'objTvFeatures', 'postInternetFeatures', 'postTvFeatures', 'objTeleFeatures', 'postTeleFeatures', 'serviceInfo'));
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
        $objTv = EnergyProduct::where('id', $id)->first();
        //jhdjhddjhf
            $objTv->title = $request->title;
            $objTv->content = $request->description3;
            $objTv->commission = $request->commission;
            $objTv->commission_type = $request->commission_type;
            $objTv->avg_delivery_time = $request->avg_delivery_time;
            $objTv->price = $request->price;
            $objTv->contract_length = $request->contract_length;
            $objTv->contract_type = $request->contract_type;
            $objTv->transfer_service = $request->transfer_service;
            $objTv->pin_codes = json_encode($request->pin_codes ? explode(",", $request->pin_codes) : []);
            $objTv->combos = json_encode($request->combos ? explode(",", $request->combos) : []);            
            $objTv->status = $request->online_status?$request->online_status:0;
            $objTv->valid_till =  $request->valid_till;
            $objTv->category =  $request->category;
            $objTv->product_type = $request->product_type;
            $objTv->manual_install = $request->manual_install;
            $objTv->is_featured = $request->is_featured;
            $objTv->mechanic_install = $request->mechanic_install;
            $objTv->mechanic_charge = $request->mechanic_charge;            
            $objTv->slug = $request->link;
            $objTv->provider = $request->provider;
        if ($request->file('image') == null || $request->file('image') == '') {
            $image = $objTv->image;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
           
        }
         $objTv->image = $image;
        if ($objTv->save()) {
            //Toastr::success('Tv Product Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            //return response()->json(["status" => true, "redirect_location" => route("admin.energy.index")]);
            return redirect()->route('admin.energy.index')->with(Toastr::success('Energy Product Updated Successfully', '', ["positionClass" => "toast-top-right"]));
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
        $objTv = EnergyProduct::find($id);
        if ($objTv->delete()) {
            return back()->with(Toastr::error(__('Energy deleted successfully!')));
        } else {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.energy.index')->with($error_msg);
        }
    }
    public function default(Request $request, $id)
    {
        //dd($id);
        $product = EnergyProduct::find($id);
        $default = DefaultProduct::latest()->get();
        $responseData = array();
        $data = DefaultProduct::where('product_id', $id)->where('product_type', 'tv')->where('is_default', '1')->pluck('default_product_id')->toArray();
        $manda_data = DefaultProduct::where('product_id', $id)->where('product_type', 'tv')->where('is_mandatory', '1')->pluck('default_product_id')->toArray();

        return view('admin.energy.default', compact('product', 'data', 'manda_data'));
    }

    public function gas_feature_update(Request $request, $post_id)
    {
        $post_category = $request->category_id;
        try{
        foreach($request->input('features') as $feature_id => $value){
            if($value != null && $post_category != null){
                
                PostFeature::updateOrCreate(['post_id' => $post_id, 'category_id' => 8, 'feature_id' => $feature_id, 'post_category' => $post_category],['post_id' => $post_id, 'category_id' => 8, 'feature_id' => $feature_id, 'feature_value' => $value, 'post_category' => $post_category]);
            
        }
        }
        }catch(\Exception $e){
            $errorMessage = 'Failed to update internet features: ' . $e->getMessage();
        // Log the error for further investigation
        \Log::error($errorMessage);
            $message = ['message' =>  $errorMessage, 'title' => 'Error'];
            return response()->json(['status' => false, 'message' => $message]);
        }
        $message = array('message' => 'Internet Features Updated Successfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        
    }
    public function energy_feature_update(Request $request, $post_id)
    {
        $post_category = $request->category_id;
        try{
        foreach($request->input('features') as $feature_id => $value){
            if($value != null && $post_category != null){                
                PostFeature::updateOrCreate(['post_id' => $post_id, 'category_id' => 9, 'feature_id' => $feature_id, 'post_category' => $post_category],['post_id' => $post_id, 'post_category' => $post_category, 'category_id' => 9, 'feature_id' => $feature_id, 'feature_value' => $value]);
            
        }
        }
        }catch(\Exception $e){
            $errorMessage = 'Failed to update internet features: ' . $e->getMessage();
        // Log the error for further investigation
        \Log::error($errorMessage);
            $message = ['message' =>  $errorMessage, 'title' => 'Error'];
            return response()->json(['status' => false, 'message' => $message]);
        }
        $message = array('message' => 'Internet Features Updated Successfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        
    }

    public function electric_feature_update(Request $request, $post_id)
    {
        $post_category = $request->category_id;
        try{
        foreach($request->input('features') as $feature_id => $value){
            if($value != null && $post_category != null){                
                PostFeature::updateOrCreate(['post_id' => $post_id, 'category_id' => 2, 'feature_id' => $feature_id, 'post_category' => $post_category],['post_id' => $post_id, 'post_category' => $post_category, 'category_id' => 2, 'feature_id' => $feature_id, 'feature_value' => $value]);
            
        }
        }
        }catch(\Exception $e){
            $errorMessage = 'Failed to update telephone features: ' . $e->getMessage();
        // Log the error for further investigation
        \Log::error($errorMessage);
            $message = ['message' =>  $errorMessage, 'title' => 'Error'];
            return response()->json(['status' => false, 'message' => $message]);
        }
        $message = array('message' => 'Telephone Features Updated Successfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        
    }

    public function service_info_update(Request $request, $post_id)
    {
        $post_category = $request->category_id;
        try{
        foreach($request->input('features') as $feature_id => $value){
            if($value != null && $post_category != null){                
                PostFeature::updateOrCreate(['post_id' => $post_id, 'category_id' => 2, 'feature_id' => $feature_id, 'post_category' => $post_category],['post_id' => $post_id, 'post_category' => $post_category, 'category_id' => 2, 'feature_id' => $feature_id, 'feature_value' => $value]);
            
        }
        }
        }catch(\Exception $e){
            $errorMessage = 'Failed to update telephone features: ' . $e->getMessage();
        // Log the error for further investigation
        \Log::error($errorMessage);
            $message = ['message' =>  $errorMessage, 'title' => 'Error'];
            return response()->json(['status' => false, 'message' => $message]);
        }
        $message = array('message' => 'Telephone Features Updated Successfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        
    }
}
