<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\insuranceCoverage;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
class InsuranceCoverageController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('auth:admin');
    //     $this->middleware('permission:insurance-coverages', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:insurance-coverages.create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:insurance-coverages.edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:insurance-coverages.destroy', ['only' => ['destroy']]);
    // }
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
        $subCategories = Category::where('parent',5)->get();
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
            $newDeal = new insuranceCoverage();
            $newDeal->name=$request->name;
            $newDeal->description=$request->description;

            $filename = time().'.'.$request->image->getClientOriginalExtension();  
     
            $request->image->move(public_path('storage/images/insurance_coverages/'), $filename);
            
            $newDeal->image = $filename;
            $newDeal->subcategory_id = $request->sub_category;
            $newDeal->save();
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
        $subCategories = Category::where('parent',5)->get();
        $coverage = insuranceCoverage::where('id', $id)->first();
        return view('admin.insurance_coverage.edit',compact('subCategories','coverage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // try {
        //     $deal = Deal::findOrFail($id);
        //     $deal->title=$request->title;
        //     $deal->valid_till=$request->valid_till;
        //     if (isset($request->icon)) {
        //         $filename = time().'.'.$request->icon->getClientOriginalExtension(); 
        //         $request->icon->move(public_path('deal_icons'), $filename);
        //     }
        //     $deal->icon = $filename ?? $deal->icon;
        //     $deal->category = $request->category;
        //     $deal->products = json_encode($request->products);
        //     $deal->status = $request->status;
        //     $deal->save();
        //     Toastr::success('Deal Updated Successfully', '', ["positionClass" => "toast-top-right"]);
        //     return redirect()->route('admin.exclusive-deals.index');
        // } catch (\Exception $e) {
        //     Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
        //     return back();
        // }
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
