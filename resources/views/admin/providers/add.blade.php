@extends('admin.layouts.app')
@section('title', 'Pricewise- Provider Create')
@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.providers', config('constant.category.energy')) }}">Provider</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New Provider</h5>
                </div>
                @php
                    $catDetails = \App\Models\Category::find($c_id);
                @endphp
                <div class="card-body p-4">
                    <form id="featureFm" method="post" action="{{ route('admin.providers.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label for="input35" class=" col-form-label">Name</label>
                                <div class="">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Name" required value="{{ old('name') }}">
                                </div>
                                @error('name')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                                <input type="hidden" name="category" class="form-control" value="{{ $c_id }}">
                            </div>
                            {{-- <div class="col-md-4 mb-3">
                                <label for="input_type" class=" col-form-label">Category</label>
                                <div class="">
                                    <select class="form-control" id="category" name="category">
                                        <option value="">Select</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div> --}}

                            <div class="col-md-4 mb-3">
                                <label for="input35" class=" col-form-label">Fixed delivery Cost</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" id="basic-addon1">€</div>
                                    </div>
                                    <input type="number" class="form-control" id="fix_delivery" name="fix_delivery"
                                        placeholder="Fixed delivery Cost" step=".01" required
                                        value="{{ old('fix_delivery') }}">
                                </div>
                                @error('fix_delivery')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="input35" class=" col-form-label">Grid Management Cost</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" id="basic-addon1">€</div>
                                    </div>
                                    <input type="number" class="form-control" step=".01" id="grid_management"
                                        name="grid_management" placeholder="Grid Management Cost"
                                        value="{{ old('grid_management') }}" required>
                                </div>
                                @error('grid_management')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="input35" class=" col-form-label">Feed In Tariff (Solar Buy Back)</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" id="basic-addon1">€</div>
                                    </div>
                                    <input type="number" class="form-control" id="feed_in_tariff" name="feed_in_tariff"
                                        placeholder="Solar Buy Back" step=".01" value="{{ old('feed_in_tariff') }}">
                                </div>
                                @error('feed_in_tariff')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="input_type" class=" col-form-label">Status</label>
                                <div class="">
                                    <select class="form-control" id="input_type" name="status">
                                        <option value="" disabled>Select</option>
                                        <option value="1">Active</option>
                                        <option value="0" selected>Inactive</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="input40" class="col col-form-label"><b>Provider Image </b></label>

                                <div id="imagePreviewContainer"></div>
                                <img src="#" id="uploaded_image" class="img img-responsive img-circle"
                                    width="100" alt="Select image" />
                                <input type="file" name="image" class="image" id="p_image" accept="image/*" />
                                {{-- <label for="upload_image">
                                    <img src="#" id="uploaded_image" class="img img-responsive img-circle"
                                        width="100" alt="Select image" />

                                    <div class="overlay">
                                        <div>Click to Change Profile Image</div>
                                    </div>

                                    <input type="hidden" name="cropped_image" id="cropped_image">

                                </label> --}}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <div class="">
                                    <label for="input_type" class=" col-form-label">About</label>
                                    <textarea name="about" id="about" class="form-control" placeholder="About Provider..." rows="5"
                                        required>{{ old('about') }}</textarea>
                                </div>
                                @error('about')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="">
                                    <label for="input_type" class=" col-form-label">Discount Term & Conditions</label>
                                    <textarea name="discount" id="discount" class="form-control" placeholder="About Discount..." rows="5"
                                        required>{{ old('discount') }}</textarea>
                                </div>
                                @error('discount')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="mt-3">
                                    <label>
                                        <input type="checkbox" name="insight_app" value="1"> Usage Insight App
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mt-1">
                                    <label for="" class="form-label"> Payment Options</label>
                                    <input type="text" name="payment_options" placeholder="Payment Option"
                                        class="form-control" value="{{ old('payment_options') }}">
                                </div>
                                @error('payment_options')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="mt-1">
                                    <label for="" class="form-label"> Annual Acounts</label>
                                    <input type="text" name="annual_accounts" placeholder="Annual Accounts"
                                        class="form-control" value="{{ old('annual_accounts') }}">
                                </div>
                                @error('annual_accounts')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="mt-1">
                                    <label for="" class="form-label"> Meter Reading</label>
                                    <input type="text" name="meter_readings" placeholder="Meter Readings"
                                        class="form-control" value="{{ old('meter_readings') }}">
                                </div>
                                @error('meter_readings')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="mt-1">
                                    <label for="" class="form-label"> Adjust Installments</label>
                                    <input type="text" name="adjust_installments" placeholder="Adjust Installments"
                                        class="form-control" value="{{ old('adjust_installments') }}">
                                </div>
                                @error('adjust_installments')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="mt-1">
                                    <label for="" class="form-label"> View consumption</label>
                                    <input type="text" name="view_consumption" placeholder="View Consumtion"
                                        class="form-control" value="{{ old('view_consumption') }}">
                                </div>
                                @error('view_consumption')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <label class=" col-form-label"></label>
                            <div class="">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" id="submitBtn" class="btn btn-primary px-4"
                                        name="submit2">Submit</button>
                                    <button type="reset" class="btn btn-light px-4">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script type="text/javascript">
        $("#featureForm").validate({
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
                name: "required",
            },
            messages: {
                name: "Name is missing",
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    method: "post",
                    data: $(form).serialize(),
                    success: function(data) {
                        //success

                        if (data.status) {
                            location.href = data.redirect_location;
                        } else {
                            toastr.error(data.message.message, '');
                        }
                    },
                    error: function(e) {
                        toastr.error('Something went wrong . Please try again later!!', '');
                    }
                });
                return false;
            }
        });

        $('#p_image').on('change', function(event) {
            const files = event.target.files;
            const previewContainer = $('#imagePreviewContainer');

            // Clear any previous previews
            previewContainer.empty();

            $.each(files, function(index, file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        $('#uploaded_image').attr('src', e.target.result);
                        // const img = $('<img>').attr('src', e.target.result)
                        //     .css('max-width', '150px')
                        //     .css('margin', '10px');
                        // previewContainer.append(img);
                    };

                    reader.readAsDataURL(file);
                } else {
                    alert("Selected file is not an image.");
                }
            });
        });
        $(document).ready(function() {


            // Function to handle decimal restriction
            function restrictDecimal(inputField) {
                var value = inputField.val();
                if (value === '') {
                    inputField.removeClass('is-valid').addClass('is-invalid');
                } else {
                    inputField.removeClass('is-invalid');
                }
                if (value.indexOf('.') !== -1) {
                    var parts = value.split('.');
                    if (parts[1].length > 3) {
                        parts[1] = parts[1].substring(0, 3); // Restrict to three decimal places
                        inputField.val(parts[0] + '.' + parts[1]);
                    }
                }
            }

            function changeInput(inputs) {
                var inputv = inputs.val();
                if (inputv === '') {
                    inputs.removeClass('is-valid').addClass('is-invalid');
                } else {
                    inputs.removeClass('is-invalid');
                }
            }

            // Apply the decimal restriction to all relevant input fields on keyup
            $('input[type="number"]').on('input', function() {
                restrictDecimal($(this));
            });
            // Apply the decimal restriction to all relevant input fields on keyup
            $('input[type="text"]').on('input', function() {
                changeInput($(this));
            });

            // Validate all inputs on form submission
            $('#submitBtn').on('click', function(e) {
                var isValid = true;

                // Check each input field for validity
                $('input[type="number"]').each(function() {
                    var value = $(this).val();
                    // Skip validation for the field named 'feed_in_tariff'
                    if ($(this).attr('name') === 'feed_in_tariff') {
                        return true; // Continue to the next iteration
                    }
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
