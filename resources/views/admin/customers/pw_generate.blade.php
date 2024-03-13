@extends('admin.layouts.app')
@section('title','POPTelecom- Customers Password Generate')
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
<div class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Password Generate</h5>
            </div>
            <div class="card-body p-4">
                <form id="customerForm" method="POST" action="{{route('admin.customers.pw-update',$objUser->id)}}">
                    @csrf
                    <div class="row mb-3">
                        <label for="input35" class=" col-form-label">Password</label>
                        <div class="">
                            <input type="text" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                    </div>
                    
                    <div class="row">
                        <label class=" col-form-label"></label>
                        <div class="">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button id="btnsubmit" type="submit" class="btn btn-primary px-4" name="submit2">Update & Send Mail</button>
                                <button id="loading" style="display:none" class="btn btn-primary" type="button" disabled=""> <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
									Loading...</button>
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
            password: "required"
        },
        messages: {
            password: "Password is missing"
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                method: "post",
                data: $(form).serialize(),
                beforeSend: function(){$( "#loading" ).show();$( "#btnsubmit" ).hide();},
                success: function(data) {
                    //success
                    $("#customerForm").trigger("reset");
                    if (data.status) {
                        $( "#loading" ).hide();$( "#btnsubmit" ).show();
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