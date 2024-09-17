@extends('admin.layouts.app')
@section('title', 'Pricewise : Job Location')


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
        <div class="ms-auto">
            <div class="btn-group">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createLocationModal">Create a New Job Location</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    @php
        use Illuminate\Support\Facades\DB;
        $qry_set = DB::table('job_location')->get();
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <h6 class="mb-0 text-uppercase">Job Location</h6>
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
                        </div>
                        <div class="col-lg-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($qry_set as $qry)
                                        <tr>
                                            <td>{{ $qry->job_location }} </td>
                                            <td><button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#deleteModal" data-id="{{ $qry->id }}">
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

    {{-- <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New (Location)</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.addlocation') }}">
                        @csrf
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
                        <div class="row">
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Add New (Location)</label>
                                <input type="text" class="form-control" name="locate">
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

                    </form>
                </div>
            </div>

        </div>
    </div> --}}

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
                <form method="POST" action="{{ route('admin.deletelocation') }}">
                    @csrf
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

    <div class="modal fade" id="createLocationModal" tabindex="-1" aria-labelledby="createLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLocationModalLabel">Create a New Job Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createLocationForm" method="post" action="{{ route('admin.addlocation') }}">
                        @csrf
                        <div class="form-group">
                            <label for="locationName">Location Name</label>
                            <input type="text" class="form-control" id="locationName" name="locate" placeholder="Enter location name" required>
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
