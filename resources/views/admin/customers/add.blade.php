@extends('admin.layouts.app')
@section('title','Price Compare- Customers Create')
@section('content')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.customers.index')}}">Customers</a></li>
            </ol>
        </nav> 
    </div>
</div>
<!--end breadcrumb-->
<form id="customerForm" method="post" action="{{route('admin.customers.store')}}">
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Add New Customer</h5>
            </div>
            <div class="card-body p-4">
                
                    @csrf
                    <div class="row mb-3">
                        <label for="input35" class=" col-form-label">Name</label>
                        <div class="">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="input35" class="col-form-label">Email</label>
                        <div class="">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email Id">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="input35" class="col-form-label">Mobile No</label>
                        <div class="">
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile No" oninput="validateMobile(this)">

                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="country" class="col-sm-3 col-form-label">Country</label>
                        <div class="">
                        <select name="country" id="country" class="select2 form-select">
                            @foreach ($country as $val)
                            <option value="{{ $val->id }}">{{ $val->country_name }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="input35" class="col-form-label">Address</label>
                        <div class="">
                            <textarea class="form-control" id="address" name="address"></textarea>
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
                
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="input40" class="col-sm-3 col-form-label"><b>Status</b>
                    </label>

                   

                </div>
                <div class="mb-3">
                <label for="input40" class="col-sm-6 col-form-label"><b>Profile Picture </b></label>
                        
                        <label for="upload_image">
                                <img src="#" id="uploaded_image" class="img img-responsive img-circle" width="100" alt="Select image" />

                                <div class="overlay">
                                    <div>Click to Change Profile Picture</div>
                                </div>
                                <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                                <input type="hidden" name="cropped_image" id="cropped_image">

                            </label>
                </div>
                
                   

            </div>
        </div>
    </div>
</div>
</form>

@endsection
@push('scripts')
<!-- validation for letter and number in mobile number -->
<script>
function validateMobile(input) {
    // Remove non-numeric characters
    input.value = input.value.replace(/\D/g, '');
    
   // Ensure input length is exactly 10 digits
   if (input.value.length !== 10) {
        input.setCustomValidity('Mobile number must be exactly 10 digits');
    } else {
        input.setCustomValidity('');
    }
}
</script>

<!-- end -->
<script type="text/javascript">
    $("#customerForm").validate({
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
            mobile: "required",
        },
        messages: {
            name: "Name is missing",
            email: "Email is missing",
            mobile: "Mobile is missing",
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                method: "post",
                data: $(form).serialize(),
                success: function(data) {
                    //success
                    $("#customerForm").trigger("reset");
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