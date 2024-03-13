@extends('admin.layouts.app')
@section('title','POPTelecom- MailChimp Setting')
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
                <h5 class="mb-0">Mailchimp Setting</h5>
            </div>
            <div class="card-body p-4">
                <form id="mailchimpForm" method="post" action="{{route('admin.mailchimp-store')}}">
                    @csrf
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Key</label>
                        <input type="text" class="form-control" id="mailchimp_key" name="mailchimp_key" placeholder="Your Key" value="{{$mailchimp ? $mailchimp->mailchimp_key : ''}}">
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">List ID</label>
                        <input type="text" class="form-control" id="listId" name="listId" placeholder="Your List ID" value="{{$mailchimp ? $mailchimp->listId : ''}}">
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
<script type="text/javascript">
    $("#mailchimpForm").validate({
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
            mailchimp_key: "required",
            listId: "required",
        },
        messages: {
            mailchimp_key: "Key is missing",
            listId: "List ID is missing",
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                method: "post",
                data: $(form).serialize(),
                success: function(data) {
                    //success
                    $("#mailchimpForm").trigger("reset");
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