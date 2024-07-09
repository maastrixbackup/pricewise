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
use Illuminate\Support\Facades\DB;
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('Ok');
        $sp_records = SmartPhone::with('categoryDetails')->orderBy('id', 'desc')->get();
        return view('admin.smartphone.index', compact('sp_records'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.smartphone.add', compact('categories'));
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
        // dd($request->all());

        $request->validate([
            'provider_name' => 'required | unique:smart_phones,provider_name',
            'description' => 'required',
            'status' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $smP = new SmartPhone();
            $smP->provider_name = $request->provider_name;
            $smP->description = $request->description;
            $smP->category_id = $request->category_id;
            $smP->slug = $request->provider_url;
            $smP->status = $request->type;

            if ($smP->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Provider Successfully Stored', '', ["positionClass" => "toast-top-right"]));
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
        $categories = Category::orderBy('name', 'asc')->get();
        $sp_record = SmartPhone::find($id);
        return view('admin.smartphone.edit', compact('sp_record', 'categories'));
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

        $request->validate([
            'provider_name' => 'required',
        ]);
        $sp_update = SmartPhone::where('id', $id)->first();
        DB::beginTransaction();
        try {
            $sp_update->provider_name = $request->provider_name;
            $sp_update->description = $request->description;
            $sp_update->category_id = $request->category_id;
            $sp_update->status = $request->type;
            $sp_update->slug = $request->provider_url;
            if ($sp_update->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Provide Updated Successfully', '', ["positionClass" => "toast-top-right"]));
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
            SmartPhone::where('id', $id)->delete();
            return back()->with(Toastr::success('Provider deleted successfully!', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
}
