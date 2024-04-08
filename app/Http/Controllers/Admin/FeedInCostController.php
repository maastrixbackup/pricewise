<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\FeedInCost;
use App\Models\Category;
use App\Models\Provider;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FeedInCostController extends Controller
{
    protected string $guard = 'admin';
    public function guard()
    {
        return Auth::guard($this->guard);
    }
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:feed-in-costs-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:feed-in-costs-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:feed-in-costs-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:feed-in-costs-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rateChats = FeedInCost::latest()->get();
//if(Auth::guard('admin')->user()->can('role-list')){dd('hi');}
//dd(\Auth::guard('admin')->user()->roles);
// foreach (Auth::guard('admin')->user()->roles as $role) {
//         $permissions = $role->permissions;
//          echo $permission->name . "\n";
//     }
    // $permissions = Permission::all();

    // foreach ($permissions as $permission) {
    //     echo $permission->name . "\n";
    // }
        if($request->category_id && $request->category_id != null){
            $subCategory = Reimbursement::where('parent', $request->category_id)->latest()->get();
            return response()->json(['status' => true, 'data' => $subCategory]);
        }
        return view('admin.feed_in_costs.index', compact('rateChats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers = Provider::all();
       return view('admin.feed_in_costs.add', compact('providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $objCategory = new FeedInCost();
        $objCategory->provider = $request->provider;
        $objCategory->from_range = $request->from_range;        
        $objCategory->to_range = $request->to_range;
        $objCategory->amount = $request->amount;
        if ($objCategory->save()) {
            return redirect()->route('admin.feed-in-costs.index')->with(Toastr::success('Feed In Cost Created Successfully', '', ["positionClass" => "toast-top-right"]));
            // Toastr::success('Driver Created Successfully', '', ["positionClass" => "toast-top-right"]);
            // return response()->json(["status" => true, "redirect_location" => route("admin.drivers.index")]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
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
        $providers = Provider::all();
        $objFeedInCost = FeedInCost::find($id);        
        return view('admin.feed_in_costs.edit', compact('objFeedInCost', 'providers'));
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
        //echo 123;exit;
        $objCategory = FeedInCost::find($id);
        $objCategory->provider = $request->provider;
        //$objCategory->slug = $request->slug;
        $objCategory->from_range = $request->from_range;
        $objCategory->to_range = $request->to_range;
        $objCategory->amount = $request->amount;
        if ($objCategory->save()) {
            // return redirect()->route('admin.drivers.index')->with(Toastr::success('Driver Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            Toastr::success('Feed In Cost Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, "redirect_location" => route("admin.feed-in-costs.index")]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $id = $request->id;
        $getCategory = FeedInCost::find($id);
        try {
            FeedInCost::find($id)->delete();
            return back()->with(Toastr::error(__('Feed In Cost deleted successfully!')));
        } catch (Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.feed-in-costs.index')->with($error_msg);
        }
    }
}

