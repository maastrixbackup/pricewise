<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmartPhone;
use App\Models\SmartPhoneFaq;
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

class SmartPhoneFaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sp_faq = SmartPhoneFaq::with('providerDetails')->orderBy('id', 'desc')->get();
        return view('admin.smartphone_faq.index', compact('sp_faq'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sp_provider = SmartPhone::orderBy('id', 'desc')->get();
        return view('admin.smartphone_faq.add', compact('sp_provider'));
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
            'title' => 'required|unique:smart_phone_faqs,title',
            'description' => 'required',
            'status' => 'required',
        ]);


        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $sp_faq = new SmartPhoneFaq();
        $sp_faq->provider_id = $request->provider_id;
        $sp_faq->title = $request->title;
        $sp_faq->description = $request->description;
        $sp_faq->status = $request->status;
        $sp_faq->slug = $slug;

        DB::beginTransaction();
        try {
            if ($sp_faq->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Faq Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(Toastr::error($e->getMessage(), ' ', ["positionClass" => "toast-top-center"]));
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
        $sp_faqs = SmartPhoneFaq::find($id);
        $sp_provider = SmartPhone::orderBy('id', 'desc')->get();
        return view('admin.smartphone_faq.edit', compact('sp_faqs', 'sp_provider'));
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
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);


        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $sp_faqs_update = SmartPhoneFaq::where('id', $id)->first();
        $sp_faqs_update->provider_id = $request->provider_id;
        $sp_faqs_update->title = $request->title;
        $sp_faqs_update->description = $request->description;
        $sp_faqs_update->status = $request->status;
        $sp_faqs_update->slug = $slug;

        DB::beginTransaction();
        try {
            if ($sp_faqs_update->save()) {
                DB::commit();
                return redirect()->back()->with(Toastr::success('Faq Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(Toastr::error($e->getMessage(), ' ', ["positionClass" => "toast-top-center"]));
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
            SmartPhoneFaq::where('id', $id)->delete();
            return back()->with(Toastr::success('Provider deleted successfully!', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
}
