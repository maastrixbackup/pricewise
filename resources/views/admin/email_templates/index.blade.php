@extends('admin.layouts.app')
@section('title','PriceCompare- Email Templates')
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
            @if(Auth::guard('admin')->user()->can('email-template-create'))
            <a href="{{route('admin.email-templates.create')}}" class="btn btn-primary">Create a New Template</a>
            @endif
        </div>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-12">
        <h6 class="mb-0 text-uppercase">Email Templates</h6>
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th scope="col">Assign To</th>
                                <th scope="col">Mail Subject</th>                               
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($templates)
                            @foreach($templates as $val)
                           
                            <tr>
                                <td>{{ $loop->iteration }}</td>                                
                                <td class="text-capitalize">
                                    @if($val->email_of == 1)
                                    Register for User
                                    @elseif($val->email_of == 2)
                                    Forgot password
                                    @elseif($val->email_of == 3)
                                    User Past Ad Order
                                    @elseif($val->email_of == 4)
                                    Admin Past Ad Order
                                    @elseif($val->email_of == 5)
                                    User Register for Admin
                                    @elseif($val->email_of == 6)
                                    Seller Past Ad Order
                                    @elseif($val->email_of == 7)
                                    Parts Request Question(parent)
                                    @elseif($val->email_of == 8)
                                    Parts Request Question(sub question)
                                    @elseif($val->email_of == 9)
                                    Bid Offer
                                    @elseif($val->email_of == 10)
                                    Sales Question
                                    @elseif($val->email_of == 11)
                                    Parts Order to User
                                    @elseif($val->email_of == 12)
                                    Parts Order to Bidder
                                    @elseif($val->email_of == 13)
                                    Parts Order to Admin
                                    @elseif($val->email_of == 14)
                                    Subscribe Alert for ad
                                    @elseif($val->email_of == 15)
                                    Subscribe Alert for Request Parts
                                    @endif
                                </td>
                                <td>{{$val->mail_subject ? $val->mail_subject : "NA"}}</td>
                                <td>
                                    <div class="col">
                                        <a title="View" href="{{route('admin.email-templates.show',$val->id)}}" class="btn btn-sm btn-outline-info"><i class="bx bx-show me-0"></i></a>
                                        @if(Auth::guard('admin')->user()->can('email-template-edit'))
                                        <a title="Edit" href="{{route('admin.email-templates.edit',$val->id)}}" class="btn btn-sm btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>
                                        @endif
                                        @if(Auth::guard('admin')->user()->can('email-template-delete'))
                                        <a title="Delete" class="btn btn-sm btn-outline-danger trash remove-category" data-id="{{ $val->id }}" data-action="{{route('admin.email-templates.destroy', $val->id)}}"><i class="bx bx-trash me-0"></i></a>
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