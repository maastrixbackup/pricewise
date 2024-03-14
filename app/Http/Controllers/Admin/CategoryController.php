<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objCategory = Category::latest()->get();
        return view('admin.categories.index', compact('objCategory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.categories.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $objDriver = new Category();
        $objDriver->name = $request->name;
        $objDriver->slug = $request->slug;
        $objDriver->parent = $request->parent;
        $objDriver->type = $request->type;
        // $objDriver->passport = $request->passport;
        // $objDriver->driving_license = $request->driving_license;
        // $objDriver->own_vehicle = $request->own_vehicle;
        // $objDriver->vehicle_model = $request->vehicle_model;
        // $objDriver->black_suit = $request->black_suit;
        // $objDriver->to_time = $request->to_time;
        // $objDriver->from_time = $request->from_time;
        // $objDriver->booking_responsible = $request->booking_responsible;
        // $objDriver->status = $request->status;
        // if ($request->file('cv') == null || $request->file('cv') == null) {
        //     $input['cv'] = $objDriver->cv;
        // } else {
        //     $destinationPath = '/driver_documents';
        //     $imgfile = $request->file('cv');
        //     $imgFilename = $imgfile->getClientOriginalName();
        //     $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
        //     $cvFile = $imgFilename;
        //     $objDriver->cv = $cvFile;
        // }
        if ($objDriver->save()) {
            return redirect()->route('admin.categories.index')->with(Toastr::success('Category Created Successfully', '', ["positionClass" => "toast-top-right"]));
            // Toastr::success('Driver Created Successfully', '', ["positionClass" => "toast-top-right"]);
            // return response()->json(["status" => true, "redirect_location" => route("admin.drivers.index")]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => false, 'message' => $message]);
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

        $objCategory = Category::find($id);
        return view('admin.categories.edit', compact('objCategory'));
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
        //echo 123;exit;
        $objCategory = Category::find($id);
        $objCategory->name = $request->name;
        $objCategory->slug = $request->slug;
        $objCategory->parent = $request->parent;
        $objCategory->type = $request->type;
        // if ($request->file('cv') == null || $request->file('cv') == null) {
        //     $input['cv'] = $objDriver->cv;
        // } else {
        //     $destinationPath = '/driver_documents';
        //     $imgfile = $request->file('cv');
        //     $imgFilename = $imgfile->getClientOriginalName();
        //     $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
        //     $cvFile = $imgFilename;
        //     $objDriver->cv = $cvFile;
        // }
        if ($objCategory->save()) {
            // return redirect()->route('admin.drivers.index')->with(Toastr::success('Driver Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            Toastr::success('Category Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, "redirect_location" => route("admin.categories.index")]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $id = $request->id;
        $getCategory = Category::find($id);
        try {
            Category::find($id)->delete();
            return back()->with(Toastr::error(__('Driver deleted successfully!')));
        } catch (Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.categories.index')->with($error_msg);
        }
    }
}

