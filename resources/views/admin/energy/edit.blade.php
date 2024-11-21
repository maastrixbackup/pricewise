@extends('admin.layouts.app')
@section('title', 'PriceWise- Energy Products Edit')
@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.energy.index') }}">Energy</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card">

        <form id="productForm2" method="post" action="{{ route('admin.energy.update', $objEnergy->id) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class=" col-12">
                    <div class="card">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Edit Energy Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="provider" class="col-form-label"><b>Provider</b>
                                    </label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ $objEnergy->providerDetails->name }}">
                                    <input type="hidden" class="form-control" name="provider"
                                        value="{{ $objEnergy->provider_id }}">
                                </div>

                                <div class="col-md-2 col-12">
                                    <div class=" mb-3">
                                        @php
                                            $powerOrigin = $objEnergy->power_origin
                                                ? json_decode($objEnergy->power_origin)
                                                : [];
                                        @endphp
                                        <label for="valid_till" class="col-form-label"> Power Origin</label>
                                        <div>
                                            <label><input type="checkbox" name="power_origin[]" value="wind"
                                                    @if (is_array($powerOrigin) && in_array('wind', $powerOrigin)) checked @endif> Wind</label>
                                        </div>
                                        <div>
                                            <label><input type="checkbox" name="power_origin[]" value="water"
                                                    @if (is_array($powerOrigin) && in_array('water', $powerOrigin)) checked @endif>
                                                Water</label>
                                        </div>
                                        <div>
                                            <label><input type="checkbox" name="power_origin[]" value="sun"
                                                    @if (is_array($powerOrigin) && in_array('sun', $powerOrigin)) checked @endif> Sun</label>
                                        </div>
                                        <div>
                                            <label><input type="checkbox" name="power_origin[]" value="thermal"
                                                    @if (is_array($powerOrigin) && in_array('thermal', $powerOrigin)) checked @endif>
                                                Thermal</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    @php
                                        $typeOfGas = $objEnergy->type_of_gas
                                            ? json_decode($objEnergy->type_of_gas)
                                            : [];
                                    @endphp
                                    <div class=" mb-3">
                                        <label for="avg_delivery_time" class=" col-form-label">Type Of Gas</label>
                                        <div>
                                            <label><input type="checkbox" name="gas_type[]" value="co2"
                                                    @if (is_array($typeOfGas) && in_array('co2', $typeOfGas)) checked @endif> Co2 Compensated
                                                Gas</label>
                                        </div>
                                        <div>
                                            <label><input type="checkbox" name="gas_type[]" value="partly_green_gas"
                                                    @if (is_array($typeOfGas) && in_array('partly_green_gas', $typeOfGas)) checked @endif> Partly
                                                Green Gas</label>
                                        </div>
                                        <div>
                                            <label><input type="checkbox" name="gas_type[]" value="100_green_gas"
                                                    @if (is_array($typeOfGas) && in_array('100_green_gas', $typeOfGas)) checked @endif> 100%
                                                Green
                                                Gas</label>
                                        </div>
                                        <div>
                                            <label><input type="checkbox" name="gas_type[]" value="gas_free"
                                                    @if (is_array($typeOfGas) && in_array('gas_free', $typeOfGas)) checked @endif> Gas
                                                Free</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="provider" class="col-form-label"><b>Status</b>
                                    </label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="0"{{ $objEnergy->status == 0 ? 'selected' : '' }}>Draft
                                        </option>
                                        <option value="1"{{ $objEnergy->status == 1 ? 'selected' : '' }}>Published
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="provider" class="col-form-label"><b>Target Group</b>
                                    </label>
                                    <select id="target_group" name="target_group" class="select2 form-select" required>
                                        <option disabled selected>Select</option>
                                        <option
                                            value="personal"{{ $objEnergy->target_group == 'personal' ? 'selected' : '' }}>
                                            Personal</option>
                                        <option
                                            value="commercial"{{ $objEnergy->target_group == 'commercial' ? 'selected' : '' }}>
                                            Commercial</option>
                                        <option
                                            value="large_business"{{ $objEnergy->target_group == 'large_business' ? 'selected' : '' }}>
                                            Large Business</option>
                                    </select>
                                    <span class="invalid-feedback">Please Select a valid value.</span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4 col-12">
                                    <div>
                                        <label for="valid_till" class="col-form-label"> Fixed Delivery Cost: <span
                                                style="font-family: Robot Mono"
                                                id="delivery_cost">{{ '€' . ' ' . $objEnergy->fixed_delivery }}</span></label>
                                        <input type="hidden" class="form-control" name="fixed_delivery" id="fixed_delivery"
                                            placeholder="Delivery Cost" value="{{ $objEnergy->fixed_delivery }}" readonly>
                                    </div>
                                    <div>
                                        <label for="avg_delivery_time" class=" col-form-label">Grid Management Cost: <span
                                                style="font-family: Robot Mono"
                                                id="grid_cost">{{ '€' . ' ' . $objEnergy->grid_management }}</span></label>
                                        <input type="hidden" readonly class="form-control" id="grid_management"
                                            name="grid_management" placeholder="Grid Management Cost"
                                            value="{{ $objEnergy->grid_management }}" min="0">
                                    </div>
                                    <div>
                                        <label for="avg_delivery_time" class=" col-form-label">Feed In Tariff: <span
                                                style="font-family: Robot Mono"
                                                id="feed_in_tariffs">{{ '€' . ' ' . $objEnergy->feed_in_tariff }}</span></label>
                                        <input type="hidden" readonly class="form-control" id="feed_in_tariff"
                                            name="feed_in_tariff" placeholder="Solar Buy Back"
                                            value="{{ $objEnergy->feed_in_tariff }}" min="0">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div>
                                        <label for="" class="col-form-label">Tax On Electric:
                                            <span
                                                style="font-family: Robot Mono">{{ '€' . ' ' . $objEnergy->tax_on_electric }}
                                                </spna></label>
                                        <input type="hidden" readonly value="{{ $objEnergy->tax_on_electric }}"
                                            name="tax_on_electric" id="tax_on_electric" class="form-control">
                                    </div>
                                    <div>
                                        <label for="" class="col-form-label">Tax on Gas:
                                            <span style="font-family: Robot Mono">{{ '€' . ' ' . $objEnergy->tax_on_gas }}
                                                </spna></label>
                                        <input type="hidden" readonly value="{{ $objEnergy->tax_on_gas }}"
                                            name="tax_on_gas" id="tax_on_gas" class="form-control">
                                    </div>
                                    <div>
                                        <label for="" class="col-form-label">ODE On Electric:
                                            <span
                                                style="font-family: Robot Mono">{{ '€' . ' ' . $objEnergy->ode_on_electric }}
                                                </spna></label>
                                        <input type="hidden" readonly value="{{ $objEnergy->ode_on_electric }}"
                                            name="ode_on_electric" id="ode_on_electric" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div>
                                        <label for="" class="col-form-label">ODE On Gas:
                                            <span style="font-family: Robot Mono">{{ '€' . ' ' . $objEnergy->ode_on_gas }}
                                                </spna></label>
                                        <input type="hidden" readonly value="{{ $objEnergy->ode_on_gas }}"
                                            name="ode_on_gas" id="ode_on_gas" class="form-control">
                                    </div>
                                    <div>
                                        <label for="" class="col-form-label">VAT:
                                            <span style="font-family: Robot Mono">{{ '€' . ' ' . $objEnergy->vat }}</spna>
                                        </label>
                                        <input type="hidden" readonly value="{{ $objEnergy->vat }}" name="vat"
                                            id="vat" class="form-control">
                                    </div>
                                    <div>
                                        <label for="" class="col-form-label">Energy Tax Reduction/Year:
                                            <span
                                                style="font-family: Robot Mono">{{ '€' . ' ' . $globalEnergy->energy_tax_reduction }}
                                                </spna></label>
                                        <input type="hidden" readonly value="{{ $globalEnergy->energy_tax_reduction }}"
                                            name="energy_tax_reduction" id="energy_tax_reduction" class="form-control">
                                    </div>

                                </div>
                            </div>

                            <div class="row mt-2">
                                @include('admin.energy.contract_year')
                            </div>

                            <div class="">
                                <label class=" col-form-label"></label>
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4" id="submitBtn"
                                        name="submit2">Update</button>
                                    {{-- <button type="reset" class="btn btn-light px-4">Reset</button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
    <!--end breadcrumb-->

@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // $('#submitBtn').click(function(e) {
            //     // Initialize the validation flag
            //     let isValid = true;

            //     // Check if at least one "Power Origin" checkbox is checked
            //     let isPowerOriginChecked = $('input[name="power_origin[]"]:checked').length > 0;
            //     // Check if at least one "Type of Gas" checkbox is checked
            //     let isGasTypeChecked = $('input[name="gas_type[]"]:checked').length > 0;

            //     // If any checkbox group is not selected, show alert and prevent form submission
            //     if (!isPowerOriginChecked || !isGasTypeChecked) {
            //         e.preventDefault(); // Prevent form submission
            //         let message = 'Please select at least one option for:\n';
            //         if (!isPowerOriginChecked) message += '- Power Origin\n';
            //         if (!isGasTypeChecked) message += '- Type of Gas\n';
            //         alert(message);
            //         isValid = false;
            //     }

            //     //Iterate through all input[type="number"] fields and check for empty values
            //     $('input[type="number"]').each(function() {
            //         if ($(this).val() === '' || isNaN($(this).val())) {
            //             // If the field is empty, mark it as invalid and apply red border
            //             $(this).addClass('is-invalid').removeClass('is-valid');
            //             isValid = false; // Mark form as invalid
            //         } else {
            //             // If the field has a valid value, mark it as valid
            //             $(this).removeClass('is-invalid');
            //         }
            //     });


            //     // Validate number inputs related to selected "Contract Year" checkboxes
            //     $('input[name="contract_year[]"]:checked').each(function() {
            //         let year = $(this).val();

            //         // Validate each corresponding input based on the selected year
            //         let powerInput = $(`#power_year_${year}`);
            //         let gasInput = $(`#gas_year_${year}`);
            //         let discountInput = $(`#discount_year_${year}`);
            //         let validTill = $(`#valid_till_${year}`);

            //         // Array of inputs to check
            //         let inputs = [powerInput, gasInput, discountInput, validTill];

            //         // Loop through inputs and check for validity
            //         inputs.forEach(function(input) {
            //             var value = input.val();
            //             if (value === "" || value < 0 || isNaN(value)) {
            //                 isValid = false;
            //                 input.addClass('is-invalid') // Add error class if invalid
            //             } else {
            //                 input.removeClass('is-invalid'); // Add valid class if valid
            //             }
            //         });
            //     });
            //     // Prevent form submission if any field is invalid
            //     if (!isValid || $('.is-invalid').length > 0) {
            //         e.preventDefault(); // Prevent form submission
            //         alert("Please correct the invalid fields before submitting.");
            //     }

            // });

            $('#submitBtn').click(function(e) {
                // Initialize the validation flag
                let isValid = true;

                // Reset all validation errors before checking
                $('.is-invalid').removeClass('is-invalid');

                // Check if at least one "Power Origin" checkbox is checked
                let isPowerOriginChecked = $('input[name="power_origin[]"]:checked').length > 0;
                // Check if at least one "Type of Gas" checkbox is checked
                let isGasTypeChecked = $('input[name="gas_type[]"]:checked').length > 0;

                // If any checkbox group is not selected, show alert and prevent form submission
                if (!isPowerOriginChecked || !isGasTypeChecked) {
                    e.preventDefault(); // Prevent form submission
                    let message = 'Please select at least one option for:\n';
                    if (!isPowerOriginChecked) message += '- Power Origin\n';
                    if (!isGasTypeChecked) message += '- Type of Gas\n';
                    alert(message);
                    isValid = false;
                }

                // Validate number inputs only if "Contract Year" checkboxes are checked
                if ($('input[name="contract_year[]"]:checked').length > 0) {
                    $('input[name="contract_year[]"]:checked').each(function() {
                        let year = $(this).val();
                        // Validate each corresponding input based on the selected year
                        let powerInput = $(`#power_year_${year}`);
                        let gasInput = $(`#gas_year_${year}`);
                        let discountInput = $(`#discount_year_${year}`);
                        let validTillInput = $(`#valid_till_${year}`);

                        // Array of inputs to check
                        let inputs = [powerInput, gasInput, discountInput];

                        // Loop through inputs and check for validity
                        inputs.forEach(function(input) {
                            var value = input.val();
                            if (value === "" || value < 0 || isNaN(value)) {
                                isValid = false;
                                input.addClass('is-invalid'); // Add error class if invalid
                            } else {
                                input.removeClass(
                                'is-invalid'); // Remove error class if valid
                            }
                        });
                    });
                }

                // Prevent form submission if any field is invalid
                if (!isValid) {
                    e.preventDefault(); // Prevent form submission
                    alert("Please correct the invalid fields before submitting.");
                }
            });

            // Ensure "is-invalid" class is removed on input change
            $(document).on('input change', '.is-invalid', function() {
                $(this).removeClass('is-invalid');
            });



            $('input[type="number"]').on('keyup', function() {
                restrictDecimal($(this));
            });

            // Function to handle decimal restriction (optional for later)
            function restrictDecimal(inputField) {
                var value = inputField.val();
                if (value === '') {
                    inputField.removeClass('is-valid').addClass('is-invalid');
                } else {
                    inputField.removeClass('is-invalid').addClass('is-valid');
                }
                if (value.indexOf('.') !== -1) {
                    var parts = value.split('.');
                    if (parts[1].length > 2) {
                        parts[1] = parts[1].substring(0, 2); // Restrict to two decimal places
                        inputField.val(parts[0] + '.' + parts[1]);
                    }
                }
            }



            $('input[type="checkbox"]').on('change', function() {
                // Get the parent row of the checkbox
                var parentRow = $(this).closest('tr');

                // Enable/disable all input fields in the same row based on the checkbox state
                if ($(this).is(':checked')) {
                    parentRow.find('input[type="number"]').prop('readonly', false);
                    parentRow.find('input[type="number"]').prop('disabled',
                        false); // Add valid class if valid
                    parentRow.find('input[type="date"]').prop('readonly', false).prop('disabled', false);
                    // parentRow.find('input[type="number"]').attr('required', true);
                    // parentRow.find('input[type="number"]').css('border', '1px solid gray');
                } else {
                    parentRow.find('input[type="number"]').prop('disabled', true);
                    parentRow.find('input[type="number"]').prop('readonly', true);
                    parentRow.find('input[type="date"]').prop('readonly', true).prop('disabled', true);
                    parentRow.find('input[type="number"]').val('');
                    parentRow.find('input[type="number"]').removeClass('is-invalid');
                    parentRow.find('input[type="number"]').removeClass('is-valid');
                    // parentRow.find('input[type="number"]').removeAttr('required');
                    // parentRow.find('input[type="number"]').css('border', '1px solid #d1d8ea');
                }
            });
        });
    </script>
@endpush
