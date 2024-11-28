@extends('admin.layouts.app')
@section('title', 'Energise - Consumtion Products')
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
                <a href="{{ route('admin.consumptions.add') }}" class="btn btn-primary">Add</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase">Sample Data </h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="" style="overflow-x:auto;">
                        <table id="userTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>House Type <i class="fa-solid fa-house"></i></th>
                                    <th>No. of Persons <i class="fa fa-users me-0 fa-1"></i></th>
                                    <th>Gas supply <i class="fa-solid fa-gas-pump"></i></th>
                                    <th>Electric Supply <i class="fa-solid fa-bolt-lightning"></i></th>
                                    <th>Action <i class="fa-solid fa-gears"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($cData)
                                    @foreach ($cData as $c)
                                        @php
                                            $houseName = \App\Models\HouseType::find($c->house_type);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $houseName->title ?? '' }}</td>
                                            <td>{{ $c->no_of_person ?? '' }}</td>
                                            <td>{{ $c->gas_supply }}</td>
                                            <td>{{ $c->electric_supply }}</td>
                                            <td>
                                                <a title="Edit" href="{{ route('admin.consumptions.edit', $c->id) }}"
                                                    class="btn1 btn-outline-primary"><i
                                                        class="fa fa-pencil me-0 fa-1"></i></a>
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
                processing: true,
                // serverSide: true,
                lengthChange: false,
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="far fa-file-excel"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf"></i>',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="far fa-file-csv"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
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



            $("body").on("click", ".remove-energy", function() {
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


        function changeStatus(id) {
            // Get action URL and current class
            var action = $('#status_' + id).attr('data-action');
            var statusElement = $('#status_' + id); // jQuery object for status element
            var currentClass = statusElement.attr('class'); // Get current class
            // console.log(currentClass);

            if (confirm("Are you sure you want to change the status?")) {
                $.ajax({
                    url: action,
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for security
                    },
                    data: {
                        id: id, // Send the ID to the server
                    },
                    processData: false, // No need to process the data
                    contentType: false, // Prevent jQuery from processing content type
                    success: function(data) {
                        console.log(data);

                        // Check if the status update was successful
                        if (data.status) {
                            if (currentClass.includes('text-warning')) {
                                statusElement.removeClass('text-warning').addClass('text-success');
                                statusElement.html('Published');
                            } else {
                                statusElement.removeClass('text-success').addClass('text-warning');
                                statusElement.html('Draft');
                            }
                            toastr.info(data.message, ''); // Show success message
                        } else {
                            toastr.error(data.message, ''); // Show error message
                        }
                    },
                    error: function(e) {
                        toastr.error('Something went wrong. Please try again later!!', ''); // Handle AJAX error
                    }
                });
            } else {
                toastr.info('Thanks for your response', ''); // Show info message if user cancels
            }
        }
    </script>
@endpush
