@extends('admin.layouts.app')
@section('title','POPTelecom- MailChimp Add Subscriber')
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
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.newsletter-template')}}">Newsletter</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-12">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Edit Newsletter</h5>
            </div>
            <div class="card-body p-4">
                <form id="newsletterForm" method="post" action="{{route('admin.update-newsletter',$newsletter->id)}}">
                    @csrf
                   
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Description</label>
                        <textarea type="text" class="form-control" id="description" name="description" value="{{json_decode($newsletter->desc)->description}}" rows="3">{{json_decode($newsletter->desc)->description}}</textarea>
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
<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description');
</script>
<script type="text/javascript">
    $("#newsletterForm").validate({
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
        },
        messages: {
            description: "Content is missing",
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                method: "post",
                data: $(form).serialize(),
                success: function(data) {
                    //success
                    $("#newsletterForm").trigger("reset");
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