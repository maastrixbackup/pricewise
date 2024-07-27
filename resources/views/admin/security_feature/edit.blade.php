@extends('admin.layouts.app')
@section('title', 'Pricewise- Feature Create')
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
                            href="{{ route('admin.security-feature.index') }}">Security Features</a></li>
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
                    <form method="post" action="{{ route('admin.security-feature.update', $sFeature->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="input35" class=" col-form-label">Title</label>
                            <div class="">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title"
                                    value="{{ $sFeature->title }}">
                                @error('title')
                                    <div class="alert alert-danger py-1 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="description" class=" col-form-label">Description</label>
                            <div class="">
                                <textarea class="form-control" name="description" placeholder="Description" id="" cols="30"
                                    rows="5">{{ $sFeature->description }}</textarea>
                                @error('description')
                                    <div class="alert alert-danger py-1 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input35" class=" col-form-label">Is Enable</label>
                            <div class="">
                                <select name="status" id="isenable" class="form-control ">
                                    <option value="active" {{ $sFeature->status == 'active' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="inactive" {{ $sFeature->status == 'inactive' ? 'selected' : '' }}>No
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
