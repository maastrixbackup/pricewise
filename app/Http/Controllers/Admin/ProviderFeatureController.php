<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmartPhone;
use App\Models\Provider;
use App\Models\ProviderDiscount;
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
use App\Models\ProviderFeature;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProviderFeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sp_features = ProviderFeature::with('providerDetails')->orderBy('id', 'desc')->get();
        return view('admin.provider_feature.index', compact('sp_features'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sp_provider = SmartPhone::orderBy('id', 'desc')->get();
        return view('admin.provider_feature.add', compact('sp_provider'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $s_feature = new ProviderFeature();
            $s_feature->provider_id = $request->provider_id;
            $s_feature->mobile_data = $request->mobile_data;
            $s_feature->call_text = $request->call_text;
            $s_feature->price = $request->price;
            $s_feature->valid_till = $request->valid_till;
            $s_feature->description = $request->description;
            $s_feature->status = $request->status;

            if ($s_feature->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Feature Added Successfully', ' ', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(Toastr::error($e->getMessage(), ' ', ["positionClass" => "toast-top-right"]));
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
        $sp_provider = SmartPhone::orderBy('id', 'desc')->get();
        $sp_feature = ProviderFeature::where('id', $id)->first();
        return view('admin.provider_feature.edit', compact('sp_provider', 'sp_feature'));
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
        DB::beginTransaction();
        try {
            $s_feature_update = ProviderFeature::where('id', $id)->first();
            $s_feature_update->provider_id = $request->provider_id;
            $s_feature_update->mobile_data = $request->mobile_data;
            $s_feature_update->call_text = $request->call_text;
            $s_feature_update->price = $request->price;
            $s_feature_update->valid_till = $request->valid_till;
            $s_feature_update->description = $request->description;
            $s_feature_update->status = $request->status;

            if ($s_feature_update->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Feature Updated Successfully', ' ', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(Toastr::error($e->getMessage(), ' ', ["positionClass" => "toast-top-right"]));
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
            ProviderFeature::where('id', $id)->delete();
            return back()->with(Toastr::success('Provider deleted successfully!', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
}
