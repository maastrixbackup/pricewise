@extends('admin.layouts.app')
@section('title', 'PriceWise- Cyber Securities')
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
        <div class="ms-auto">

            <div class="btn-group">
                <a href="{{ route('admin.cyber-security.create') }}" class="btn btn-primary">Create Security Product</a>

            </div>

        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase">Security Products</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Title</th>
                                    <th>License Duration</th>
                                    <th> Backup</th>
                                    <th>No. of PCs</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($securities)
                                    @foreach ($securities as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $val->title }}</td>
                                            <td>{{ isset($val->license_duration) ? $val->license_duration . ' Years' : '' }}
                                            </td>
                                            <td>{{ isset($val->cloud_backup) ? $val->cloud_backup . ' GB' : '' }}</td>
                                            <td>{{ isset($val->no_of_pc) ? $val->no_of_pc . ' PCs' : '' }}</td>
                                            <td>
                                                @if (!empty($val->image))
                                                    <img src="{{ asset('storage/images/cyber_security/' . $val->image) }}"
                                                        alt="{{ $val->image }}" width="100px" height="50px">
                                                @else
                                                    <img src="{{ asset('storage/images/no_image/no-image.png') }}"
                                                        alt="{{ $val->image }}" width="60px" height="60px">
                                                @endif
                                            </td>
                                            <td>
                                                @if ($val->status == 'active')
                                                    <div
                                                        class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">
                                                        <i class="bx bxs-circle me-1"></i>Published
                                                    </div>
                                                @else
                                                    <div
                                                        class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">
                                                        <i class="bx bxs-circle me-1"></i>Draft
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="col">
                                                    <a title="Edit"
                                                        href="{{ route('admin.cyber-security.edit', $val->id) }}"
                                                        class="btn1 btn-outline-primary"><i
                                                            class="bx bx-pencil me-0"></i></a>

                                                    <a title="Delete" class="btn1 btn-outline-danger trash remove-category"
                                                        data-id="{{ $val->id }}"
                                                        data-action="{{ route('admin.cyber-security.destroy', $val->id) }}"><i
                                                            class="bx bx-trash me-0"></i></a>

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
                        text: '<i class="fa fa-file-excel-o"></i>',
                        exportOptions: {
                            columns: [0, 1]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: [0, 1]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
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

                        $('body').html(
                            "<form class='form-inline remove-form' method='post' action='" +
                            action + "'></form>");
                        $('body').find('.remove-form').append(
                            '<input name="_method" type="hidden" value="DELETE">');
                        $('body').find('.remove-form').append(
                            '<input name="_token" type="hidden" value="' + token + '">');
                        $('body').find('.remove-form').append(
                            '<input name="id" type="hidden" value="' + id + '">');
                        $('body').find('.remove-form').submit();
                    }

                });
            });
        });
    </script>
@endpush
