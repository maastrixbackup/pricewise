@extends('admin.layouts.app')
@section('title','POPTelecom- Change Password')
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
            </ol>
        </nav>
    </div>

</div>
<!--end breadcrumb-->
<div class="card">
    <div class="card-body">

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <form id="passwordForm" method="post" action="{{route('admin.password-update',['id'=>$admin->id]) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Change Password</h5>
                        </div>
                        <div class="col-md-7 col-12">
                            <div class="card-body p-4">

                             
                                <div class="mb-3">
                                    <label for="input35" class="col-sm-3 col-form-label">New Password</label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="New Password">
                                </div>


                                <div class="mb-3">
                                    <label for="input35" class="col-sm-3 col-form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-8">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" id="submitBtn" class="btn btn-primary px-4">Submit</button>
                                    <button type="reset" class="btn btn-light px-4">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
</div>
@endsection
@push('scripts')

<script type="text/javascript">
    $("#passwordForm").validate({
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
            oldPassword: "required",
            newPassword: "required",
            confirmPassword: "required",
        },
        messages: {
            oldPassword: "Old Password is missing",
            newPassword: "New Password is missing",
            confirmPassword: "Confirm Password is missing",
        },
        submitHandler: function(form) {
            var formData = new FormData(this);
            $.ajax({
                url: form.action,
                method: "post",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    //success
                    $("#passwordForm").trigger("reset");
                    if (data.status) {
                        toastr.success(data.message.message, '');
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