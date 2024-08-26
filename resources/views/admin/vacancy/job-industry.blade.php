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

    @php
        use Illuminate\Support\Facades\DB;
        $jobTypes = DB::table('job_industry')->get();
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <h6 class="mb-0 text-uppercase">Industry Type</h6>
            <hr>
            <div class="card" style="margin-bottom: 15px;">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Job Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($jobTypes as $jobType)
                                        <tr>
                                            <td>{{ $jobType->id }}</td>
                                            <td>{{ $jobType->job_industry }}</td>
                                            <td><a href="#"><i class="bx bx-trash me-0"></i></a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New Industry Type</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.vacancy.jobindustry.submit') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Add New Industry Type</label>
                                <input type="text" class="form-control" name="industry_type">
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
                        <br>
                        <div class="row">
                            <div class="col-12 ">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @elseif (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                   
                            </div>
                            @endif
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
