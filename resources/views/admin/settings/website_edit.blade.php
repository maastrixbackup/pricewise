@extends('admin.layouts.app')
@section('title','POPTelecom- Website Setting')
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
<div class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Website Setting</h5>
            </div>
            <div class="card-body p-4">
                <form id="websiteForm" method="post" action="{{route('admin.website-store')}}">
                    @csrf
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Website Description</label>
                        <textarea type="text" class="form-control" id="description" name="description" placeholder="Description.." > {{$website->description}}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Address</label>
                        <textarea type="text" class="form-control" id="address" name="address" placeholder="Address">{{$website->address}}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone No." value="{{$website->phone}}">
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{$website->email}}">
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Facebook</label>
                        <input type="text" class="form-control" id="facebook" name="facebook" placeholder="Facebook" value="{{$website->facebook}}">
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Twitter</label>
                        <input type="text" class="form-control" id="twitter" name="twitter" placeholder="Twitter" value="{{$website->twitter}}">
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Instagram</label>
                        <input type="text" class="form-control" id="instagram" name="instagram" placeholder="Instagram" value="{{$website->instagram}}">
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">LinkedIn</label>
                        <input type="text" class="form-control" id="linkedin" name="linkedin" placeholder="LinkedIn" value="{{$website->linkedin}}">
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Tiktok</label>
                        <input type="text" class="form-control" id="Tiktok" name="tiktok" placeholder="Tiktok" value="{{$website->tiktok}}">
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">YouTube</label>
                        <input type="text" class="form-control" id="YouTube" name="youTube" placeholder="YouTube" value="{{$website->youTube}}">
                    </div>

                    <label class="col-form-label"></label>

                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    //CKEDITOR.replace('description', {
   // allowedContent: true
//});

    $("#websiteForm").validate({
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
            description: "required",
            address: "required",
        },
        messages: {
            description: "Description is missing",
            address: "Address is missing",
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                method: "post",
                data: $(form).serialize(),
                success: function(data) {
                    //success
                    $("#websiteForm").trigger("reset");
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