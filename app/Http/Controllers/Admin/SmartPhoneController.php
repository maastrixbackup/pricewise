<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmartPhone;
use App\Models\Provider;
use App\Models\Document;
use App\Models\DefaultProduct;
use App\Models\AdditionalInfo;
use App\Models\ShopProduct;
use App\Models\Category;
use App\Models\PostFeature;
use App\Models\TvPackage;
use App\Models\Combo;
use App\Models\Affiliate;
use App\Models\Feature;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SmartPhoneController extends Controller
{
    
    protected string $guard = 'admin';
    public function guard()
    {
        return Auth::guard($this->guard);
    }
    // function __construct()
    // {
    //     $this->middleware('auth:admin');
    //     $this->middleware('permission:smartphone-list', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:smartphone-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:smartphone-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:smartphone-delete', ['only' => ['destroy']]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('Ok');
        return view('admin.smartphone.index');
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.smartphone.add');
        // dd('Ok');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
