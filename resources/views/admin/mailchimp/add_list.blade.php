@extends('admin.layouts.app')
@section('title','POPTelecom- MailChimp Add Contact List')
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
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.subscribers-list')}}">MailChimp</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Add Contact List</h5>
            </div>
            <div class="card-body p-4">
                <form id="subscriberForm" method="post" action="{{route('admin.store-contact-list')}}">
                    @csrf
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Company</label>
                        <input type="text" class="form-control" id="company" name="company" placeholder="Company">
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" placeholder="Address ..." rows="3"></textarea>

                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="City">
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country" placeholder="Country">
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">From Name</label>
                        <input type="text" class="form-control" id="from_name" name="from_name" placeholder="From Name">
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">From Email</label>
                        <input type="email" class="form-control" id="from_email" name="from_email" placeholder="From Email">
                    </div>
                    <label class="col-form-label"></label>
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4" name="submit2">Submit</button>
                        <button type="reset" class="btn btn-light px-4">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $("#subscriberForm").validate({
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
            fname: "required",
            lname: "required",
            email_id: "required",
            address: "required",
            city: "required",
            state: "required",
            zip: "required",
        },
        messages: {
            fname: "First Name is missing",
            lname: "Last Name is missing",
            email_id: "Email ID is missing",
            address: "Address is missing",
            city: "City is missing",
            state: "State is missing",
            zip: "ZIP is missing",
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                method: "post",
                data: $(form).serialize(),
                success: function(data) {
                    //success
                    $("#subscriberForm").trigger("reset");
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