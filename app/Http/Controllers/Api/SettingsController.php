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
        // Validate if postal_code is provided
        if (!$request->filled('postal_code')) {
            return $this->jsonResponse(false, 'Postal Code is required');
        }

        // Retrieve postal code data
        $postalCode = str_replace(' ', '', $request->input('postal_code'));
        $postalCodeData = PostalCode::where('post_code', $postalCode)->first();

        if (!$postalCodeData) {
            return $this->jsonResponse(false, 'Invalid Postal Code', '1');
        }

        // Validate if house_no is provided
        if (!$request->filled('house_no')) {
            return $this->jsonResponse(false, 'House Number is required', '2');
        }

        // Retrieve and validate house number
        $houseNumber = str_replace(' ', '', $request->input('house_no'));
        $houseData = HouseNumber::where('pc_id', $postalCodeData->id)
            ->first();

        if (!$houseData) {
            return $this->jsonResponse(false, 'House Number Data Not Found', 2);
        }

        $add = '';
        $houseDt = json_decode($houseData->house_number, true);
        if (is_array($houseDt) && array_key_exists($houseNumber, $houseDt)) {
            $add = $houseDt[$houseNumber];
        } else {
            return $this->jsonResponse(false, 'House Number Not Found in this Combination', '2');
        }


        $hData['id'] = $houseData->id;
        $hData['pc_id'] = $houseData->pc_id;
        $hData['postal_code'] = $houseData->postal_codes;
        $hData['house_numbers'] = $houseNumber;
        $hData['address'] = $add;

        // Return success response with house data
        return $this->jsonResponse(true, 'House Number and Address found in Postal Code', $hData);
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
