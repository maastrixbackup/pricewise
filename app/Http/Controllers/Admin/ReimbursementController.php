<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Reimbursement;
use App\Models\Category;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReimbursementController extends Controller
{
    protected string $guard = 'admin';
    public function guard()
    {
        return Auth::guard($this->guard);
    }
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:category-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $objCategory = Reimbursement::latest()->get();
//if(Auth::guard('admin')->user()->can('role-list')){dd('hi');}
//dd(\Auth::guard('admin')->user()->roles);
// foreach (Auth::guard('admin')->user()->roles as $role) {
//         $permissions = $role->permissions;
//          echo $permission->name . "\n";
//     }
    // $permissions = Permission::all();

    // foreach ($permissions as $permission) {
    //     echo $permission->name . "\n";
    // }
        if($request->category_id && $request->category_id != null){
            $subCategory = Reimbursement::where('parent', $request->category_id)->latest()->get();
            return response()->json(['status' => true, 'data' => $subCategory]);
        }
        return view('admin.reimbursement.index', compact('objCategory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = Reimbursement::whereNull('parent')->latest()->get();
        $categories = Category::where('parent', 5)->get();
       return view('admin.reimbursement.add', compact('parents','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $objCategory = new Reimbursement();
        $objCategory->name = $request->name;
        $objCategory->description = $request->description;        
        $objCategory->parent = $request->parent;
        $objCategory->type = $request->type;
        $objCategory->sub_category = $request->sub_category;
        // $objCategory->icon = $request->icon;
        
        // $objCategory->status = $request->status;
        if($request->has('cropped_image')){
        $croppedImage = $request->cropped_image;

        // Extract base64 encoded image data and decode it
        $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));

        // Generate a unique file name for the image
        $imageName = 'category_' . time() . '.png';

        // Specify the destination directory where the image will be saved
        $destinationDirectory = 'public/images/reimbursement';

        // Create the directory if it doesn't exist
        Storage::makeDirectory($destinationDirectory);

        // Save the image to the server using Laravel's file upload method
        $filePath = $destinationDirectory . '/' . $imageName;
        Storage::put($filePath, $imgData);

        // Set the image file name for the provider
        //$objCategory->image = $imageName;
        }
        if ($objCategory->save()) {
            return redirect()->route('admin.reimbursement.index')->with(Toastr::success('Reimbursement Created Successfully', '', ["positionClass" => "toast-top-right"]));
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
        $categories = Category::where('parent', 5)->get();
        $objCategory = Reimbursement::find($id);
        $parents = Reimbursement::whereNull('parent')->latest()->get();
        return view('admin.reimbursement.edit', compact('objCategory', 'parents', 'categories'));
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
        $objCategory = Reimbursement::find($id);
        $objCategory->name = $request->name;
        //$objCategory->slug = $request->slug;
        $objCategory->parent = $request->parent;
        $objCategory->type = $request->type;
        $objCategory->sub_category = $request->sub_category;
        //$objCategory->icon = $request->icon;
        $objCategory->description = $request->description;
        //$objCategory->status = $request->status;
        // if ($request->has('cropped_image')) {
        // // Access base64 encoded image data directly from the request
        // $croppedImage = $request->cropped_image;

        // // Extract base64 encoded image data and decode it
        // $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));

        // // Generate a unique file name for the image
        // $imageName = 'category_' . time() . '.png';

        // // Specify the destination directory where the image will be saved
        // $destinationDirectory = 'public/images/reimbursement';

        // // Create the directory if it doesn't exist
        // Storage::makeDirectory($destinationDirectory);

        // // Save the image to the server using Laravel's file upload method
        // $filePath = $destinationDirectory . '/' . $imageName;

        // // Delete the old image if it exists
        // if ($objCategory->image) {
        //     Storage::delete($destinationDirectory . '/' . $objCategory->image);
        // }

        // // Save the new image
        // Storage::put($filePath, $imgData);

        // // Set the image file name for the provider
        // $objCategory->image = $imageName;
        // }
        if ($objCategory->save()) {
            // return redirect()->route('admin.drivers.index')->with(Toastr::success('Driver Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            Toastr::success('Reimbursement Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, "redirect_location" => route("admin.reimbursement.index")]);
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
        $getCategory = Reimbursement::find($id);
        try {
            Reimbursement::find($id)->delete();
            return back()->with(Toastr::error(__('Reimbursement deleted successfully!')));
        } catch (Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.categories.index')->with($error_msg);
        }
    }
}

