@extends('admin.layouts.app')
@section('title', 'PriceWise- Energy Create')
@section('content')
    <style type="text/css">
        .form-check-box {
            display: flex;
            align-items: center;
        }

        .form-check-pr label {
            position: relative;
            cursor: pointer;
        }

        .form-check-pr input {
            padding: 0;
            height: initial;
            width: initial;
            margin-bottom: 0;
            display: none;
            cursor: pointer;
        }

        .form-check-pr label:before {
            content: '';
            -webkit-appearance: none;
            background-color: transparent;
            border: 2px solid #fa9f1d;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05);
            padding: 10px;
            display: inline-block;
            position: relative;
            vertical-align: middle;
            cursor: pointer;
            margin-right: 5px;
        }

        .form-check-pr input:checked+label:after {
            content: '';
            display: block;
            position: absolute;
            top: 7px;
            left: 9px;
            width: 6px;
            height: 14px;
            border: solid #0079bf;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
    </style>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.dashboard') }}">Dashboard</a></li>

                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <form id="productForm" method="post" action="{{ route('admin.global-setting-store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class=" col-12">
                <div class="card">
                    <div class="card-header px-4 py-3">
                        <h5 class="mb-0">Global Setting</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label for="tax_on_electric" class="col-form-label"> Tax on Electric Per Unit</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">€</span>
                                        </div>
                                        <input type="number" step="0.001" required class="form-control"
                                            name="tax_on_electric" id="tax_on_electric" placeholder="Per Unit Cost"
                                            value="{{ $globalEnergy->tax_on_electric ?? '' }}" min="0">
                                        <span class="invalid-feedback">Please enter a valid value.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label for="tax_on_gas" class="col-form-label">Tax on Gas Per Unit</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">€</span>
                                        </div>
                                        <input type="number" step="0.001" required class="form-control" id="tax_on_gas"
                                            name="tax_on_gas" placeholder="Per Unit Cost"
                                            value="{{ $globalEnergy->tax_on_gas ?? '' }}" min="0">
                                        <span class="invalid-feedback">Please enter a valid value.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label for="ode_on_electric" class="col-form-label"> ODE on Electric Per Unit</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">€</span>
                                        </div>
                                        <input type="number" step="0.001" required class="form-control"
                                            name="ode_on_electric" id="ode_on_electric" placeholder="Per Unit Cost"
                                            value="{{ $globalEnergy->ode_on_electric ?? '' }}" min="0">
                                        <span class="invalid-feedback">Please enter a valid value.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label for="ode_on_gas" class="col-form-label">ODE on Gas Per Unit</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">€</span>
                                        </div>
                                        <input type="number" step="0.001" required class="form-control" id="ode_on_gas"
                                            name="ode_on_gas" placeholder="Per Unit Cost"
                                            value="{{ $globalEnergy->ode_on_gas ?? '' }}" min="0">
                                        <span class="invalid-feedback">Please enter a valid value.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label for="vat" class="col-form-label">Energy Tax Reduction/Year</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">€</span>
                                        </div>
                                        <input type="number" step="0.001" required class="form-control"
                                            id="energy_tax_reduction" name="energy_tax_reduction"
                                            placeholder="Energy Tax Reduction"
                                            value="{{ $globalEnergy->energy_tax_reduction ?? '' }}" min="0">
                                        <span class="invalid-feedback">Please enter a valid value.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label for="vat" class="col-form-label">VAT</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">€</span>
                                        </div>
                                        <input type="number" step="0.001" required class="form-control"
                                            id="vat" name="vat" placeholder="VAT"
                                            value="{{ $globalEnergy->vat ?? '' }}" min="0">
                                        <span class="invalid-feedback">Please enter a valid value.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label for="vat" class="col-form-label">Electric Consumption</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1" style="padding: 11px 11px;"><i
                                                    class="fa-solid fa-bolt-lightning"></i></span>
                                        </div>
                                        <input type="number" step="0.001" required class="form-control"
                                            id="electric_consume" name="electric_consume" placeholder="Electric Consume"
                                            value="{{ $globalEnergy->electric_consume ?? '' }}" >
                                        <span class="invalid-feedback">Please enter a valid value.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label for="vat" class="col-form-label">Gas Consumption</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1" style="padding: 11px 11px;"><i
                                                    class="fa-solid fa-gas-pump"></i></span>
                                        </div>
                                        <input type="number" step="0.001" required class="form-control"
                                            id="gas_consume" name="gas_consume" placeholder="Gas Consume"
                                            value="{{ $globalEnergy->gas_consume ?? '' }}">
                                        <span class="invalid-feedback">Please enter a valid value.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <label class=" col-form-label"></label>
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4" id="submitBtn"
                                    name="submit2">Update</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Function to handle decimal restriction
            function restrictDecimal(inputField) {
                var value = inputField.val();
                if (value === '') {
                    inputField.removeClass('is-valid').addClass('is-invalid');
                } else {
                    inputField.removeClass('is-invalid').addClass('is-valid');
                }
                if (value.indexOf('.') !== -1) {
                    var parts = value.split('.');
                    if (parts[1].length > 3) {
                        parts[1] = parts[1].substring(0, 3); // Restrict to three decimal places
                        inputField.val(parts[0] + '.' + parts[1]);
                    }
                }
            }

            // Apply the decimal restriction to all relevant input fields on keyup
            $('input[type="number"]').on('input', function() {
                restrictDecimal($(this));
            });

            // Validate all inputs on form submission
            $('#submitBtn').on('click', function(e) {
                var isValid = true;

                // Check each input field for validity
                $('input[type="number"]').each(function() {
                    var value = $(this).val();

                    if (value === "" || value < 0 || isNaN(value)) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // If any input is invalid, prevent form submission
                if (!isValid) {
                    e.preventDefault();
                    alert("Please enter valid values in all fields.");
                }
            });
        });
    </script>
@endpush
