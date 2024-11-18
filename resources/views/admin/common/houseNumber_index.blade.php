@extends('admin.layouts.app')
@section('title', 'Pricewise- House Number')
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
                <a type="button" href="{{ route('admin.house-numbers.create') }}" class="btn btn-primary">
                    Add New PostalCode
                </a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase">House Numbers</h6>
            <hr />
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="roleTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Postal Code</th>
                                    <th>House No & Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($houseNumbers)
                                    @foreach ($houseNumbers as $houseNumber)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $houseNumber->postal_codes }}</td>
                                            <td>
                                                <a href="javascript:;" class="btn text-success"
                                                    onclick="viewHouseDetails('{{ $houseNumber->id }}')">View <i
                                                        class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                            <td>
                                                {{-- <a title="Edit"
                                                    href="{{ route('admin.house-numbers.edit', $houseNumber->id) }}"
                                                    class="btn btn-outline-primary"><i class="bx bx-pencil me-0"></i></a> --}}
                                                <a title="Delete" class="btn btn-outline-danger trash remove-role"
                                                    data-id="{{ $houseNumber->id }}"
                                                    data-action="{{ route('admin.house-numbers.destroy', $houseNumber->id) }}"><i
                                                        class="bx bx-trash me-0"></i></a>
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
    <!-- Modal -->
    <div class="modal fade" id="postalCodeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">House No & Address Details</h5>
                </div>
                <div class="modal-body">
                    <table id="hDatas" class="table ">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>House No </th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody id="fData">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#roleTable').DataTable({
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
                        text: '<i class="fa fa-file-pdf"></i>',
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
                .appendTo('#roleTable_wrapper .col-md-6:eq(0)');
        });

        $('#toggleButton').on('click', function() {
            var content = $('#content'); // Get the content div
            var button = $(this); // Reference to the button

            // Check if the content is expanded by looking for a class or data attribute
            if (content.hasClass('expanded')) {
                content.css('height', '20px'); // Collapse the content
                button.text('View More'); // Change button text
                content.removeClass('expanded'); // Remove the expanded class
            } else {
                content.css('height', 'auto'); // Expand the content
                button.text('View Less'); // Change button text
                content.addClass('expanded'); // Add the expanded class
            }
        });

        function viewHouseDetails(id) {
            $.ajax({
                url: '{{ route('admin.get-postal-code-data') }}',
                method: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id,
                },
                cache: false,
                success: function(data) {
                    // console.log('AJAX Success:', data);

                    $('#fData').html(''); // Insert the new data
                    // Check if data contains the expected HTML
                    if (data.status) {
                        $('#fData').html(data.html); // Insert the new data

                        // Open the modal
                        $('#postalCodeModal').modal('show');
                    } else {
                        // console.error('Invalid response data:', data);
                        toastr.error('', data.message, {
                            "positionClass": "toast-top-right"
                        });
                    }
                },
                error: function(e) {
                    console.error('AJAX Error:', e);
                    toastr.error('Something went wrong. Please try again later!', '', {
                        "positionClass": "toast-top-right"
                    });
                }
            });
            // $('#postalCodeModal').modal('show');
        }
    </script>

    <script type="text/javascript">
        $("body").on("click", ".remove-role", function() {
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

                    $('body').html("<form class='form-inline remove-form' method='Post' action='" + action +
                        "'></form>");
                    $('body').find('.remove-form').append(
                        '<input name="_method" type="hidden" value="Post">');
                    $('body').find('.remove-form').append('<input name="_token" type="hidden" value="' +
                        token + '">');
                    $('body').find('.remove-form').append('<input name="id" type="hidden" value="' + id +
                        '">');
                    $('body').find('.remove-form').submit();
                }
            });
        });
    </script>
@endpush
