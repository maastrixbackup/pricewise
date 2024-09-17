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
        $jobTypes = DB::table('job_all_jobs')->get();
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <h6 class="mb-0 text-uppercase">List all jobs...</h6>
            <hr>
            <div class="card" style="margin-bottom: 15px;">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            @if (session('deleted'))
                                <div class="alert alert-success">
                                    {{ session('deleted') }}
                                </div>
                            @endif
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Job Title</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($jobTypes as $jobType)
                                        <tr>
                                            <td>{{ $jobType->id }}</td>
                                            <td>{{ $jobType->job_title }}</td>
                                            <td>{{ $jobType->job_location }}</td>
                                            <td>{{ $jobType->job_status }}</td>
                                            <td>
                                                <a href="{{ route('admin.edit', $jobType->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
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
            <form method="POST" action="{{ route('admin.deleteJob') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete?</p>
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
        $(document).ready(function() {
            $('.table').DataTable();
        });
    </script>

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
