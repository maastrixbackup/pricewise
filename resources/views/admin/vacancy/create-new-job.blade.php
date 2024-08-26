@extends('admin.layouts.app')
@section('title', 'Pricewise : List all jobs')

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
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New Job</h5>
                </div>
                <div class="card-body" style="padding-bottom: 0">
                    <div class="row">
                        <div class="col-md-12">
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div></div>
                @endif</div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.vacancy.form.submit') }}">
                        @csrf
                        <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="input_type" class="col-form-label">Job Industry</label>
                            <input type="text" class="form-control" name="" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="input_type" class="col-form-label">Job Role</label>
                            <input type="text" class="form-control" name="" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="input_type" class="col-form-label">Location</label>
                            <input type="text" class="form-control" name="" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="input_type" class="col-form-label">Job Type</label>
                            <input type="text" class="form-control" name="" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="input_type" class="col-form-label">Experience Level</label>
                            <input type="text" class="form-control" name="" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="input_type" class="col-form-label">Educational Qualification</label>
                            <input type="text" class="form-control" name="" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="input_type" class="col-form-label">Price per Hour</label>
                            <input type="text" class="form-control" name="" value="">
                        </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="input_type" class="col-form-label">Job Title </label>
                        <input type="text" class="form-control" name="">
                    </div>
                    <div class="col-12">
                        <label for="input_type" class="col-form-label">Description</label><br>
                        <textarea></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="input_type" class="col-form-label">Remote Job</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="input_type" class="col-form-label">Job Status</label>
                        <input type="text" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <label class="col-form-label"></label>
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
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Choices(document.querySelector(".choices-multiple"), {
                removeItemButton: true
            });
        });
    </script>
@endpush
