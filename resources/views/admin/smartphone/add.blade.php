@extends('admin.layouts.app')
@section('title', 'Pricewise- Smartphone Create')
@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.smartphone.index') }}">Smartphone</a>
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
                    <h5 class="mb-0">Add Smartphone</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.smartphone.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Model Name<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="model_name" value="{{ old('model_name') }}"
                                    placeholder="Model Name">
                                @error('model_name')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Model Number<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="model_number"
                                    value="{{ old('model_number') }}" placeholder="Model Number">
                                @error('model_number')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Description</label>
                            <div class="">
                                <textarea name="description" class="form-control" cols="30" rows="5">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Price<sup class="text-danger">*</sup></label>
                            <div class="">
                                <input type="number" class="form-control" name="price" value="{{ old('price') }}"
                                    placeholder="Price" min="0">
                                @error('price')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Type<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="type">
                                    <option value="">Select</option>
                                    <option value="SMARTPHONE">SMARTPHONE</option>
                                    <option value="NORMAL">NORMAL</option>
                                </select>
                                @error('type')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="mb-3">
                            <div class="">
                                <label for="input_type" class=" col-form-label">Features</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="features[]"
                                    value="At home via TV App">
                                <label class="form-check-label">At home via TV App</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="features[]"
                                    value="Everywhere via TV App">
                                <label class="form-check-label">Everywhere via TV App</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="features[]" value="Missed start">
                                <label class="form-check-label">Missed start</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="features[]"
                                    value="Missed programme">
                                <label class="form-check-label">Missed programme</label>
                            </div>

                        </div> --}}
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
