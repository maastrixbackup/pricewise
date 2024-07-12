@extends('admin.layouts.app')
@section('title', 'Pricewise- Create Insurance Coverage')
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
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.insurance-coverages.index') }}">Insurance Coverages</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Edit New Insurance Coverage</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.insurance-coverages.update',$coverage->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Coverage Name<sup class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text"  class="form-control" name="name" value="{{$coverage->name}}" placeholder="Coverage Name">
                                @error('name')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Description</label>
                            <div class="">
                                <textarea name="description" class="form-control" cols="30" rows="5">{{ $coverage->description}}</textarea>
                                @error('description')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input40" class="col-sm-6 col-form-label"><b>Category Image </b></label>

                            <label for="upload_image">
                                    <img src="{{asset('storage/images/insurance_coverages/'. $coverage->image)}}" id="uploaded_image" class="img img-responsive img-circle" width="100" alt="Select image" />

                                    <div class="overlay">
                                        <div>Click to Change Image</div>
                                    </div>
                                    <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                                    <input type="hidden" id="cropped_image">

                                </label>
                                @error('image')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                    </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Sub Category<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="sub_category">
                                    <option value="">Select</option>
                                    @foreach ($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}"
                                            {{ $coverage->subcategory_id == $subCategory->id ? 'selected' : '' }}>
                                            {{ $subCategory->title }}</option>
                                    @endforeach
                                </select>
                                @error('sub_category')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <label class=" col-form-label"></label>
                            <div class="">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
