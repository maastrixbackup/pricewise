<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CyberSecurity;
use App\Models\SecurityFeature;
use App\Models\SecurityProvider;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class CyberSecurityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $securities = CyberSecurity::orderBy('id', 'desc')->get();
        return view('admin.cyber_security.index', compact('securities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cyber_security.add');
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
            'title' => 'required|unique:cyber_securities,title',
            // 'description' => 'required',
            'price' => 'required',
            'pin_codes' => 'required'
        ]);

        // dd($request->all());
        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $security = new CyberSecurity();
        $security->title = $request->title;
        $security->slug = $slug;
        $security->description = $request->description;
        $security->country_id = $request->country_id;
        $security->pin_codes = json_encode($request->pin_codes ? explode(",", $request->pin_codes) : []);
        $security->provider_id = json_encode($request->provider_id);
        $security->features = json_encode($request->features);
        $security->status = $request->status;
        $security->price = $request->price;
        $security->cloud_backup = $request->cloud_backup;
        $security->license_duration = $request->license_duration;
        $security->no_of_pc = $request->no_of_pc;
        $security->product_type = $request->product_type;

        if ($request->image) {
            // Generate a unique file name for the image
            $imageName = 'cyber_security_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

            $destinationDirectory = public_path('storage/images/cyber_security');

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);

            $security->image = $imageName;
        }

        try {
            if ($security->save()) {
                return redirect()->back()->with(Toastr::success('Product Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = CyberSecurity::find($id);
        return view('admin.cyber_security.edit', compact('product'));
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
        $request->validate([
            'title' => 'required',
            // 'description' => 'required',
            'price' => 'required',
            'pin_codes' => 'required'
        ]);

        // dd($request->all());
        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $security = CyberSecurity::find($id);
        $security->title = $request->title;
        $security->slug = $slug;
        $security->description = $request->description;
        $security->country_id = $request->country_id;
        $security->pin_codes = json_encode($request->pin_codes ? explode(",", $request->pin_codes) : []);
        $security->provider_id = json_encode($request->provider_id);
        $security->features = json_encode($request->features);
        $security->status = $request->status;
        $security->price = $request->price;
        $security->cloud_backup = $request->cloud_backup;
        $security->license_duration = $request->license_duration;
        $security->no_of_pc = $request->no_of_pc;
        $security->product_type = $request->product_type;


        if ($request->image) {
            // Generate a unique file name for the image
            $imageName = 'cyber_security_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

            $destinationDirectory = public_path('storage/images/cyber_security');

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);

            $existingFilePath = $destinationDirectory . '/' . $security->image;

            if (file_exists($existingFilePath)) {
                // Delete the file
                unlink($existingFilePath);
            }

            $security->image = $imageName;
        }

        try {
            if ($security->save()) {
                return redirect()->back()->with(Toastr::success('Product Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
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
            CyberSecurity::find($id)->delete();
            return redirect()->back()->with(Toastr::success('Security Product Deleted','', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(),'', ["positionClass" => "toast-top-right"]));
        }
    }


    // Security Provider Function

    public function sProvider_index(Request $request)
    {
        $sProviders = SecurityProvider::latest()->with('countryDetails')->get();
        return view('admin.security_provider.index', compact('sProviders'));
    }
    public function sProvider_create(Request $request)
    {
        return view('admin.security_provider.add');
    }

    public function sProvider_store(Request $request)
    {
        $request->validate([
            'title' => 'required |unique:security_providers,title',
            'address' => 'required',
            'description' => 'required',
            'country' => 'required'
        ]);

        // dd($request->all());

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');


        // Assuming $sProvider is an instance of your model
        $sProvider = new SecurityProvider();
        $sProvider->title = $request->title;
        $sProvider->slug = $slug;
        $sProvider->address = $request->address;
        $sProvider->description = $request->description;
        $sProvider->country_id = $request->country;
        $sProvider->status = $request->status;

        // Check if an image file was uploaded
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Get the original extension of the uploaded file
            $extension = $request->image->getClientOriginalExtension();
            // Create a unique filename
            $filename = 's_provider_' . time() . '.' . $extension;
            // Move the uploaded file to the desired directory
            $request->image->move(public_path('storage/images/cyber_security/'), $filename);
            // Store the filename in the database
            $sProvider->image = $filename;
        } else {
            // Handle the case where no file was uploaded or the file is invalid
            $sProvider->image = ''; // or handle according to your application's requirements
        }


        try {
            if ($sProvider->save()) {
                return redirect()->back()->with(Toastr::success('Data Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }


    public function sProvider_edit(Request $request, $id)
    {
        $sProvider = SecurityProvider::find($id);
        return view('admin.security_provider.edit', compact('sProvider'));
    }


    public function sProvider_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required ',
            'address' => 'required',
            'description' => 'required',
            'country' => 'required'
        ]);

        // dd($request->all());

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');


        // Assuming $sProvider is an instance of your model
        $sProvider = SecurityProvider::find($id);
        $sProvider->title = $request->title;
        $sProvider->slug = $slug;
        $sProvider->address = $request->address;
        $sProvider->description = $request->description;
        $sProvider->country_id = $request->country;
        $sProvider->status = $request->status;

        // Check if an image file was uploaded
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Get the original extension of the uploaded file
            $extension = $request->image->getClientOriginalExtension();
            // Create a unique filename
            $filename = 's_provider_' . time() . '.' . $extension;
            // Move the uploaded file to the desired directory
            $request->image->move(public_path('storage/images/cyber_security/'), $filename);
        }

        $sProvider->image = $filename ?? $sProvider->image;

        try {
            if ($sProvider->save()) {
                return redirect()->back()->with(Toastr::success('Data Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function sProvider_destroy(Request $request)
    {
        try {
            SecurityProvider::find($request->id)->delete();
            return redirect()->back()->with(Toastr::success('Data Deleted Successfully', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }



    public function sFeatures_index(Request $request)
    {
        $sFeatures = SecurityFeature::orderBy('id', 'desc')->get();
        return view('admin.security_feature.index', compact('sFeatures'));
    }

    public function sFeatures_create(Request $request)
    {
        return view('admin.security_feature.add');
    }

    public function sFeatures_store(Request $request)
    {
        $request->validate([
            'title' => 'required |unique:security_features,title',
        ]);

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');


        // Assuming $sProvider is an instance of your model
        $sFeature = new SecurityFeature();
        $sFeature->title = $request->title;
        $sFeature->slug = $slug;
        $sFeature->description = $request->description;
        $sFeature->status = $request->status;

        try {
            if ($sFeature->save()) {
                return redirect()->back()->with(Toastr::success('Feature Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function sFeatures_edit(Request $request, $id)
    {
        $sFeature = SecurityFeature::find($id);
        return view('admin.security_feature.edit', compact('sFeature'));
    }

    public function sFeatures_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
        ]);

        // Convert to lowercase
        $slug = strtolower($request->title);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');


        // Assuming $sProvider is an instance of your model
        $sFeature = SecurityFeature::find($id);
        $sFeature->title = $request->title;
        $sFeature->slug = $slug;
        $sFeature->description = $request->description;
        $sFeature->status = $request->status;

        try {
            if ($sFeature->save()) {
                return redirect()->back()->with(Toastr::success('Feature Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function sFeatures_destroy(Request $request)
    {
        try {
            SecurityFeature::find($request->id)->delete();
            return redirect()->back()->with(Toastr::success('Feature Deleted Successfully', '', ["positionClass" => "toast-top-right"]));
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::warning($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
}
