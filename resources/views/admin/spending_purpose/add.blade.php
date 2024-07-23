@extends('admin.layouts.app')
@section('title', 'Pricewise- Spending Purpose')
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
                            href="{{ route('admin.purposes.index') }}">Purposes</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New Purpose</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.purposes.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class=" mb-3">
                            <label for="input35" class=" col-form-label">Purpose Name</label>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Spending Purpose" value="{{ old('name') }}">

                            @error('name')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="input35" class=" col-form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" placeholder="Description">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <label for="upload_image" class="mb-3">

                            <img src="#" id="uploaded_image" class="img img-responsive img-circle" width="100"
                                alt="Select image" />

                            <div class="overlay" style="cursor: pointer">
                                <div>Click to Change Image</div>
                            </div>
                            <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                            <input type="hidden" name="cropped_image" id="cropped_image">

                        </label>
                        <div class="row mb-3">
                            <label for="input35" class=" col-form-label">Is Enable</label>
                            <div class="">
                                <select name="status" id="status" class="form-control">
                                    <option value="active">Yes</option>
                                    <option value="inactive">No</option>
                                </select>
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
