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
        $jobTypes = DB::table('job_role')->get();
        $jobIndustry = DB::table('job_industry')->get();
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <h6 class="mb-0 text-uppercase">Role</h6>
            <hr>
            <div class="card" style="margin-bottom: 15px;">
                <div class="card-body">

                    <div class="row">
                        @if (session('deleted'))
                                <div class="alert alert-success">
                                    {{ session('deleted') }}
                                </div>
                            @endif
                        <div class="col-lg-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Industry</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($jobTypes as $jobType)
                                        <tr>
                                            <td>{{ $jobType->id }}</td>
                                            <td>{{ $jobType->job_industry }}</td>
                                            <td>{{ $jobType->job_role }}</td>
                                            <td><a href="#"><button type="button" class="btn btn-danger" data-toggle="modal"
                                                    data-target="#deleteModal" data-id="{{ $jobType->id }}">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
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
                    <h5 class="mb-0">Add New Role</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.vacancy.role.submit') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Industry</label>
                                <select class="form-control" name="industry">
                                    <option value="" disabled selected>Select Industry</option>
                                    @foreach ($jobIndustry as $ji)
                                        <option value="{{ $ji->job_industry }}">{{ $ji->job_industry }}</option>
                                    @endforeach
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Add New Role</label>
                                <input type="text" class="form-control" name="role">
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.deleteRow') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Are you sure you want to delete this item?</p>
                        <input type="hidden" name="id" id="delete-id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract the id from data-* attributes
            console.log("Button ID:", id); // Log the ID to see if it's correctly retrieved
            var modal = $(this);
            modal.find('.modal-body #delete-id').val(id);
        });
    </script>

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
