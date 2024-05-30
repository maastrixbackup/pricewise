@extends('admin.layouts.app')
@section('title', 'Pricewise- Tv Option Edit')

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
                            href="{{ route('admin.tv-options.index') }}">Tv Options</a>
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
                    <h5 class="mb-0">Edit Tv Option</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.tv-options.update',$tv_option->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="input_type" class="col-form-label">Internet Options</label>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">1 Addtional Wifi Point</label>
                                <input type="text" class="form-control" name="internet_options[0][name]"
                                    value="1 Addtional Wifi Point" readonly>
                            </div>
                        
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">One-Off Cost</label>
                                <input type="text" class="form-control" name="internet_options[0][one_off_cost]"
                                    value="{{ $internet_options[0]->one_off_cost  }}" placeholder="One-Off Cost">
                                @error('internet_options[0][one_off_cost]')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Normal Cost</label>
                                <input type="text" class="form-control" name="internet_options[0][normal_cost]"
                                    value="{{ $internet_options[0]->normal_cost }}" placeholder="Normal Cost">
                                @error('internet_options[0][normal_cost]')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">2 Addtional Wifi Point</label>
                                <input type="text" class="form-control" name="internet_options[1][name]"
                                    value="2 Addtional Wifi Point" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">One-Off Cost</label>
                                <input type="text" class="form-control" name="internet_options[1][one_off_cost]"
                                    value="{{ $internet_options[1]->one_off_cost }}" placeholder="One-Off Cost">
                                @error('internet_options[1][one_off_cost]')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Normal Cost</label>
                                <input type="text" class="form-control" name="internet_options[1][normal_cost]"
                                    value="{{ $internet_options[1]->normal_cost }}" placeholder="Normal Cost">
                                @error('internet_options[1][normal_cost]')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class="col-form-label">Tv Options</label>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">1 Receiver </label>
                                <input type="text" class="form-control" name="tv_options[0][name]" value="1 Receiver"
                                    readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Normal Cost per/month</label>
                                <input type="text" class="form-control" name="tv_options[0][normal_cost]"
                                    value="{{ $tv_options[0]->normal_cost }}" placeholder="Normal Cost">
                                @error('tv_options[0][normal_cost]')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">2 Receivers</label>
                                <input type="text" class="form-control" name="tv_options[1][name]" value="1 Receivers"
                                    readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Normal Cost per/month</label>
                                <input type="text" class="form-control" name="tv_options[1][normal_cost]"
                                    value="{{ $tv_options[1]->normal_cost }}" placeholder="Normal Cost">
                                @error('tv_options[1][normal_cost]')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">3 Receivers</label>
                                <input type="text" class="form-control" name="tv_options[2][name]" value="3 Receivers"
                                    readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Normal Cost per/month</label>
                                <input type="text" class="form-control" name="tv_options[2][normal_cost]"
                                    value="{{ $tv_options[2]->normal_cost }}" placeholder="Normal Cost">
                                @error('tv_options[2][normal_cost]')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">4 Receivers</label>
                                <input type="text" class="form-control" name="tv_options[3][name]" value="4 Receivers"
                                    readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Normal Cost per/month</label>
                                <input type="text" class="form-control" name="tv_options[3][normal_cost]"
                                    value="{{ $tv_options[3]->normal_cost }}" placeholder="Normal Cost">
                                @error('tv_options[3][normal_cost]')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <label for="input_type" class="col-form-label">Telephone Options</label>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Call Europe</label>
                                <input type="text" class="form-control" name="telephone_options[0][name]" value="Call Europe"
                                    readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Normal Cost per/month</label>
                                <input type="text" class="form-control" name="telephone_options[0][normal_cost]"
                                    value="{{ $telephone_options[0]->normal_cost }}" placeholder="Normal Cost">
                                @error('telephone_options[0][normal_cost]')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Call World</label>
                                <input type="text" class="form-control" name="telephone_options[1][name]" value="Call World"
                                    readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Normal Cost per/month</label>
                                <input type="text" class="form-control" name="telephone_options[1][normal_cost]"
                                    value="{{ $telephone_options[0]->normal_cost }}" placeholder="Normal Cost">
                                @error('telephone_options[1][normal_cost]')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Unlimited Calling Netherlands</label>
                                <input type="text" class="form-control" name="telephone_options[1][name]" value="Unlimited Calling Netherlands"
                                    readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Normal Cost per/month</label>
                                <input type="text" class="form-control" name="telephone_options[1][normal_cost]"
                                    value="{{ $telephone_options[1]->normal_cost}}" placeholder="Normal Cost">
                                @error('telephone_options[1][normal_cost]')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-4 mb-3">
                            <label for="input_type" class="col-form-label">Provider<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="provider" required>
                                    <option value="">Select</option>
                                    @foreach ($providers as $provider)
                                        <option value="{{ $provider->id }}" {{$tv_option->provider == $provider->id ? 'selected' : '' }}>{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                                @error('provider')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
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
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Choices(document.querySelector(".choices-multiple"), {
                removeItemButton: true
            });
        });
    </script>
@endpush
