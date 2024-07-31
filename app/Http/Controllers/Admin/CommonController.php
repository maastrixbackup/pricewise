<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HouseType;
use App\Models\LoanType;
use App\Models\SpendingPurpose;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function purposes_index(Request $request)
    {
        if ($request->p_id && $request->p_id != null) {
            $loanType = LoanType::whereJsonContains('p_id', $request->p_id)->get();
            if (!$loanType->isEmpty()) {
                return response()->json(['status' => true, 'data' => $loanType]);
            }
        }


        $objSpending = SpendingPurpose::orderBy('id', 'desc')->get();
        return view('admin.spending_purpose.index', compact('objSpending'));
    }

    public function purposes_create()
    {
        return view('admin.spending_purpose.add');
    }

    public function purposes_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|unique:spending_purposes,name',
            // 'image' => 'required|image|max:2048|mimes:jpeg,png,jpg,gif,svg',
            // 'sub_category' => 'required',
        ]);
        // Convert to lowercase
        $slug = strtolower($request->name);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $postPurpose = new SpendingPurpose();
        $postPurpose->name = $request->name;
        $postPurpose->slug = $slug;
        $postPurpose->description = $request->description;
        $postPurpose->status = $request->status;

        // Handle the image file upload
        $filename = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->move(public_path('storage/images/loans/'), $filename);

        // Save the filename in the database
        $postPurpose->image = $filename;

        try {
            if ($postPurpose->save()) {
                return redirect()->back()->with(Toastr::success('Data Added Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }


    public function purposes_edit($id)
    {
        $purpose = SpendingPurpose::find($id);
        return view('admin.spending_purpose.edit', compact('purpose'));
    }

    public function purposes_update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            // 'image' => 'required|image|max:2048|mimes:jpeg,png,jpg,gif,svg',
            // 'sub_category' => 'required',
        ]);

        // Convert to lowercase
        $slug = strtolower($request->name);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Replace spaces and multiple hyphens with a single hyphen
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Trim hyphens from the beginning and end of the string
        $slug = trim($slug, '-');

        $purose_update = SpendingPurpose::find($id);
        $purose_update->name = $request->name;
        $purose_update->slug = $slug;
        $purose_update->description = $request->description;
        $purose_update->status = $request->status;

        if (isset($request->image)) {
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/loans/'), $filename);


            $existingFilePath = public_path('storage/images/loans/') . $purose_update->image;

            if (file_exists($existingFilePath)) {
                // Delete the file
                unlink($existingFilePath);
            }
        }

        $purose_update->image = $filename ?? $purose_update->image;

        try {
            if ($purose_update->save()) {
                return redirect()->back()->with(Toastr::success('Data Updated Successfully', '', ["positionClass" => "toast-top-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }

    public function purposes_destroy(Request $request)
    {
        $id = $request->input('id');
        try {
            SpendingPurpose::where('id', $id)->delete();
            return redirect()->back()->with('success', 'Data Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with($e->getMessage());
        }
    }

    public function loanType_index()
    {
        $loanTypes = LoanType::with('purposeDetails')->orderBy('id', 'desc')->get();
        return view('admin.loan_type.index', compact('loanTypes'));
    }



    public function loanType_create()
    {
        return view('admin.loan_type.add');
    }




    public function loanType_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'loan_type' => 'required|unique:loan_types,loan_type',
            'p_id' => 'required',
            'status' => 'required',
        ]);
        $loanType = new LoanType();
        $loanType->loan_type = $request->loan_type;
        $loanType->p_id = json_encode($request->p_id);
        $loanType->status = $request->status;
        $loanType->description = $request->description;
        if ($request->image) {
            // Handle the image file upload
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/loans/'), $filename);
        }
        // Save the filename in the database
        $loanType->image = $filename ?? '';

        try {
            if ($loanType->save()) {
                return redirect()->back()->with(Toastr::success('Loan Type Added Successfully.', '', ["positionClass" => "toast-bottom-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-bottom-right"]));
        }
    }


    public function loanType_edit($id)
    {
        $loanType = LoanType::find($id);
        return view('admin.loan_type.edit', compact('loanType'));
    }

    public function loanType_update(Request $request, $id)
    {
        $request->validate([
            'loan_type' => 'required',
            'p_id' => 'required',
            'status' => 'required',
        ]);

        $loanType = LoanType::find($id);
        $loanType->loan_type = $request->loan_type;
        $loanType->p_id = json_encode($request->p_id);
        $loanType->status = $request->status;
        $loanType->description = $request->description;

        if ($request->image) {
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/loans/'), $filename);


            $existingFilePath = public_path('storage/images/loans/') . $loanType->image;

            if (file_exists($existingFilePath)) {
                // Delete the file
                unlink($existingFilePath);
            }
        }

        $loanType->image = $filename ?? $loanType->image;

        try {
            if ($loanType->save()) {
                return redirect()->back()->with(Toastr::success('Loan Type Updated Successfully.', '', ["positionClass" => "toast-bottom-right"]));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-bottom-right"]));
        }
    }

    public function loanType_destroy(Request $request)
    {
        $id = $request->input('id');
        try {
            LoanType::where('id', $id)->delete();
            return redirect()->back()->with(Toastr::error('Data Deleted Successfully', '', ["positionClass" => "toast-bottom-right"]));
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-bottom-right"]));
        }
    }

    public function houseType_index(Request $request)
    {
        $ObjHouses = HouseType::orderBy('title', 'asc')->get();
        return view('admin.house_type.index', compact('ObjHouses'));
    }
    public function houseType_create(Request $request)
    {
        return view('admin.house_type.add');
    }
    public function houseType_store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:house_types,title',
            'image' => 'required'
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

        $houseType = new HouseType();
        $houseType->title = trim($request->title);
        $houseType->slug = $slug;
        $houseType->description = $request->description;
        $houseType->status = $request->status;
        if ($request->image) {
            // Handle the image file upload
            $filename = 'houses_' . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/houses/'), $filename);
        }
        // Save the filename in the database
        $houseType->image = $filename ?? '';

        try {
            if ($houseType->save()) {
                return redirect()->route('admin.house-type.index')->with(Toastr::success('House Type Added Successfully', '', ["positionClass" => "toast-top-right"]));

            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
    public function houseType_edit(Request $request, $id)
    {
        $house = HouseType::find($id);
        return view('admin.house_type.edit', compact('house'));
    }
    public function houseType_update(Request $request, $id)
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

        $houseType = HouseType::find($id);
        $houseType->title = trim($request->title);
        $houseType->slug = $slug;
        $houseType->description = $request->description;
        $houseType->status = $request->status;

        if (isset($request->image)) {
            // Handle the image file upload
            $filename = 'houses_' . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/houses/'), $filename);

            $existingFilePath = public_path('storage/images/houses/') . $houseType->image;

            if (file_exists($existingFilePath)) {
                // Delete the file
                unlink($existingFilePath);
            }
        }
        // Save the filename in the database
        $houseType->image = $filename ?? $houseType->image;

        try {
            if ($houseType->save()) {
                return redirect()->route('admin.house-type.index')->with(Toastr::success('House Updated Successfully', '', ["positionClass" => "toast-top-right"]));

            }
        } catch (\Exception $e) {
            return redirect()->back()->with(Toastr::error($e->getMessage(), '', ["positionClass" => "toast-top-right"]));
        }
    }
    public function houseType_destroy(Request $request)
    {
    }


    public function getLoanData(Request $request)
    {
    }
}
