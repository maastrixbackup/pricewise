@extends('admin.layouts.app')
@section('title','Pricewise- Features Edit')
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
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.features.index')}}">Feature</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Edit Feature</h5>
            </div>
            <div class="card-body p-4">
                <form id="categoryForm" method="post" action="{{route('admin.features.update',$objFeature->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <label for="input35" class=" col-form-label">Name</label>
                        <div class="">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{$objFeature->features}}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="input_type" class=" col-form-label">Input Type</label>
                        <div class="">
                            <select class="form-control" id="input_type" name="input_type">
                            <option value="">Select</option>
                            <option value="text" @if($objFeature->input_type == "text")selected @endif>Text</option>
                            <option value="checkbox" @if($objFeature->input_type == "checkbox")selected @endif>Checkbox</option>
                            <option value="textarea" @if($objFeature->input_type == "textarea")selected @endif>Textarea</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="input_type" class=" col-form-label">Category</label>
                        <div class="">
                            <select class="form-control" id="category" name="category">
                            <option value="">Select</option>
                            @foreach($categories as $category)
                            <option value="{{$category->id}}" @if($objFeature->category == $category->id)selected @endif>{{$category->name}}</option>
                            @endforeach
                            
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="sub_category" class="col-form-label"><b>Sub Category</b>
                        </label>
                        <!-- HTML -->
                        

                        <select id="sub_category" name="sub_category" class="select2 form-select">
                            <option value="{{ $objFeature->sub_category}}">{{ $objFeature->subCategory ? $objFeature->subCategory->name : 'Select' }}</option>
                            
                        </select>
                    </div>
                     <div class="row mb-3">
                        <label for="input_type" class=" col-form-label">Select Icon</label>
                        <div class="form-group">
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa {{$objFeature->icon}}"></i></span>
                            </div>
                            <select class="form-control selectpicker" data-live-search="true" name="icon" id="icon">
                                <option value="">Select</option>
                                @include('admin.layouts.icons')
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class=" col-form-label"></label>
                        <div class="">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>
                              
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
    $("#categoryForm").validate({
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
                method: "put",
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

</script>
@endpush