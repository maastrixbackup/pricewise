@extends('admin.layouts.app')
@section('title', 'Pricewise- Operater Edit')
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
                            href="{{ route('admin.grid-operater.index') }}">Grid Operaters</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Edit Operaters</h5>
                </div>
                <div class="card-body p-4">
                    <form id="featureFmI" method="post"
                        action="{{ route('admin.grid-operater.update', $gridOperater->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="input35" class="col-md-6 col-form-label">Postal Code <sup
                                    class="text-danger">*</sup></label>
                            <div class="col-md-6 mb-3">
                                @php
                                    $postCodes = json_decode($gridOperater->postal_code, true);
                                @endphp
                                <select name="postal_code[]" class="form-select" id="postal_code" multiple>
                                    <option value="" disabled>Select Post Code...</option>
                                    @foreach ($postalCodes as $val)
                                        <option value="{{ $val->post_code }}"
                                            {{ $postCodes && in_array($val->post_code, $postCodes) ? 'selected' : '' }}>
                                            {{ $val->post_code }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('postal_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input35" class="col-md-6 col-form-label">Name <sup
                                    class="text-danger">*</sup></label>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" id="op_name" name="op_name" placeholder="Name"
                                    value="{{ $gridOperater->operater_name }}">
                                @error('op_name')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input35" class="col-md-6 col-form-label">Transport Fee Current (Per Year) <sup
                                    class="text-danger">*</sup></label>
                            <div class="col-md-6 mb-3">
                                <input type="number" step="0.00001" class="form-control" id="current_transport_fee"
                                    name="current_transport_fee" placeholder="Transport Fee Current"
                                    value="{{ $gridOperater->current_transport_fee }}">
                                @error('current_transport_fee')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input35" class="col-md-6 col-form-label">Transport Fee Gas (Per Year) <sup
                                    class="text-danger">*</sup></label>
                            <div class="col-md-6 mb-3">
                                <input type="number" step="0.00001" class="form-control" id="gas_transport_fee"
                                    name="gas_transport_fee" placeholder="Transport Fee Gas"
                                    value="{{ $gridOperater->gas_transport_fee }}">
                                @error('gas_transport_fee')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input35" class="col-md-6 col-form-label">Current Measurment Tariff (Per Year) <sup
                                    class="text-danger">*</sup></label>
                            <div class="col-md-6 mb-3">
                                <input type="number" step="0.00001" class="form-control" id="measurment_tariff"
                                    name="measurment_tariff" placeholder="Current measurment Tariff"
                                    value="{{ $gridOperater->measurment_tariff ?? '' }}">
                                @error('measurment_tariff')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input40" class="col-md-6 col-form-label"><b>Operater Image </b></label>
                            <div class="col-md-6 mb-3">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <input type="file" name="image" class="image" id="p_image"
                                            accept="image/*" />
                                    </div>
                                    <div class="col-sm-5">
                                        <div id="imagePreviewContainer"></div>
                                        <img src="{{ asset('storage/images/operaters/' . $gridOperater->image) }}"
                                            id="uploaded_image" class="img img-responsive img-circle" width="100%"
                                            alt="Select image" />
                                    </div>
                                </div>
                                @error('image')
                                    <div class="alert py-1 alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <label class=" col-form-label"></label>
                            <div class="">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" id="submitBtn1" class="btn btn-primary px-4"
                                        name="submit2">Update</button>
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
            new Choices(document.querySelector("#postal_code"), {
                removeItemButton: true
            });

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
                    if (parts[1].length > 5) {
                        parts[1] = parts[1].substring(0, 5); // Restrict to five decimal places
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
