<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvPackage;
use App\Models\TvChannel;
use App\Models\Provider;
use Brian2694\Toastr\Facades\Toastr;
class TvPackageController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('auth:admin');
    //     $this->middleware('permission:tv-packages', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:tv-packages.create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:tv-packages.edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:tv-packages.destroy', ['only' => ['destroy']]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = TvPackage::latest()->with('providerDetails')->get();
        return view('admin.tv_package.index',compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $channels=TvChannel::get();
        $providers = Provider::get();
        return view('admin.tv_package.create',compact('channels','providers'));
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
            'package_name' => 'required|unique:tv_packages,package_name',
            'channels' => 'required|min:1',
            'provider' => 'required',
        ]);
 
        try {
            $newPackage = new TvPackage();
            $newPackage->package_name = $request->package_name;
            $newPackage->tv_channels = json_encode($request->channels);
            $newPackage->provider_id = $request->provider;
            $newPackage->save();
            Toastr::success('Package Added Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.tv-packages.index');
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
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
        $channels=TvChannel::get();
        $providers = Provider::get();
        $package = TvPackage::where('id', $id)->first();
        return view('admin.tv_package.edit', compact('package','channels','providers'));
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
            'package_name' => 'required|unique:tv_packages,package_name,' . $id,
            'channels' => 'required|min:1',
            'provider' => 'required',
        ]);
 
        try {
            $package = TvPackage::findOrFail($id);
            $package->package_name = $request->package_name;
            $package->tv_channels = json_encode($request->channels);
            $package->provider_id = $request->provider;
            $package->save();
            Toastr::success('Package Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.tv-packages.index');
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
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
            TvPackage::where('id', $id)->delete();
            return back()->with(Toastr::error(__('Package deleted successfully!')));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
}
