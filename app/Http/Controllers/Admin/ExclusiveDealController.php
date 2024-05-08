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
    // function __construct()
    // {
    //     $this->middleware('auth:admin');
    //     $this->middleware('permission:exclusive-deals', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:exclusive-deals.create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:exclusive-deals.edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:exclusive-deals.destroy', ['only' => ['destroy']]);
    // }
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
