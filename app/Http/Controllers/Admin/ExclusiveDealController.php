<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Category;
use App\Models\TvInternetProduct;
use App\Models\InsuranceProduct;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ExclusiveDealController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:exclusive-deals', ['only' => ['index', 'store']]);
        $this->middleware('permission:exclusive-deals.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:exclusive-deals.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:exclusive-deals.destroy', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Deal::latest()->get();
        return view('admin.exclusive_deals.index',compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::latest()->get();
        return view('admin.exclusive_deals.create',compact('categories'));
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
            'title' => 'required',
            'valid_till' => 'required',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'required',
            'products' => 'required|min:1',
        ]);
        try {
            $newDeal = new Deal();
            $newDeal->title=$request->title;
            $newDeal->valid_till=$request->valid_till;

            $filename = time().'.'.$request->icon->getClientOriginalExtension();  
     
            $request->icon->move(public_path('deal_icons'), $filename);
            
            $newDeal->icon = $filename;
            $newDeal->category = $request->category;
            $newDeal->products = json_encode($request->products);
            $newDeal->status = $request->status;
            $newDeal->save();
            Toastr::success('Deal Added Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.exclusive-deals.index');
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
        $categories = Category::latest()->get();
        $deal = Deal::where('id', $id)->first();
        return view('admin.exclusive_deals.edit',compact('categories','deal'));
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
        // return $request;
        $request->validate([
            'title' => 'required',
            'valid_till' => 'required',
            'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'required',
            'products' => 'required|min:1',
        ]);
        try {
            $deal = Deal::findOrFail($id);
            $deal->title=$request->title;
            $deal->valid_till=$request->valid_till;
            if (isset($request->icon)) {
                $filename = time().'.'.$request->icon->getClientOriginalExtension(); 
                $request->icon->move(public_path('deal_icons'), $filename);
            }
            $deal->icon = $filename ?? $deal->icon;
            $deal->category = $request->category;
            $deal->products = json_encode($request->products);
            $deal->status = $request->status;
            $deal->save();
            Toastr::success('Deal Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.exclusive-deals.index');
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
            Deal::where('id', $id)->delete();
            return back()->with(Toastr::error(__('Deal deleted successfully!')));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }

    public function getProductsCategoryWise(Request $request)
    {
        
        if ($request->category_id==1) {

            $response = TvInternetProduct::latest()->get(); 
            return response()->json($response,200);

        }elseif ($request->category_id == 2) {

            $response = TvInternetProduct::latest()->get(); 
            return response()->json($response,200);

        }elseif ($request->category_id == 5) {

            $response = InsuranceProduct::latest()->get(); 
            return response()->json($response,200);

        }elseif ($request->category_id == 6) {

            $response = []; 
            return response()->json($response,200);

        }elseif ($request->category_id == 13) {

            $response = []; 
            return response()->json($response,200); 

        }elseif ($request->category_id == 14) {

            $response = TvInternetProduct::latest()->get(); 
            return response()->json($response,200);

        }elseif ($request->category_id == 16) {

            $response = TvInternetProduct::latest()->get(); 
            return response()->json($response,200); 
        }
    }
}
