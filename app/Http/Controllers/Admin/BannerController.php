<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Banner;
use App\Models\Page;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{
    protected string $guard = 'admin';
    public function guard()
    {
        return Auth::guard($this->guard);
    }
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:banner-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:banner-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:banner-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:banner-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::latest()->get();
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
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pages = Page::where('status', 1)->get();
       return view('admin.banners.add', compact('pages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        echo($request->description);exit;
        $banner = new Banner();
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->type = $request->type;
        $banner->status = $request->status;
        $banner->slider_id = $request->slider_id;
        $banner->page = $request->page;
        $banner->section = $request->section;
        $banner->link = $request->link;
        $banner->image = $request->image;
        if ($banner->save()) {
            Toastr::success('Banner Created Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, "redirect_location" => route("admin.banners.index")]);
           
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

        $banner = Banner::find($id);
        return view('admin.banners.edit', compact('banner'));
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
        // echo($request->description);exit;
        $banner = Banner::find($id);
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->type = $request->type;
        $banner->status = $request->status;
        $banner->slider_id = $request->slider_id;
        $banner->page = $request->page;
        $banner->section = $request->section;
        $banner->link = $request->link;
        $banner->image = $request->image;
        if ($banner->save()) {
            // return redirect()->route('admin.drivers.index')->with(Toastr::success('Driver Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            Toastr::success('Banner Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, "redirect_location" => route("admin.banners.index")]);
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
        $banner = Banner::find($id);
        try {
            Banner::find($id)->delete();
            return back()->with(Toastr::error(__('Banner deleted successfully!')));
        } catch (Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.banners.index')->with($error_msg);
        }
    }
}

