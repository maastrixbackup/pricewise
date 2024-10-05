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
                                </div>

                                <div class="col-md-3 col-12">
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
                                            <label><input type="checkbox" name="gas_type[]" value="partlt_green_gas"
                                                    @if (is_array($typeOfGas) && in_array('partlt_green_gas', $typeOfGas)) checked @endif> Partly
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
                                <div class="col-md-3 mb-3">
                                    <label for="provider" class="col-form-label"><b>Status</b>
                                    </label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="0"{{ $objEnergy->status == 0 ? 'selected' : '' }}>Draft
                                        </option>
                                        <option value="1"{{ $objEnergy->status == 1 ? 'selected' : '' }}>Published
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4 col-12">
                                    <div>
                                        <label for="valid_till" class="col-form-label"> Fixed Delivery Cost:-<span
                                                id="delivery_cost">{{'€'. $objEnergy->fixed_delivery }}</span></label>
                                        <input type="hidden" class="form-control" name="fixed_delivery" id="fixed_delivery"
                                            placeholder="Delivery Cost" value="{{ $objEnergy->fixed_delivery }}" readonly>
                                    </div>
                                    <div>
                                        <label for="avg_delivery_time" class=" col-form-label">Grid Management Cost:-<span
                                                id="grid_cost">{{'€'. $objEnergy->grid_management }}</span></label>
                                        <input type="hidden" readonly class="form-control" id="grid_management"
                                            name="grid_management" placeholder="Grid Management Cost"
                                            value="{{ $objEnergy->grid_management }}" min="0">
                                    </div>
                                    <div>
                                        <label for="avg_delivery_time" class=" col-form-label">Feed In Tariff:-<span
                                                id="feed_in_tariffs">{{'€'. $objEnergy->feed_in_tariff }}</span></label>
                                        <input type="hidden" readonly class="form-control" id="feed_in_tariff"
                                            name="feed_in_tariff" placeholder="Solar Buy Back"
                                            value="{{ $objEnergy->feed_in_tariff }}" min="0">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div>
                                        <label for="" class="col-form-label">Tax On Electric:-
                                            {{'€'. $objEnergy->tax_on_electric }}</label>
                                        <input type="hidden" readonly value="{{ $objEnergy->tax_on_electric }}"
                                            name="tax_on_electric" id="tax_on_electric" class="form-control">
                                    </div>
                                    <div>
                                        <label for="" class="col-form-label">Tax on Gas:-
                                            {{'€'. $objEnergy->tax_on_gas }}</label>
                                        <input type="hidden" readonly value="{{ $objEnergy->tax_on_gas }}"
                                            name="tax_on_gas" id="tax_on_gas" class="form-control">
                                    </div>
                                    <div>
                                        <label for="" class="col-form-label">ODE On Electric:-
                                            {{'€'. $objEnergy->ode_on_electric }}</label>
                                        <input type="hidden" readonly value="{{ $objEnergy->ode_on_electric }}"
                                            name="ode_on_electric" id="ode_on_electric" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div>
                                        <label for="" class="col-form-label">ODE On Gas:-
                                            {{'€'. $objEnergy->ode_on_gas }}</label>
                                        <input type="hidden" readonly value="{{ $objEnergy->ode_on_gas }}"
                                            name="ode_on_gas" id="ode_on_gas" class="form-control">
                                    </div>
                                    <div>
                                        <label for="" class="col-form-label">VAT:-
                                            {{'€'. $objEnergy->vat }}</label>
                                        <input type="hidden" readonly value="{{ $objEnergy->vat }}" name="vat"
                                            id="vat" class="form-control">
                                    </div>
                                    <div>
                                        <label for="" class="col-form-label">Energy Tax Reduction/Year:-
                                            {{'€'. $globalEnergy->energy_tax_reduction }}</label>
                                        <input type="hidden" readonly value="{{ $globalEnergy->energy_tax_reduction }}"
                                            name="energy_tax_reduction" id="energy_tax_reduction" class="form-control">
                                    </div>

                                </div>
                            </div>

                            <div class="row mt-2">

                                <div class="col-md-3 mb-3">
                                    <label for="provider" class="col-form-label"><b>Contact Year</b>
                                    </label>
                                    <input type="text" class="form-control" readonly placeholder="Contract Year"
                                        value="{{ $objEnergy->contract_length }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="provider" class="col-form-label"><b>Power Cost Per Unit (€)</b>
                                    </label>
                                    <input type="number" class="form-control" name="power_cost_per_unit" step=".01"
                                        placeholder="Ex:-0.02" value="{{ $objEnergy->power_cost_per_unit }}">
                                    <span class="invalid-feedback">Please enter a valid value.</span>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="provider" class="col-form-label"><b>Gas Cost Per Unit (€)</b>
                                    </label>
                                    <input type="number" class="form-control" name="gas_cost_per_unit" step=".01"
                                        placeholder="Ex:-0.02" value="{{ $objEnergy->gas_cost_per_unit }}">
                                    <span class="invalid-feedback">Please enter a valid value.</span>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="provider" class="col-form-label"><b>Discount(%)</b>
                                    </label>
                                    <input type="number" class="form-control" name="discount" step=".01"
                                        placeholder="Ex:-20" value="{{ $objEnergy->discount }}">
                                    <span class="invalid-feedback">Please enter a valid value.</span>
                                </div>
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
            $('#submitBtn').click(function(e) {
                // Initialize the validation flag
                let isValid = true;

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

                // Iterate through all input[type="number"] fields and check for empty values
                $('input[type="number"]').each(function() {
                    if ($(this).val() === '' || isNaN($(this).val())) {
                        // If the field is empty, mark it as invalid and apply red border
                        $(this).addClass('is-invalid').removeClass('is-valid');
                        isValid = false; // Mark form as invalid
                    } else {
                        // If the field has a valid value, mark it as valid
                        $(this).removeClass('is-invalid');
                    }
                });

                // Prevent form submission if any field is invalid
                if (!isValid || $('.is-invalid').length > 0) {
                    e.preventDefault(); // Prevent form submission
                    alert("Please correct the invalid fields before submitting.");
                }
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
        });
    </script>
@endpush
