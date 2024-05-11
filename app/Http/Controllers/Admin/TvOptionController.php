<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TvOption;
use App\Models\Provider;
use Brian2694\Toastr\Facades\Toastr;
class TvOptionController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:tv-options', ['only' => ['index', 'store']]);
        $this->middleware('permission:tv-options.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:tv-options.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tv-options.destroy', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = TvOption::latest()->get();
        return view('admin.tv_option.index',compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers = Provider::get();
        return view('admin.tv_option.create',compact('providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $newTvOption=new TvOption();
            $newTvOption->internet_options=json_encode($request->internet_options);
            $newTvOption->tv_options=json_encode($request->tv_options);
            $newTvOption->telephone_options=json_encode($request->telephone_options);
            $newTvOption->provider = $request->provider;
            $newTvOption->save();
            Toastr::success('Tv Option Added Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.tv-options.index');
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
        $providers = Provider::get();
        $tv_option = TvOption::where('id', $id)->first();
        $internet_options = json_decode($tv_option->internet_options);
        $tv_options = json_decode($tv_option->tv_options);
        $telephone_options = json_decode($tv_option->telephone_options);
        return view('admin.tv_option.edit', compact('tv_option','providers','internet_options','tv_options','telephone_options'));
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
            $TvOption=TvOption::findOrFail($id);
            $TvOption->internet_options=json_encode($request->internet_options);
            $TvOption->tv_options=json_encode($request->tv_options);
            $TvOption->telephone_options=json_encode($request->telephone_options);
            $TvOption->provider = $request->provider;
            $TvOption->save();
            Toastr::success('Tv Option Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.tv-options.index');
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
            TvOption::where('id', $id)->delete();
            return back()->with(Toastr::error(__('Tv Option deleted successfully!')));
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
}
