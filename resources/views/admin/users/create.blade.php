@extends('admin.layouts.app')
@section('title','NoPlan- Users Create')
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
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.users.index')}}">Users</a></li>
            </ol>
        </nav>
    </div>

</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Add New User</h5>
            </div>
            <div class="card-body p-4">
                <form id="userForm" method="post" action="{{route('admin.users.store')}}">
                    @csrf
                    <div class="row mb-3">
                        <label for="input35" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="input37" class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="username" name="user_name" placeholder="Username">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="input40" class="col-sm-3 col-form-label">Email Address</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="input36" class="col-sm-3 col-form-label">Phone No</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone No">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="input38" class="col-sm-3 col-form-label">Choose Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Choose Password">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="input38" class="col-sm-3 col-form-label">Confirm Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="roles" class="col-sm-3 col-form-label">Role & Permission</label>
                        <div class="col-sm-9">
                        <select name="roles[]" id="roles" class="select2 form-select">
                            @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
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
    $("#userForm").validate({
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
            user_name: "required",
            email: {
                required: true,
                email: true,
            },
            phone: "required",
            password: {
                required: true,
                minlength: 5,
            },
            confirm_password: {
                minlength: 5,
                equalTo: "#password"
            }
        },
        messages: {
            name: "Name is missing",
            user_name: "UserName is missing",
            email: {
                required: "Email is required",
                email: "Enter Valid Email",
            },
            phone: "Phone no is missing",
            password: {
                required: "Password is required",
                minlength: "Enter minimum 5 characters",
            },
            confirm_password: {
                required: "Confirm Password is required",
                minlength: "Enter minimum 5 characters",
            },
        },
        submitHandler: function(form) {


            $.ajax({
                url: form.action,
                method: "post",
                data: $(form).serialize(),
                success: function(data) {
                    //success
                    $("#userForm").trigger("reset");
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