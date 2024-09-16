<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Category;
use App\Models\Provider;
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
        $providers = Provider::latest()->get();
        return view('admin.providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::latest()->get();
        return view('admin.providers.add', compact('categories'));
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
        $objProvider->about = $request->about;
        $objProvider->category = $request->category;
        $objProvider->status = $request->status;
        $objProvider->payment_options = $request->payment_options;
        $objProvider->annual_accounts = $request->annual_accounts;
        $objProvider->meter_readings = $request->meter_readings;
        $objProvider->adjust_installments = $request->adjust_installments;
        $objProvider->view_consumption = $request->view_consumption;
        $objProvider->rose_scheme = $request->rose_scheme;

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
                return redirect()->route('admin.providers.index')->with(Toastr::success('Provider Created Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.providers.index')->with(Toastr::success($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
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
        $categories = Category::whereNull('parent')->latest()->get();
        $provider  = Provider::find($id);
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
        // dd($request->all());
        $request->validate(['name' => 'required']);

        $objProvider = Provider::find($id);
        $objProvider->name = $request->name;
        $objProvider->about = $request->about;
        $objProvider->status = $request->status;
        $objProvider->category = $request->category;
        $objProvider->payment_options = $request->payment_options;
        $objProvider->annual_accounts = $request->annual_accounts;
        $objProvider->meter_readings = $request->meter_readings;
        $objProvider->adjust_installments = $request->adjust_installments;
        $objProvider->view_consumption = $request->view_consumption;
        $objProvider->rose_scheme = $request->rose_scheme;

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

        if ($objProvider->save()) {
            return redirect()->route('admin.providers.index')->with(Toastr::success('Provider Updated Successfully', '', ["positionClass" => "toast-top-right"]));
        } else {
            return redirect()->route('admin.providers.index')->with(Toastr::error('Unable to Updated!', '', ["positionClass" => "toast-top-right"]));
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
        $getCategory = Provider::find($id);
        try {
            Provider::find($id)->delete();
            return back()->with(Toastr::error(__('Provider deleted successfully!')));
        } catch (\Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.providers.index')->with($error_msg);
        }
    }
}
