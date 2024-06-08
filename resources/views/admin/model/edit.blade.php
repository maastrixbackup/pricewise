@extends('admin.layouts.app')
@section('title', 'Pricewise - Edit Models')

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
                            href="{{ route('admin.models.index') }}">Models</a>
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
                    <h5 class="mb-0">Edit Model</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.models.update',$model->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="input_type" class="col-form-label">Name<sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" value="{{$model->name}}" name="name">
                            </div>
                            <div class="col-md-12">
                                <label for="input_type" class=" col-form-label">Brand<sup class="text-danger">*</sup></label>
                                <div class="">
                                    <select class="form-control" name="brand">
                                        <option value="">Select</option>
                                        @foreach ($brands as $brand)
                                        <option value="{{$brand->id}}" {{$brand->id == $model->brand_id ? "selected" : ""}}>{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('brand')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                   
                        <div class="row">
                            <label class="col-form-label"></label>
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

