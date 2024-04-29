<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Brian2694\Toastr\Facades\Toastr;

class UserController extends Controller
{
    protected string $guard = 'admin';
    public function guard()
    {
        return Auth::guard($this->guard);
    }
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:user-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Admin::latest()->get();
        return view('admin.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $objAdmin = new Admin();
        $objAdmin->name = $request->name;
        $objAdmin->user_name = $request->user_name;
        $objAdmin->email = $request->email;
        $objAdmin->phone_no = $request->phone;
        $objAdmin->password = Hash::make($request->password);
        if ($objAdmin->save()) {
            if($request->roles)
            {
				$objAdmin->assignRole($request->input('roles'));
			}
            Toastr::success('User Added Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true,"redirect_location" => route("admin.users.index")]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
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
    public function edit(Request $request ,$id)
    {
        $roles = Role::all();
        $objAdmin = Admin::find($id);
        return view('admin.users.edit',compact('objAdmin','roles'));
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
        $objAdmin = Admin::find($id);
        $objAdmin->name = $request->name;
        $objAdmin->user_name = $request->user_name;
        $objAdmin->email = $request->email;
        $objAdmin->phone_no = $request->phone;
        $objAdmin->password = $request->password ? Hash::make($request->password) : $objAdmin->password;
        if ($objAdmin->save()) {
            if($request->roles)
            {
				$objAdmin->assignRole($request->input('roles'));
			}
            Toastr::success('User Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true,"redirect_location" => route("admin.users.index")]);
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
    public function destroy($id)
    {
        //
    }
}
