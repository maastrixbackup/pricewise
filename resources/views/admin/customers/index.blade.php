@extends('admin.layouts.app')
@section('title','Price Compare- Customers')
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
            @if(Auth::guard('admin')->user()->can('customer-create'))
            <a href="{{route('admin.customers.create')}}" class="btn btn-primary">Create a New Customer</a>
            @endif
        </div>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-12">
        <h6 class="mb-0 text-uppercase">Customers</h6>
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($objUsers)
                            @foreach($objUsers as $val)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$val->name ? $val->name : "NA"}}</td>
                                <td>{{$val->email ? $val->email  : "NA"}}</td>
                                <td>{{$val->phone ? $val->phone  : "NA"}}</td>
                                <td>
                                    <div class="col">
                                        @if(Auth::guard('admin')->user()->can('customer-edit'))
                                        <a title="Edit" href="{{route('admin.customers.edit',$val->id)}}" class="btn1 btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>
                                        @endif
                                        <!-- <a title="Password Generate" href="{{--route('admin.customers.pw-generate',$val->id)--}}" class="btn1 btn-outline-success"><i class="lni lni-key me-0"></i></a> -->
                                        @if(Auth::guard('admin')->user()->can('customer-delete'))
                                        <a title="Delete" class="btn1 btn-outline-danger trash remove-category" data-id="{{ $val->id }}" data-action="{{route('admin.customers.destroy', $val->id)}}"><i class="bx bx-trash me-0"></i></a>
                                        @endif
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


        $("body").on("click", ".remove-category", function() {
            var current_object = $(this);
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data!",
                type: "error",
                showCancelButton: true,
                dangerMode: true,
                cancelButtonClass: '#DD6B55',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Delete!',
            }, function(result) {
                if (result) {
                    var action = current_object.attr('data-action');
                    var token = jQuery('meta[name="csrf-token"]').attr('content');
                    var id = current_object.attr('data-id');

                    $('body').html("<form class='form-inline remove-form' method='post' action='" + action + "'></form>");
                    $('body').find('.remove-form').append('<input name="_method" type="hidden" value="DELETE">');
                    $('body').find('.remove-form').append('<input name="_token" type="hidden" value="' + token + '">');
                    $('body').find('.remove-form').append('<input name="id" type="hidden" value="' + id + '">');
                    $('body').find('.remove-form').submit();
                }
            });
        });
    });
</script>
@endpush