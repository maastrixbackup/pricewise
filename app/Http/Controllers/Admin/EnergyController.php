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
use App\Models\EnergyRateChat;
use App\Models\FeedInCost;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
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
    	$objEnergy = EnergyProduct::latest()->get();
        $objEnergyFeatures = Feature::select('id','features','input_type')->where('category', 9)->get();
        $objInternetFeatures = Feature::select('id','features','input_type')->where('category', 8)->get();
        $objTeleFeatures = Feature::select('id','features','input_type')->where('category', 2)->get();
        return view('admin.energy.index', compact('objEnergy', 'objEnergyFeatures', 'objInternetFeatures', 'objTeleFeatures'));
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
            $default_btn = route('admin.energy-default', $record->id);
            $duplicate_btn = route('admin.duplicate-energy', $record->id);

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
            $objEnergy = new EnergyProduct();
            $objEnergy->title = $request->title;
            $objEnergy->description = $request->description;
            $objEnergy->commission = $request->commission;
            $objEnergy->commission_type = $request->commission_type;
            $objEnergy->avg_delivery_time = $request->avg_delivery_time;            
            $objEnergy->contract_length = $request->contract_length;
            $objEnergy->contract_type = $request->contract_type;
            $objEnergy->transfer_service = $request->transfer_service;
            $objEnergy->pin_codes = json_encode($request->pin_codes ? explode(",", $request->pin_codes) : []);
            $combos = implode(",", $request->combos);
            $objEnergy->combos = json_encode($request->combos ? explode(",", $combos) : []);            
            $objEnergy->status = $request->status?$request->status:0;
            $objEnergy->valid_till =  $request->valid_till;
            $objEnergy->no_of_person = $request->no_of_person;
            $objEnergy->category =  $request->category;
            $objEnergy->product_type = $request->product_type;
            $objEnergy->manual_install = $request->manual_install;
            $objEnergy->is_featured = $request->is_featured;
            $objEnergy->mechanic_install = $request->mechanic_install;
            $objEnergy->mechanic_charge = $request->mechanic_charge;            
            $objEnergy->slug = $request->link;
            $objEnergy->provider = $request->provider;
            $objEnergy->wind_energy = $request->wind_energy;
            $objEnergy->sun_energy = $request->sun_energy;
            $objEnergy->water_energy = $request->water_energy;
            $objEnergy->thermal_energy = $request->thermal_energy;
            $croppedImage = $request->cropped_image;

            // Extract base64 encoded image data and decode it
            $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));

            // Generate a unique file name for the image
            $imageName = 'energy_' . time() . '.png';

            // Specify the destination directory where the image will be saved
            $destinationDirectory = 'public/images/energy';

            // Create the directory if it doesn't exist
            Storage::makeDirectory($destinationDirectory);

            // Save the image to the server using Laravel's file upload method
            $filePath = $destinationDirectory . '/' . $imageName;
            Storage::put($filePath, $imgData);

            // Set the image file name for the provider
            $objEnergy->image = $imageName;
            if ($objEnergy->save()) {
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
        $objEnergy = EnergyProduct::find($id);
        $providers = Provider::all();
        
        $objEnergyFeatures = Feature::select('id','features','input_type','parent')->where('category', $objEnergy->category)->get()->groupBy('parent');
        // dd($objEnergyFeatures->except(''));
        $postEnergyFeatures = PostFeature::where('post_id', $id)
        ->where('category_id', $objEnergy->category)
        ->orWhere('sub_category', $objEnergy->sub_category)
        ->select('feature_id', 'feature_value', 'details')
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item['feature_id'] => ['feature_value' => $item['feature_value'], 'details' => $item['details']]];
        })->toArray();
        
        $serviceInfo = PostFeature::where('post_id', $id)->where('category_id', $objEnergy->category)->where('type', 'info')->get();
        $objRelatedProducts = EnergyProduct::orderBy('id', 'asc')->get();
        $objCategory = Category::latest()->get();
        return view('admin.energy.edit', compact('objEnergy', 'objRelatedProducts', 'objCategory', 'providers', 'objEnergyFeatures', 'postEnergyFeatures',  'serviceInfo'));
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
        $objEnergy = EnergyProduct::where('id', $id)->first();        
            $objEnergy->title = $request->title;
            $objEnergy->description = $request->description;
            $objEnergy->commission = $request->commission;
            $objEnergy->commission_type = $request->commission_type;
            $objEnergy->avg_delivery_time = $request->avg_delivery_time;            
            $objEnergy->contract_length = $request->contract_length;
            $objEnergy->contract_type = $request->contract_type;
            $objEnergy->transfer_service = $request->transfer_service;
            $objEnergy->pin_codes = json_encode($request->pin_codes ? explode(",", $request->pin_codes) : []);
            $objEnergy->combos = json_encode($request->combos ? explode(",", $request->combos) : []);            
            $objEnergy->status = $request->online_status?$request->online_status:0;
            $objEnergy->valid_till =  $request->valid_till;
            $objEnergy->category =  $request->category;
            $objEnergy->product_type = $request->product_type;
            $objEnergy->manual_install = $request->manual_install;
            $objEnergy->is_featured = $request->is_featured;
            $objEnergy->mechanic_install = $request->mechanic_install;
            $objEnergy->mechanic_charge = $request->mechanic_charge;            
            $objEnergy->slug = $request->link;
            $objEnergy->no_of_person = $request->no_of_person;
            $objEnergy->provider = $request->provider;
            $objEnergy->wind_energy = $request->wind_energy;
            $objEnergy->sun_energy = $request->sun_energy;
            $objEnergy->water_energy = $request->water_energy;
            $objEnergy->thermal_energy = $request->thermal_energy;
            if ($request->has('cropped_image')) {
            // Access base64 encoded image data directly from the request
            $croppedImage = $request->cropped_image;

            // Extract base64 encoded image data and decode it
            $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));

            // Generate a unique file name for the image
            $imageName = 'energy_' . time() . '.png';

            // Specify the destination directory where the image will be saved
            $destinationDirectory = 'public/images/energy';

            // Create the directory if it doesn't exist
            Storage::makeDirectory($destinationDirectory);

            // Save the image to the server using Laravel's file upload method
            $filePath = $destinationDirectory . '/' . $imageName;

            // Delete the old image if it exists
            if ($objEnergy->image) {
                Storage::delete($destinationDirectory . '/' . $objEnergy->image);
            }

            // Save the new image
            Storage::put($filePath, $imgData);

            // Set the image file name for the provider
            $objEnergy->image = $imageName;
            }
        if ($objEnergy->save()) {
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
        $objEnergy = EnergyProduct::find($id);
        if ($objEnergy->delete()) {
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

    public function energy_doc_update(Request $request, $post_id)
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
    public function energy_price_update(Request $request, $id)
    {
        $post_category = $request->category_id;
        try{
            $objEnergy = EnergyProduct::where('id', $id)->first();        
            $objEnergy->gas_price = $request->gas_price;
            $objEnergy->normal_electric_price = $request->normal_electric_price;
            $objEnergy->peak_electric_price = $request->peak_electric_price;
            $objEnergy->feed_in_normal = $request->normal_feed_in_cost;
            $objEnergy->feed_in_peak = $request->peak_feed_in_cost;
            $objEnergy->network_cost_gas = $request->network_cost_gas;
            $objEnergy->network_cost_electric = $request->network_cost_electric;
            $objEnergy->delivery_cost_electric = $request->delivery_cost_electric;
            $objEnergy->delivery_cost_gas = $request->delivery_cost_gas;
            $objEnergy->cashback = $request->cashback;
            $objEnergy->save();
        }catch(\Exception $e){
            $errorMessage = 'Failed to Update Energy Price: ' . $e->getMessage();
        // Log the error for further investigation
        \Log::error($errorMessage);
            $message = ['message' =>  $errorMessage, 'title' => 'Error'];
            return response()->json(['status' => false, 'message' => $message]);
        }
        $message = array('message' => 'Energy Prices Updated Successfully', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        
    }

    public function energy_feature_update(Request $request, $post_id)
    {
        $post_category = $request->category_id;
        $sub_category = $request->sub_category;
        //dd($request->features);
        try{
        foreach($request->input('features') as $feature_id => $value){
            if($value != null && $post_category != null){                
                PostFeature::updateOrCreate(['post_id' => $post_id, 'category_id' => $post_category, 'feature_id' => $feature_id],['post_id' => $post_id, 'category_id' => $post_category, 'sub_category' => $sub_category, 'feature_id' => $feature_id, 'feature_value' => $value, 'details' => $request->details[$feature_id], 'post_category' => $post_category]);
            
        }
        }
        }catch(\Exception $e){
            $errorMessage = 'Failed to update energy features: ' . $e->getMessage();
        // Log the error for further investigation
        \Log::error($errorMessage);
            $message = ['message' =>  $errorMessage, 'title' => 'Error'];
            return response()->json(['status' => false, 'message' => $message]);
        }
        $message = array('message' => 'Energy Features Updated Successfully', 'title' => '');
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
