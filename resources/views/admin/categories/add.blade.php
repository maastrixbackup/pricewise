@extends('admin.layouts.app')
@section('title', 'POPTelecom- Driver Create')
@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.categories.index') }}">Categories</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <form id="productForm" method="post" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9 col-12">
                <div class="card">
                    <div class="card-header px-4 py-3">
                        <h5 class="mb-0">Add New Category</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class=" mb-3">
                            <label for="input35" class=" col-form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        </div>

                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Url<sup class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="slug" id="slug"
                                    value="{{ old('slug') }}" placeholder="Url" readonly>
                                @error('slug')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Select Icon</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="" id="fa_icon"></i></span>
                                    </div>
                                    <select class="form-control selectpicker" data-live-search="true" name="icon"
                                        id="icon">
                                        <option value="">Select</option>
                                        @include('admin.layouts.icons')
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="input40" class="col-sm-6 col-form-label"><b>Category Image </b></label>

                            <label for="upload_image">
                                <img src="#" id="uploaded_image" class="img img-responsive img-circle" width="100"
                                    alt="Select image" />

                                <div class="overlay">
                                    <div>Click to Change Image</div>
                                </div>
                                <input type="file" name="image" class="image" id="upload_image"
                                    style="display:none" />
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
    <script>
        $("#name").keyup(function() {
            var name_val = $("#name").val();
            $("#slug").val(name_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
        $("#name").keydown(function() {
            var name_val = $("#name").val();
            $("#slug").val(name_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
    </script>
@endpush
