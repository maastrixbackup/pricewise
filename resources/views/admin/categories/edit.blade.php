@extends('admin.layouts.app')
@section('title','POPTelecom- Driver Edit')
@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.categories.index')}}">Driver</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<form id="productForm" method="post" action="{{route('admin.categories.update',$objCategory->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Edit Driver</h5>
                </div>
                <div class="card-body p-4">
                    <div class="card-body p-4">
                        <div class=" mb-3">
                            <label for="input35" class=" col-form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{$objCategory->name}}">
                        </div>

                        

                        <div class="row mb-3">
                            <div class="">
                                <label class=" col-form-label"></label>

                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        
    </div>

</form>
@endsection
@push('scripts')
<script>
    // Get relevant element
    checkBox = document.getElementById('own_vehicle');
    // Check if the element is selected/checked
    if (checkBox.checked) {
        // Respond to the result
        $(".model").show();
    } else {
        $(".model").hide();
    }

    $("#own_vehicle").click(function() {
        if ($(this).is(":checked")) {
            $(".model").show();
        } else {
            $(".model").hide();
        }
    });
</script>
<script type="text/javascript">
    $("#productForm").validate({
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
            

        },
        messages: {
            name: "Name is missing",
            
        },
        submitHandler: function(form) {

            var formData = new FormData(form);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: form.action,
                method: "post",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    //success
                    $("#productForm").trigger("reset");
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