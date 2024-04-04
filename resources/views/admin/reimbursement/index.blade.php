@extends('admin.layouts.app')
@section('title','Energise- Reimbursement')
@section('content')

<!--breadcrumb-->
<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
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
            @if(Auth::guard('admin')->user()->can('reimbursement-create'))
            <a href="{{route('admin.reimbursement.create')}}" class="btn btn-primary">Create New Reimbursement</a>
            @endif
        </div>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-12">
        <h6 class="mb-0 text-uppercase">Reimbursement</h6>
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
                   <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->             
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>                               
                                <th>Parent</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($objCategory)
                            @foreach($objCategory as $val)
                           
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$val->name ? $val->name : "NA"}}</td>
                                <td>{{$val->parentCat ? $val->parentCat->name : "NA"}}</td>                            
                                
                                <td>
                                    <div class="col">
                                        @if(Auth::guard('admin')->user()->can('reimbursement-edit'))
                                        <a title="Edit" data-bs-toggle="tooltip" data-bs-placement="top" href="{{route('admin.reimbursement.edit',$val->id)}}" class="btn1 btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>
                                        @endif
                                        @if(Auth::guard('admin')->user()->can('reimbursement-delete'))
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="btn1 btn-outline-danger trash remove-category" data-id="{{ $val->id }}" data-action="{{route('admin.reimbursement.destroy', $val->id)}}"><i class="bx bx-trash me-0"></i></a>
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
    // $(document).ready(function() {
    //     var table = $('#userTable').DataTable({
    //         lengthChange: false,
    //         buttons: ['excel', 'pdf', 'print']
    //     });

    //     table.buttons().container()
    //         .appendTo('#userTable_wrapper .col-md-6:eq(0)');


    //     $("body").on("click", ".remove-category", function() {
    //         var current_object = $(this);
    //         swal({
    //             title: "Are you sure?",
    //             text: "You will not be able to recover this data!",
    //             type: "error",
    //             showCancelButton: true,
    //             dangerMode: true,
    //             cancelButtonClass: '#DD6B55',
    //             confirmButtonColor: '#dc3545',
    //             confirmButtonText: 'Delete!',
    //         }, function(result) {
    //             if (result) {
    //                 var action = current_object.attr('data-action');
    //                 var token = jQuery('meta[name="csrf-token"]').attr('content');
    //                 var id = current_object.attr('data-id');

    //                 $('body').html("<form class='form-inline remove-form' method='post' action='" + action + "'></form>");
    //                 $('body').find('.remove-form').append('<input name="_method" type="hidden" value="DELETE">');
    //                 $('body').find('.remove-form').append('<input name="_token" type="hidden" value="' + token + '">');
    //                 $('body').find('.remove-form').append('<input name="id" type="hidden" value="' + id + '">');
    //                 $('body').find('.remove-form').submit();
    //             }
    //         });
    //     });
    // });
</script>
@endpush