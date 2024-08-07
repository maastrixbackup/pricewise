@extends('admin.layouts.app')
@section('title', 'PriceWise- Shop Products')
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
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Create a Product</a>

            </div>

        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase">Products</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ExclusiveDealsTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Title</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Availability</th>
                                    <th>Product Type</th>
                                    {{-- <th>Status</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($shopProducts)
                                    @foreach ($shopProducts as $record)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $record->title ?? '' }}</td>
                                            <td>{{ $record->brandDetails->title ?? '' }}</td>
                                            <td>{{ $record->categoryDetails->title ?? '' }}</td>
                                            <td>
                                                @if ($record->p_status == 1)
                                                    <span
                                                        class="badge rounded-pill text-success bg-light-success  text-uppercase"
                                                        style="color: #3DB44D;">In
                                                        Stock</span><span>({{ $record->qty }})</span>
                                                @elseif ($record->p_status == 2)
                                                    <span
                                                        class="badge rounded-pill text-warning bg-light-warning  text-uppercase"
                                                        style="color: rgb(255, 166, 0);">Limited
                                                        Stock</span><span>({{ $record->qty }})</span>
                                                @elseif ($record->p_status == 3)
                                                    <span
                                                        class="badge rounded-pill text-warning bg-light-warning  text-uppercase"
                                                        style="color: gold;">On Request</span>
                                                @elseif ($record->p_status == 0)
                                                    <span class="badge rounded-pill   text-uppercase"
                                                        style="color: gray">Out of Stock</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($record->product_type == 'personal')
                                                    <span class="  text-uppercase">Personal</span>
                                                @elseif ($record->product_type == 'commercial')
                                                    <span class="text-uppercase">Commercial</span>
                                                @else
                                                    <span class="  text-uppercase">Large Bussiness</span>
                                                @endif
                                            </td>
                                            {{-- <td>
                                                @if ($record->is_publish == 1)
                                                    <span
                                                        class="badge rounded-pill text-success bg-light-success text-uppercase">Published</span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill text-primary bg-light-success  text-uppercase">Draft</span>
                                                @endif
                                            </td> --}}
                                            <td>
                                                <div class="col d-flex col d-flex justify-content-evenly">
                                                    <a title="Edit" href="{{ route('admin.products.edit', $record->id) }}"
                                                        class="btn1 btn-outline-primary"><i
                                                            class="bx bx-pencil me-0"></i></a>
                                                    <a title="Delete" class="btn1 btn-outline-danger trash remove-package"
                                                        data-id="{{ $record->id }}"
                                                        data-action="{{ route('admin.products.destroy', $record->id) }}"><i
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
            var table = $('#ExclusiveDealsTable').DataTable({
                lengthChange: false,
                // buttons: [{
                //         extend: 'excelHtml5',
                //         text: '<i class="far fa-file-excel"></i>',
                //         exportOptions: {
                //             columns: [0, 1, 2, 4, 5]
                //         }
                //     },
                //     {
                //         extend: 'pdfHtml5',
                //         text: '<i class="fal fa-file-pdf"></i>',
                //         orientation: 'landscape',
                //         pageSize: 'LEGAL',
                //         exportOptions: {
                //             columns: [0, 1, 2, 4, 5]
                //         }
                //     },
                //     {
                //         extend: 'print',
                //         text: '<i class="far fa-print"></i>',
                //         exportOptions: {
                //             columns: [0, 1, 2, 4, 5]
                //         }
                //     },
                // ],
                // 'columnDefs': [{
                //     'targets': [2], // column index (start from 0)
                //     'orderable': false, // set orderable false for selected columns
                // }]
            });

            table.buttons().container()
                .appendTo('#ExclusiveDealsTable_wrapper .col-md-6:eq(0)');

            $("body").on("click", ".remove-package", function() {
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
