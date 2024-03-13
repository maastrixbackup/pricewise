@extends('admin.layouts.app')
@section('title','POPTelecom- Driver Create')
@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.drivers.index')}}">Driver</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<form id="productForm" method="post" action="{{route('admin.drivers.store')}}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New Driver</h5>
                </div>
                <div class="card-body p-4">
                    <div class=" mb-3">
                        <label for="input35" class=" col-form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                    </div>

                    <div class=" mb-3">
                        <label for="input35" class=" col-form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                    <div class=" mb-3">
                        <label for="input35" class=" col-form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone No.">
                    </div>

                    <div class="row mb-3">
                        <label for="input35" class="col-form-label">Address</label>
                        <div class="">
                            <textarea class="form-control" id="address" name="address" placeholder="Address ..." rows="3"></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="input40" class="col-sm-3 col-form-label">CV </label>
                        <input type="file" class="form-control" name="cv" id="cv" accept=".doc,.docx,.xml,.csv,.pdf,.xls,.xlsx" />
                    </div>

                    <div class=" mb-3">
                        <label for="input35" class=" col-form-label">Passport</label>
                        <input type="text" class="form-control" id="passport" name="passport" placeholder="Passport">
                    </div>

                    <div class=" mb-3">
                        <label for="input35" class=" col-form-label">Driving License</label>
                        <input type="text" class="form-control" id="driving_license" name="driving_license" placeholder="Driving License">
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-3">
                                <label for="input35" class=" col-form-label">Time Management :</label>
                            </div>
                            <div class="col-md-4 col-4">
                            <input type="text" class="form-control" id="to_time" name="to_time" placeholder="Start Time">
                            </div>
                            <div class="col-md-1 col-1">
                                <label for="input35" class=" col-form-label">To</label>
                            </div>
                            <div class="col-md-4 col-4">
                            <input type="text" class="form-control" id="from_time" name="from_time" placeholder="End Time">
                            </div>
                    </div>

                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="mb-3 add-scroll">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="own_vehicle" id="own_vehicle" value="1">
                                <label class="form-check-label" for="flexCheckDefault"><b>Do You have a Car?</b></label>
                            </div>
                        </div>

                        <div class="mb-3 model">
                            <!-- <label for="input35" class=" col-form-label">Model Name</label> -->
                            <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" placeholder="Vehicle Model">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="mb-3 add-scroll">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="black_suit" value="1">
                                <label class="form-check-label" for="flexCheckDefault"><b>Black Suit?</b></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="mb-3 add-scroll">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="booking_responsible" value="1">
                                <label class="form-check-label" for="flexCheckDefault"><b>Responsible to booking?</b></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="">
                        <label class=" col-form-label"></label>

                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4" name="submit2">Submit</button>
                            <button type="reset" class="btn btn-light px-4">Reset</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="col-md-3 col-12">
        <div class="card">
            <div class="card-body p-4">
                <div class="mb-3 form-group">
                    <label for="input40" class="col-form-label"><b>Publish Status</b>
                    </label>
                    <select id="status" name="status" class="select2 form-select">

                        <option value="1">Publish</option>
                        <option value="0">Draft</option>
                    </select>
                </div>


            </div>
        </div>
    </div>
    </div>

    </div>

</form>
@endsection
@push('scripts')
<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

<script>
    $(".model").hide();
    $("#own_vehicle").click(function() {
        if ($(this).is(":checked")) {
            $(".model").show();
        } else {
            $(".model").hide();
        }
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
            name: "required",
            email: "required",
            phone: "required",
            address: "required",
            passport: "required",
            driving_license: "required",

        },
        messages: {
            name: "Name is missing",
            email: "Email is missing",
            phone: "Phone No. is missing",
            address: "Address is missing",
            passport: "Passport is missing",
            driving_license: "Driving License is missing"
        },
        submitHandler: function(form) {
            // var value = CKEDITOR.instances['description'].getData()
            // $("#description").val(value);

            var formData = new FormData(this);
            $.ajax({

                url: form.action,
                method: "post",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    //success
                    // alert(data);
                    $("#productForm").trigger("reset");
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