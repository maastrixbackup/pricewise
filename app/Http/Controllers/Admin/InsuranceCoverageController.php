<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\insuranceCoverage;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
class InsuranceCoverageController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:insurance-coverages', ['only' => ['index', 'store']]);
        $this->middleware('permission:insurance-coverages.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:insurance-coverages.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:insurance-coverages.destroy', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $records = insuranceCoverage::latest()->get();
       return view('admin.insurance_coverage.index',compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subCategories = SubCategory::where('category_id',5)->where('status', 'active')->get();
        return view('admin.insurance_coverage.create',compact('subCategories'));
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
            'name' => 'required',
            'image' => 'required|image|max:2048|mimes:jpeg,png,jpg,gif,svg',
            'sub_category' => 'required',
        ]);
        try {
            $newCoverage = new insuranceCoverage();
            $newCoverage->name=$request->name;
            $newCoverage->description=$request->description;

            $filename = time().'.'.$request->image->getClientOriginalExtension();

            $request->image->move(public_path('storage/images/insurance_coverages/'), $filename);

            $newCoverage->image = $filename;
            $newCoverage->subcategory_id = $request->sub_category;
            $newCoverage->save();
            Toastr ::success('Coverage Added Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.insurance-coverages.index');
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subCategories = SubCategory::where('category_id',5)->where('status', 'active')->get();
        $coverage = insuranceCoverage::where('id', $id)->first();
        return view('admin.insurance_coverage.edit',compact('subCategories','coverage'));
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
        try {
            $coverage = insuranceCoverage::findOrFail($id);
            $coverage->name=$request->name;
            $coverage->description=$request->description;
            if (isset($request->image)) {
                $filename = time().'.'.$request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/insurance_coverages/'), $filename);
            }
            $coverage->image = $filename ?? $coverage->image;
            $coverage->subcategory_id =  $request->sub_category;
            $coverage->save();
            Toastr::success('Coverage Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.insurance-coverages.index');
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
            insuranceCoverage::where('id', $id)->delete();
            return back()->with(Toastr::error(__('Coverage deleted successfully!')));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
}
