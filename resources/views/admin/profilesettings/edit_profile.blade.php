@extends('admin.layouts.app')
@section('title','Energise- Profile Edit')
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

<div class="container">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            @if ($admin->profileImage != null || $admin->profileImage != '')
                            <img src="{{asset('images/'.$admin->profileImage)}}" alt="image" id="pImage" alt="Admin" class="rounded-circle p-1" width="110">
                            @else
                            <img src="{{ asset('assets/images/Logo.png')}}" alt="Admin" class="rounded-circle p-1" width="110">
                            @endif

                            <div class="mt-3">
                                <h4>{{$admin->name ? $admin->name : 'N/A'}}</h4>
                                <p class="text-secondary mb-1">{{$admin->email ? $admin->email : 'N/A'}}</p>
                                <!-- <p class="text-muted font-size-sm">Bay Area, San Francisco, CA</p> -->
                                <!-- <button class="btn btn-primary">Follow</button>
												<button class="btn btn-outline-primary">Message</button> -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form id="profileForm" method="post" action="{{route('admin.profile-update',['id'=>$admin->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <div class="col-sm-3">
                                        <label>Profile Image</label>
                                    </div>
                                    <input type="file" class="form-control" name="image" id="image" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <div class="col-sm-3">
                                        <lable>Name</lable>
                                    </div>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="ex. John Doe" value="{{$admin ? $admin->name : ''}}">
                                </div>
                                <div class="mb-3">
                                    <div class="col-sm-3">
                                        <lable>Email</lable>
                                    </div>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="ex. john@gmail.com" value="{{$admin ? $admin->email : ''}}">
                                </div>
                                <div class="mb-3">
                                    <div class="col-sm-3">
                                        <lable>Contact No.</lable>
                                    </div>
                                    <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Contact No." value="{{$admin ? $admin->phone_no : ''}}">
                                </div>
                                <div class="mb-3">
                                    <div class="col-sm-3"></div>
                                    <button type="submit" id="submitBtn" class="btn btn-primary px-4">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
@push('scripts')

<script type="text/javascript">
    $("#profileForm").validate({
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
            phone_no: "required",
        },
        messages: {
            name: "Name is missing",
            email: "Email is missing",
            phone_no: "Contact No. is missing",
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
                    $("#profileForm").trigger("reset");
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