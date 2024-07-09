@extends('admin.layouts.app')
@section('title', 'User Requests')
@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
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
    <form method="GET" id="search-form" name="searchform">
        <div class="row">
            <div class="col-lg-3 mb-2">
                <label for="input35" class=" col-form-label">Customer</label>
                <input type="text" class="form-control" id="user_id" name="user_id"
                    placeholder="Search by Customer Email/Mobile/Name">
            </div>

            <div class="col-lg-3 mb-2">
                <label for="input40" class="col-form-label">User Type
                </label>
                <select id="user_type" name="user_type" class="select2 form-select">
                    <option value="">Select</option>
                    <option value="personal">Personal</option>
                    <option value="business">Business</option>
                    <option value="large_business">Large Business</option>
                </select>
            </div>

            <div class="col-lg-3 mb-2">
                <label for="input40" class="col-form-label">Request Status
                </label>
                <select id="request_status" name="request_status" class="select2 form-select">
                    <option value="">All</option>
                    <option value="Under Process">Under Process</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase">User Requests</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Customer</th>
                                    <th>Customer Type</th>
                                    <th>Request Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            var table = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.get.requests') }}",
                    data: function(d) {
                        d.user_id = $('input[name=user_id]').val(),
                            d.user_type = $('select[name=user_type]').val(),
                            d.request_status = $('select[name=request_status]').val()
                    },
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'product'
                    },
                    {
                        data: 'user_name'
                    },
                    {
                        data: 'user_type'
                    },
                    {
                        data: 'request_status'
                    },
                    {
                        data: 'action'
                    },
                ],
                dom: 'Blfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        text: '<i class="fal fa-file"></i>',
                        exportOptions: {
                            columns: [0, ':visible']
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="far fa-file-excel"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fal fa-file-pdf"></i>',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fas fa-file-csv"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="far fa-print"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                ],
                'columnDefs': [{
                    'targets': [3], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }],
                order: [
                    [0, 'desc']
                ]
            });

            // Refilter the table
            $("#user_id").keyup(function() {
                table.draw();
            });
            $("#user_type").change(function() {
                table.draw();
            });
            $("#request_status").change(function() {
                table.draw();
            });
        });
    </script>
@endpush
