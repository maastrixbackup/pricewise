<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HouseNumber;
use App\Models\PostalCode;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PostalCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postalCodes = PostalCode::latest()->get();
        return view('admin.common.postcode_index', compact('postalCodes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.common.postcode_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'postal_code' => 'required|unique:postal_codes,post_code,'
        ]);

        $postalCode = new PostalCode();
        $postalCode->post_code = $request->postal_code;

        DB::beginTransaction();
        try {
            $postalCode->save();
            DB::commit();
            session()->flash('toastr', [
                'type' => 'success',  // success, error, info, warning
                'message' => 'Post Code Added.',
                'title' => ''
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',  // success, error, info, warning
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            PostalCode::find($id)->delete();
            session()->flash('toastr', [
                'type' => 'success',  // success, error, info, warning
                'message' => 'Post Code Deleted.',
                'title' => ''
            ]);
            return redirect()->back();
        } catch (Exception $e) {
            session()->flash('toastr', [
                'type' => 'error',  // success, error, info, warning
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }


    public function houseNumberIndex()
    {
        $houseNumbers = HouseNumber::latest()->get();
        return view('admin.common.houseNumber_index', compact('houseNumbers'));
    }

    public function houseNumberCreate()
    {
        $postalCodes = PostalCode::latest()->get();
        return view('admin.common.houseNumber_add', compact('postalCodes'));
    }

    public function houseNumberStore(Request $request)
    {
        $request->validate([
            'postal_code' => 'required|unique:house_numbers,pc_id', // Validate postal code exists in postal_codes table
            'house_no' => [
                'required',
                function ($attribute, $value, $fail) {
                    $houseNumbers = explode(',', $value);
                    foreach ($houseNumbers as $houseNumber) {
                        if (!ctype_digit(trim($houseNumber))) {
                            $fail('The House Number must be a comma (,) separated numbers.');
                        }
                    }
                }
            ]
        ]);

        // Ensure no duplicates for postal_code and house_number combined
        if (HouseNumber::where('pc_id', $request->postal_code)
            ->where('house_number', json_encode(array_map('trim', explode(',', $request->house_no))))
            ->exists()
        ) {
            session()->flash('toastr', [
                'type' => 'error',  // success, error, info, warning
                'message' => 'House Number Already Exists for this postal code.',
                'title' => ''
            ]);
            return redirect()->back();
        }

        $houseNumber = new HouseNumber();
        $houseNumber->pc_id = $request->postal_code;
        $houseNumber->postal_codes = PostalCode::find($request->postal_code)->post_code;
        $houseNumber->house_number = json_encode($request->house_no ? array_map('trim', explode(",", $request->house_no)) : []);

        DB::beginTransaction();
        try {
            $houseNumber->save();
            DB::commit();
            session()->flash('toastr', [
                'type' => 'success',  // success, error, info, warning
                'message' => 'House Data Added.',
                'title' => ''
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('toastr', [
                'type' => 'error',  // success, error, info, warning
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }

    public function houseNumberEdit($id)
    {
        $postalCodes = PostalCode::latest()->get();
        $houseNumber = HouseNumber::find($id);
        // dd($houseNumber);
        return view('admin.common.houseNumber_edit', compact('postalCodes', 'houseNumber'));
    }

    public function houseNumberUpdate(Request $request, $id)
    {
        $request->validate([
            'house_no' => [
                'required',
                function ($attribute, $value, $fail) {
                    $houseNumbers = explode(',', $value);
                    foreach ($houseNumbers as $houseNumber) {
                        if (!ctype_digit(trim($houseNumber))) {
                            $fail('The House Number must be a comma (,) separated list of numbers.');
                        }
                    }
                }
            ]
        ]);

        // Fetch the existing house number record
        $houseNumberUpdate = HouseNumber::find($id);
        if (!$houseNumberUpdate) {
            session()->flash('toastr', [
                'type' => 'error',  // success, error, info, warning
                'message' => 'House number record not found.',
                'title' => ''
            ]);
            return redirect()->back();
        }

        // Get existing house numbers from the database and decode them from JSON format
        $existingHouseNumbers = json_decode($houseNumberUpdate->house_number, true) ?? [];

        // Convert the request house numbers to an array
        $newHouseNumbers = $request->house_no ? array_map('trim', explode(",", $request->house_no)) : [];

        // Merge new and existing house numbers, remove duplicates, and re-index the array
        $mergedHouseNumbers = array_values(array_unique(array_merge($existingHouseNumbers, $newHouseNumbers)));

        // Save the merged house numbers back to the database
        $houseNumberUpdate->house_number = json_encode($mergedHouseNumbers);

        DB::beginTransaction();
        try {
            $houseNumberUpdate->save();
            DB::commit();

            session()->flash('toastr', [
                'type' => 'success',  // success, error, info, warning
                'message' => 'House Number updated successfully.',
                'title' => ''
            ]);

            // toastr('success', 'House Number updated successfully.', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            session()->flash('toastr', [
                'type' => 'error',  // success, error, info, warning
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }

    public function houseNumberDestroy(Request $request)
    {
        try {
            HouseNumber::find($request->id)->delete();
            session()->flash('toastr', [
                'type' => 'success',  // success, error, info, warning
                'message' => 'Data Deleted',
                'title' => ''
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('toastr', [
                'type' => 'error',  // success, error, info, warning
                'message' => $e->getMessage(),
                'title' => ''
            ]);
            return redirect()->back();
        }
    }
}
