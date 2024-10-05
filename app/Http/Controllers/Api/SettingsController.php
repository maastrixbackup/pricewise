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
        // return $housesTypeArray;
        // $houses =   [
        //     '1' => ['name' => 'appartment', 'image' => asset('storage/images/houses/appartment.png')],
        //     '2' => ['name' => 'corner_house', 'image' => asset('storage/images/houses/corner_house.png')],
        //     '3' => ['name' => 'mansion', 'image' => asset('storage/images/houses/mansion.png')],
        //     '4' => ['name' => 'studio', 'image' => asset('storage/images/houses/studio.png')],
        //     '5' => ['name' => 'terraced_house', 'image' => asset('storage/images/houses/terraced_house.png')],
        //     '6' => ['name' => 'under_hood', 'image' => asset('storage/images/houses/under_hood.png')],
        //     '7' => ['name' => 'villa', 'image' => asset('storage/images/houses/villa.png')],
        //     '8' => ['name' => 'woonboederii', 'image' => asset('storage/images/houses/woonboederii.png')],
        // ];

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
        // Validate if postal_code is provided
        if (!$request->filled('postal_code')) {
            return $this->jsonResponse(false, 'Postal Code is required');
        }

        // Retrieve postal code data
        $postalCode = $request->input('postal_code');
        $postalCodeData = PostalCode::where('post_code', $postalCode)->first();

        if (!$postalCodeData) {
            return $this->jsonResponse(false, 'Invalid Postal Code');
        }

        // Validate if house_no is provided
        if (!$request->filled('house_no')) {
            return $this->jsonResponse(false, 'House Number is required');
        }

        // Retrieve and validate house number
        $houseNumber = $request->input('house_no');
        $houseData = HouseNumber::where('pc_id', $postalCodeData->id)
            ->whereRaw('JSON_CONTAINS(house_number, ?)', [json_encode($houseNumber)])
            ->first();

        if (!$houseData) {
            return $this->jsonResponse(false, 'Invalid House Number for Postal Code');
        }

        $hData['id'] = $houseData->id;
        $hData['pc_id'] = $houseData->pc_id;
        $hData['postal_code'] = $houseData->postal_codes;
        $hData['house_numbers'] = json_decode($houseData->house_number, true);

        // Return success response with house data
        return $this->jsonResponse(true, 'House Number found in Postal Code', $hData);
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
}
