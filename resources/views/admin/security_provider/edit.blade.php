@extends('admin.layouts.app')
@section('title', 'Pricewise- Caterer Create')
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
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.security-provider.index') }}">Security provider</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New Provider</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.security-provider.update', $sProvider->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="input35" class=" col-form-label">Provider Name</label>
                            <div class="">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title"
                                    value="{{ $sProvider->title }}">
                            </div>
                            @error('title')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <label for="address" class=" col-form-label">Address</label>
                            <div class="">
                                <textarea class="form-control" name="address" placeholder="Address" id="" cols="30" rows="7">{{ $sProvider->address }}</textarea>
                            </div>
                            @error('address')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <label for="description" class=" col-form-label">Description</label>
                            <div class="">
                                <textarea class="form-control" name="description" placeholder="Description" id="" cols="30"
                                    rows="5">{{ $sProvider->description }}</textarea>
                            </div>
                            @error('description')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <label for="upload_image" class="mb-3">

                            <img src="{{ asset('storage/images/cyber_security/' . $sProvider->image) }}" id="uploaded_image"
                                class="img img-responsive img-circle" width="100" alt="Select image" />

                            <div class="overlay" style="cursor: pointer">
                                <div>Click to Change Image</div>
                            </div>
                            <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                            <input type="hidden" name="cropped_image" id="cropped_image">

                        </label>
                        <div class="row mb-3">
                            <label for="input35" class=" col-form-label">Country</label>
                            <div class="">
                                <select name="country" id="country" class="form-control selectpicker">
                                    <option value="">-- Select --</option>
                                    @foreach (App\Models\Country::all() as $country)
                                        <option value="{{ $country->id }}"
                                            {{ $sProvider->country_id == $country->id ? 'selected' : '' }}>
                                            {{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input35" class=" col-form-label">Is Enable</label>
                            <div class="">
                                <select name="status" id="status" class="form-control ">
                                    <option value="active" {{ $sProvider->status == 'active' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="inactive" {{ $sProvider->status == 'inactive' ? 'selected' : '' }}>No
                                    </option>
                                </select>
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
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Choices(document.querySelector(".selectpicker"), {
                removeItemButton: true
            });
        });
    </script>
@endpush
