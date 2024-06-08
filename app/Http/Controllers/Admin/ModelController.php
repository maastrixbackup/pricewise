<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
class ModelController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:models', ['only' => ['index', 'store']]);
        $this->middleware('permission:models.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:models.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:models.destroy', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = VehicleModel::latest()->get();
        return view('admin.model.index',compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::get();
        return view('admin.model.create',compact('brands'));
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
            'brand' => 'required',
        ]);
        try {
            $newModel = new VehicleModel();
            $newModel->name = $request->name;
            $newModel->brand_id = $request->brand;
            $newModel->save();
            Toastr::success('Model Added Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.models.index');
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
        $brands = Brand::get();
        $model = VehicleModel::where('id',$id)->first();
        return view('admin.model.edit',compact('brands','model'));
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
            'name' => 'required',
            'brand' => 'required',
        ]);
        try {
            $model=VehicleModel::findOrFail($id);
            $model->name = $request->name;
            $model->brand_id = $request->brand;
            $model->save();
            Toastr::success('Model Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.models.index');
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
            VehicleModel::where('id', $id)->delete();
            return back()->with(Toastr::error(__('Model deleted successfully!')));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
}
