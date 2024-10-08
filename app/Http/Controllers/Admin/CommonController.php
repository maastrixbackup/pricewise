<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\EnergyProduct;
use App\Models\GlobalEnergySetting;
use App\Models\HouseType;
use App\Models\LoanType;
use App\Models\Provider;
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
                $this->sendToastResponse('success', 'Data Added Successfully');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }


    public function purposes_edit($id)
    {
        $purpose = SpendingPurpose::find($id);
        return view('admin.spending_purpose.edit', compact('purpose'));
    }

    public function purposes_update(Request $request, $id)
    {
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

        $pUpdate = SpendingPurpose::find($id);
        $pUpdate->name = $request->name;
        $pUpdate->slug = $slug;
        $pUpdate->description = $request->description;
        $pUpdate->status = $request->status;

        if (isset($request->image)) {
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('storage/images/loans/'), $filename);

            $existingFilePath = public_path('storage/images/loans/') . $pUpdate->image;

            if (file_exists($existingFilePath)) {
                // Delete the file
                unlink($existingFilePath);
            }
        }

        $pUpdate->image = $filename ?? $pUpdate->image;

        try {
            if ($pUpdate->save()) {
                $this->sendToastResponse('success', 'Data Updated Successfully');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
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
                $this->sendToastResponse('success', 'Loan Data Added Successfully');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
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
                $this->sendToastResponse('error', 'Data Updated Successfully');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function loanType_destroy(Request $request)
    {
        $id = $request->input('id');
        try {
            LoanType::where('id', $id)->delete();
            $this->sendToastResponse('success', 'Data Deleted Successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
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
                $this->sendToastResponse('success', 'House type Added Successfully');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
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
                $this->sendToastResponse('success', 'Data Updated Successfully');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }
    public function houseType_destroy(Request $request) {}


    public function getLoanData(Request $request) {}

    public function getProviderData(Request $request)
    {
        $provider = Provider::find($request->id);
        // Check if provider exists
        if (!$provider) {
            return $this->jsonResponse(false, 'Provider not found');
        }
        // Return provider data as JSON
        return  response()->json($provider);
    }

    public function globalEnergySetting(Request $request)
    {
        $globalEnergy = GlobalEnergySetting::find(1);
        $c_id = config('constant.category.energy');
        return view('admin.energy.global_setting', compact('globalEnergy', 'c_id'));
    }

    public function globalEnergySettingStore(Request $request)
    {
        try {
            $geSetting = GlobalEnergySetting::updateOrCreate(
                ['id' => 1],
                [
                    'tax_on_electric' => $request->tax_on_electric,
                    'tax_on_gas' => $request->tax_on_gas,
                    'ode_on_electric' => $request->ode_on_electric,
                    'ode_on_gas' => $request->ode_on_gas,
                    'vat' => $request->vat,
                    'energy_tax_reduction' => $request->energy_tax_reduction,
                ]
            );
            // If setting updated, update all EnergyProduct records
            if ($geSetting) {
                $energyProducts = EnergyProduct::all();

                foreach ($energyProducts as $product) {
                    $product->update([
                        'tax_on_electric' => $geSetting->tax_on_electric,
                        'tax_on_gas' => $geSetting->tax_on_gas,
                        'ode_on_electric' => $geSetting->ode_on_electric,
                        'ode_on_gas' => $geSetting->ode_on_gas,
                        'vat' => $geSetting->vat,
                        'energy_tax_reduction' => $geSetting->energy_tax_reduction,
                    ]);
                }
            }
            $this->sendToastResponse('success', 'Setting Updated Successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }


    public function uploadDocument($id)
    {
        $p = Provider::find($id);
        $documents = Document::where(['post_id' => $p->id, 'category' => $p->category])->get();
        // dd($documents);
        return view('admin.partials.upload_documents', compact('p', 'documents'));
    }


    public function uploadDocumentStore(Request $request)
    {
        // Validate the request for multiple files
        $request->validate([
            'files.*' => 'required|file|mimes:pdf', // |max:1024 Validation rules for each file
            'files' => 'required|array', // Ensure 'files' is an array
        ]);

        $files = $request->file('files');

        $uploadResults = [];

        try {
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                    $fileName =  $file->getClientOriginalName();

                    // Store the file in the 'public/documents' directory
                    $filePath = $file->move(public_path('storage/documents/'), $fileName);

                    // Insert file details into the database
                    Document::create([
                        'filename' => $fileName,
                        'category' => $request->category,
                        'post_id' => $request->p_id,
                        'type' => $originalFileName,
                        'path' => 'storage/documents/' . $fileName,
                    ]);

                    $uploadResults[] = ['file' => $fileName, 'status' => 'success', 'message' => 'File uploaded successfully'];
                } else {
                    $uploadResults[] = ['file' => $file->getClientOriginalName(), 'status' => 'error', 'message' => 'Invalid file'];
                }
            }

            // Loop through the results and send a toast response for each file
            foreach ($uploadResults as $result) {
                $this->sendToastResponse($result['status'], $result['message']);
            }

            return redirect()->back();
        } catch (\Throwable $th) {
            // Handle any exception that occurred during the process
            $this->sendToastResponse('error', $th->getMessage());
            return redirect()->back();
        }
    }


    public function deleteUploadDocument(Request $request, $id)
    {
        try {
            $d = Document::find($id);
            if (!empty($d->filename)) {
                $existingFilePath = public_path('storage/documents/') . $d->filename;
                if (file_exists($existingFilePath)) {
                    // Delete the file if it exists
                    unlink($existingFilePath);
                }
            }
            $d->delete();
            $this->sendToastResponse('success', 'Document Deleted Successfully.');
            return redirect()->back();
        } catch (\Throwable $th) {
            $this->sendToastResponse('error', $th->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Helper function to return JSON responses
     */
    private function jsonResponse($status, $message, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Helper function to return Toastr responses
     */
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
