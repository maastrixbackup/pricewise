<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnergyProduct;
use App\Models\Provider;
use App\Models\DefaultProduct;
use App\Models\AdditionalInfo;
use App\Models\ShopProduct;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Document;
use App\Models\PostFeature;
use App\Models\Affiliate;
use App\Models\EnergyConsumption;
use App\Models\Feature;
use App\Models\EnergyRateChat;
use App\Models\FeedInCost;
use App\Models\GlobalEnergySetting;
use App\Models\HouseType;
use App\Models\PostalCode;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnergyController extends Controller
{
    protected string $guard = 'admin';
    public function guard()
    {
        return Auth::guard($this->guard);
    }
    function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:energy-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:energy-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:energy-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:energy-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $objEnergy = EnergyProduct::with('providerDetails')
            ->orderBy('provider_id', 'asc')
            ->orderBy('contract_length', 'asc')
            ->get();
        $objEnergyFeatures = Feature::select('id', 'features', 'input_type')->where('category', 16)->get();

        return view('admin.energy.index', compact('objEnergy', 'objEnergyFeatures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function create()
    {
        // $objContract = TvContractLength::latest()->get();
        // $objCommission = CommissionType::latest()->get();
        $globalEnergy = GlobalEnergySetting::find(1);
        $postalCodes = PostalCode::latest()->get();
        $combos = Combo::where('category', 16)->latest()->get();
        $objRelatedProducts = EnergyProduct::orderBy('id', 'asc')->get();
        $objCategory = Category::find(config('constant.category.energy'));
        $providers = Provider::where('category', config('constant.category.energy'))->get();
        //$objFeature = TvFeature::latest()->get();
        return view('admin.energy.add', compact('globalEnergy', 'objCategory', 'objRelatedProducts', 'providers', 'combos', 'postalCodes'));
        //, compact('objContract', 'objCommission', 'objAdditionalCategories', 'objRelatedProducts', 'objCategory', 'objAffiliates', 'objFeature')
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $a = [];
            $missingData = false;

            foreach ($request->contract_year as $k => $v) {
                // Check if all required data for this contract year exists
                if (
                    isset($request->year_power[$v]) &&
                    isset($request->year_gas[$v]) &&
                    isset($request->discount[$v]) &&
                    isset($request->boiler[$v]) &&
                    isset($request->valid_till[$v])
                ) {
                    $newP = new EnergyProduct();
                    $newP->contract_length = $v;
                    $newP->power_cost_per_unit = $request->year_power[$v];
                    $newP->gas_cost_per_unit = $request->year_gas[$v];
                    $newP->discount = $request->discount[$v];
                    $newP->boiler = $request->boiler[$v];
                    $newP->valid_till = $request->valid_till[$v];
                    $newP->provider_id = $request->provider;
                    $newP->tax_on_electric = $request->tax_on_electric;
                    $newP->tax_on_gas = $request->tax_on_gas;
                    $newP->ode_on_electric = $request->ode_on_electric;
                    $newP->ode_on_gas = $request->ode_on_gas;
                    $newP->fixed_delivery = $request->fixed_delivery;
                    $newP->grid_management = $request->grid_management;
                    $newP->feed_in_tariff = $request->feed_in_tariff;
                    $newP->energy_tax_reduction = $request->energy_tax_reduction;
                    $newP->target_group = $request->target_group;
                    $newP->vat = $request->vat;
                    $newP->power_origin = json_encode($request->power_origin, true);
                    $newP->type_of_gas = json_encode($request->gas_type, true);
                    $newP->created_at = now();
                    $newP->updated_at = now();
                    $newP->save();

                    // Optionally, store the values in array $a for debugging or other purposes
                    $a[$v] = [
                        'year_power' => $request->year_power[$v],
                        'year_gas' => $request->year_gas[$v],
                        'discount' => $request->discount[$v],
                        'boiler' => $request->boiler[$v],
                        'valid_till' => $request->valid_till[$v],
                    ];
                } else {
                    // Mark that some data is missing
                    $missingData = true;
                    $this->sendToastResponse('error', "Missing data for contract year: {$v}");
                }
            }

            // Check if there was missing data and redirect accordingly
            if ($missingData) {
                return redirect()->back();  // Redirect back if there was any missing data
            }
            $this->sendToastResponse('success', 'Energy Product Added successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}


    public function edit($id)
    {
        $postalCodes = PostalCode::latest()->get();
        $combos = Combo::where('category', 16)->latest()->get();

        $globalEnergy = GlobalEnergySetting::find(1);
        $objEnergy = EnergyProduct::with('providerDetails')->find($id);
        $objC = EnergyProduct::where('provider_id', $objEnergy->provider_id)
            ->orderBy('contract_length', 'asc')
            ->get();
        $bArr = [];
        foreach ($objC as $k => $v) {
            $bArr['id'][$v->contract_length] = $v->id ?? '';
            $bArr['contract_length'][$v->contract_length] = $v->contract_length;
            $bArr['power_cost_per_unit'][$v->contract_length] = $v->power_cost_per_unit;
            $bArr['gas_cost_per_unit'][$v->contract_length] = $v->gas_cost_per_unit;
            $bArr['discount'][$v->contract_length] = $v->discount;
            $bArr['boiler'][$v->contract_length] = $v->boiler;
            $bArr['valid_till'][$v->contract_length] = $v->valid_till;
        }
        // dd($bArr);
        $providers = Provider::where('category', config('constant.category.energy'))->get();
        $documents = Document::where('post_id', $id)->where('category', $objEnergy->category)->get();
        $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No Parent") as parent'))
            ->from('features as f1')
            ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
            ->where('f1.category', $objEnergy->category)
            ->get()
            ->groupBy('parent');


        $postEnergyFeatures = PostFeature::where('post_id', $id)
            ->where('category_id', $objEnergy->category)
            ->select('feature_id', 'feature_value', 'details')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item['feature_id'] => ['feature_value' => $item['feature_value'], 'details' => $item['details']]];
            })->toArray();
        //dd($postEnergyFeatures);
        $serviceInfo = PostFeature::where('post_id', $id)->where('category_id', $objEnergy->category)->where('type', 'info')->get();
        $objRelatedProducts = EnergyProduct::orderBy('id', 'asc')->get();
        $objCategory = Category::whereNull('parent')->latest()->get();
        return view('admin.energy.edit', compact('bArr', 'globalEnergy', 'objEnergy', 'objRelatedProducts', 'objCategory', 'providers', 'objEnergyFeatures', 'postEnergyFeatures',  'serviceInfo', 'documents', 'combos', 'postalCodes'));
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
        // dd($request->all());
        try {
            $missingData = false;

            foreach ($request->contract_year as $k => $v) {
                // Ensure that the keys exist in the request arrays
                $yearPower = $request->year_power[$v] ?? null;
                $yearGas = $request->year_gas[$v] ?? null;
                $discount = $request->discount[$v] ?? null;
                $boiler = $request->boiler[$v] ?? null;
                $validTill = $request->valid_till[$v] ?? null;

                // Check if all required data for this contract year exists
                if ($yearPower && $yearGas && $discount && $validTill) {
                    // Find existing record based on `contract_length`
                    $existingData = EnergyProduct::where([
                        'contract_length' => $v,
                        'id' => $request->ids[$v] ?? null,
                    ])->first();

                    // Update or create the consumption record
                    if ($existingData) {
                        $existingData->update([
                            'contract_length' => $v,
                            'power_cost_per_unit' => $yearPower,
                            'gas_cost_per_unit' => $yearGas,
                            'discount' => $discount,
                            'boiler' => $boiler,
                            'valid_till' => $validTill,
                            'power_origin' => json_encode($request->power_origin, true),
                            'type_of_gas' => json_encode($request->gas_type, true),
                            'status' => $request->status,
                            'updated_at' => now(),
                        ]);
                    } else {
                        // Create new consumption entry if it does not exist
                        EnergyProduct::create([
                            'provider_id' => $request->provider,
                            'contract_length' => $v,
                            'power_cost_per_unit' => $yearPower,
                            'gas_cost_per_unit' => $yearGas,
                            'tax_on_electric' => $request->tax_on_electric,
                            'tax_on_gas' => $request->tax_on_gas,
                            'ode_on_electric' => $request->ode_on_electric,
                            'ode_on_gas' => $request->ode_on_gas,
                            'fixed_delivery' => $request->fixed_delivery,
                            'grid_management' => $request->grid_management,
                            'feed_in_tariff' => $request->feed_in_tariff,
                            'energy_tax_reduction' => $request->energy_tax_reduction,
                            'target_group' => $request->target_group,
                            'vat' => $request->vat,
                            'power_origin' => json_encode($request->power_origin, true),
                            'type_of_gas' => json_encode($request->gas_type, true),
                            'discount' => $discount,
                            'boiler' => $boiler,
                            'valid_till' => $validTill,
                            'target_group' => $request->target_group,
                            'status' => $request->status,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    // Mark that some data is missing
                    $missingData = true;
                    $this->sendToastResponse('error', "Missing data for contract year: {$v}");
                }
            }

            // Check if there was missing data and redirect accordingly
            if ($missingData) {
                return redirect()->back();  // Redirect back if there was any missing data
            }

            $this->sendToastResponse('success', 'Energy Updated Successfully');
            return redirect()->route('admin.energy.index');
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
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
            EnergyProduct::find($id)->delete();
            $this->sendToastResponse('success', 'Energy Deleted Successfully');
            return redirect()->route('admin.energy.index');
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->route('admin.energy.index');
        }
    }

    public function energyStatusUpdate(Request $request, $s_id)
    {
        try {
            $energyProductUpdate = EnergyProduct::find($s_id);

            // Check if the energy product exists
            if (!$energyProductUpdate) {
                return response()->json(['status' => false, 'message' => 'Energy product not found']);
            }
            // Toggle the status using a ternary operator
            $newStatus = $energyProductUpdate->status == '1' ? '0' : '1';
            $energyProductUpdate->status = $newStatus;
            $energyProductUpdate->save();

            return response()->json(['status' => true, 'message' => 'Status Updated Successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }


    public function energy_doc_update(Request $request, $post_id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048', // Example validation rules
        ]);

        // Check if the file is uploaded and valid
        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Store the file in the 'public/documents' directory
            $filePath = $file->storeAs('public/documents', $fileName);

            // Check if the file was stored successfully
            if ($filePath) {
                // Insert file details into the database
                Document::create([
                    'filename' => $fileName,
                    'category' => $request->category_id,
                    'post_id' => $request->post_id,
                    'path' => $filePath // Store the file path in the database
                ]);

                // Return success response
                return response()->json(['success' => true, 'message' => 'File uploaded successfully']);
            } else {
                // Return error response if file storage failed
                return response()->json(['success' => false, 'message' => 'Failed to store the file'], 500);
            }
        } else {
            // Return error response if file upload failed
            return response()->json(['success' => false, 'message' => 'Invalid file'], 400);
        }
    }
    public function energy_doc_delete(Request $request, $post_id)
    {
        if ($request->file_id && $request->file_id != '') {
            // Retrieve the document from the database
            $document = Document::find($request->file_id);

            if ($document) {
                // Delete the file from the storage path
                Storage::delete($document->path . $document->filename);

                // Delete the document record from the database
                $document->delete();

                return response()->json(['success' => true, 'message' => 'File deleted successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'File not found'], 400);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid file ID'], 400);
        }
    }
    public function energy_price_update(Request $request, $id)
    {
        $post_category = $request->category_id;
        try {
            $objEnergy = EnergyProduct::where('id', $id)->first();
            $objEnergy->gas_price = $request->gas_price;
            $objEnergy->normal_electric_price = $request->normal_electric_price;
            $objEnergy->peak_electric_price = $request->peak_electric_price;
            $objEnergy->feed_in_normal = $request->normal_feed_in_cost;
            $objEnergy->feed_in_peak = $request->peak_feed_in_cost;
            $objEnergy->network_cost_gas = $request->network_cost_gas;
            $objEnergy->network_cost_electric = $request->network_cost_electric;
            $objEnergy->delivery_cost_electric = $request->delivery_cost_electric;
            $objEnergy->delivery_cost_gas = $request->delivery_cost_gas;
            $objEnergy->cashback = $request->cashback;
            $objEnergy->government_levies_gas = $request->government_levies_gas;
            $objEnergy->government_levies_electric = $request->government_levies_electric;
            $objEnergy->reduction_of_energy_tax = $request->reduction_of_energy_tax;
            $objEnergy->save();
        } catch (\Exception $e) {
            $errorMessage = 'Failed to Update Energy Price: ' . $e->getMessage();
            // Log the error for further investigation
            // \Log::error($errorMessage);
            $message = ['message' =>  $errorMessage, 'title' => 'Error'];
            return response()->json(['status' => false, 'message' => $message]);
        }
        $message = array('message' => 'Energy Prices Updated Successfully', 'title' => '');
        return response()->json(["status" => true, 'message' => $message]);
    }

    public function energy_feature_update(Request $request, $post_id)
    {
        $post_category = $request->category_id;
        $sub_category = $request->sub_category;
        //dd($post_category);
        try {
            $mainfeature = $request->input('features');
            if (is_array($mainfeature)) {
                foreach ($mainfeature as $feature_id => $value) {
                    if ($value != null && $post_category != null) {
                        PostFeature::updateOrCreate(['post_id' => $post_id, 'category_id' => $post_category, 'feature_id' => $feature_id], ['post_id' => $post_id, 'category_id' => $post_category, 'sub_category' => $sub_category, 'feature_id' => $feature_id, 'feature_value' => $value, 'details' => $request->details[$feature_id], 'post_category' => $post_category]);
                    }
                }
            } else {
                $mainfeature = []; // Default to an empty array
            }
        } catch (\Exception $e) {
            $errorMessage = 'Failed to update energy features: ' . $e->getMessage();
            // Log the error for further investigation
            // \Log::error($errorMessage);
            $message = ['message' =>  $errorMessage, 'title' => 'Error'];
            return response()->json(['status' => false, 'message' => $message]);
        }
        $message = array('message' => 'Energy Features Updated Successfully', 'title' => '');
        return response()->json(["status" => true, 'message' => $message]);
    }

    public function service_info_update(Request $request, $post_id)
    {
        $post_category = $request->category_id;
        try {
            foreach ($request->input('features') as $feature_id => $value) {
                if ($value != null && $post_category != null) {
                    PostFeature::updateOrCreate(['post_id' => $post_id, 'category_id' => 2, 'feature_id' => $feature_id, 'post_category' => $post_category], ['post_id' => $post_id, 'post_category' => $post_category, 'category_id' => 2, 'feature_id' => $feature_id, 'feature_value' => $value]);
                }
            }
        } catch (\Exception $e) {
            $errorMessage = 'Failed to update telephone features: ' . $e->getMessage();
            // Log the error for further investigation
            // \Log::error($errorMessage);
            $message = ['message' =>  $errorMessage, 'title' => 'Error'];
            return response()->json(['status' => false, 'message' => $message]);
        }
        $message = array('message' => 'Telephone Features Updated Successfully', 'title' => '');
        return response()->json(["status" => true, 'message' => $message]);
    }

    public function viewConsumptions($id)
    {
        $cData = EnergyConsumption::orderBy('house_type', 'asc')->get();
        return view('admin.energy.view_consumption', compact('cData'));
    }

    public function addConsumption(Request $request)
    {
        $bArr = [];
        $houseData = HouseType::orderBy('title', 'asc')->get();
        $cData = EnergyConsumption::select('house_type')->groupBy('house_type')->get();
        foreach ($cData as $k => $v) {
            $bArr[$k] = $v->house_type;
        }
        // dd($bArr);
        return view('admin.energy.add_consumption', compact('houseData', 'bArr'));
    }

    public function checkHouseType(Request $req)
    {
        $cData = EnergyConsumption::select('house_type')->groupBy('house_type')->get();
        foreach ($cData as $k => $v) {
            $bArr[$k] = $v->house_type;
        }

        if (in_array($req->id, $bArr)) {
            return $this->jsonResponse(false, 'House Data Already Exists!');
        }
        return $this->jsonResponse(true, 'Proceed Selection..');
    }

    public function storeConsumption(Request $req)
    {
        // dd($req->all());
        try {
            $a = [];
            $missingData = false;

            foreach ($req->no_of_person as $k => $v) {
                // Check if all required data for this contract year exists
                if (
                    isset($req->electric_supply[$v]) &&
                    isset($req->electric_supply[$v])
                ) {
                    $newC = new EnergyConsumption();
                    $newC->no_of_person = $v;
                    $newC->gas_supply = $req->gas_supply[$v];
                    $newC->electric_supply = $req->electric_supply[$v];
                    $newC->cat_id = $req->category;
                    $newC->house_type = $req->house_type;
                    $newC->created_at = now();
                    $newC->updated_at = now();
                    $newC->save();

                    // Optionally, store the values in array $a for debugging or other purposes
                    $a[$v] = [
                        'gas_supply' => $req->gas_supply[$v],
                        'electric_supply' => $req->electric_supply[$v],
                    ];
                } else {
                    // Mark that some data is missing
                    $missingData = true;
                    $this->sendToastResponse('error', "Missing data for contract year: {$v}");
                }
            }

            // Check if there was missing data and redirect accordingly
            if ($missingData) {
                return redirect()->back();  // Redirect back if there was any missing data
            }
            $this->sendToastResponse('success', 'Consumption Added successfully');
            return redirect()->route('admin.consumptions', config('constant.category.energy'));
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }
    public function editConsumption($id)
    {
        $cData = EnergyConsumption::find($id);
        $cFinalData = EnergyConsumption::where('house_type', $cData->house_type)
            ->orderBy('no_of_person', 'asc')
            ->get();
        $bArr = [];
        foreach ($cFinalData as $k => $v) {
            $bArr['id'][$v->no_of_person] = $v->id ?? '';
            $bArr['gas'][$v->no_of_person] = $v->gas_supply;
            $bArr['electric'][$v->no_of_person] = $v->electric_supply;
        }
        // dd($bArr);
        return view('admin.energy.edit_consumption', compact('cData', 'bArr'));
    }

    // public function updateConsumption(Request $req, $id)
    // {
    //     dd($req->all());
    //     try {
    //         $a = [];
    //         $missingData = false;

    //         foreach ($req->no_of_person as $k => $v) {
    //             // Check if all required data for this contract year exists
    //             if (
    //                 isset($req->electric_supply[$v]) &&
    //                 isset($req->electric_supply[$v])
    //             ) {
    //                 $newC = EnergyConsumption::where('no_of_person', $v)->first();
    //                 if ($newC) {
    //                     $newC->update([
    //                         'no_of_person' => $v,
    //                         'gas_supply' => $req->gas_supply[$v],
    //                         'electric' => $req->electric_supply[$v],
    //                         'house_type' => $req->house_type,
    //                         'cat_id' => $req->category,
    //                         'created_at' => now(),
    //                         'updated_at' => now()
    //                     ]);

    //                 }
    //                 // $newC = EnergyConsumption::updateOrCreate([
    //                 //     'no_of_person' => $v,
    //                 //     'gas_supply' => $req->gas_supply[$v],
    //                 //     'electric' => $req->electric_supply[$v],
    //                 //     'house_type' => $req->house_type,
    //                 //     'cat_id' => $req->category,
    //                 //     'created_at' => now(),
    //                 //     'updated_at' => now()
    //                 // ]);

    //                 $newCc = new EnergyConsumption();

    //                 $newCc->no_of_person = $v;
    //                 $newCc->gas_supply = $req->gas_supply[$v];
    //                 $newCc->electric_supply = $req->electric_supply[$v];
    //                 $newCc->cat_id = $req->category;
    //                 $newCc->house_type = $req->house_type;
    //                 $newCc->created_at = now();
    //                 $newCc->updated_at = now();
    //                 $newCc->save();

    //                 // Optionally, store the values in array $a for debugging or other purposes
    //                 $a[$v] = [
    //                     'gas_supply' => $req->gas_supply[$v],
    //                     'electric_supply' => $req->electric_supply[$v],
    //                 ];
    //             } else {
    //                 // Mark that some data is missing
    //                 $missingData = true;
    //                 $this->sendToastResponse('error', "Missing data for contract year: {$v}");
    //             }
    //         }

    //         // Check if there was missing data and redirect accordingly
    //         if ($missingData) {
    //             return redirect()->back();  // Redirect back if there was any missing data
    //         }
    //         $this->sendToastResponse('success', 'Counsumption Updated successfully');
    //         return redirect()->back();
    //     } catch (\Exception $e) {
    //         $this->sendToastResponse('error', $e->getMessage());
    //         return redirect()->back();
    //     }


    //     // try {
    //     //     $cData = EnergyConsumption::find($id);
    //     //     $cData->gas_supply = $req->gas_supply;
    //     //     $cData->electric_supply = $req->electric_supply;
    //     //     $cData->save();

    //     //     $this->sendToastResponse('success', 'Consumption Updated successfully');
    //     //     return redirect()->back();
    //     // } catch (\Throwable $th) {
    //     //     $this->sendToastResponse('error', $th->getMessage());
    //     //     return redirect()->back();
    //     // }
    // }

    public function updateConsumption(Request $req, $id)
    {
        // dd($req->all());
        try {
            $a = [];
            $missingData = false;

            foreach ($req->no_of_person as $k => $v) {
                // Check if all required data for this contract year exists
                if (
                    isset($req->gas_supply[$v]) &&
                    isset($req->ids[$v]) &&
                    isset($req->electric_supply[$v])
                ) {
                    // Find existing record based on `no_of_person`
                    $existingConsumption = EnergyConsumption::where(['no_of_person' => $v, 'id' => $req->ids[$v]])->first();

                    // Update or create the consumption record
                    if ($existingConsumption) {
                        $existingConsumption->update([
                            'no_of_person' => $v,
                            'gas_supply' => $req->gas_supply[$v],
                            'electric_supply' => $req->electric_supply[$v],
                            'house_type' => $req->house_type,
                            'cat_id' => $req->category,
                            'updated_at' => now()
                        ]);
                    } else {
                        // Create new consumption entry if it does not exist
                        EnergyConsumption::create([
                            'no_of_person' => $v,
                            'gas_supply' => $req->gas_supply[$v],
                            'electric_supply' => $req->electric_supply[$v],
                            'house_type' => $req->house_type,
                            'cat_id' => $req->category,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    // Optionally, store the values in array $a for debugging or other purposes
                    $a[$v] = [
                        'gas_supply' => $req->gas_supply[$v],
                        'electric_supply' => $req->electric_supply[$v],
                    ];
                } else {
                    // Mark that some data is missing
                    $missingData = true;
                    $this->sendToastResponse('error', "Missing data for no_of_person: {$v}");
                }
            }

            // Check if there was missing data and redirect accordingly
            if ($missingData) {
                return redirect()->back();  // Redirect back if there was any missing data
            }

            $this->sendToastResponse('success', 'Consumption updated successfully');
            return redirect()->route('admin.consumptions', config('constant.category.energy'));
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
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

    public function jsonResponse($status, $message, $data = null)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }
        return response()->json($response);
    }
}
