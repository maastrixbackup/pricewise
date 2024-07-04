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
use DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProviderDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sp_records = ProviderDiscount::with('providerDetails')->orderBy('id','desc')->get();
        return view('admin.providers_discount.index', compact('sp_records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sp_provider = SmartPhone::orderBy('id', 'desc')->get();
        return view('admin.providers_discount.add', compact('sp_provider'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->provider);
        DB::beginTransaction();
        try {
            $sp_data = new ProviderDiscount();
            $sp_data->title = $request->discount_title;
            $sp_data->slug = $request->discount_url;
            $sp_data->sp_provider = $request->provider;
            $sp_data->discount_type = $request->discount_type;
            $sp_data->discount = $request->discount;
            $sp_data->status = $request->status;
            $sp_data->valid_from = $request->valid_from;
            $sp_data->valid_till = $request->valid_till;
            if ($sp_data->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Discount Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
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
        $sp_discount = ProviderDiscount::with('providerDetails')->where('id', $id)->first();
        return view('admin.providers_discount.edit', compact('sp_discount', 'sp_provider'));
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
        // dd($request->all());
        DB::beginTransaction();
        try {
            $sp_data_update = ProviderDiscount::where('id', $id)->first();
            $sp_data_update->title = $request->discount_title;
            $sp_data_update->slug = $request->discount_url;
            $sp_data_update->sp_provider = $request->provider;
            $sp_data_update->discount_type = $request->discount_type;
            $sp_data_update->discount = $request->discount;
            $sp_data_update->status = $request->status;
            $sp_data_update->valid_from = $request->valid_from;
            $sp_data_update->valid_till = $request->valid_till;
            if ($sp_data_update->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Discount Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
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
            ProviderDiscount::where('id', $id)->delete();
            return back()->with(Toastr::success('Provider deleted successfully!', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
}
