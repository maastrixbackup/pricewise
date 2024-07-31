<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Page;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class PageController extends AdminController
{
    protected string $guard = 'admin';
    public function guard()
    {
        return Auth::guard($this->guard);
    }
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:page-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:page-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:page-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:page-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::all()->sortByDesc('id');

        return view('admin.pages.index', ['pages' => $pages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $page = Page::updateOrCreate(['title' => $request->title], [
            'title' => $request->title,
            'image' => $request->image,
            'status' => $request->status,
            'description' => $request->description
        ]);
        if ($page) {
            return redirect()->route('admin.pages.index')->with(Toastr::success('page Created Successfully', '', ["positionClass" => "toast-top-right"]));
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
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Page $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {

        return view('admin.pages.edit', ['page' => $page]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {

        $page->update([
            'title' => $request->title,
            'image' => $request->image,
            'status' => $request->status,
            'description' => $request->description
        ]);
        if ($page) {
            // return redirect()->route('admin.drivers.index')->with(Toastr::success('Driver Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            Toastr::success('Page Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, "redirect_location" => route("admin.pages.index")]);
        } else {
            $message = array('message' => 'Something went wrong !! Please Try again later', 'title' => '');
            return response()->json(["status" => true, 'message' => $message]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $id = $request->id;
        $page = Page::find($id);
        try {
            Page::find($id)->delete();
            return back()->with(Toastr::error(__('Page deleted successfully!')));
        } catch (\Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.pages.index')->with($error_msg);
        }
    }
}
