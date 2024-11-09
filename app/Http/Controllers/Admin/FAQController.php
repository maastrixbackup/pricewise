<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\FAQ;
use App\Models\GeneralFaq;
use App\Models\ProviderFaq;
use App\Models\Feature;
use App\Models\Provider;
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
        $categories = Category::latest()->whereNull('parent')->get();
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
            $this->sendToastResponse('success', 'FAQ Added Successfully');
            return redirect()->route('admin.FAQ-list');
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return back();
        }
    }
    public function FAQEdit(Request $request)
    {
        $faq = FAQ::where('id', $request->id)->first();
        $categories = Category::latest()->whereNull('parent')->get();
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
            $this->sendToastResponse('success', 'FAQ Updated Successfully');
            return redirect()->route('admin.FAQ-list');
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return back();
        }
    }
    public function FAQDelete(Request $request)
    {
        try {
            FAQ::where('id', $request->id)->delete();
            $this->sendToastResponse('success', 'FAQ Deleted Successfully');
            return back();
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return back();
        }
    }

    public function generalFaqs($id)
    {
        $generalFaqs = GeneralFaq::where('cat_id', $id)->orderBy('id', 'desc')->get();
        $c_id = $id;
        return view('admin.general_faqs.list', compact('generalFaqs', 'c_id'));
    }

    public function generalFaqsAdd($id)
    {
        return view('admin.general_faqs.add', compact('id'));
    }

    public function generalFaqsStore(Request $req)
    {
        // dd($req->all());
        try {
            $missingData = false;
            foreach ($req->title as $k => $v) {
                // Check if all required data for this contract year exists
                if (isset($req->description[$k])) {
                    $newP = new GeneralFaq();
                    $newP->title = $v;
                    $newP->description = $req->description[$k];
                    $newP->cat_id = $req->cat;
                    $newP->created_at = now();
                    $newP->updated_at = now();
                    $newP->save();
                } else {
                    // Mark that some data is missing
                    $missingData = true;
                    $this->sendToastResponse('error', "Missing data for: {$v}");
                }
            }

            // Check if there was missing data and redirect accordingly
            if ($missingData) {
                return redirect()->back();  // Redirect back if there was any missing data
            }
            $this->sendToastResponse('success', 'Faq Added successfully');
            return redirect()->route('admin.general-faqs', $req->cat);
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function generalFaqsEdit($id)
    {
        $gFaqs = GeneralFaq::find($id);
        return view('admin.general_faqs.edit', compact('gFaqs'));
    }

    public function generalFaqsUpdate(Request $req)
    {
        try {
            $faq = GeneralFaq::find($req->id);
            $faq->title = $req->title;
            $faq->description = $req->description;
            $faq->save();
            $this->sendToastResponse('success', 'Faq Updated Successfully.');
            return redirect()->route('admin.general-faqs', $faq->cat_id);
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function generalFaqsDelete(Request $req)
    {
        try {
            GeneralFaq::where('id', $req->id)->delete();
            $this->sendToastResponse('success', 'Faq Deleted Successfully');
            return back();
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return back();
        }
    }

    public function providerFaqs($id)
    {
        $pFaqs = ProviderFaq::where('cat_id', $id)->orderBy('id', 'desc')->get();
        return view('admin.provider_faqs.list', compact('pFaqs', 'id'));
        // dd($provider);
    }

    public function providerFaqsAdd($id)
    {
        $provider = Provider::where('category', $id)->get();
        return view('admin.provider_faqs.add', compact('provider', 'id'));
    }

    public function providerFaqsStore(Request $req)
    {
        try {
            $missingData = false;
            foreach ($req->title as $k => $v) {
                // Check if all required data for this contract year exists
                if (isset($req->description[$k])) {
                    $newP = new ProviderFaq();
                    $newP->title = $v;
                    $newP->provider_id = $req->provider;
                    $newP->description = $req->description[$k];
                    $newP->cat_id = $req->cat;
                    $newP->created_at = now();
                    $newP->updated_at = now();
                    $newP->save();
                } else {
                    // Mark that some data is missing
                    $missingData = true;
                    $this->sendToastResponse('error', "Missing data for: {$v}");
                }
            }

            // Check if there was missing data and redirect accordingly
            if ($missingData) {
                return redirect()->back();  // Redirect back if there was any missing data
            }
            $this->sendToastResponse('success', 'Provider Faq Added successfully');
            return redirect()->route('admin.provider-faqs', $req->cat);
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function providerFaqsEdit($id)
    {
        $pFaq = ProviderFaq::find($id);
        $provider = Provider::find($pFaq->provider_id);
        return view('admin.provider_faqs.edit', compact('provider', 'pFaq'));
    }

    public function providerFaqsUpdate(Request $req)
    {
        try {
            $faq = ProviderFaq::find($req->id);
            $faq->title = $req->title;
            $faq->description = $req->description;
            $faq->save();
            $this->sendToastResponse('success', 'Provider Faq Updated Successfully.');
            return redirect()->route('admin.provider-faqs', $faq->cat_id);
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function providerFaqsDelete(Request $req)
    {
        try {
            ProviderFaq::where('id', $req->id)->delete();
            $this->sendToastResponse('success', 'Faq Deleted Successfully');
            return back();
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return back();
        }
    }

    public function sendToastResponse($type, $message, $title = '')
    {
        // Set up toast response with type, message, and optional title
        return session()->flash('toastr', [
            'type' => $type,
            'message' => $message,
            'title' => $title
        ]);
    }
}
