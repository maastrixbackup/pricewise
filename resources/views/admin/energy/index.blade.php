@extends('admin.layouts.app')
@section('title','Energise - Energy Products')
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
        {{--@if (Auth::guard('admin')->user()->can('energy-add'))--}}
        <div class="btn-group">
            {{-- <a href="{{route('admin.energy.create')}}" class="btn btn-secondary">Import</a>--}}
            <a href="{{route('admin.energy.create')}}" class="btn btn-primary">Create New Energy Product</a>
        </div>
       {{-- @endif--}}
    </div>
</div>
<!--end breadcrumb-->
<form method="GET" id="search-form" name="searchform">
    <div class="row">
        <div class="col-lg-3 mb-2">
            <label for="input35" class=" col-form-label">Product Name</label>
            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Search by Product Name">
        </div>

        <div class="col-lg-3 mb-2">
            <label for="input40" class="col-form-label">Product Type
            </label>
            <select id="product_type" name="product_type" class="select2 form-select">
                <option value="">Select</option>
                <option value="personal">Personal</option>
                <option value="business">Business</option>
                <option value="large-business">Large Business</option>
                
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label for="input40" class="col-form-label">Status
            </label>
            <select id="status" name="status" class="select2 form-select">
                <option value="">All</option>
                <option value="1">Publish</option>
                <option value="0">Draft</option>
               

            </select>
        </div>
    </div>
    <div class="row">
        
        <div class="col-lg-3 mb-2">
            <label for="tv" class="col-form-label">Features
            </label>
            <select id="tv" name="tv" class="select2 form-select">
                <option value="">All</option>
                @if($objEnergyFeatures)
                @foreach($objEnergyFeatures as $tv)
                <option value="{{$tv->id}}">{{$tv->features}}</option>
                @endforeach
                @endif
            </select>
        </div>
        
    </div>
</form>
<div class="row">
    <div class="col-12 col-lg-12">
        <h6 class="mb-0 text-uppercase">Energy Products </h6>
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Title</th>
                                <th>Product Type</th>
                                <th>Status</th>
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
                url: "{{route('admin.get.energy')}}",
                data: function(d) {
                    d.product_name = $('input[name=product_name]').val(),
                        d.product_type = $('select[name=product_type]').val(),
                        d.internet = $('select[name=internet]').val(),
                        d.tv = $('select[name=tv]').val(),
                        d.telephone = $('select[name=telephone]').val(),
                        d.status = $('#status').val()

                },
            },
            columns: [{
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'title'
                },
                {
                    data: 'product_type'
                },
                
                {
                    data: 'status'
                },
                {
                    data: 'action'
                },
            ],

            // 'order': [
            //     [1, 'asc']
            // ],
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
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fal fa-file-pdf"></i>',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fas fa-file-csv"></i>',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="far fa-print"></i>',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
            ],
            'columnDefs': [{
                'targets': [3], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }],
            order: [[0, 'desc']] 
        });

        // Refilter the table
        $("#product_name").keyup(function() {
            table.draw();
        });
        $("#product_type").change(function() {
            table.draw();
        });
        $("#internet").change(function() {
            table.draw();
        });

        $("#status").change(function() {
            table.draw();
        });
    });
    $(document).ready(function() {

        $("body").on("click", ".remove-tv", function() {
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