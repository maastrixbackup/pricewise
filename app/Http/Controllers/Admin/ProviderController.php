<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Category;
use App\Models\Document;
use App\Models\EnergyProduct;
use App\Models\InsuranceProduct;
use App\Models\Provider;
use App\Models\ProviderFaq;
use App\Models\SwitchingPlanFaq;
use App\Models\TvInternetProduct;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    protected string $guard = 'admin';
    public function guard()
    {
        return Auth::guard($this->guard);
    }
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:providers-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:providers-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:providers-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:providers-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = Provider::latest()->with('categoryDetail')->get();
        return view('admin.providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $c_id = session()->get('ct_id');
        $categories = Category::latest()->get();
        return view('admin.providers.add', compact('categories', 'c_id'));
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
            'category' => 'required',
            'status' => 'required',
            'fix_delivery' => 'required',
            'grid_management' => 'required',
            'feed_in_tariff' => 'required',
            'about' => 'required',
            'discount' => 'required',
            'payment_options' => 'required',
            'annual_accounts' => 'required',
            'meter_readings' => 'required',
            'adjust_installments' => 'required',
            'view_consumption' => 'required',
            // 'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Check for existing provider with the same name and category
        if (Provider::where('name', $request->name)->where('category', $request->category)->exists()) {
            $this->sendToastResponse('warning', 'Provider Name Already Exists');
            return redirect()->back()->withInput();
        }

        $objProvider = new Provider();
        $objProvider->name = $request->name;
        $objProvider->category = $request->category;
        $objProvider->status = $request->status;
        $objProvider->fixed_deliver_cost = $request->fix_delivery;
        $objProvider->grid_management_cost = $request->grid_management;
        $objProvider->feed_in_tariff = $request->feed_in_tariff;
        $objProvider->about = $request->about;
        $objProvider->discount = $request->discount;
        $objProvider->payment_options = $request->payment_options;
        $objProvider->annual_accounts = $request->annual_accounts;
        $objProvider->meter_readings = $request->meter_readings;
        $objProvider->adjust_installments = $request->adjust_installments;
        $objProvider->view_consumption = $request->view_consumption;

        if ($request->hasFile('image')) {
            $filename = 'provider_' . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/providers/'), $filename);
            $objProvider->image = $filename;
        }

        try {
            if ($objProvider->save()) {
                $this->sendToastResponse('success', 'Provider Added Successfully!');
                return redirect()->route('admin.providers', config('constant.category.energy'));
            }
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->route('admin.providers', config('constant.category.energy'))->withInput();
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
        $id = $id;
        session()->put('ct_id', $id);
        $providers = Provider::where('category', $id)->with('categoryDetail')->get();
        return view('admin.providers.index', compact('providers', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::whereNull('parent')->latest()->get();
        $provider  = Provider::with('categoryDetail')->find($id);
        return view('admin.providers.edit', compact('provider', 'categories'));
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
        // $request->validate([
        //     'name' => 'required',
        //     'status' => 'required',
        //     'category' => 'required',
        //     'fix_delivery' => 'required',
        //     'grid_management' => 'required',
        //     'feed_in_tariff' => 'required'
        // ]);

        try {
            $objProvider = Provider::find($id);
            $objProvider->name = $request->name;
            $objProvider->status = $request->status;
            $objProvider->category = $request->category;
            $objProvider->fixed_deliver_cost = $request->fix_delivery;
            $objProvider->grid_management_cost = $request->grid_management;
            $objProvider->feed_in_tariff = $request->feed_in_tariff;
            $objProvider->about = $request->about;
            $objProvider->discount = $request->discount;
            $objProvider->payment_options = $request->payment_options;
            $objProvider->annual_accounts = $request->annual_accounts;
            $objProvider->meter_readings = $request->meter_readings;
            $objProvider->adjust_installments = $request->adjust_installments;
            $objProvider->view_consumption = $request->view_consumption;

            if ($request->hasFile('image')) {
                // Handle the image file upload
                $filename = 'provider_' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('storage/images/providers/'), $filename);

                // Check if the provider has an existing image
                if (!empty($objProvider->image)) {
                    $existingFilePath = public_path('storage/images/providers/') . $objProvider->image;
                    if (file_exists($existingFilePath)) {
                        // Delete the file if it exists
                        unlink($existingFilePath);
                    }
                }

                // Save the new filename in the database
                $objProvider->image = $filename;
            } else {
                // If no new image is uploaded, retain the existing image
                $filename = $objProvider->image;
            }
            EnergyProduct::where('provider_id', $objProvider->id)->update([
                'fixed_delivery' => $request->fix_delivery,
                'grid_management' => $request->grid_management,
                'feed_in_tariff' => $request->feed_in_tariff
            ]);
            $objProvider->save();
            $this->sendToastResponse('success', 'Provider Updated Successfully!');
            return redirect()->route('admin.providers', config('constant.category.energy'));
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->route('admin.providers', config('constant.category.energy'));
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
        try {
            // Find the provider by ID
            $provider = Provider::find($id);

            // Check if provider exists
            if (!$provider) {
                // Send error response if the provider is not found
                $this->sendToastResponse('error', 'Provider not found!');
                return redirect()->back();
            }
            // Check if the provider has an existing image
            if (!empty($provider->image)) {
                $existingFilePath = public_path('storage/images/providers/') . $provider->image;
                if (file_exists($existingFilePath)) {
                    // Delete the file if it exists
                    unlink($existingFilePath);
                }
            }

            // Delete related products based on provider's category
            switch ($provider->category) {
                case config('constant.category.energy'):
                    // For Energy Products
                    SwitchingPlanFaq::where(['provider_id' => $provider->id, 'cat_id' => $provider->category])->delete();
                    ProviderFaq::where(['provider_id' => $provider->id, 'cat_id' => $provider->category])->delete();
                    EnergyProduct::where('provider_id', $provider->id)->delete();
                    Document::where('post_id', $provider->id)->delete();
                    break;

                case config('constant.category.Smartphones'):
                    // For SmartPhone Products (implement the logic as needed)
                    $spP = ''; // Placeholder for smartphone product logic
                    break;

                case config('constant.category.Insurance'):
                    // For Insurance Products
                    InsuranceProduct::where('provider', $provider->id)->delete();
                    break;

                case '1':
                    // For Internet & TV Products
                    TvInternetProduct::where('provider', $provider->id)->delete();
                    break;

                default:
                    // Send error response for an unknown provider category
                    $this->sendToastResponse('error', 'Unknown provider category.');
                    return redirect()->back();
            }

            // Delete the provider itself
            $provider->delete();

            // Success response after deletion
            $this->sendToastResponse('success', 'Provider deleted successfully.');
            return redirect()->route('admin.providers', config('constant.category.energy'));
        } catch (\Exception $e) {
            // Send error response if there's an exception
            $this->sendToastResponse('error', 'There was an error: ' . $e->getMessage());
            return redirect()->route('admin.providers', config('constant.category.energy'));
        }
    }

    public function switchingPlanFaqs($id)
    {
        $pFaqs = SwitchingPlanFaq::where('provider_id', $id)->get();
        return view('admin.switch_plan.list', compact('id', 'pFaqs'));
    }

    public function switchingPlanFaqsAdd($id)
    {
        $p = Provider::find($id);
        return view('admin.switch_plan.add', compact('p'));
    }

    public function switchingPlanFaqsStore(Request $req)
    {
        // dd($req->all());
        try {
            $missingData = false;
            foreach ($req->question as $k => $v) {
                // Check if all required data for this contract year exists
                if (isset($req->answer[$k])) {
                    $newP = new SwitchingPlanFaq();
                    $newP->provider_id = $req->p_id;
                    $newP->cat_id = $req->c_id;
                    $newP->title = $req->title;
                    $newP->description = $req->desc;
                    $newP->question = $v;
                    $newP->answer = $req->answer[$k];
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
            $this->sendToastResponse('success', 'Plan Faq Added successfully');
            return redirect()->route('admin.switching-plan-faqs', $req->p_id);
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }
    public function switchingPlanFaqsEdit(Request $req, $id)
    {
        $pFaq = SwitchingPlanFaq::find($id);
        return view('admin.switch_plan.edit', compact('pFaq'));
        // dd($id);
    }
    public function switchingPlanFaqsUpdate(Request $req)
    {
        // dd($req->all());
        try {
            $sP = SwitchingPlanFaq::find($req->id);
            $sP->question = $req->question;
            $sP->answer = $req->answer;
            $sP->save();
            $this->sendToastResponse('success', 'Plan Faq Updated successfully');
            return redirect()->route('admin.switching-plan-faqs', $req->p_id);
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }
    public function switchingPlanFaqsDelete(Request $req, $id)
    {
        // dd($id);
        try {
            SwitchingPlanFaq::where('id', $id)->delete();
            $this->sendToastResponse('success', 'Plan Faq Deleted Successfully');
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
