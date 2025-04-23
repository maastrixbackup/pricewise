<?php


namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\TvInternetProduct;
use App\Models\EnergyProduct;
use App\Models\Provider;
use App\Models\Feature;
use Validator;
use App\Http\Resources\EnergyResource;
use App\Models\HouseType;
use App\Models\HouseNumber;
use App\Models\PostalCode;
use DB;
use App\Models\User;
use App\Models\UserData;

class SettingsController extends BaseController
{



    public function getSupliers()
    {
        $providers = Provider::get();

        return $this->sendResponse($providers, 'Suppliers retrieved successfully.');
    }

    public function houseTypes()
    {
        $housesTypeArray = HouseType::orderBy('title', 'asc')->get();
        $housesArray = [];
        foreach ($housesTypeArray as $val) {
            $housesArray[] = [
                'id' => $val->id,
                'name' => $val->title,
                'slug' => $val->slug,
                'image' => asset('storage/images/houses/' . $val->image)
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $housesArray,
            'message' => 'House types retrieved successfully.'
        ]);
    }


    public function getHouseNumber(Request $request)
    {
        // Validate request input
        $validatedData = $request->validate([
            'postal_code' => 'required|string',
            'house_no' => 'required|string',
        ], [
            'postal_code.required' => 'Postal Code is required.',
            'house_no.required' => 'House Number is required.',
        ]);

        // Normalize postal code and house number
        $postalCode = str_replace(' ', '', $validatedData['postal_code']);
        $houseNumber = str_replace(' ', '', $validatedData['house_no']);

        // Retrieve postal code data
        $postalCodeData = PostalCode::where('post_code', $postalCode)->first();

        if (!$postalCodeData) {
            return $this->jsonResponse(false, 'Invalid Postal Code', '1');
        }

        // Retrieve house number data associated with postal code
        $houseData = HouseNumber::where('pc_id', $postalCodeData->id)->get();

        if ($houseData->isEmpty()) {
            return $this->jsonResponse(false, 'House Number Data Not Found', '2');
        }

        // Parse house number data
        $address = '';
        foreach ($houseData as $houseNumberRecord) {
            $decodedData = json_decode($houseNumberRecord->house_number, true);

            if (isset($decodedData[$houseNumber])) {
                $address = $decodedData[$houseNumber];
                break;
            }
        }

        if (empty($address)) {
            return $this->jsonResponse(false, 'House Number not found in records', '3');
        }

        // Prepare response data
        $response = [
            'postal_code' => $postalCode,
            'house_no' => $houseNumber,
            'address' => $address,
        ];

        // Return success response
        return $this->jsonResponse(true, 'Address found in Postal Code and House No', $response);
    }

    // public function getHouseNumber(Request $request)
    // {
    //     // Validate if postal_code is provided
    //     if (!$request->filled('postal_code')) {
    //         return $this->jsonResponse(false, 'Postal Code is required');
    //     }

    //     // Retrieve postal code data
    //     $postalCode = str_replace(' ', '', $request->input('postal_code'));
    //     $postalCodeData = PostalCode::where('post_code', $postalCode)->first();

    //     if (!$postalCodeData) {
    //         return $this->jsonResponse(false, 'Invalid Postal Code', '1');
    //     }

    //     // Validate if house_no is provided
    //     if (!$request->filled('house_no')) {
    //         return $this->jsonResponse(false, 'House Number is required', '2');
    //     }

    //     // Retrieve and validate house number
    //     $house_number = str_replace(' ', '', $request->input('house_no'));
    //     $houseData = HouseNumber::where('pc_id', $postalCodeData->id)
    //         ->get();

    //     if (!$houseData) {
    //         return $this->jsonResponse(false, 'House Number Data Not Found', 2);
    //     }

    //     $arrData = [];
    //     foreach ($houseData as $houseNumber) {
    //         $arrData[] = json_decode($houseNumber->house_number, true);
    //     }


    //     $decodedData = [];
    //     $add = '';
    //     foreach ($arrData as $houseNumber) {
    //         foreach ($houseNumber as $key => $value) {
    //             $decodedData[$key] = $value;
    //             if ($key == $house_number) {
    //                 $add = $value;
    //             }
    //         }
    //     }

    //     $hData['postal_code'] = $postalCode;
    //     $hData['house_numbers'] = $house_number;
    //     $hData['address'] = $add;

    //     // Return success response with house data
    //     return $this->jsonResponse(true, 'Address found in Postal Code and House No', $hData);
    // }


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
}
