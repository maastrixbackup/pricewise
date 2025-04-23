<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CapacityTransportGasSlab;
use App\Models\CapacityTransportTariff;
use App\Models\EnergyConnectionRate;
use App\Models\EnergyElectricConnectionSlab;
use App\Models\EnergyGasConnectionSlab;
use App\Models\EnergyGridOperater;
use App\Models\GasMeasurementTariff;
use App\Models\PostalCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GridOperaterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grids = EnergyGridOperater::latest()->get();
        $cSlab = EnergyElectricConnectionSlab::orderBy('id')->get();
        $gSlab = EnergyGasConnectionSlab::orderBy('id')->get();
        $rates = EnergyConnectionRate::latest()->get();
        // dd($gSlab);
        return view('admin.grid_operater.list', compact('grids', 'cSlab', 'gSlab'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $postalCodes = PostalCode::latest()->get();
        return view('admin.grid_operater.add', compact('postalCodes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // dd($req->all());
        $req->validate([
            'op_name' => 'required|unique:energy_grid_operaters,operater_name',
            'current_transport_fee' => 'required',
            'gas_transport_fee' => 'required',
            'postal_code' => 'required|array',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'op_name.required' => 'The operator name is required.',
            'op_name.unique' => 'The operator name must be unique.',
            'current_transport_fee.required' => 'The current transport fee is required.',
            'gas_transport_fee.required' => 'The gas transport fee is required.',
            'postal_code.required' => 'The postal code is required.',
            'postal_code.array' => 'The postal code must be an array.',
            'image.required' => 'An image is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: png, jpg, jpeg.',
            'image.max' => 'The image must not be greater than 2MB.',
        ]);
        $postCodes = json_encode($req->postal_code, true);
        // dd($postCodes);

        try {
            $gridOperater = new EnergyGridOperater();
            $gridOperater->operater_name = $req->op_name;
            $gridOperater->current_transport_fee = $req->current_transport_fee;
            $gridOperater->gas_transport_fee = $req->gas_transport_fee;
            $gridOperater->measurment_tariff = $req->measurment_tariff;
            $gridOperater->postal_code = $postCodes;


            if ($req->hasFile('image')) {
                $filename = 'grid_oprater_' . time() . '.' . $req->image->getClientOriginalExtension();
                $req->image->move(public_path('storage/images/operaters/'), $filename);
                $gridOperater->image = $filename;
            }

            if ($gridOperater->save()) {
                $this->sendToastResponse('success', 'Operater Added!');
                return redirect()->route('admin.grid-operater.index');
            }
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $postalCodes = PostalCode::latest()->get();
        $gridOperater = EnergyGridOperater::find($id);
        return view('admin.grid_operater.edit', compact('gridOperater', 'postalCodes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $req->validate([
            'op_name' => 'required|unique:energy_grid_operaters,operater_name,' . $id, // Excluding current record
            'current_transport_fee' => 'required',
            'gas_transport_fee' => 'required',
            'postal_code' => 'required|array',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // Image is optional
        ], [
            'op_name.required' => 'The operator name is required.',
            'op_name.unique' => 'The operator name must be unique.',
            'current_transport_fee.required' => 'The current transport fee is required.',
            'gas_transport_fee.required' => 'The gas transport fee is required.',
            'postal_code.required' => 'The postal code is required.',
            'postal_code.array' => 'The postal code must be an array.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: png, jpg, jpeg.',
            'image.max' => 'The image must not be greater than 2MB.',
        ]);

        $postCodes = json_encode($req->postal_code, true);
        // dd($postCodes);

        DB::beginTransaction();
        try {
            $gridOperater =  EnergyGridOperater::find($id);
            $gridOperater->operater_name = $req->op_name;
            $gridOperater->current_transport_fee = $req->current_transport_fee;
            $gridOperater->gas_transport_fee = $req->gas_transport_fee;
            $gridOperater->measurment_tariff = $req->measurment_tariff;
            $gridOperater->postal_code = $postCodes;


            if ($req->hasFile('image')) {
                $filename = 'grid_oprater_' . time() . '.' . $req->image->getClientOriginalExtension();
                $req->image->move(public_path('storage/images/operaters/'), $filename);

                // Check if the provider has an existing image
                if (!empty($gridOperater->image)) {
                    $existingFilePath = public_path('storage/images/operaters/') . $gridOperater->image;
                    if (file_exists($existingFilePath)) {
                        // Delete the file if it exists
                        unlink($existingFilePath);
                    }
                }
                // Save the new filename in the database
                $gridOperater->image = $filename;
            } else {
                // If no new image is uploaded, retain the existing image
                $filename = $gridOperater->image;
            }
            $gridOperater->save();
            DB::commit();
            $this->sendToastResponse('success', 'Operater Added!');
            return redirect()->route('admin.grid-operater.index');
        } catch (\Exception $e) {
            DB::rollBack();
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
        //
    }

    public function manageAllCosts($id)
    {
        $gridOperater = EnergyGridOperater::find($id);
        $anlCRates = EnergyConnectionRate::where([
            'op_id' => $id,
            'source' => 'current'
        ])->with('getCurrentSlabData')
            ->get()
            ->map(function ($acr) {
                return [
                    'id' => $acr->id,
                    'op_id' => $acr->op_id,
                    'meter_type' => $acr->getCurrentSlabData->meter_type_from . ' t/m ' . $acr->getCurrentSlabData->meter_type_to ?? '',
                    'range' => $acr->getCurrentSlabData->usage_from . ' kWh' . ' - ' . $acr->getCurrentSlabData->usage_to . ' kWh' ?? '',
                    'rate' => $acr->rate,
                    'source' => $acr->source,
                ];
            });
        $anlGRates = EnergyConnectionRate::where([
            'op_id' => $id,
            'source' => 'gas'
        ])->with('getGasSlabData')
            ->get()
            ->map(function ($agr) {
                return [
                    'id' => $agr->id,
                    'op_id' => $agr->op_id,
                    'meter_type' => $agr->getGasSlabData->meter_type ?? '',
                    'range' => $agr->getGasSlabData->usage_from . 'm<sup>3</sup>' . ' - ' . $agr->getGasSlabData->usage_to . 'm<sup>3</sup>' ?? '',
                    'rate' => $agr->rate,
                    'source' => $agr->source,
                ];
            });

        $anlCttRates = CapacityTransportTariff::where([
            'op_id' => $id,
            'source' => 'current'
        ])->with('getCurrentSlabData')
            ->get()
            ->map(function ($actt) {
                return [
                    'id' => $actt->id,
                    'op_id' => $actt->op_id,
                    'meter_type' => $actt->getCurrentSlabData->meter_type_from . ' t/m ' . $actt->getCurrentSlabData->meter_type_to ?? '',
                    'range' => $actt->getCurrentSlabData->usage_from . ' kWh' . ' - ' . $actt->getCurrentSlabData->usage_to . ' kWh' ?? '',
                    'rate' => $actt->tariff_rate,
                    'source' => $actt->source,
                ];
            });
        $anlGttRates = CapacityTransportTariff::where([
            'op_id' => $id,
            'source' => 'gas'
        ])->with('getGasSlabData')
            ->get()
            ->map(function ($agtt) {
                return [
                    'id' => $agtt->id,
                    'op_id' => $agtt->op_id,
                    'meter_type' => $agtt->getGasSlabData->meter_type ?? '',
                    'range' => $agtt->getGasSlabData->usage_from . 'm<sup>3</sup>' . ' - ' . $agtt->getGasSlabData->usage_to . 'm<sup>3</sup>' ?? '',
                    'rate' => $agtt->tariff_rate,
                    'source' => $agtt->source,
                ];
            });

        $gmtRates = GasMeasurementTariff::where(['op_id' => $id,])->with('getGasSlabData')->get()->map(function ($gmt) {
            return [
                'id' => $gmt->id,
                'op_id' => $gmt->op_id,
                'meter_type' => $gmt->getGasSlabData->meter_type ?? '',
                'range' => $gmt->getGasSlabData->usage_from . 'm<sup>3</sup>' . ' - ' . $gmt->getGasSlabData->usage_to . 'm<sup>3</sup>' ?? '',
                'rate' => $gmt->tariff_rate,
            ];
        });
        // dd($anlCRates, $anlGRates);
        return view('admin.grid_operater.manage_costs', compact('id', 'gridOperater', 'anlCRates', 'anlGRates', 'gmtRates', 'anlCttRates', 'anlGttRates'));
    }

    public function usageRateEdit(Request $req)
    {
        $gridOperaterId = $req->op_id;
        $sources = $req->source;
        $htmlData = '';

        if ($sources == 'current') {
            $usage = 'NA'; // Default value
            $meterType = 'NA'; // Default value
            $cSlab = EnergyElectricConnectionSlab::orderBy('id')->get();

            if (!$cSlab->isEmpty()) {
                $htmlData .= '<input type="hidden" name="op_id" id="currOpId" value="' . $gridOperaterId . '">
                        <input type="hidden" name="source" id="currSources" value="' . $sources . '">';

                foreach ($cSlab as $k => $val) {
                    $sRates = EnergyConnectionRate::where([
                        'slab_id' => $val->id,
                        'op_id' => $gridOperaterId,
                        'source' => $sources,
                    ])->first();

                    $meterType = $val->meter_type_from . '  - ' . $val->meter_type_to . ' ';
                    $usage = $val->usage_from . ' kWh - ' . $val->usage_to . ' kWh';

                    $htmlData .=
                        '<tr>
                        <td>
                            <input type="hidden" name="id[]" value="' . ($sRates->id ?? '') . '">
                            <input type="hidden" name="slab_id[]" value="' . $val->id . '">
                            ' . $meterType . '
                        </td>
                        <td>' . $usage . '</td>
                        <td>
                            <input type="number" class="form-control" name="usage_rates[]"
                                step="0.000001" placeholder="NA" value="' . ($sRates->rate ?? '') . '">
                        </td>
                    </tr>';
                }
            } else {
                $htmlData .= '<tr><td colspan="3" class="text-center">You Have to Add Slab Data First.</td></tr>';
            }
        } else {
            $gSlab = EnergyGasConnectionSlab::orderBy('id')->get();
            if (!$gSlab->isEmpty()) {
                $usage = 'NA'; // Default value
                $htmlData .= '<input type="hidden" name="op_id" id="opId" value="' . $gridOperaterId . '">
                        <input type="hidden" name="source" id="sources" value="' . $sources . '">';

                foreach ($gSlab as $k => $v) {
                    $sRates = EnergyConnectionRate::where([
                        'slab_id' => $v->id,
                        'op_id' => $gridOperaterId,
                        'source' => $sources,
                    ])->first();

                    $usage = $v->usage_from . 'm<sup>3</sup> - ' . $v->usage_to . 'm<sup>3</sup>';

                    $htmlData .= '<tr>
                                    <td>
                                        <input type="hidden" name="id[]" value="' . ($sRates->id ?? '') . '">
                                        <input type="hidden" name="slab_id[]" value="' . $v->id . '">
                                        ' . ($v->meter_type ?? 'NA') . '
                                    </td>
                                    <td>' . $usage . '</td>
                                    <td>
                                        <input type="number" class="form-control" name="usage_rates[]"
                                            step="0.000001" placeholder="NA"
                                            value="' . ($sRates->rate ?? '') . '">
                                    </td>
                                </tr>';
                }
            } else {
                $htmlData .= '<tr><td colspan="3" class="text-center">You Have to Add Slab Data First.</td></tr>';
            }
        }

        return response()->json([
            'status' => !empty($htmlData),
            'message' => !empty($htmlData) ? 'Data Retrieved.' : 'No Data To Retrieve.',
            'htmlData' => $htmlData
        ]);
    }

    public function usageRateStore(Request $req)
    {
        // Check if all usage rates are null
        if (collect($req->usage_rates)->filter()->isEmpty()) {
            $this->sendToastResponse('error', 'At least one usage rate must be provided.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            foreach ($req->slab_id as $k => $v) {
                // Skip entries with null usage rates
                if ($req->usage_rates[$k] === null) {
                    continue;
                }

                // Find existing record or create a new one if ID is null
                $sbData = !empty($req->id[$k]) ? EnergyConnectionRate::find($req->id[$k]) : new EnergyConnectionRate();

                $sbData->slab_id = $v;
                $sbData->op_id = $req->op_id;
                $sbData->source = $req->source;
                $sbData->rate = $req->usage_rates[$k];
                $sbData->updated_at = now();

                // Only set created_at for new records
                if (!$sbData->exists) {
                    $sbData->created_at = now();
                }

                $sbData->save();
            }

            DB::commit();
            $this->sendToastResponse('success', 'Slab Data Added');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->sendToastResponse('error', $e->getMessage());
        }

        return redirect()->back();
    }

    public function measurementCostEdit(Request $req)
    {
        $gridOperaterId = $req->op_id;
        $htmlData = '';
        $gSlab = EnergyGasConnectionSlab::orderBy('id')->get();
        if (!$gSlab->isEmpty()) {
            $usage = 'NA'; // Default value
            $htmlData .= '<input type="hidden" name="op_id" id="opId" value="' . $gridOperaterId . '">';
            foreach ($gSlab as $k => $v) {
                $sRates = GasMeasurementTariff::where([
                    'slab_id' => $v->id,
                    'op_id' => $gridOperaterId,
                ])->first();

                $usage = $v->usage_from . 'm<sup>3</sup> - ' . $v->usage_to . 'm<sup>3</sup>';

                $htmlData .= '<tr>
                                    <td>
                                        <input type="hidden" name="id[]" value="' . ($sRates->id ?? '') . '">
                                        <input type="hidden" name="slab_id[]" value="' . $v->id . '">
                                        ' . ($v->meter_type ?? 'NA') . '
                                    </td>
                                    <td>' . $usage . '</td>
                                    <td>
                                        <input type="number" class="form-control" name="gas_tariff[]"
                                            step="0.000001" placeholder="NA"
                                            value="' . ($sRates->tariff_rate ?? '') . '">
                                    </td>
                                </tr>';
            }
        } else {
            $htmlData .= '<tr><td colspan="3" class="text-center">No Record Found.</td></tr>';
        }

        return response()->json([
            'status' => !empty($htmlData),
            'message' => !empty($htmlData) ? 'Data Retrieved.' : 'No Data To Retrieve.',
            'htmlData' => $htmlData
        ]);
    }

    public function measurementCostStore(Request $req)
    {
        // Check if all usage rates are null
        if (collect($req->gas_tariff)->filter()->isEmpty()) {
            $this->sendToastResponse('error', 'At least one tariff cost must be provided.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            foreach ($req->slab_id as $k => $v) {
                // Skip entries with null usage rates
                if ($req->gas_tariff[$k] === null) {
                    continue;
                }

                // Find existing record or create a new one if ID is null
                $sbData = !empty($req->id[$k]) ? GasMeasurementTariff::find($req->id[$k]) : new GasMeasurementTariff();

                $sbData->slab_id = $v;
                $sbData->op_id = $req->op_id;
                $sbData->tariff_rate = $req->gas_tariff[$k];
                $sbData->updated_at = now();

                // Only set created_at for new records
                if (!$sbData->exists) {
                    $sbData->created_at = now();
                }

                $sbData->save();
            }

            DB::commit();
            $this->sendToastResponse('success', 'Slab Data Added');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->sendToastResponse('error', $e->getMessage());
        }
        return redirect()->back();
    }


    public function transportTariffEdit(Request $req)
    {
        $gridOperaterId = $req->op_id;
        $sources = $req->source;
        $htmlData = '';

        if ($sources == 'current') {
            $usage = 'NA'; // Default value
            $meterType = 'NA'; // Default value
            $cSlab = EnergyElectricConnectionSlab::orderBy('id')->get();

            if (!$cSlab->isEmpty()) {
                $htmlData .= '<input type="hidden" name="op_id" id="currOpId" value="' . $gridOperaterId . '">
                        <input type="hidden" name="source" id="currSources" value="' . $sources . '">';

                foreach ($cSlab as $k => $val) {
                    $sRates = CapacityTransportTariff::where([
                        'slab_id' => $val->id,
                        'op_id' => $gridOperaterId,
                        'source' => $sources,
                    ])->first();

                    $meterType = $val->meter_type_from . '  - ' . $val->meter_type_to . ' ';
                    $usage = $val->usage_from . ' kWh - ' . $val->usage_to . ' kWh';

                    $htmlData .=
                        '<tr>
                        <td>
                            <input type="hidden" name="id[]" value="' . ($sRates->id ?? '') . '">
                            <input type="hidden" name="slab_id[]" value="' . $val->id . '">
                            ' . $meterType . '
                        </td>
                        <td>' . $usage . '</td>
                        <td>
                            <input type="number" class="form-control" name="tariff_rate[]"
                                step="0.000001" placeholder="NA" value="' . ($sRates->rate ?? '') . '">
                        </td>
                    </tr>';
                }
            } else {
                $htmlData .= '<tr><td colspan="3" class="text-center">You Have to Add Slab Data First.</td></tr>';
            }
        } else {
            $gSlab = CapacityTransportGasSlab::orderBy('id')->get();
            if (!$gSlab->isEmpty()) {
                $usage = 'NA'; // Default value
                $htmlData .= '<input type="hidden" name="op_id" id="opId" value="' . $gridOperaterId . '">
                        <input type="hidden" name="source" id="sources" value="' . $sources . '">';

                foreach ($gSlab as $k => $v) {
                    $sRates = CapacityTransportTariff::where([
                        'slab_id' => $v->id,
                        'op_id' => $gridOperaterId,
                        'source' => $sources,
                    ])->first();

                    $usage = $v->usage_from . 'm<sup>3</sup> - ' . $v->usage_to . 'm<sup>3</sup>';

                    $htmlData .= '<tr>
                                    <td>
                                        <input type="hidden" name="id[]" value="' . ($sRates->id ?? '') . '">
                                        <input type="hidden" name="slab_id[]" value="' . $v->id . '">
                                        ' . ($v->meter_type ?? 'NA') . '
                                    </td>
                                    <td>' . $usage . '</td>
                                    <td>
                                        <input type="number" class="form-control" name="tariff_rate[]"
                                            step="0.000001" placeholder="NA"
                                            value="' . ($sRates->rate ?? '') . '">
                                    </td>
                                </tr>';
                }
            } else {
                $htmlData .= '<tr><td colspan="3" class="text-center">You Have to Add Slab Data First.</td></tr>';
            }
        }

        return response()->json([
            'status' => !empty($htmlData),
            'message' => !empty($htmlData) ? 'Data Retrieved.' : 'No Data To Retrieve.',
            'htmlData' => $htmlData
        ]);
    }

    public function transportTariffStore(Request $req)
    {
        // Check if all usage rates are null
        if (collect($req->tariff_rate)->filter()->isEmpty()) {
            $this->sendToastResponse('error', 'At least one tariff rate must be provided.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            foreach ($req->slab_id as $k => $v) {
                // Skip entries with null usage rates
                if ($req->tariff_rate[$k] === null) {
                    continue;
                }

                // Find existing record or create a new one if ID is null
                $cttData = !empty($req->id[$k]) ? CapacityTransportTariff::find($req->id[$k]) : new CapacityTransportTariff();

                $cttData->slab_id = $v;
                $cttData->op_id = $req->op_id;
                $cttData->source = $req->source;
                $cttData->tariff_rate = $req->tariff_rate[$k];
                $cttData->updated_at = now();

                // Only set created_at for new records
                if (!$cttData->exists) {
                    $cttData->created_at = now();
                }

                $cttData->save();
            }

            DB::commit();
            $this->sendToastResponse('success', 'Slab Data Added');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->sendToastResponse('error', $e->getMessage());
        }

        return redirect()->back();
    }


    public function currentSlabIndex()
    {
        $currentSlabs = EnergyElectricConnectionSlab::orderBy('id')->get();
        return view('admin.grid_operater.current_slab', compact('currentSlabs'));
    }

    public function currentSlabStore(Request $req)
    {
        // dd($req->all());
        DB::beginTransaction();
        try {
            $a = [];
            $missingData = false;
            foreach ($req->meter_type_from as $k => $v) {
                // Check if all required data for this contract year exists
                if (
                    isset($req->meter_type_to[$k]) &&
                    isset($req->usage_from[$k]) &&
                    isset($req->usage_to[$k])
                ) {
                    // Data is present, so add it to the database
                    $sbData = EnergyElectricConnectionSlab::where([
                        'meter_type_from' => $v,
                        'meter_type_to' => $req->meter_type_to[$k]
                    ])->first() ?? new EnergyElectricConnectionSlab();
                    $sbData->cat = config('constant.category.energy');
                    $sbData->meter_type_from = $v;
                    $sbData->meter_type_to = $req->meter_type_to[$k];
                    $sbData->usage_from = $req->usage_from[$k];
                    $sbData->usage_to = $req->usage_to[$k];
                    if (!$sbData->exists) {
                        $sbData->created_at = now();
                    }
                    $sbData->updated_at = now();
                    $sbData->save();

                    // Optionally, store the values in array $a for debugging or other purposes
                    $a[$k] = [
                        'meter_type_to' => $req->meter_type_to[$k],
                        'usage_from' => $req->usage_from[$k],
                        'usage_to' => $req->usage_to[$k],
                    ];
                } else {
                    // Mark that some data is missing
                    $missingData = true;
                    $this->sendToastResponse('error', "Missing data for: {$v}");
                }
            }
            // Check if there was missing data and redirect accordingly
            if ($missingData) {
                return redirect()->back();  // Redirect back if there was any missing data
            }
            DB::commit();
            $this->sendToastResponse('success', 'Slab Data Added');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function currentSlabEdit($id)
    {
        try {
            $slab = EnergyElectricConnectionSlab::find($id);
            $sbData = $slab ? [
                'id' => $slab->id,
                'meter_type_from' => $slab->meter_type_from,
                'meter_type_to' => $slab->meter_type_to,
                'usage_from' => $slab->usage_from,
                'usage_to' => $slab->usage_to,
            ] : null;
            return response()->json([
                'status' => true,
                'message' => 'Data Retrieved Successfully.',
                'data' => $sbData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function currentSlabUpdate(Request $req)
    {
        try {
            $slab = EnergyElectricConnectionSlab::find($req->id);
            $slab->meter_type_from = $req->meter_type_from;
            $slab->meter_type_to = $req->meter_type_to;
            $slab->usage_from = $req->usage_from;
            $slab->usage_to = $req->usage_to;
            $slab->save();
            $this->sendToastResponse('success', 'Data Updated Successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function currentSlabDelete($id)
    {
        //
    }

    public function gasSlabIndex()
    {
        $gasSlabs = EnergyGasConnectionSlab::orderBy('id')->get();
        $tGasSlabs = CapacityTransportGasSlab::orderBy('id')->get();
        return view('admin.grid_operater.gas_slab', compact('gasSlabs', 'tGasSlabs'));
    }

    public function gasSlabStore(Request $req)
    {

        DB::beginTransaction();
        try {
            $missingData = false;
            foreach ($req->meter_type as $k => $v) {
                // Check if all required data for this contract year exists
                if (
                    isset($req->usage_from[$k]) &&
                    isset($req->usage_to[$k])
                ) {
                    if ($req->type === 'normal') {
                        $sbData = EnergyGasConnectionSlab::where(['meter_type' => $v])->first() ?? new EnergyGasConnectionSlab();
                    } else {
                        $sbData =  new CapacityTransportGasSlab();
                    }
                    $sbData->cat = config('constant.category.energy');
                    $sbData->meter_type = $v;
                    $sbData->usage_from = $req->usage_from[$k];
                    $sbData->usage_to = $req->usage_to[$k];
                    if (!$sbData->exists) {
                        $sbData->created_at = now();
                    }
                    $sbData->updated_at = now();
                    $sbData->save();
                } else {
                    // Mark that some data is missing
                    $missingData = true;
                    $this->sendToastResponse('error', "Missing data for: {$v}");
                }
            }
            // Check if there was missing data and redirect accordingly
            if ($missingData) {
                return redirect()->back();  // Redirect back if there was any missing data
            }
            DB::commit();
            $this->sendToastResponse('success', 'Slab Data Added');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function gasSlabEdit(Request $req, $id)
    {
        try {
            if ($req->type === 'normal') {
                $slab = EnergyGasConnectionSlab::find($id);
            } else {
                $slab = CapacityTransportGasSlab::find($id);
            }
            $sbData = $slab ? [
                'id' => $slab->id,
                'meter_type' => $slab->meter_type,
                'usage_from' => $slab->usage_from,
                'usage_to' => $slab->usage_to,
            ] : null;
            return response()->json([
                'status' => true,
                'message' => 'Data Retrieved Successfully.',
                'data' => $sbData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function gasSlabUpdate(Request $req)
    {
        try {
            if ($req->type === 'normal') {
                $slab = EnergyGasConnectionSlab::find($req->id);
            } else {
                $slab = CapacityTransportGasSlab::find($req->id);
            }
            $slab->meter_type = $req->meter_type;
            $slab->usage_from = $req->usage_from;
            $slab->usage_to = $req->usage_to;
            $slab->save();
            $this->sendToastResponse('success', 'Data Updated Successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            $this->sendToastResponse('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function gasSlabDelete(Request $req)
    {
        //
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
}
