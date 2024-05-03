<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\FAQ;
use App\Models\Feature;
use Brian2694\Toastr\Facades\Toastr;

class FAQController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:FAQ-list', ['only' => ['FAQList', 'FAQStore']]);
        $this->middleware('permission:FAQ-add', ['only' => ['FAQAdd', 'FAQStore']]);
        $this->middleware('permission:FAQ-edit', ['only' => ['FAQEdit', 'FAQupdate']]);
        $this->middleware('permission:FAQ-delete', ['only' => ['FAQDelete']]);
    }
    public function FAQList()
    {
        $data = FAQ::latest()->with('categoryDetails')->get();
        return view('admin.FAQ.list', compact('data'));
    }
    public function FAQAdd()
    {
        $categories = Category::latest()->get();
        return view('admin.FAQ.add', compact('categories'));
    }
    public function FAQStore(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category' => 'required',
        ]);
        try {
            $newFAQ = new FAQ();
            $newFAQ->title = $request->title;
            $newFAQ->description = $request->description;
            $newFAQ->icon = $request->icon;
            $newFAQ->category_id = $request->category;
            $newFAQ->save();
            Toastr::success('FAQ Added Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.FAQ-list');
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
    public function FAQEdit(Request $request)
    {
        $faq = FAQ::where('id', $request->id)->first();
        $categories = Category::latest()->get();
        return view('admin.FAQ.edit', compact('categories', 'faq'));
    }
    public function FAQupdate(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category' => 'required',
        ]);
        try {
            $faq = FAQ::where('id', $request->id)->first();
            $faq->title = $request->title;
            $faq->description = $request->description;
            $faq->category_id = $request->category;
            $faq->icon =  $request->icon ? $request->icon : $faq->icon;
            $faq->save();
            Toastr::success('FAQ Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.FAQ-list');
        } catch (\Exception $e) {
            Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }
    public function FAQDelete(Request $request)
    {
    try {
        FAQ::where('id', $request->id)->delete();
        return back()->with(Toastr::error(__('FAQ deleted successfully!')));
    } catch (\Exception $e) {
        Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]);
        return back();
    }
    }
}
