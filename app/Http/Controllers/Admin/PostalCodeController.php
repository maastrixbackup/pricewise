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
            $this->sendToastResponse('success', 'Postal Code Added');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $pCode = PostalCode::find($id);

            if (!$pCode) {
                $this->sendToastResponse('error', 'Postal Code not found');
                return redirect()->back();
            }

            HouseNumber::where('pc_id', $pCode->id)->delete();
            $pCode->delete();

            $this->sendToastResponse('success', 'Postal Code Deleted');
            return redirect()->back();
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
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


        // Ensure no duplicates for postal_code and house_number combined
        if (HouseNumber::where('pc_id', $request->postal_code)->exists()) {
            $this->sendToastResponse('error', 'House Data Already Exists for this postal code.');
            return redirect()->back();
        }
        try {
            $hNoAddress = [];
            $missingData = false;

            foreach ($request->house_no as $k => $v) {
                // Check if there's an address corresponding to the current house number
                if (isset($request->address[$k])) {
                    // Store house number and corresponding address in $hNoAddress
                    $hNoAddress[$v] = $request->address[$k];
                } else {
                    // Mark that some data is missing and send an error response
                    $missingData = true;
                    $this->sendToastResponse('error', "Missing data for house number: {$v}");
                }
            }

            // Check if there was missing data and handle accordingly
            if ($missingData) {
                return redirect()->back();  // Redirect back if there was any missing data
            }
            $houseNumber = new HouseNumber();
            $houseNumber->pc_id = $request->postal_code;
            $houseNumber->postal_codes = PostalCode::find($request->postal_code)->post_code;
            $houseNumber->house_number = json_encode($hNoAddress, true);
            $houseNumber->save();

            // If no data is missing, send success response
            $this->sendToastResponse('success', 'House Number & Address Added successfully');
            return redirect()->route('admin.house-numbers.index');
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
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
            $this->sendToastResponse('error', 'house number not found');
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
            $this->sendToastResponse('success', 'House number Updated Successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function houseNumberDestroy(Request $request)
    {
        try {
            HouseNumber::find($request->id)->delete();
            $this->sendToastResponse('success', 'Data Deleted');
            return redirect()->back();
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function postalCodeData(Request $req)
    {
        $pCodeData = HouseNumber::find($req->id);
        // Check if data exists for the given ID
        if (!$pCodeData) {
            return response()->json(['status' => false, 'message' => 'Postal code data not found'], 404);
        }

        $hData = json_decode($pCodeData->house_number, true);
        $cnt = 1;
        $html = '';

        // Accumulate HTML rows
        foreach ($hData as $k => $v) {
            $html .= '<tr>
                    <td>' . $cnt++ . '</td>
                    <td>' . htmlspecialchars($k) . '</td>
                    <td>' . htmlspecialchars($v) . '</td>
                  </tr>';
        }

        return response()->json([
            'status' => true,
            'html' => $html,
            'message' => 'Data Retrieved.',
        ]);
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

    // public function houseNumberStore(Request $request)
    // {
    //     // $request->validate([
    //     //     'postal_code' => 'required|unique:house_numbers,pc_id', // Validate postal code exists in postal_codes table
    //     //     'house_no' => [
    //     //         'required',
    //     //         function ($attribute, $value, $fail) {
    //     //             $houseNumbers = explode(',', $value);
    //     //             foreach ($houseNumbers as $houseNumber) {
    //     //                 if (!ctype_digit(trim($houseNumber))) {
    //     //                     $fail('The House Number must be a comma (,) separated numbers.');
    //     //                 }
    //     //             }
    //     //         }
    //     //     ]
    //     // ]);

    //     dd($request->all());

    //     try {
    //         $missingData = false;
    //         foreach ($request->house_no as $k => $v) {
    //             $hNoAddress = [];
    //             // Check if all required data for this contract year exists
    //             if (isset($req->address[$k])) {
    //                 $hNoAddress[$v] = $request->address[$k];

    //                 // $newP = new HouseNumber();
    //                 // $newP->provider_id = $req->p_id;
    //                 // $newP->cat_id = $req->c_id;
    //                 // $newP->title = $req->title;
    //                 // $newP->description = $req->desc;
    //                 // $newP->question = $v;
    //                 // $newP->answer = $req->answer[$k];
    //                 // $newP->created_at = now();
    //                 // $newP->updated_at = now();
    //                 // $newP->save();
    //             } else {
    //                 // Mark that some data is missing
    //                 $missingData = true;
    //                 $this->sendToastResponse('error', "Missing data for: {$v}");
    //             }
    //         }
    //         dd($hNoAddress);

    //         // Check if there was missing data and redirect accordingly
    //         if ($missingData) {
    //             return redirect()->back();  // Redirect back if there was any missing data
    //         }
    //         $this->sendToastResponse('success', 'Plan Faq Added successfully');
    //         return redirect()->route('admin.switching-plan-faqs', $request->p_id);
    //     } catch (\Exception $e) {
    //         $this->sendToastResponse('error', $e->getMessage());
    //         return redirect()->back();
    //     }



    //     // Ensure no duplicates for postal_code and house_number combined
    //     if (HouseNumber::where('pc_id', $request->postal_code)
    //         ->where('house_number', json_encode(array_map('trim', explode(',', $request->house_no))))
    //         ->exists()
    //     ) {
    //         $this->sendToastResponse('error', 'House Number Already Exists for this postal code.');
    //         return redirect()->back();
    //     }

    //     $houseNumber = new HouseNumber();
    //     $houseNumber->pc_id = $request->postal_code;
    //     $houseNumber->postal_codes = PostalCode::find($request->postal_code)->post_code;
    //     $houseNumber->house_number = json_encode($request->house_no ? array_map('trim', explode(",", $request->house_no)) : []);

    //     DB::beginTransaction();
    //     try {
    //         $houseNumber->save();
    //         DB::commit();
    //         $this->sendToastResponse('success', 'House Data Added');
    //         return redirect()->back();
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         $this->sendToastResponse('error', $e->getMessage());
    //         return redirect()->back();
    //     }
    // }
}
