<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Category;
use App\Models\Provider;
use Illuminate\Support\Facades\Storage;

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
        $croppedImage = $request->cropped_image;
        $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));
        $imageName = 'provider_' . time() . '.png';
        $destinationDirectory = 'public/images/providers';
        Storage::makeDirectory($destinationDirectory);
        $filePath = $destinationDirectory . '/' . $imageName;
        Storage::put($filePath, $imgData);
        $objProvider->image = $imageName;
        if ($objProvider->save()) {
            //return redirect()->route('admin.providers.index')->with(Toastr::success('Provider Created Successfully', '', ["positionClass" => "toast-top-right"]));
            Toastr::success('Provider Created Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, "redirect_location" => route("admin.providers.index")]);
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
        $categories = Category::latest()->get();
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
        //echo 123;exit;
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
        if ($request->has('cropped_image')) {
        // Access base64 encoded image data directly from the request
        $croppedImage = $request->cropped_image;

        // Extract base64 encoded image data and decode it
        $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));

        // Generate a unique file name for the image
        $imageName = 'provider_' . time() . '.png';

        // Specify the destination directory where the image will be saved
        $destinationDirectory = 'public/images/providers';

        // Create the directory if it doesn't exist
        Storage::makeDirectory($destinationDirectory);

        // Save the image to the server using Laravel's file upload method
        $filePath = $destinationDirectory . '/' . $imageName;

        // Delete the old image if it exists
        if ($objProvider->image) {
            Storage::delete($destinationDirectory . '/' . $objProvider->image);
        }

        // Save the new image
        Storage::put($filePath, $imgData);

        // Set the image file name for the provider
        $objProvider->image = $imageName;
        }

        if ($objProvider->save()) {
            // return redirect()->route('admin.drivers.index')->with(Toastr::success('Driver Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            Toastr::success('Provider Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, "redirect_location" => route("admin.providers.index")]);
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

