<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Category;
use App\Models\SubCategory;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
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
        $objCategory = Category::latest()->whereNull('parent')->get();
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
        if ($request->category_id && $request->category_id != null) {
            $subCategory = SubCategory::where('category_id', $request->category_id)->latest()->get();
            return response()->json(['status' => true, 'data' => $subCategory]);
        }
        return view('admin.categories.index', compact('objCategory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = Category::whereNull('parent')->latest()->get();
        return view('admin.categories.add', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Convert to lowercase
        $slug = strtolower($request->name);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $objCategory = new Category();
        $objCategory->name = $request->name;
        $objCategory->slug = $slug;
        // $objCategory->parent = $request->parent;
        $objCategory->type = $request->type;
        $objCategory->image = $request->image;
        $objCategory->icon = $request->icon;

        $objCategory->status = $request->status;
        $croppedImage = $request->cropped_image;


        if ($request->image) {
            // Generate a unique file name for the image
            $imageName = 'category_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

            $destinationDirectory = public_path('storage/images/categories');

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);

            $objCategory->image = $imageName;
        }


        if ($objCategory->save()) {
            return redirect()->route('admin.categories.index')->with(Toastr::success('Category Created Successfully', '', ["positionClass" => "toast-top-right"]));
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

        $objCategory = Category::find($id);
        $parents = Category::whereNull('parent')->latest()->get();
        return view('admin.categories.edit', compact('objCategory', 'parents'));
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

        // Convert to lowercase
        $slug = strtolower($request->name);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $objCategory = Category::find($id);
        $objCategory->name = $request->name;
        $objCategory->slug = $slug;
        // $objCategory->parent = $request->parent;
        $objCategory->type = $request->type;
        $objCategory->icon = $request->icon;
        $objCategory->status = $request->status;

        if ($request->image) {
            // Generate a unique file name for the image
            $imageName = 'category_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

            $destinationDirectory = public_path('storage/images/categories');

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);

            // Check if the dealProduct has an existing image
            if (!empty($objCategory->image)) {
                $existingFilePath = public_path('storage/images/categories/') . $objCategory->image;
                if (file_exists($existingFilePath)) {
                    // Delete the file if it exists
                    unlink($existingFilePath);
                }
            }
        }
        $objCategory->image = $imageName ?? $objCategory->image;

        if ($objCategory->save()) {
            // return redirect()->route('admin.drivers.index')->with(Toastr::success('Driver Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            Toastr::success('Category Updated Successfully', '', ["positionClass" => "toast-top-right"]);
            return response()->json(["status" => true, "redirect_location" => route("admin.categories.index")]);
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
        $getCategory = Category::find($id);
        try {
            Category::find($id)->delete();
            return back()->with(Toastr::error(__('Category deleted successfully!')));
        } catch (\Exception $e) {
            $error_msg = Toastr::error(__('There is an error! Please try later!'));
            return redirect()->route('admin.categories.index')->with($error_msg);
        }
    }
}
