<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvPackage;
use App\Models\TvChannel;
use App\Models\Provider;
use Brian2694\Toastr\Facades\Toastr;

use function PHPUnit\Framework\returnSelf;

class TvPackageController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:tv-packages', ['only' => ['index', 'store']]);
        $this->middleware('permission:tv-packages.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:tv-packages.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tv-packages.destroy', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = TvPackage::latest()->with('providerDetails')->get();
        return view('admin.tv_package.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $channels = TvChannel::get();
        $providers = Provider::get();
        return view('admin.tv_package.create', compact('channels', 'providers'));
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
            $newPackage->package_price = $request->package_price;
            $newPackage->features = $request->features;

            if ($request->image) {
                // Handle the image file upload
                $filename = 'tvPackages_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/tvPackages/'), $filename);
            }
            // Save the filename in the database
            $newPackage->image = $filename ?? '';

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
        $channels = TvChannel::get();
        $providers = Provider::get();
        $package = TvPackage::where('id', $id)->first();
        return view('admin.tv_package.edit', compact('package', 'channels', 'providers'));
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
            $package->package_price = $request->package_price;
            $package->features = $request->features;


            if ($request->image) {
                // Handle the image file upload
                $filename = 'tvPackages_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/tvPackages/'), $filename);


                $existingFilePath = public_path('storage/images/tvPackages/') . $package->image;

                // Check if there is an existing image and delete it if it exists
                if ($package->image) {
                    $existingFilePath = public_path('storage/images/tvPackages/') . $package->image;

                    if (file_exists($existingFilePath)) {
                        // Delete the file
                        unlink($existingFilePath);
                    }
                }
            }
            // Save the filename in the database
            $package->image = $filename ?? $package->image;

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
