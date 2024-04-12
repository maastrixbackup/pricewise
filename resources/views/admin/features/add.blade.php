@extends('admin.layouts.app')
@section('title','Pricewise- Features Create')
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
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.features.index')}}">Features</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Add New Feature</h5>
            </div>
            <div class="card-body p-4">
                <form id="featureForm" method="post" action="{{route('admin.features.store')}}">
                    @csrf
                    <div class="row mb-3">
                        <label for="input35" class=" col-form-label">Name</label>
                        <div class="">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="input_type" class=" col-form-label">Input Type</label>
                        <div class="">
                            <select class="form-control" id="input_type" name="input_type">
                            <option value="">Select</option>
                            <option value="text">Text</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="textarea">Textarea</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="input_type" class=" col-form-label">Category</label>
                        <div class="">
                            <select class="form-control" id="category" name="category">
                            <option value="">Select</option>
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                            
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="sub_category" class="col-form-label">Sub Category
                        </label>
                        <div class="">
                        <select id="sub_category" name="sub_category" class="select2 form-control">
                            <option value="">Select</option>
                            
                        </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="parent" class="col-form-label">Parent Feature
                        </label>
                        <div class="">
                        <select id="parent" name="parent" class="select2 form-control">
                            <option value="">Select</option>
                            
                        </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="input_type" class=" col-form-label">Select Icon</label>
                        <div class="">
                        <select class="form-control selectpicker" data-live-search="true" name="icon" id="icon">
                            <option value="">Select</option>
                            @include('admin.layouts.icons')
                        </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                    <div class="col-md-6 col-12">
                        <!-- <label for="input35" class="col-form-label"><b>Top 3 Product</b></label> -->
                        <div class="mb-3 add-scroll">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_preferred" value="1" >
                                <label class="form-check-label" for="flexCheckDefault">Is Preferred</label>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <label class=" col-form-label"></label>
                        <div class="">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4" name="submit2">Submit</button>
                                <button type="reset" class="btn btn-light px-4">Reset</button>
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
    $("#featureForm").validate({
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
            $.ajax({
                url: form.action,
                method: "post",
                data: $(form).serialize(),
                success: function(data) {
                    //success
                    
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

    $(document).ready(function(){
        $("#category").on('change', function() {
            var cat_val = $(this).val(); // Get the selected category value

            // Make an AJAX call to fetch subcategories based on the selected category
            $.ajax({
                url: "{{route('admin.features.index')}}", // Replace this with the actual URL to fetch subcategories
                type: 'GET',
                data: {
                    category_id: cat_val
                },
                success: function(response) {
                    // Clear existing options
                    $("#parent").html('');
                    var options = '';
                    // Populate subcategories dropdown with new options
                    $.each(response.data, function(index, feature) {
                        options += '<option value="' + feature.id + '">' + feature.features + '</option>';
                    });
                    $("#parent").append('<option value="">Select</option>' + options);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Handle errors here
                }
            });
        });
    });
</script>
@endpush