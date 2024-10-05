<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Category;
use App\Models\EnergyProduct;
use App\Models\InsuranceProduct;
use App\Models\Provider;
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
        // dd($request->all());
        $request->validate([
            'name' => 'required|unique:providers,name'
        ]);

        $objProvider = new Provider();
        $objProvider->name = $request->name;
        $objProvider->category = $request->category;
        $objProvider->status = $request->status;
        $objProvider->fixed_deliver_cost = $request->fix_delivery;
        $objProvider->grid_management_cost = $request->grid_management;
        $objProvider->feed_in_tariff = $request->feed_in_tariff;

        $croppedImage = $request->image;
        if ($request->hasFile('image')) {
            // Handle the image file upload
            $filename = 'provider_' . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/providers/'), $filename);
        }
        // Save the filename in the database
        $objProvider->image = $filename ?? '';

        try {
            if ($objProvider->save()) {
                $this->sendToastResponse('success', 'Provider Added Successfully!');
                return redirect()->route('admin.providers', config('constant.category.energy'));
            }
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->route('admin.providers', config('constant.category.energy'));
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
                    EnergyProduct::where('provider_id', $provider->id)->delete();
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
