@extends('admin.layouts.app')
@section('title','POPTelecom- Subscribers Lists')
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
            <a href="{{route('admin.create-subscriber')}}" class="btn btn-primary">Add New</a>
        </div>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-12">
        <h6 class="mb-0 text-uppercase">Subscribers Lists</h6>
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
                                <th>City</th>
                                <th>ZIP</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($subscribers_list)
                            @foreach($subscribers_list->members as $val)
                           
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$val->full_name}}</td>
                                <td>{{$val->email_address}}</td>
                                <td>{{$val->merge_fields->ADDRESS->city ? $val->merge_fields->ADDRESS->city : 'NA'}}</td>
                                <td>{{$val->merge_fields->ADDRESS->zip ? $val->merge_fields->ADDRESS->zip : 'NA'}}</td>
                                <td>
                                    <div class="col">
                                         <a title="Edit" href="{{route('admin.edit-subscriber',$val->email_address)}}" class="btn1 btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>
                                         <a title="Delete" href="javascript:void(0)" data-id="{{ $val->email_address }}" data-action="{{route('admin.delete-subscriber',$val->id)}}" class="btn1 btn-outline-danger trash"><i class="bx bx-trash me-0"></i></a> 
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
            buttons: ['excel', 'pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#userTable_wrapper .col-md-6:eq(0)');


        $("body").on("click", ".trash", function() {
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
                    $('body').find('.remove-form').append('<input name="_method" type="hidden" value="post">');
                    $('body').find('.remove-form').append('<input name="_token" type="hidden" value="' + token + '">');
                    $('body').find('.remove-form').append('<input name="id" type="hidden" value="' + id + '">');
                    $('body').find('.remove-form').submit();
                }
            });
        });
    });
</script>
@endpush