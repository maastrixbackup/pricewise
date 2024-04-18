<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\TvInternetProduct;
use App\Models\EnergyProduct;
use App\Models\Provider;
use App\Models\Feature;
use Validator;
use App\Http\Resources\EnergyResource;
use DB;

class SettingsController extends BaseController
{
  


    public function getSupliers()
    {
        $providers = Provider::get();
    
        return $this->sendResponse($providers, 'Suppliers retrieved successfully.');
    }
    public function houseTypes()
    {
        $houses = config('master_data')['houses'];
        $housesArray = [];
        foreach ($houses as $house) {
            $housesArray[] = (object) $house;
        }
            return response()->json([
                'success' => true,
                'data' => $housesArray,
                'message' => 'House types retrieved successfully.'
            ]);
        }
    
}