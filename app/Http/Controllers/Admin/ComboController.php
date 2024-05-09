<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Combo;
use App\Models\Category;
use Brian2694\Toastr\Facades\Toastr;
class ComboController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:combos', ['only' => ['index', 'store']]);
        $this->middleware('permission:combos.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:combos.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:combos.destroy', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Combo::latest()->get();
        return view('admin.combo.index',compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::latest()->get();
        return view('admin.combo.create',compact('categories'));
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'category' => 'required',
            'price' => 'required',
        ]);
        try {
            $newCombo = new Combo();
            $newCombo->title=$request->title;
            $filename = time().'.'.$request->image->getClientOriginalExtension();  
     
            $request->image->move(public_path('combo_images'), $filename);
            
            $newCombo->image = $filename;
            $newCombo->category = $request->category;
            $newCombo->price = $request->price;
            $newCombo->save();
            Toastr::success('Combo Added Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.combos.index');
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
        $combo = Combo::where('id', $id)->first();
        return view('admin.combo.edit',compact('categories','combo'));
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
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'category' => 'required',
            'price' => 'required',
        ]);
        try {
            $combo = Combo::findOrFail($id);
            $combo->title=$request->title;
            if ($request->image) {
                $filename = time().'.'.$request->image->getClientOriginalExtension(); 
                $request->image->move(public_path('combo_images'), $filename);
            }
            $combo->image = $filename  ??  $combo->image;
            $combo->category = $request->category;
            $combo->price = $request->price;
            $combo->save();
            Toastr::success('Combo Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.combos.index');
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
            Combo::where('id', $id)->delete();
            return back()->with(Toastr::error(__('Combo deleted successfully!')));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
}
