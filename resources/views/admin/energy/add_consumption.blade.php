@extends('admin.layouts.app')
@section('title', 'PriceWise- Consumtion Create')
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
                            href="{{ route('admin.consumptions', config('constant.category.energy')) }}">Counsumption
                            Products</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <form id="productForm" method="post" action="{{ route('admin.consumptions.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class=" col-12">
                <div class="card">
                    <div class="card-header px-4 py-3">
                        <h5 class="mb-0">Add Consumptions Product</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="col-form-label"><b>House Type</b>
                                </label>

                                <select id="provider" name="house_type" class="select2 form-select" required>
                                    <option disabled selected>Select</option>
                                    @if ($houseData)
                                        @foreach ($houseData as $data)
                                            <option value="{{ $data->id }}"
                                                {{ in_array($data->id, $bArr) ? 'disabled' : '' }}>{{ $data->title }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="invalid-feedback">Please Select a valid value.</span>
                                <input type="hidden" value="{{ config('constant.category.energy') }}" class="form-control"
                                    name="category">
                            </div>

                        </div>

                        <div class="row mt-2">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>No. of Person</th>
                                        <th>Gas Supply</th>
                                        <th>Electric Supply</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <label for="year_one">
                                                <input type="hidden" checked name="no_of_person[]" id="year_one"
                                                    value="1">
                                                1
                                            </label>
                                        </td>
                                        <td><input type="number" disabled placeholder="" value="" readonly
                                                class="form-control" name="gas_supply[1]" id="gas_1">
                                        </td>
                                        <td><input type="number" disabled placeholder="" value="" readonly
                                                class="form-control" name="electric_supply[1]" id="electric_1">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="year_two">
                                                <input type="hidden" name="no_of_person[]" id="year_two" value="2"> 2
                                            </label>
                                        </td>
                                        <td><input type="number" disabled placeholder="" value="" readonly
                                                class="form-control" name="gas_supply[2]" id="gas_2">
                                        </td>
                                        <td><input type="number" disabled placeholder="" value="" readonly
                                                class="form-control" name="electric_supply[2]" id="electric_2">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="year_three">
                                                <input type="hidden" name="no_of_person[]" id="year_three" value="3">
                                                3
                                            </label>
                                        </td>
                                        <td><input type="number" disabled placeholder="" value="" readonly
                                                class="form-control" name="gas_supply[3]" id="gas_3">
                                        </td>
                                        <td><input type="number" disabled placeholder="" value="" readonly
                                                class="form-control" name="electric_supply[3]" id="electric_3">
                                        </td>

                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="year_four">
                                                <input type="hidden" name="no_of_person[]" id="year_four" value="4"> 4
                                            </label>
                                        </td>
                                        <td><input type="number" disabled placeholder="" value="" readonly
                                                class="form-control" name="gas_supply[4]" id="gas_4">
                                        </td>
                                        <td><input type="number" disabled placeholder="" value="" readonly
                                                class="form-control" name="electric_supply[4]" id="electric_4">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="year_five">
                                                <input type="hidden" name="no_of_person[]" id="year_five"
                                                    value="5"> 5
                                            </label>
                                        </td>
                                        <td><input type="number" disabled placeholder="" value="" readonly
                                                class="form-control" name="gas_supply[5]" id="gas_5">
                                        </td>
                                        <td><input type="number" disabled placeholder="" value="" readonly
                                                class="form-control" name="electric_supply[5]" id="electric_5">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                        <div class="">
                            <label class=" col-form-label"></label>
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4" id="submitBtn"
                                    name="submit2">Submit</button>
                                <button type="reset" class="btn btn-light px-4">Reset</button>
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
            // Enable or disable input fields based on provider selection
            $('#provider').on('change', function() {
                let providerSelected = $(this).val();
                if (providerSelected) {
                    // Enable input fields when a valid provider is selected
                    $('input[type="number"]').prop('readonly', false).prop('disabled', false);
                    $('#provider').removeClass('is-invalid').addClass('is-valid');
                } else {
                    // Disable and reset input fields if no provider is selected
                    $('input[type="number"]').prop('readonly', true).prop('disabled', true).val('');
                    $('#provider').removeClass('is-valid').addClass('is-invalid');
                }
            });


            // Validate inputs when the submit button is clicked
            $('#submitBtn').click(function(e) {
                // Initialize the validation flag
                let isValid = true;

                // Validate provider select field
                if (!$('#provider').val()) {
                    isValid = false;
                    $('#provider').addClass('is-invalid');
                } else {
                    $('#provider').removeClass('is-invalid').addClass('is-valid');
                }

                // Validate number inputs only if provider is selected
                $('input[type="number"]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid').addClass('is-valid');
                    }
                });

                // Prevent form submission if any field is invalid
                if (!isValid) {
                    e.preventDefault();
                    alert("Please correct the invalid fields before submitting.");
                }
            });
        });

        $('input[type="number"]').on('keyup', function() {
            let typeData = $(this).val();
            if (typeData) {
                $(this).removeClass('is-invalid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        });
    </script>
@endpush
