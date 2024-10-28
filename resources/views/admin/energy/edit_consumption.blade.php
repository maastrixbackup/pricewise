@extends('admin.layouts.app')
@section('title', 'PriceWise- Consumtion Edit')
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
    <form id="productForm" method="post" action="{{ route('admin.consumptions.update', $cData->id) }}"
        enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class=" col-12">
                <div class="card">
                    <div class="card-header px-4 py-3">
                        <h5 class="mb-0">Edit Consumptions Product</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="col-form-label">House Type
                                </label>
                                <input type="hidden" name="house_type" id="" value="{{ $cData->house_type }}">
                                @php
                                    $houseName = \App\Models\HouseType::find($cData->house_type);
                                @endphp
                                <input type="text" id="" readonly value="{{ $houseName->title }}"
                                    class="form-control">
                                <span class="invalid-feedback">Please Select a valid value.</span>
                                <input type="hidden" value="{{ $cData->cat_id }}" class="form-control" name="category">
                            </div>

                            {{-- <div class="col-md-6 mb-3">
                                <label class="col-form-label">No. of Person</label>
                                <input type="text" class="form-control" name="no_of_person" readonly
                                    value="{{ $cData->no_of_person }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="col-form-label">Gas Supply</label>
                                <input type="number" class="form-control" name="gas_supply"
                                    value="{{ $cData->gas_supply }}">

                                <span class="invalid-feedback">Please Enter valid value.</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="col-form-label">Electric Supply</label>
                                <input type="number" class="form-control" name="electric_supply"
                                    value="{{ $cData->electric_supply }}">

                                <span class="invalid-feedback">Please Enter valid value.</span>
                            </div> --}}

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
                                        <td>
                                            <input type="number" placeholder="" value="{{ $bArr['gas'][1] }}"
                                                class="form-control" name="gas_supply[1]" id="gas_1">

                                                <input type="hidden" placeholder="" value="{{ $bArr['id'][1] }}"
                                                class="form-control" name="ids[1]" >
                                        </td>
                                        <td><input type="number" placeholder="" value="{{ $bArr['electric'][1] }}"
                                                class="form-control" name="electric_supply[1]" id="electric_1">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="year_two">
                                                <input type="hidden" name="no_of_person[]" id="year_two" value="2"> 2
                                            </label>
                                        </td>
                                        <td><input type="number" placeholder="" value="{{ $bArr['gas'][2] }}"
                                                class="form-control" name="gas_supply[2]" id="gas_2">

                                                <input type="hidden" placeholder="" value="{{ $bArr['id'][2] }}"
                                                class="form-control" name="ids[2]" >
                                        </td>
                                        <td><input type="number" placeholder="" value="{{ $bArr['electric'][2] }}"
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
                                        <td><input type="number" placeholder="" value="{{ $bArr['gas'][3] }}"
                                                class="form-control" name="gas_supply[3]" id="gas_3">
                                                <input type="hidden" placeholder="" value="{{ $bArr['id'][3] }}"
                                                class="form-control" name="ids[3]" >
                                        </td>
                                        <td><input type="number" placeholder="" value="{{ $bArr['electric'][3] }}"
                                                class="form-control" name="electric_supply[3]" id="electric_3">
                                        </td>

                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="year_four">
                                                <input type="hidden" name="no_of_person[]" id="year_four" value="4"> 4
                                            </label>
                                        </td>
                                        <td><input type="number" placeholder="" value="{{ $bArr['gas'][4] }}"
                                                class="form-control" name="gas_supply[4]" id="gas_4">
                                                <input type="hidden" placeholder="" value="{{ $bArr['id'][4] }}"
                                                class="form-control" name="ids[4]" >
                                        </td>
                                        <td><input type="number" placeholder="" value="{{ $bArr['electric'][4] }}"
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
                                        <td><input type="number" placeholder="" value="{{ $bArr['gas'][5] }}"
                                                class="form-control" name="gas_supply[5]" id="gas_5">

                                                <input type="hidden" placeholder="" value="{{ $bArr['id'][5] }}"
                                                class="form-control" name="ids[5]" >
                                        </td>
                                        <td><input type="number" placeholder="" value="{{ $bArr['electric'][5] }}"
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
    <script>
        $(document).ready(function() {
            $('input[type="number"]').on('keyup', function() {
                let typeData = $(this).val();
                if (typeData) {
                    $(this).removeClass('is-invalid');
                } else {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                }
            });

            // Validate inputs when the submit button is clicked
            $('#submitBtn').click(function(e) {
                // Initialize the validation flag
                let isValid = true;

                // Validate number inputs only if provider is selected
                $('input[type="number"]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Prevent form submission if any field is invalid
                if (!isValid) {
                    e.preventDefault();
                    alert("Please correct the invalid fields before submitting.");
                }
            });
        });
    </script>
@endpush
