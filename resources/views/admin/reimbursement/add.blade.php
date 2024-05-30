@extends('admin.layouts.app')
@section('title','Energise - Reimbursement Create')
@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.categories.index')}}">Reimbursements</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<form id="productForm" method="post" action="{{route('admin.reimbursement.store')}}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New Reimbursement</h5>
                </div>
                <div class="card-body p-4">
                    <div class=" mb-3">
                        <label for="input35" class=" col-form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                    </div>
                    <div class=" mb-3">
                        <label for="parent" class=" col-form-label">Parent Reimbursement</label>
                        <select class="form-control selectpicker" data-live-search="true" name="parent" id="parent">
                                <option value="">Select</option>
                                @foreach($parents as $parent)
                                <option value="{{$parent->id}}">{{$parent->name}}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class=" mb-3">
                        <label for="sub_category" class=" col-form-label">Insurance Category</label>
                        <select class="form-control selectpicker" data-live-search="true" name="sub_category" id="sub_category">
                                <option value="">Select</option>
                                @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class=" mb-3">
                        <label for="parent" class=" col-form-label">Reimbursement Description</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Description"></textarea>
                    </div>
                    
                <div class="mb-3">
                        <label for="input40" class="col-sm-6 col-form-label"><b>Image </b></label>
                        
                        <label for="upload_image">
                                <img src="#" id="uploaded_image" class="img img-responsive img-circle" width="100" alt="Select image" />

                                <div class="overlay">
                                    <div>Click to Change Image</div>
                                </div>
                                <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                                <input type="hidden" name="cropped_image" id="cropped_image">

                            </label>
                </div>

                <div class="row mb-3">
                    <div class="">
                        <label class=" col-form-label"></label>

                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4" name="submit2">Submit</button>
                            <button type="reset" class="btn btn-light px-4">Reset</button>
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
<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

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
            // var value = CKEDITOR.instances['description'].getData()
            // $("#description").val(value);

            var formData = new FormData(this);
            $.ajax({

                url: form.action,
                method: "post",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    //success
                    // alert(data);
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