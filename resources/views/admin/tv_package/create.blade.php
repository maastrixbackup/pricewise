@extends('admin.layouts.app')
@section('title', 'Pricewise- Tv Package Create')

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
                            href="{{ route('admin.tv-packages.index') }}">Tv Packages</a>
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
                    <h5 class="mb-0">Add New Tv Package</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.tv-packages.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Package Name<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="package_name"
                                    value="{{ old('package_name') }}" placeholder="Package Name">
                                @error('package_name')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Channels<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select name="channels[]" multiple>
                                    <option value="">Select</option>
                                    @foreach ($channels as $channel)
                                        <option value="{{ $channel->id }}" {{ old('package_name') ==  $channel->id ? 'selected' : ''}}>{{ $channel->channel_name }}</option>
                                    @endforeach
                                </select>
                                @error('channels')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Provider<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="provider">
                                    <option value="">Select</option>
                                    @foreach ($providers as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                                @error('provider')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="row">
                            <label class=" col-form-label"></label>
                            <div class="">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4">Submit</button>
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
