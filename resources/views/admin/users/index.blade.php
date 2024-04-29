@extends('admin.layouts.app')
@section('title','NoPlan- Users')
@section('content')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('admin.users.create')}}" class="btn btn-primary">Create a New User</a>

        </div>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-12">
        <h6 class="mb-0 text-uppercase">Users</h6>
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone No</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($users)
                            @foreach($users as $val)
                            <tr>
                                <td>{{$val->name ? $val->name : "NA"}}</td>
                                <td>{{$val->email ? $val->email : "NA"}}</td>
                                <td>{{$val->phone_no ? $val->phone_no : "NA"}}</td>
                                <td>
                                    <div class="col">
										<a title="Edit" href="{{route('admin.users.edit',$val->id)}}" class="btn btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>
									</div>
                                </td>
                            </tr>
                            @endforeach
                            @endif

                        </tbody>
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
            lengthChange: false,
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel"></i>',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fal fa-file-pdf"></i>',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="far fa-print"></i>',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
            ],
            'columnDefs': [{
                'targets': [2], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }]
        });

        table.buttons().container()
            .appendTo('#userTable_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush