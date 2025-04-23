@extends('admin.layouts.app')
@section('title', 'Current Slabs')
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
                <a href="javascript:;" onclick="addCurrentSlab('{{ route('admin.current-slab-store') }}')"
                    class="btn btn-primary">Create</a>
            </div>

        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase"> Electric Slabs</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Meter Type</th>
                                    <th>Usage From</th>
                                    <th>Usage To</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($currentSlabs))
                                    @foreach ($currentSlabs as $val)
                                        @php
                                            $meterType = 'NA'; // Default value

                                            if (!empty($val->meter_type_from) && !empty($val->meter_type_to)) {
                                                $meterType =
                                                    $val->meter_type_from . ' ' . ' t / m' . ' ' . $val->meter_type_to;
                                            } elseif (empty($val->meter_type_from) && !empty($val->meter_type_to)) {
                                                $meterType = $val->meter_type_to;
                                            } elseif (!empty($val->meter_type_from) && empty($val->meter_type_to)) {
                                                $meterType = $val->meter_type_from;
                                            }
                                        @endphp

                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $meterType }}</td>
                                            <td>{{ $val->usage_from . ' ' . 'kWh' ?? 'NA' }}</td>
                                            <td>{{ $val->usage_to . ' ' . 'kWh' ?? 'NA' }}</td>
                                            <td>
                                                <div class="col">
                                                    <a title="Edit" href="javascript:;" class="btn1 btn-outline-primary"
                                                        onclick="editCurrentSlab('{{ $val->id }}','{{ route('admin.current-slab-update') }}','{{ route('admin.current-slab-edit', $val->id) }}','{{ $loop->iteration }}')">
                                                        <i class="bx bx-pencil me-0"></i>
                                                    </a>
                                                    {{-- <a title="Delete" class="btn1 btn-outline-danger trash remove-slab"
                                                        data-id="{{ $val->id }}"
                                                        data-action="{{ route('admin.current-slab-delete', $val->id) }}">
                                                        <i class="bx bx-trash me-0"></i>
                                                    </a> --}}
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


    <!-- Current Slab Modal -->
    <div class="modal fade" id="currentModal" role="dialog" aria-labelledby="currentModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="currentModalLabel">Modal title</h5>
                </div>
                <form id="currForm" action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead id="appHead"></thead>
                            <tbody id="appBody"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="slabSubmitBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Current Slab Modal -->

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#userTable').DataTable({
                lengthChange: false,
                // buttons: [{
                //         extend: 'excelHtml5',
                //         text: '<i class="far fa-file-excel"></i>',
                //         exportOptions: {
                //             columns: [0, 2]
                //         }
                //     },
                //     {
                //         extend: 'pdfHtml5',
                //         text: '<i class="fa fa-file-pdf"></i>',
                //         orientation: 'landscape',
                //         pageSize: 'LEGAL',
                //         exportOptions: {
                //             columns: [0, 2]
                //         }
                //     },
                //     {
                //         extend: 'print',
                //         text: '<i class="fa fa-print"></i>',
                //         exportOptions: {
                //             columns: [0, 2]
                //         }
                //     },
                // ],
                'columnDefs': [{
                    'targets': [2], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            table.buttons().container()
                .appendTo('#userTable_wrapper .col-md-6:eq(0)');


            $("body").on("click", ".remove-slab", function() {
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

            // Event delegation to handle dynamically added rows
            $(document).on('input', 'input[type="number"]', function() {
                restrictDecimal($(this));
            });

            $(document).on('input', 'input[type="text"]', function() {
                validateInput($(this));
            });

            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });

            // Function to restrict decimal places to 6
            function restrictDecimal(inputField) {
                var value = inputField.val();
                if (value === '') {
                    inputField.addClass('is-invalid');
                    return;
                } else {
                    inputField.removeClass('is-invalid');
                }

                if (value.includes('.')) {
                    var parts = value.split('.');
                    if (parts.length === 2 && parts[1].length > 6) {
                        parts[1] = parts[1].substring(0, 6);
                        inputField.val(parts.join('.'));
                    }
                }
            }

            // Function to validate text input
            function validateInput(inputField) {
                if (inputField.val().trim() === '') {
                    inputField.removeClass('is-valid').addClass('is-invalid');
                } else {
                    inputField.removeClass('is-invalid').addClass('is-valid');
                }
            }
        });

        function addCurrentSlab(url) {
            // Reset the form
            $('#currForm').attr('action', url);
            $('#currentModalLabel').html('Add Current Slab');
            $('#slabSubmitBtn').html('Save');

            // Set table headers
            $('#appHead').html(`<tr>
                                    <th colspan="2" class="text-center">Meter Type</th>
                                    <th colspan="2" class="text-center">Range</th>
                                    <th>
                                        <button class="btn btn-alt-primary" type="button" id="addRow">
                                            <i class="fa fa-plus-square-o" aria-hidden="true"></i>
                                        </button>
                                    </th>
                                </tr>
                                `);

            // Clear existing rows
            $('#appBody').html('');

            // console.log(url);
            $('#currentModal').modal('show');
        }

        // Event delegation to handle dynamically added elements
        $(document).on('click', '#addRow', function() {
            var html = `<tr>
                    <td>
                        <input type="text" name="meter_type_from[]" class="form-control" placeholder="Meter From">
                    </td>
                    <td>
                        <input type="text" name="meter_type_to[]" class="form-control" placeholder="Meter To">
                    </td>
                    <td>
                        <input type="number" step="0.000001" name="usage_from[]" class="form-control" placeholder="Scale From">
                    </td>
                    <td>
                        <input type="number" step="0.000001" name="usage_to[]" class="form-control" placeholder="Scale To">
                    </td>
                    <td>
                        <button class="btn btn-alt-danger removeRow" type="button">
                            <i class="fa fa-minus-square-o" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>`;
            $('#appBody').append(html);
        });

        // Event delegation for removing rows
        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
        });


        function editCurrentSlab(id, action, url, k) {
            // Clear existing rows
            $('#appBody').html('');
            $.ajax({
                url: url,
                method: "GET",
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                success: function(res) {
                    // console.log(res);

                    if (res.status) {
                        // Set form action and modal title
                        $('#currForm').attr('action', action);
                        $('#currentModalLabel').html('Update Current Slab');

                        // Set table headers
                        $('#appHead').html(`
                                    <tr>
                                        <th colspan="2" class="text-center">Meter Type</th>
                                        <th colspan="2" class="text-center">Range</th>
                                    </tr>
                                `);

                        // Populate form fields with existing data
                        var html = `
                            <tr>
                                <td>
                                    <input type="hidden" name="id" class="form-control"  value="${id}">
                                    <input type="text" name="meter_type_from" class="form-control" placeholder="Meter From" value="${res.data.meter_type_from}">
                                </td>
                                <td>
                                    <input type="text" name="meter_type_to" class="form-control" placeholder="Meter To" value="${res.data.meter_type_to}">
                                </td>
                                <td>
                                    <input type="number" step="0.000001" name="usage_from" class="form-control" placeholder="Scale From" value="${res.data.usage_from}">
                                </td>
                                <td>
                                    <input type="number" step="0.000001" name="usage_to" class="form-control" placeholder="Scale To" value="${res.data.usage_to }">
                                </td>
                            </tr>`;

                        $('#appBody').append(html);
                        $('#slabSubmitBtn').html('Update');

                        // Show modal
                        $('#currentModal').modal('show');
                    } else {
                        toastr.error(res.message, '');
                    }
                },
                error: function() {
                    toastr.error('Something went wrong. Please try again later!', '');
                }
            });
        }
    </script>
@endpush
