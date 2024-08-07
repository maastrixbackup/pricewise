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
                            href="{{ route('admin.providers.index') }}">Provider</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New Provider</h5>
                </div>
                <div class="card-body p-4">
                    <form id="featureFm" method="post" action="{{ route('admin.providers.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="input35" class=" col-form-label">Name</label>
                            <div class="">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="input_type" class=" col-form-label">Status</label>
                                <div class="">
                                    <select class="form-control" id="input_type" name="status">
                                        <option value="" disabled>Select</option>
                                        <option value="1">Active</option>
                                        <option value="0" selected>Inactive</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="input_type" class=" col-form-label">Category</label>
                                <div class="">
                                    <select class="form-control" id="category" name="category">
                                        <option value="">Select</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-8 mb-3">
                                <label for="payment_options" class=" col-form-label">Payment Options</label>
                                <div class="">
                                    <input type="text" class="form-control" id="payment_options" name="payment_options"
                                        placeholder="Payment Options">
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="input40" class="col col-form-label"><b>Provider Image </b></label>

                                <label for="upload_image">
                                    <img src="#" id="uploaded_image" class="img img-responsive img-circle"
                                        width="100" alt="Select image" />

                                    <div class="overlay">
                                        <div>Click to Change Profile Image</div>
                                    </div>
                                    <input type="file" name="image" class="image" id="upload_image"
                                        style="display:none" />
                                    <input type="hidden" name="cropped_image" id="cropped_image">

                                </label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">About</label>
                            <div class="">
                                <textarea name="about" id="about" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="meter_readings" class=" col-form-label">Meter Readings</label>
                                <div class="">
                                    <input type="text" class="form-control" id="meter_readings" name="meter_readings"
                                        placeholder="Meter Readings">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="annual_accounts" class=" col-form-label">Anual Accounts</label>
                                <div class="">
                                    <input type="text" class="form-control" id="annual_accounts"
                                        name="annual_accounts" placeholder="Anual Accounts">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="adjust_installments" class=" col-form-label">Adjust Installments</label>
                                <div class="">
                                    <input type="text" class="form-control" id="adjust_installments"
                                        name="adjust_installments" placeholder="Adjust Installments">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="view_consumption" class=" col-form-label">View Consumption</label>
                                <div class="">
                                    <input type="text" class="form-control" id="view_consumption"
                                        name="view_consumption" placeholder="View Consumption">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="rose_scheme" class=" col-form-label">Rose Scheme</label>

                                <div class="mb-3 add-scroll">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="rose_scheme"
                                            value="1">
                                        <label class="form-check-label" for="flexCheckDefault">Active</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <label class=" col-form-label"></label>
                            <div class="">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4" name="submit2">Submit</button>
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
    </script>
@endpush
