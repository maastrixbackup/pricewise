@extends('admin.layouts.app')
@section('title', 'Energise - Energy Products')
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
            {{-- @if (Auth::guard('admin')->user()->can('energy-add')) --}}
            <div class="btn-group">
                {{-- <a href="{{route('admin.energy.create')}}" class="btn btn-secondary">Import</a> --}}
                <a href="{{ route('admin.energy.create') }}" class="btn btn-primary">Create New Energy Product</a>
            </div>
            {{-- @endif --}}
        </div>
    </div>
    <!--end breadcrumb-->
    {{-- <form method="GET" id="search-form" name="searchform">
        <div class="row">
            <div class="col-lg-3 mb-2">
                <label for="input35" class=" col-form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name"
                    placeholder="Search by Product Name">
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
                    @if ($objEnergyFeatures)
                        @foreach ($objEnergyFeatures as $tv)
                            <option value="{{ $tv->id }}">{{ $tv->features }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

        </div>
    </form> --}}
    <div class="row">
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase">Energy Products </h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="" style="overflow-x:auto;">
                        <table id="userTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="6"></th>
                                    <th colspan="7" class="text-center">Cost Per Unit</th>
                                    <th colspan="3"></th>
                                </tr>
                                <tr>
                                    <th>Sl</th>
                                    <th>Provider</th>
                                    <th>Contact Year</th>
                                    <th>Delivery Cost</th>
                                    <th>Grid Manage Cost</th>
                                    <th>Feed In Tariffs</th>
                                    <th>Power Cost </th>
                                    <th>Gas Cost </th>
                                    <th>Tax On Electric</th>
                                    <th>Tax On Gas</th>
                                    <th>ODE On Electric</th>
                                    <th>ODE On Gas</th>
                                    <th>VAT</th>
                                    <th>Discount(%)</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($objEnergy)
                                    @foreach ($objEnergy as $energy)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $energy->providerDetails->name ?? '' }}</td>
                                            <td>{{ $energy->contract_length }}</td>
                                            <td>{{ $energy->fixed_delivery }}</td>
                                            <td>{{ $energy->grid_management }}</td>
                                            <td>{{ $energy->feed_in_tariff }}</td>
                                            <td>{{ $energy->power_cost_per_unit }}</td>
                                            <td>{{ $energy->gas_cost_per_unit }}</td>
                                            <td>{{ $energy->tax_on_electric }}</td>
                                            <td>{{ $energy->tax_on_gas }}</td>
                                            <td>{{ $energy->ode_on_electric }}</td>
                                            <td>{{ $energy->ode_on_gas }}</td>
                                            <td>{{ $energy->vat }}</td>
                                            <td>{{ $energy->discount ?? 'NA' }}</td>
                                            <td>
                                                @php
                                                    $color = $energy->status == '0' ? 'warning' : 'success';
                                                    $text = $energy->status == '0' ? 'Draft' : 'Published';
                                                @endphp
                                                <a href="javascript:;" id="status_{{ $energy->id }}"
                                                    onclick="changeStatus({{ $energy->id }})"
                                                    data-action="{{ route('admin.energy-status-update', $energy->id) }}"
                                                    class="text-{{ $color }}">{{ $text }}</a>
                                            </td>
                                            <td>
                                                <a title="Edit" href="{{ route('admin.energy.edit', $energy->id) }}"
                                                    class="btn1 btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>
                                                <a title="Delete" class="btn1 btn-outline-danger trash remove-energy"
                                                    data-id="{{ $energy->id }}"
                                                    data-action="{{ route('admin.energy.destroy', $energy->id) }}"><i
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf"></i>',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="far fa-file-csv"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
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
