<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\TvInternetProduct;
use App\Models\EnergyProduct;
use App\Models\Provider;
use App\Models\Feature;
use Dotenv\Validator;
use App\Models\FeeSetting;
use App\Http\Resources\EnergyResource;
use App\Models\EnergyConsumption;
use App\Models\GlobalEnergySetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class EnergyController extends BaseController
{

    public function index(Request $request)
    {
        // return $request->input('power_origin');
        $pageNo = $request->input('pageNo', 1);
        $postsPerPage = $request->input('postsPerPage', 10);
        $toSkip = ($postsPerPage * $pageNo) - $postsPerPage;

        $powerConsumption = $request->input('power_consumption', 0);
        $gasConsumption = $request->input('gas_consumption', 0);
        $feedInTariff = $request->input('feed_in_tariff', 0);

        $products = EnergyProduct::with(
            'postFeatures',
            'prices',
            'feedInCost',
            'documents',
            'providerDetails',
            'govtTaxes'
        );

        // Filter by contract length
        if ($request->filled('contract_length')) {
            $products->where('contract_length', '>=', $request->input('contract_length'));
        }

        // Filter by Provider
        if ($request->filled('provider')) {
            $providers = $request->input('provider');
            foreach ($providers as $p) {
                $products->where('provider_id',  $p);
            }
        }

        // // Filter by power origin
        // if ($request->filled('power_origin')) {
        //     $power_origin = json_encode($request->input('power_origin'));
        //     $products->whereRaw('JSON_CONTAINS(power_origin, ?)', [$power_origin]);
        // }

        // // Filter by current type
        // if ($request->filled('type_of_current')) {
        //     $current_type = json_encode($request->input('type_of_current'));
        //     $products->whereRaw('JSON_CONTAINS(type_of_current, ?)', [$current_type]);
        // }

        // // Filter by gas type
        // if ($request->filled('type_of_gas')) {
        //     $gas_type = json_encode($request->input('type_of_gas'));
        //     $products->whereRaw('JSON_CONTAINS(type_of_gas, ?)', [$gas_type]);
        // }

        // Filter by power origin
        if ($request->filled('power_origin')) {
            // Decode JSON string into an array
            $power_origin = $request->input('power_origin');
            // $products->where(function ($query) use ($power_origin) {
            foreach ($power_origin as $origin) {
                $products->whereRaw('JSON_CONTAINS(power_origin, ?, "$")', [json_encode($origin)]);
            }
            // });
        }

        // Filter by current type
        if ($request->filled('type_of_current')) {
            $currentTypes = $request->input('type_of_current');
            // $products->where(function ($query) use ($currentTypes) {
            foreach ($currentTypes as $type) {
                $products->WhereRaw('JSON_CONTAINS(type_of_current, ?)', [json_encode($type)]);
            }
            // });
        }

        // Filter by gas type
        if ($request->filled('type_of_gas')) {
            $gasTypes = $request->input('type_of_gas');
            // $products->where(function ($query) use ($gasTypes) {
            foreach ($gasTypes as $type) {
                $products->WhereRaw('JSON_CONTAINS(type_of_gas, ?)', [json_encode($type)]);
            }
            // });
        }


        // Retrieve filtered products and count
        $filteredProducts = $products->skip($toSkip)->take($postsPerPage)->get();
        $recordsCount = $products->count();

        // Get preferred energy features
        $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
            ->from('features as f1')
            ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
            ->where('f1.category', 16)
            ->where('f1.is_preferred', 1)
            ->get()
            ->groupBy('parent');

        // Map features to filters array
        $filters = [];
        foreach ($objEnergyFeatures as $parent => $items) {
            $filters[] = [
                $parent => $items->map(function ($item) {
                    return (object) $item->toArray();
                })->toArray()
            ];
        }

        $mergedData = [];
        $businessGeneralSettings = getSettings('business_general');
        $globalSetting = GlobalEnergySetting::find(1);
        foreach ($filteredProducts as $product) {
            $formattedProduct = (new EnergyResource($product))->toArray($request);

            // Calculate power, gas, taxes, and tariffs
            $powerCostPerUnit = $product->power_cost_per_unit * ($powerConsumption - $feedInTariff);
            $gasCostPerUnit = $product->gas_cost_per_unit * $gasConsumption;
            $taxOnElectric = ($powerConsumption - $feedInTariff) * ($product->tax_on_electric ?? $globalSetting->tax_on_electric);
            $taxOnGas = $gasConsumption * ($product->tax_on_gas ?? $globalSetting->tax_on_gas);
            $odeOnElectric = ($powerConsumption - $feedInTariff) * ($product->ode_on_electric ?? $globalSetting->ode_on_electric);
            $odeOnGas = $gasConsumption * ($product->ode_on_gas ?? $globalSetting->ode_on_gas);
            $feedInTariffs = $feedInTariff * $product->feed_in_tariff;

            //Sub Total
            $subTotal = $powerCostPerUnit
                + $gasCostPerUnit
                + $taxOnElectric
                + $taxOnGas
                + $odeOnElectric
                + $odeOnGas;

            // After Fee In credit
            $afterFeedInTariffCredit = $subTotal - $feedInTariffs;

            // total before vat
            $totalBeforeVat = round($afterFeedInTariffCredit + $product->fixed_delivery + $product->grid_management, 2);

            $reductionOnTax = round(($product->energy_tax_reduction ?? $globalSetting->energy_tax_reduction) / 12, 2);
            $discountAmount = round($product->discount / 12, 2);
            // total After tax Reduction
            $totalAfterTaxReduction = round($totalBeforeVat - $reductionOnTax - $discountAmount, 2);
            $totalAfterTaxReduction = $totalAfterTaxReduction === -0.0 ? 0.0 : $totalAfterTaxReduction;

            $vat = round(($totalAfterTaxReduction * ($product->vat ?? $globalSetting->vat)) / 100, 2);
            // total including tax
            $totalIncludingTax = round($totalAfterTaxReduction + $vat, 2);

            // Populate formatted product data
            $formattedProduct['power_cost'] = $powerCostPerUnit;
            $formattedProduct['gas_cost'] = $gasCostPerUnit;
            $formattedProduct['tax_electric'] = $taxOnElectric;
            $formattedProduct['tax_gas'] = $taxOnGas;
            $formattedProduct['ode_electric'] = $odeOnElectric;
            $formattedProduct['ode_gas'] = $odeOnGas;
            $formattedProduct['feed_in_cost'] = $feedInTariffs;
            $formattedProduct['sub_total'] = $subTotal;
            $formattedProduct['after_feed_in_tariff'] = $afterFeedInTariffCredit;
            $formattedProduct['fixed_delivery'] = $product->fixed_delivery;
            $formattedProduct['grid_manage'] = $product->grid_management;
            $formattedProduct['total_before_vat'] = $totalBeforeVat;
            $formattedProduct['tax_reduction'] = $reductionOnTax;
            $formattedProduct['discount_amount'] = $discountAmount;
            $formattedProduct['total_after_tax_reduction'] = $totalAfterTaxReduction;
            $formattedProduct['vat_amount'] = $vat;
            $formattedProduct['total'] = $totalIncludingTax;

            $mergedData[] = $formattedProduct;
        }

        usort($mergedData, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        // if ($request->has('callFromExclusiveDeal')) {
        //     return [$mergedData, $filters];
        // }

        $nominalFee = FeeSetting::where('category_id', config('constant.category.energy'))->first()->amount;

        return response()->json([
            'success' => true,
            'data' => $mergedData,
            // 'filters' => $filters,
            'recordsCount' => $recordsCount,
            'nominalFees' => $nominalFee,
            'message' => 'Products retrieved successfully.'
        ]);
    }


    public function energyCompare(Request $request)
    {
        // $compareIds = $request->input('compare_ids');
        // $compareIds = ["6", "8"];
        $powerConsumption = $request->input('power_consumption', 0);
        $gasConsumption = $request->input('gas_consumption', 0);
        $feedInTariff = $request->input('feed_in_tariff', 0);
        $globalSetting = GlobalEnergySetting::find(1);

        // Early return if no comparison IDs are provided
        if (empty($compareIds)) {
            return response()->json(['status' => false, 'message' => 'No comparison IDs provided.']);
        }

        // Fetch products and related data in one query
        $filteredProducts = EnergyProduct::with('postFeatures', 'prices', 'feedInCost', 'documents', 'providerDetails')
            ->whereIn('id', $compareIds)
            ->get();

        // Fetch energy features and group by parent
        // $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
        //     ->from('features as f1')
        //     ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
        //     ->where('f1.category', 16)
        //     ->where('f1.is_preferred', 1)
        //     ->get()
        //     ->groupBy('parent');

        // // Structure filters in the desired format
        // $filters = $objEnergyFeatures->map(function ($items, $parent) {
        //     return [
        //         $parent => $items->map(function ($item) {
        //             return (object) $item->toArray();
        //         })->toArray()
        //     ];
        // })->values()->toArray();

        $mergedData = $filteredProducts->map(function ($product) use ($request, $powerConsumption, $feedInTariff, $gasConsumption, $globalSetting) {
            $formattedProduct = (new EnergyResource($product))->toArray($request);

            // Calculate power, gas, taxes, and tariffs
            $powerCostPerUnit = $product->power_cost_per_unit * ($powerConsumption - $feedInTariff);
            $gasCostPerUnit = $product->gas_cost_per_unit * $gasConsumption;
            $taxOnElectric = ($powerConsumption - $feedInTariff) * ($product->tax_on_electric ?? $globalSetting->tax_on_electric);
            $taxOnGas = $gasConsumption * ($product->tax_on_gas ?? $globalSetting->tax_on_gas);
            $odeOnElectric = ($powerConsumption - $feedInTariff) * ($product->ode_on_electric ?? $globalSetting->ode_on_electric);
            $odeOnGas = $gasConsumption * ($product->ode_on_gas ?? $globalSetting->ode_on_gas);
            $feedInTariffs = $feedInTariff * $product->feed_in_tariff;

            // Sub Total
            $subTotal = $powerCostPerUnit
                + $gasCostPerUnit
                + $taxOnElectric
                + $taxOnGas
                + $odeOnElectric
                + $odeOnGas;

            // After Fee In credit
            $afterFeedInTariffCredit = $subTotal - $feedInTariffs;

            // Total before VAT
            $totalBeforeVat = $afterFeedInTariffCredit + $product->fixed_delivery + $product->grid_management;

            $reductionOnTax = round(($product->energy_tax_reduction ?? $globalSetting->energy_tax_reduction) / 12, 2);
            $discountAmount = round($product->discount / 12, 2);

            // Total after tax reduction
            $totalAfterTaxReduction = round($totalBeforeVat - $reductionOnTax - $discountAmount, 2);
            $totalAfterTaxReduction = $totalAfterTaxReduction === -0.0 ? 0.0 : $totalAfterTaxReduction;
            $vat = round(($totalAfterTaxReduction * ($product->vat ?? $globalSetting->vat)) / 100, 2);

            // Total including tax
            $totalIncludingTax = round($totalAfterTaxReduction + $vat, 2);

            // Populate formatted product data
            $formattedProduct['power_cost'] = $powerCostPerUnit;
            $formattedProduct['gas_cost'] = $gasCostPerUnit;
            $formattedProduct['tax_electric'] = $taxOnElectric;
            $formattedProduct['tax_gas'] = $taxOnGas;
            $formattedProduct['ode_electric'] = $odeOnElectric;
            $formattedProduct['ode_gas'] = $odeOnGas;
            $formattedProduct['feed_in_cost'] = $feedInTariffs;
            $formattedProduct['sub_total'] = $subTotal;
            $formattedProduct['after_feed_in_tariff'] = $afterFeedInTariffCredit;
            $formattedProduct['fixed_delivery'] = $product->fixed_delivery;
            $formattedProduct['grid_manage'] = $product->grid_management;
            $formattedProduct['total_before_vat'] = $totalBeforeVat;
            $formattedProduct['tax_reduction'] = $reductionOnTax;
            $formattedProduct['discount_amount'] = $discountAmount;
            $formattedProduct['total_after_tax_reduction'] = $totalAfterTaxReduction;
            $formattedProduct['vat_amount'] = $vat;
            $formattedProduct['total'] = $totalIncludingTax;

            return $formattedProduct;
        })->sortBy('total')->values()->toArray();

        return response()->json([
            'success' => true,
            'data' => $mergedData,
            // 'filters' => $filters,
            'message' => 'Products retrieved successfully.'
        ]);
    }


    public function topEnergyDeals(Request $request)
    {
        $powerConsumption = $request->input('power_consumption', 0);
        $gasConsumption = $request->input('gas_consumption', 0);
        $feedInTariff = $request->input('feed_in_tariff', 0);
        $products = EnergyProduct::with(
            'postFeatures',
            'prices',
            'feedInCost',
            'documents',
            'providerDetails',
            'govtTaxes'
        );

        // Filter by contract length
        if ($request->filled('contract_length')) {
            $products->where('contract_length', '>=', $request->input('contract_length'));
        }

        // // Filter by power origin
        // if ($request->filled('power_origin')) {
        //     $power_origin = json_encode($request->input('power_origin'));
        //     $products->whereRaw('JSON_CONTAINS(power_origin, ?)', [$power_origin]);
        // }

        // // Filter by current type
        // if ($request->filled('type_of_current')) {
        //     $current_type = json_encode($request->input('type_of_current'));
        //     $products->whereRaw('JSON_CONTAINS(type_of_current, ?)', [$current_type]);
        // }

        // // Filter by gas type
        // if ($request->filled('type_of_gas')) {
        //     $gas_type = json_encode($request->input('type_of_gas'));
        //     $products->whereRaw('JSON_CONTAINS(type_of_gas, ?)', [$gas_type]);
        // }

        // Filter by power origin
        if ($request->filled('power_origin')) {
            // Decode JSON string into an array
            $power_origin = $request->input('power_origin');
            // $products->where(function ($query) use ($power_origin) {
            foreach ($power_origin as $origin) {
                $products->whereRaw('JSON_CONTAINS(power_origin, ?, "$")', [json_encode($origin)]);
            }
            // });
        }

        // Filter by current type
        if ($request->filled('type_of_current')) {
            $currentTypes = $request->input('type_of_current');
            // $products->where(function ($query) use ($currentTypes) {
            foreach ($currentTypes as $type) {
                $products->orWhereRaw('JSON_CONTAINS(type_of_current, ?)', [json_encode($type)]);
            }
            // });
        }

        // Filter by gas type
        if ($request->filled('type_of_gas')) {
            $gasTypes = $request->input('type_of_gas');
            // $products->where(function ($query) use ($gasTypes) {
            foreach ($gasTypes as $type) {
                $products->orWhereRaw('JSON_CONTAINS(type_of_gas, ?)', [json_encode($type)]);
            }
            // });
        }

        // Retrieve filtered products
        $filteredProducts = $products->get();

        // Retrieve preferred energy features
        $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
            ->from('features as f1')
            ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
            ->where('f1.category', 16)
            ->where('f1.is_preferred', 1)
            ->get()
            ->groupBy('parent');

        $filters = [];
        foreach ($objEnergyFeatures as $parent => $items) {
            $filters[] = [
                $parent => $items->map(function ($item) {
                    return (object) $item->toArray();
                })->toArray()
            ];
        }

        $mergedData = [];
        $businessGeneralSettings = getSettings('business_general');
        $globalSetting = GlobalEnergySetting::find(1);
        foreach ($filteredProducts as $product) {
            $formattedProduct = (new EnergyResource($product))->toArray($request);

            // Calculate power, gas, taxes, and tariffs
            $powerCostPerUnit = $product->power_cost_per_unit * ($powerConsumption - $feedInTariff);
            $gasCostPerUnit = $product->gas_cost_per_unit * $gasConsumption;
            $taxOnElectric = ($powerConsumption - $feedInTariff) * ($product->tax_on_electric ?? $globalSetting->tax_on_electric);
            $taxOnGas = $gasConsumption * ($product->tax_on_gas ?? $globalSetting->tax_on_gas);
            $odeOnElectric = ($powerConsumption - $feedInTariff) * ($product->ode_on_electric ?? $globalSetting->ode_on_electric);
            $odeOnGas = $gasConsumption * ($product->ode_on_gas ?? $globalSetting->ode_on_gas);
            $feedInTariffs = $feedInTariff * $product->feed_in_tariff;

            //Sub Total
            $subTotal = $powerCostPerUnit
                + $gasCostPerUnit
                + $taxOnElectric
                + $taxOnGas
                + $odeOnElectric
                + $odeOnGas;

            // After Fee In credit
            $afterFeedInTariffCredit = $subTotal - $feedInTariffs;

            // total before vat
            $totalBeforeVat = $afterFeedInTariffCredit + $product->fixed_delivery + $product->grid_management;

            $reductionOnTax = round(($product->energy_tax_reduction ?? $globalSetting->energy_tax_reduction) / 12, 2);
            $discountAmount = round($product->discount / 12, 2);
            // total After tax Reduction
            $totalAfterTaxReduction = round($totalBeforeVat - $reductionOnTax - $discountAmount, 2);
            $totalAfterTaxReduction = $totalAfterTaxReduction === -0.0 ? 0.0 : $totalAfterTaxReduction;
            $vat = round(($totalAfterTaxReduction * ($product->vat ?? $globalSetting->vat)) / 100, 2);
            // total including tax
            $totalIncludingTax = round($totalAfterTaxReduction + $vat, 2);

            // Populate formatted product data
            $formattedProduct['power_cost'] = $powerCostPerUnit;
            $formattedProduct['gas_cost'] = $gasCostPerUnit;
            $formattedProduct['tax_electric'] = $taxOnElectric;
            $formattedProduct['tax_gas'] = $taxOnGas;
            $formattedProduct['ode_electric'] = $odeOnElectric;
            $formattedProduct['ode_gas'] = $odeOnGas;
            $formattedProduct['feed_in_cost'] = $feedInTariffs;
            $formattedProduct['sub_total'] = $subTotal;
            $formattedProduct['after_feed_in_tariff'] = $afterFeedInTariffCredit;
            $formattedProduct['fixed_delivery'] = $product->fixed_delivery;
            $formattedProduct['grid_manage'] = $product->grid_management;
            $formattedProduct['total_before_vat'] = $totalBeforeVat;
            $formattedProduct['tax_reduction'] = $reductionOnTax;
            $formattedProduct['discount_amount'] = $discountAmount;
            $formattedProduct['total_after_tax_reduction'] = $totalAfterTaxReduction;
            $formattedProduct['vat_amount'] = $vat;
            $formattedProduct['total'] = $totalIncludingTax;

            $mergedData[] = $formattedProduct;
        }

        // Sort the merged data by total value
        usort($mergedData, fn($a, $b) => $b['total'] <=> $a['total']);
        $nominalFee = FeeSetting::where('category_id', config('constant.category.energy'))->first()->amount;
        // Return the merged data
        return response()->json([
            'success' => true,
            'data' => $mergedData,
            'filters' => $filters,
            'nominalFees' => $nominalFee,
            'message' => 'Products retrieved successfully.'
        ]);
    }

    public function getEnergyConsumption(Request $request)
    {
        // Retrieve input values with validation
        $noOfPerson = $request->input('no_of_person');
        $houseType = $request->input('house_type');

        // Validate the required inputs
        if (is_null($noOfPerson)) {
            return $this->jsonResponse(false, 'No. of Person is required.');
        }

        if (is_null($houseType)) {
            return $this->jsonResponse(false, 'House Type is required.');
        }

        if ($noOfPerson > 5) {
            return $this->jsonResponse(false, 'No. of persons can be a maximum of 5. Please enter a valid input.');
        }

        // Fetch the consumption data with error handling
        try {
            $consumeData = EnergyConsumption::where([
                'house_type' => $houseType,
                'no_of_person' => $noOfPerson,
            ])->first();

            if (!$consumeData) {
                return $this->jsonResponse(false, 'Consumption data not found for the given combination.');
            }

            $cData = [
                'house_type' => $consumeData->house_type,
                'no_of_person' => $consumeData->no_of_person,
                'electric' => $consumeData->electric_supply,
                'gas' => $consumeData->gas_supply,
                'return' => '0',
            ];

            // Return the found consumption data
            return $this->jsonResponse(true, 'Consumption Retrieved Successfully', $cData);
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'An error occurred: ' . $e->getMessage());
        }
    }


    private function jsonResponse($status, $message, $data = null)
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



    // public function index(Request $request)
    // {
    //     $pageno = $request->pageNo ?? 1;
    //     $postsPerPage = $request->postsPerPage ?? 10;
    //     $toSkip = (int)$postsPerPage * (int)$pageno - (int)$postsPerPage;

    //     $products = EnergyProduct::with(
    //         'postFeatures',
    //         'prices',
    //         'feedInCost',
    //         'documents',
    //         'providerDetails',
    //         'govtTaxes'
    //     );

    //     // Filter by postal code
    //     if ($request->filled('postal_code')) {
    //         $postalCode = json_encode($request->input('postal_code'));
    //         // Use whereRaw with JSON_CONTAINS to check if the postal code is present in the pin_codes array
    //         $products->whereRaw('JSON_CONTAINS(pin_codes, ?)', [$postalCode]);
    //     }

    //     // Filter by number of persons
    //     if ($request->filled('no_of_person')) {
    //         $products->where('no_of_person', '>=', $request->input('no_of_person'));
    //     }

    //     // Filter by Solar Panel
    //     if ($request->filled('solar_panel')) {
    //         $products->where('solar_panels', $request->input('solar_panel'));
    //     }

    //     // Filter by Normal electric consume
    //     if ($request->filled('normal_electric_consume')) {
    //         $products->where('feed_in_normal', $request->input('normal_electric_consume'));
    //     }

    //     // Filter by Peak electric consume
    //     if ($request->filled('peak_electric_consume')) {
    //         $products->where('feed_in_peak', $request->input('peak_electric_consume'));
    //     }


    //     // Filter by house type
    //     // if ($request->has('house_type')) {
    //     //     $products->where('house_type', $request->input('house_type'));
    //     // }

    //     // Filter by current supplier
    //     if ($request->has('current_supplier')) {
    //         $products->where('provider', $request->input('current_supplier'));
    //     }

    //     // Filter by meter type
    //     if ($request->has('meter_type')) {
    //         $products->where('meter_type', $request->input('meter_type'));
    //     }

    //     // Filter by gas availability
    //     if ($request->has('no_gas') && $request->no_gas == 1) {
    //         $products->where('no_gas', $request->input('no_gas'));
    //     }

    //     // Filter by energy label
    //     if ($request->has('energy_label')) {
    //         $energy_label = json_encode($request->input('energy_label'));
    //         // Use whereRaw with JSON_CONTAINS to check if the energy_label is present in the energy_label array
    //         $products->whereRaw('JSON_CONTAINS(energy_label, ?)', [$energy_label]);
    //     }

    //     if ($request->has('features') && $request->features !== null) {
    //         $features = $request->input('features');
    //         $products->whereHas('postFeatures', function ($query) use ($features) {
    //             $query->whereIn('feature_id', $features);
    //         });
    //     }


    //     $filteredProducts = $products->skip($toSkip)->take($postsPerPage)->get();
    //     $recordsCount = $products->count();

    //     $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
    //         ->from('features as f1')
    //         ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
    //         ->where('f1.category', 16)
    //         ->where('f1.is_preferred', 1)
    //         ->get()
    //         ->groupBy('parent');


    //     // Initialize an empty array to store the grouped filters
    //     $filters = [];

    //     // Loop through the grouped features and convert them to the desired structure
    //     foreach ($objEnergyFeatures as $parent => $items) {
    //         $filters[] = [
    //             $parent => $items->map(function ($item) {
    //                 return (object) $item->toArray();
    //             })->toArray()
    //         ];
    //     }
    //     //dd($request->advantages);
    //     $mergedData = [];
    //     $businessGeneralSettings = getSettings('business_general');
    //     $totalFeedIn = ($request->advantages['feed_in_normal'] ?? 1) + ($request->advantages['feed_in_peak'] ?? 1);
    //     $normal_electric_consume = $request->advantages['normal_electric_consume'] ?? 1;
    //     $peak_electric_consume = $request->advantages['peak_electric_consume'] ?? 1;
    //     $feed_in_normal = $request->advantages['feed_in_normal'] ?? 1;
    //     $feed_in_peak = $request->advantages['feed_in_peak'] ?? 1;
    //     $gas_consume = $request->advantages['gas_consume'] ?? 1;

    //     foreach ($filteredProducts as $product) {
    //         $formattedProduct = (new EnergyResource($product))->toArray($request);
    //         if ($product->feedInCost) {
    //             $feedInCostRange = json_decode($product->feedInCost->feed_in_cost, true);
    //             $feedInCostValue = array_filter($feedInCostRange, function ($item) use ($totalFeedIn) {
    //                 return $totalFeedIn >= $item['from_range'] && $totalFeedIn <= $item['to_range'];
    //             });
    //         }
    //         if (!empty($feedInCostValue)) {
    //             $feedInCostValue = reset($feedInCostValue);
    //         }
    //         $feedInCostRangeValue = $feedInCostValue['amount'] ?? 0;
    //         $reductionCostElectric = $product->reduction_of_energy_tax ?? $businessGeneralSettings['reduction_of_energy_tax'];
    //         $govt_levies_gas = $product->government_levies_gas ?? $businessGeneralSettings['governement_levies_gas'];

    //         if ($request->no_gas === 1) {
    //             $gas_consume = 0;
    //             $del_gas = 0;
    //             $govt_levies_gas = 0;
    //             $net_gas = 0;
    //         } else {
    //             $gas_consume = $request->advantages['gas_consume'] ?? 1;
    //             $del_gas = 1;
    //             $net_gas = 1;
    //         }
    //         if ($request->solar_panels > 0) {
    //         } else {
    //             $feed_in_normal = 0;
    //             $feed_in_peak = 0;
    //             $feedInCostRangeValue = 0;
    //         }

    //         $total = (($product->normal_electric_price ?? $product->prices->electric_rate) * $normal_electric_consume)
    //             + (($product->peak_electric_price ?? $product->prices->off_peak_electric_rate) * $peak_electric_consume)
    //             + $product->delivery_cost_electric
    //             + ($product->network_cost_electric ?? 0)
    //             + $feedInCostRangeValue
    //             - ($product->feedInCost ? $product->feedInCost->normal_return_delivery * $feed_in_normal : 0)
    //             - ($product->feedInCost ? $product->feedInCost->off_peak_return_delivery * $feed_in_peak : 0)
    //             - $reductionCostElectric
    //             + ($gas_consume * $product->prices->gas_rate)
    //             + ($product->delivery_cost_gas * $del_gas)
    //             + ($govt_levies_gas * $govt_levies_gas)
    //             + (($product->network_cost_gas ?? 0) * $net_gas)
    //             - $product->cashback;
    //         $advantages = [
    //             'gas_consume' => $gas_consume,
    //             'normal_electric_consume' => $normal_electric_consume,
    //             'peak_electric_consume' => $peak_electric_consume,
    //             'feed_in_peak' => $feed_in_peak,
    //             'feed_in_normal' => $feed_in_normal,
    //         ];
    //         $formattedProduct['total'] = $total;
    //         $formattedProduct['advantages'] = $advantages;

    //         $mergedData[] = $formattedProduct;
    //     }
    //     // Sort the merged data based on the total values
    //     usort($mergedData, function ($a, $b) {
    //         return $a['total'] <=> $b['total'];
    //     });
    //     if ($request->has('callFromExclusiveDeal')) {
    //         return [$mergedData, $filters];
    //     }

    //     // Return the merged data
    //     return response()->json([
    //         'success' => true,
    //         'data' => $mergedData,
    //         'filters' => $filters,
    //         //'advantages' => $advantages,
    //         'recordsCount' => $recordsCount,
    //         'message' => 'Products retrieved successfully.'
    //     ]);
    // }

    // public function topEnergyDeals(Request $request)
    // {
    //     $products = EnergyProduct::with('postFeatures', 'prices', 'feedInCost', 'documents', 'providerDetails', 'govtTaxes');

    //     // Filter by postal code
    //     if ($request->filled('postal_code')) {
    //         $postalCode = json_encode($request->input('postal_code'));
    //         // Use whereRaw with JSON_CONTAINS to check if the postal code is present in the pin_codes array
    //         $products->whereRaw('JSON_CONTAINS(pin_codes, ?)', [$postalCode]);
    //     }

    //     // Filter by number of persons
    //     if ($request->filled('no_of_person')) {
    //         $products->where('no_of_person', '<=', $request->input('no_of_person'));
    //     }

    //     // Filter by Normal electric consume
    //     if ($request->filled('normal_electric_consume')) {
    //         $products->where('feed_in_normal', $request->input('normal_electric_consume'));
    //     }

    //     // Filter by Peak electric consume
    //     if ($request->filled('peak_electric_consume')) {
    //         $products->where('feed_in_peak', $request->input('peak_electric_consume'));
    //     }


    //     // Filter by house type
    //     // if ($request->has('house_type')) {
    //     //     $products->where('house_type', $request->input('house_type'));
    //     // }

    //     // Filter by current supplier
    //     if ($request->filled('current_supplier')) {
    //         $products->where('provider', $request->input('current_supplier'));
    //     }

    //     // Filter by meter type
    //     if ($request->filled('meter_type')) {
    //         $products->where('meter_type', $request->input('meter_type'));
    //     }

    //     // Filter by gas availability
    //     if ($request->filled('no_gas') && $request->no_gas == 1) {
    //         $products->where('no_gas', $request->input('no_gas'));
    //     }

    //     // Filter by energy label
    //     if ($request->filled('energy_label')) {
    //         $energyLabel = json_encode($request->input('energy_label'));
    //         $products->whereRaw('JSON_CONTAINS(energy_label, ?)', [$energyLabel]);
    //     }

    //     if ($request->filled('features')) {
    //         $features = $request->input('features');
    //         $products->whereHas('postFeatures', function ($query) use ($features) {
    //             $query->whereIn('feature_id', $features);
    //         });
    //     }
    //     // Retrieve filtered products and return response
    //     $filteredProducts = $products->get();

    //     $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
    //         ->from('features as f1')
    //         ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
    //         ->where('f1.category', 16)
    //         ->where('f1.is_preferred', 1)
    //         ->get()
    //         ->groupBy('parent');

    //     $filters = [];
    //     foreach ($objEnergyFeatures as $parent => $items) {
    //         $filters[] = [
    //             $parent => $items->map(function ($item) {
    //                 return (object) $item->toArray();
    //             })->toArray()
    //         ];
    //     }

    //     $mergedData = [];
    //     $businessGeneralSettings = getSettings('business_general');
    //     $totalFeedIn = ($request->advantages['feed_in_normal'] ?? 500) + ($request->advantages['feed_in_peak'] ?? 300);
    //     $normal_electric_consume = $request->advantages['normal_electric_consume'] ?? 500;
    //     $peak_electric_consume = $request->advantages['peak_electric_consume'] ?? 300;
    //     $feed_in_normal = $request->advantages['feed_in_normal'] ?? 500;
    //     $feed_in_peak = $request->advantages['feed_in_peak'] ?? 300;
    //     $gas_consume = $request->advantages['gas_consume'] ?? 200;

    //     foreach ($filteredProducts as $product) {
    //         $formattedProduct = (new EnergyResource($product))->toArray($request);

    //         if ($product->feedInCost) {
    //             $feedInCostRange = json_decode($product->feedInCost->feed_in_cost, true);
    //             $feedInCostValue = array_filter($feedInCostRange, function ($item) use ($totalFeedIn) {
    //                 return $totalFeedIn >= $item['from_range'] && $totalFeedIn <= $item['to_range'];
    //             });
    //         }
    //         if (!empty($feedInCostValue)) {
    //             $feedInCostValue = reset($feedInCostValue);
    //         }
    //         $feedInCostRangeValue = $feedInCostValue['amount'] ?? 0;
    //         $reductionCostElectric = $product->reduction_of_energy_tax ?? $businessGeneralSettings['reduction_of_energy_tax'];
    //         $govt_levies_gas = $product->government_levies_gas ?? $businessGeneralSettings['governement_levies_gas'];
    //         if ($request->no_gas == 1) {
    //             $gas_consume = 0;
    //             $del_gas = 0;
    //             $govt_levies_gas = 0;
    //             $net_gas = 0;
    //         } else {
    //             $gas_consume = $request->advantages['gas_consume'] ?? 200;
    //             $del_gas = 1;
    //             $net_gas = 1;
    //         }
    //         if ($request->solar_panels > 0) {
    //         } else {
    //             $feed_in_normal = 0;
    //             $feed_in_peak = 0;
    //             $feedInCostRangeValue = 0;
    //         }


    //         // $total = ($product->normal_electric_price ?? $product->prices->electric_rate) * $normal_electric_consume
    //         //     + ($product->peak_electric_price ?? $product->prices->off_peak_electric_rate) * $peak_electric_consume
    //         //     + ($product->delivery_cost_electric ?? 0)
    //         //     + ($product->network_cost_electric ?? 0)
    //         //     + $feedInCostRangeValue
    //         //     - ($product->feedInCost ? $product->feedInCost->normal_return_delivery * $feed_in_normal : 0)
    //         //     - ($product->feedInCost ? $product->feedInCost->off_peak_return_delivery * $feed_in_peak : 0)
    //         //     - $reductionCostElectric
    //         //     + ($gas_consume * ($product->prices ? $product->prices->gas_rate : 0))
    //         //     + ($product->delivery_cost_gas * $del_gas)
    //         //     + ($govt_levies_gas * $govt_levies_gas)
    //         //     + (($product->network_cost_gas ?? 0) * $net_gas)
    //         //     - $product->cashback;
    //         // $advantages = [
    //         //     'gas_consume' => $gas_consume,
    //         //     'normal_electric_consume' => $normal_electric_consume,
    //         //     'peak_electric_consume' => $peak_electric_consume,
    //         //     'feed_in_peak' => $feed_in_peak,
    //         //     'feed_in_normal' => $feed_in_normal,
    //         // ];
    //         $total = (($product->normal_electric_price ?? ($product->prices->electric_rate ?? 0)) * $normal_electric_consume)
    //             + (($product->peak_electric_price ?? ($product->prices->off_peak_electric_rate ?? 0)) * $peak_electric_consume)
    //             + ($product->delivery_cost_electric ?? 0)
    //             + ($product->network_cost_electric ?? 0)
    //             + $feedInCostRangeValue
    //             - ($product->feedInCost ? ($product->feedInCost->normal_return_delivery * $feed_in_normal) : 0)
    //             - ($product->feedInCost ? ($product->feedInCost->off_peak_return_delivery * $feed_in_peak) : 0)
    //             - $reductionCostElectric
    //             + ($gas_consume * (($product->prices->gas_rate ?? 0)))
    //             + (($product->delivery_cost_gas ?? 0) * $del_gas)
    //             + ($govt_levies_gas ?? 0) * $govt_levies_gas
    //             + (($product->network_cost_gas ?? 0) * $net_gas)
    //             - ($product->cashback ?? 0);

    //         $advantages = [
    //             'gas_consume' => $gas_consume,
    //             'normal_electric_consume' => $normal_electric_consume,
    //             'peak_electric_consume' => $peak_electric_consume,
    //             'feed_in_peak' => $feed_in_peak,
    //             'feed_in_normal' => $feed_in_normal,
    //         ];

    //         $formattedProduct['total'] = $total;
    //         $formattedProduct['advantages'] = $advantages;
    //         $mergedData[] = $formattedProduct;
    //     }
    //     // Sort the merged data based on the total values
    //     usort($mergedData, function ($a, $b) {
    //         return $a['total'] <=> $b['total'];
    //     });

    //     // Return the merged data
    //     return response()->json([
    //         'success' => true,
    //         'data' => $mergedData,
    //         'filters' => $filters,
    //         'message' => 'Products retrieved successfully.'
    //     ]);
    // }


    // public function energyCompare(Request $request)
    // {
    //     $compareIds = $request->compare_ids;

    //     if (!empty($compareIds)) {
    //         $products = EnergyProduct::with('postFeatures', 'prices', 'feedInCost', 'documents', 'providerDetails');
    //         $filteredProducts = $products->whereIn('id', $compareIds)->get();

    //         $objEnergyFeatures = Feature::select('f1.id', 'f1.features', 'f1.input_type', DB::raw('COALESCE(f2.features, "No_Parent") as parent'))
    //             ->from('features as f1')
    //             ->leftJoin('features as f2', 'f1.parent', '=', 'f2.id')
    //             ->where('f1.category', 16)
    //             ->where('f1.is_preferred', 1)
    //             ->get()
    //             ->groupBy('parent');


    //         // Initialize an empty array to store the grouped filters
    //         $filters = [];

    //         // Loop through the grouped features and convert them to the desired structure
    //         foreach ($objEnergyFeatures as $parent => $items) {
    //             $filters[] = [
    //                 $parent => $items->map(function ($item) {
    //                     return (object) $item->toArray();
    //                 })->toArray()
    //             ];
    //         }
    //         //dd($request->advantages);
    //         $mergedData = [];
    //         $businessGeneralSettings = getSettings('business_general');
    //         $totalFeedIn = ($request->advantages['feed_in_normal'] ?? 1) + ($request->advantages['feed_in_peak'] ?? 1);
    //         $normal_electric_consume = $request->advantages['normal_electric_consume'] ?? 1;
    //         $peak_electric_consume = $request->advantages['peak_electric_consume'] ?? 1;
    //         $feed_in_normal = $request->advantages['feed_in_normal'] ?? 1;
    //         $feed_in_peak = $request->advantages['feed_in_peak'] ?? 1;
    //         $gas_consume = $request->advantages['gas_consume'] ?? 1;

    //         foreach ($filteredProducts as $product) {
    //             $formattedProduct = (new EnergyResource($product))->toArray($request);
    //             if ($product->feedInCost) {
    //                 $feedInCostRange = json_decode($product->feedInCost->feed_in_cost, true);
    //                 $feedInCostValue = array_filter($feedInCostRange, function ($item) use ($totalFeedIn) {
    //                     return $totalFeedIn >= $item['from_range'] && $totalFeedIn <= $item['to_range'];
    //                 });
    //             }
    //             if (!empty($feedInCostValue)) {
    //                 $feedInCostValue = reset($feedInCostValue);
    //             }
    //             $feedInCostRangeValue = $feedInCostValue['amount'] ?? 0;
    //             $reductionCostElectric = $product->reduction_of_energy_tax ?? $businessGeneralSettings['reduction_of_energy_tax'];
    //             $govt_levies_gas = $product->government_levies_gas ?? $businessGeneralSettings['governement_levies_gas'];

    //             if ($request->no_gas === 1) {
    //                 $gas_consume = 0;
    //                 $del_gas = 0;
    //                 $govt_levies_gas = 0;
    //                 $net_gas = 0;
    //             } else {
    //                 $gas_consume = $request->advantages['gas_consume'] ?? 1;
    //                 $del_gas = 1;
    //                 $net_gas = 1;
    //             }
    //             if ($request->solar_panels > 0) {
    //             } else {
    //                 $feed_in_normal = 0;
    //                 $feed_in_peak = 0;
    //                 $feedInCostRangeValue = 0;
    //             }

    //             $total = (($product->normal_electric_price ?? $product->prices->electric_rate) * $normal_electric_consume)
    //                 + (($product->peak_electric_price ?? $product->prices->off_peak_electric_rate) * $peak_electric_consume)
    //                 + $product->delivery_cost_electric
    //                 + ($product->network_cost_electric ?? 0)
    //                 + $feedInCostRangeValue
    //                 - ($product->feedInCost ? $product->feedInCost->normal_return_delivery * $feed_in_normal : 0)
    //                 - ($product->feedInCost ? $product->feedInCost->off_peak_return_delivery * $feed_in_peak : 0)
    //                 - $reductionCostElectric
    //                 + ($gas_consume * $product->prices->gas_rate)
    //                 + ($product->delivery_cost_gas * $del_gas)
    //                 + ($govt_levies_gas * $govt_levies_gas)
    //                 + (($product->network_cost_gas ?? 0) * $net_gas)
    //                 - $product->cashback;
    //             $advantages = [
    //                 'gas_consume' => $gas_consume,
    //                 'normal_electric_consume' => $normal_electric_consume,
    //                 'peak_electric_consume' => $peak_electric_consume,
    //                 'feed_in_peak' => $feed_in_peak,
    //                 'feed_in_normal' => $feed_in_normal,
    //             ];
    //             $formattedProduct['total'] = $total;
    //             $formattedProduct['advantages'] = $advantages;

    //             $mergedData[] = $formattedProduct;
    //         }

    //         usort($mergedData, function ($a, $b) {
    //             return $a['total'] <=> $b['total'];
    //         });
    //         return response()->json([
    //             'success' => true,
    //             'data' => $mergedData,
    //             'filters' => $filters,
    //             //'advantages' => $advantages,
    //             'message' => 'Products retrieved successfully.'
    //         ]);
    //     } else {
    //         return $this->sendError('No comparison IDs provided.', [], 400);
    //     }
    // }
}
