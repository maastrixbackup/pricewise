@extends('admin.layouts.app')
@section('title', 'Energise - Loan Create')
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
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.loans.index') }}">Loan
                            Products</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <form method="post" action="{{ route('admin.loans.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9 col-lg-9 col-12">
                <div class="card">
                    <div class="card-header px-4 py-3">
                        <h5 class="mb-0">Add Loan Product</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class=" mb-3">
                            <label for="input35" class=" col-form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Loan Title" value="{{ old('title') }}">
                            @error('title')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="">
                            <label for="input35" class=" col-form-label">Description</label>
                            <textarea class="form-control" name="description2" id="description23" placeholder="Product Description">{{ old('description2') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class=" mb-3">
                                    <label for="input37" class="col-form-label">Max Borrow Amount</label>
                                    <input type="number" class="form-control" id="max_price_borrow" name="max_price_borrow"
                                        placeholder="Max Borrow Amount" min="0" value="{{ old('max_price_borrow') }}"
                                        onKeyPress="if(this.value.length==10) return false;">
                                </div>
                                @error('max_price_borrow')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-12">
                                <div class=" mb-3">
                                    <label for="expected_amount" class="col-form-label">Excepted
                                        Amount</label>
                                    <input type="number" class="form-control" id="expected_amount" name="expected_amount"
                                        placeholder="Expected Amount" min="0"
                                        onKeyPress="if(this.value.length==10) return false;"
                                        value="{{ old('expected_amount') }}">
                                </div>
                                @error('expected_amount')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 col-12">
                                <div class=" mb-3">
                                    <label for="pin_codes" class="col-form-label">Area PIN Codes</label>
                                    <input type="text" class="form-control" id="pin_codes" name="pin_codes"
                                        placeholder="PIN codes with coma separated" value="{{ old('pin_codes') }}">
                                </div>
                                @error('pin_codes')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 col-12">
                                <div class="mb-3">
                                    <label for="provider" class="col-form-label"><b>Provider</b>
                                    </label>
                                    @php
                                        $providers = App\Models\Bank::all();
                                    @endphp
                                    <select id="provider" name="provider[]" class="select2 form-select" multiple>
                                        <option value="">Select</option>
                                        @if ($providers)
                                            @foreach ($providers as $provider)
                                                <option value="{{ $provider->id }}">{{ $provider->bank_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-3">
                                    <label for="purpose" class="col-form-label"><b>Spending Purpose</b>
                                    </label>
                                    @php
                                        $purposes = App\Models\SpendingPurpose::all();
                                    @endphp
                                    <select id="p_id" name="p_id" class="form-select">
                                        <option value="">Select</option>
                                        @if ($purposes)
                                            @foreach ($purposes as $purpose)
                                                <option value="{{ $purpose->id }}">{{ $purpose->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-3">
                                    <label for="loan_type_id" class="col-form-label"><b>Loan Type</b>
                                    </label>
                                    <div id="loan_type">
                                    </div>
                                    {{-- <select id="loan_type_id" name="loan_type_id[]" class="select2-mm mnm form-select"
                                        multiple>
                                        <option value="">Select</option>

                                    </select> --}}
                                </div>

                            </div>
                            <div class="col-md-6 col-12">
                                <div class=" mb-3">
                                    <label for="avg_delivery_time" class=" col-form-label">Average Aproval Time</label>
                                    <input type="number" class="form-control" id="avg_delivery_time"
                                        name="avg_delivery_time" placeholder="Average Delivery Time" min="0"
                                        value="{{ old('avg_delivery_time') }}">
                                </div>
                                @error('avg_delivery_time')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 col-12">
                                <div class=" mb-3">
                                    <label for="input40" class=" col-form-label">Rate of Intrest (<i
                                            class="fa fa-percent"></i>)
                                    </label>
                                    <input type="number" class="form-control" id="interest_rate"
                                        placeholder="Rate of Interest" name="interest_rate"
                                        onKeyPress="if(this.value.length==5) return false;"
                                        value="{{ old('interest_rate') }}">
                                </div>
                                @error('interest_rate')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="">
                            <label class=" col-form-label"></label>
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4" name="submit">Submit</button>
                                <button type="reset" class="btn btn-light px-4">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-12 col-lg-3">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="mb-3 form-group">
                            <label for="input40" class="col-form-label"><b>Publish Status</b>
                            </label>
                            <select id="status" name="status" class="select2 form-select">
                                <option value="1">Publish</option>
                                <option value="0" selected>Draft</option>

                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="product_type" class="col-form-label"><b>Product Type</b>
                            </label>
                            <select id="product_type" name="product_type" class="select2 form-select">
                                <option value="personal">Personal</option>
                                <option value="business">Business</option>
                                <option value="large-business">Large Business</option>

                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="product_type" class="col-form-label"><b>Service Type</b>
                            </label>
                            <select id="service_type" name="service_type" class="select2 form-select">
                                <option value="Zelfstandig">Zelfstandig</option>
                                <option value="Begeleiding">Begeleiding</option>

                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="col-form-label"><b>Category</b>
                            </label>

                            <select id="" name="category" class="select2 form-select" @readonly(true)>
                                <option value="" disabled>Select</option>
                                @if ($objCategory)
                                    @foreach ($objCategory as $val)
                                    @if ($val->name == 'Loans')
                                        <option value="{{ $val->id }}"
                                            {{ $val->name == 'Loans' ? 'selected' : '' }} >{{ $val->name }}</option>
                                            @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="sub_category" class="col-form-label"><b>Sub Category</b>
                            </label>

                            <select id="sub_category" name="sub_category" class="select2 form-select">
                                <option>Select</option>

                            </select>
                        </div> --}}
                        <label for="upload_image">
                            <img src="#" id="uploaded_image" class="img img-responsive img-circle" width="100"
                                alt="Select image" />

                            <div class="overlay">
                                <div>Click to Change Image</div>
                            </div>
                            <input type="file" name="image" class="image" id="upload_image"
                                style="display:none" />
                            <input type="hidden" name="cropped_image" id="cropped_image">

                        </label>



                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('description2', {
            allowedContent: true,
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#affiliate_name').on('change', function() {
                $("temp_old").hide();
                $("#temp").show();
            });

            $("#p_id").on('change', function() {
                var p_id = $(this).val(); // Get the selected p_id value

                $.ajax({
                    url: "{{ route('admin.purposes.index') }}", // Replace this with the actual URL to fetch
                    type: 'GET',
                    data: {
                        p_id: p_id
                    },
                    success: function(response) {
                        // console.log(response);
                        // return false;
                        // Clear existing options
                        $("#loan_type").html('');

                        // Populate subcategories dropdown with new options
                        $.each(response.data, function(index, loan_types) {
                            $("#loan_type").append(
                                '<div class="form-check form-check-inline">' +
                                    '<label class="form-check-label">' +
                                '<input class="form-check-input" type="checkbox" name="loan_type_id[]" value="' +
                                loan_types.id + '">' + loan_types
                                .loan_type + '</label>' +
                                '</div>'
                            );
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle errors here
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        $("#productForm").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
                $(element).closest('.form-group').addClass("has-success");
            },

            rules: {
                title: "required",
                description: "required",

            },
            messages: {
                title: "Title is missing",
                description: "Description is missing",
            },
            submitHandler: function(form) {
                var data = CKEDITOR.instances.description.getData();
                $("#description").val(data);
                var formData = new FormData(form);
                $.ajax({

                    url: form.action,
                    method: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        //success

                        if (data.status) {
                            location.href = data.redirect_location;
                        } else {
                            toastr.error(data.message.message, 'Already Exists!');
                        }
                    },
                    error: function(e) {
                        toastr.error('Something went wrong . Please try again later!!', '');
                    }
                });
                return false;
            }
        });

        // $("#title").keyup(function() {
        //     var title_val = $("#title").val();
        //     $("#link").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        // });
        // $("#title").keydown(function() {
        //     var title_val = $("#title").val();
        //     $("#link").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        // });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Choices(document.querySelector(".select2"), {
                removeItemButton: true
            });
        });
    </script>
@endpush
