@extends('admin.layouts.app')
@section('title', 'Price Compare- SMTP Setting')
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
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">SMTP Setting</h5>
                </div>
                <div class="card-body p-4">
                    <form id="mailSetForm" method="post" action="{{ route('admin.smtp-store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="host" class="col-form-label">Host</label>
                            <input class="form-control" type="text" name="mail_host" value="{{ $smtpSettings['host'] }}">
                        </div>

                        <div class="mb-3">
                            <label for="port" class="col-form-label">Port</label>
                            <input class="form-control" type="text" name="mail_port" value="{{ $smtpSettings['port'] }}">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="col-form-label">Username</label>
                            <input class="form-control" type="text" name="mail_username"
                                value="{{ $smtpSettings['username'] }}">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="col-form-label">Password</label>
                            <input class="form-control" type="password" name="mail_password"
                                value="{{ $smtpSettings['password'] }}">
                        </div>
                        <div class="mb-3">
                            <label for="encryption" class="col-form-label">Encryption</label>
                            <input class="form-control" type="text" name="mail_encryption"
                                value="{{ $smtpSettings['encryption'] }}">
                        </div>
                        <div class="mb-3">
                            <label for="from_address" class="col-form-label">From Address</label>
                            <input class="form-control" type="text" name="mail_from_address"
                                value="{{ $smtpSettings['from_address'] }}">
                        </div>
                        <div class="mb-3">
                            <label for="from_name" class="col-form-label">From Name</label>
                            <input class="form-control" type="text" name="mail_from_name"
                                value="{{ $smtpSettings['from_name'] }}">
                        </div>
                        <label class="col-form-label"></label>

                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4" name="submit2"
                                value="Update">Update</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $("#mailSetForm").validate({
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
                mail_host: "required",
                mail_port: "required",
            },
            messages: {
                mail_host: "Host is missing",
                mail_port: "Port is missing",
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    method: "post",
                    data: $(form).serialize(),
                    success: function(data) {
                        //success
                        //$("#mailchimpForm").trigger("reset");
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
