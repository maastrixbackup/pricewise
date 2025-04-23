@extends('admin.layouts.app')
@section('title', 'PriceWise- Operater Costs')
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
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.grid-operater.index') }}">Grid Operaters</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $gridOperater->operater_name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">

        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row mb-3">
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase">Operater Costs ({{ $gridOperater->operater_name ?? '' }})</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="mb-1">
                                <a href="javascript:;" class="btn btn-alt-info btn-sm p-1 my-1"
                                    onclick="manageRates('{{ $id }}','current','{{ $gridOperater->operater_name }}')">Annual
                                    Current
                                    Rate</a>
                            </div>
                            <div class="mb-1">
                                <a href="javascript:;" class="btn btn-alt-info btn-sm p-1 my-1"
                                    onclick="manageRates('{{ $id }}','gas','{{ $gridOperater->operater_name }}')">Annual
                                    Gas
                                    Rate</a>
                            </div>
                            <div class="mb-1">
                                <a href="javascript:;" class="btn btn-alt-info btn-sm p-1 my-1"
                                    onclick="measurementCosts('{{ $id }}','{{ $gridOperater->operater_name }}')">Gas
                                    Measurment
                                    Tariff Rate</a>
                            </div>
                            <div class="mb-1">
                                <a href="javascript:;" class="btn btn-alt-info btn-sm p-1 my-1"
                                    onclick="capacityTransportTariff('{{ $id }}','current','{{ $gridOperater->operater_name }}')">Current
                                    Transport
                                    Tariffs </a>
                            </div>
                            <div class="mb-1">
                                <a href="javascript:;" class="btn btn-alt-info btn-sm p-1 my-1"
                                    onclick="capacityTransportTariff('{{ $id }}','gas','{{ $gridOperater->operater_name }}')">Gas
                                    Transport
                                    Tariffs </a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div style="height: 800px; overflow-y:scroll;">
                                <div class="table-responsive mb-3">
                                    <table id="userTabl" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="text-center">Annual Current Rate </th>
                                            </tr>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Meter Type</th>
                                                <th>Range </th>
                                                <th>Rate </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!$anlCRates->isEmpty())
                                                @foreach ($anlCRates as $k => $v)
                                                    <tr>
                                                        <td>{{ $k + 1 }}</td>
                                                        <td>{{ $v['meter_type'] ?? 'NA' }}</td>
                                                        <td>{{ $v['range'] ?? '' }}</td>
                                                        <td>{{ $v['rate'] ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No Record Found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="table-responsive mb-3">
                                    <table id="userTabl" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="text-center">Annual Gas Rate </th>
                                            </tr>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Meter Type</th>
                                                <th>Range </th>
                                                <th>Rate </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!$anlGRates->isEmpty())
                                                @foreach ($anlGRates as $k => $v)
                                                    <tr>
                                                        <td>{{ $k + 1 }}</td>
                                                        <td>{{ $v['meter_type'] ?? 'NA' }}</td>
                                                        <td>{!! $v['range'] ?? '' !!}</td>
                                                        <td>{{ $v['rate'] ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No Record Found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="table-responsive mb-3">
                                    <table id="userTabl" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="text-center">Gas Measurement Tariffs</th>
                                            </tr>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Meter Type</th>
                                                <th>Range </th>
                                                <th>Rate </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!$gmtRates->isEmpty())
                                                @foreach ($gmtRates as $k => $v)
                                                    <tr>
                                                        <td>{{ $k + 1 }}</td>
                                                        <td>{{ $v['meter_type'] ?? 'NA' }}</td>
                                                        <td>{!! $v['range'] ?? '' !!}</td>
                                                        <td>{{ $v['rate'] ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No Record Found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>


                                <div class="table-responsive mb-3">
                                    <table id="userTabl" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="text-center"> Current Transport Tariffs Rate </th>
                                            </tr>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Meter Type</th>
                                                <th>Range </th>
                                                <th>Rate </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!$anlCttRates->isEmpty())
                                                @foreach ($anlCttRates as $k => $v)
                                                    <tr>
                                                        <td>{{ $k + 1 }}</td>
                                                        <td>{{ $v['meter_type'] ?? 'NA' }}</td>
                                                        <td>{{ $v['range'] ?? '' }}</td>
                                                        <td>{{ $v['rate'] ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No Record Found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="table-responsive mb-3">
                                    <table id="userTabl" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="text-center">Gas Transport Tariffs Rate </th>
                                            </tr>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Meter Type</th>
                                                <th>Range </th>
                                                <th>Rate </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!$anlGttRates->isEmpty())
                                                @foreach ($anlGttRates as $k => $v)
                                                    <tr>
                                                        <td>{{ $k + 1 }}</td>
                                                        <td>{{ $v['meter_type'] ?? 'NA' }}</td>
                                                        <td>{!! $v['range'] ?? '' !!}</td>
                                                        <td>{{ $v['rate'] ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No Record Found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Current Modal -->
    <div class="modal fade" id="currentModal" role="dialog" aria-labelledby="currentModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gasModalLabel">Manage Current Rates (<span id="currOpName"></span>)</h5>
                </div>
                <form action="{{ route('admin.store-usage-rate') }}" method="post" id="currForm">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Meter Type</th>
                                    <th>Maximum Power</th>
                                    <th>Rate</th>
                                </tr>
                            </thead>
                            <tbody id="cSlabData">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="currSubmit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Current Modal -->

    <!-- Gas Modal -->
    <div class="modal fade" id="gasModal" role="dialog" aria-labelledby="gasModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gasModalLabel">Manage Gas Rates (<span id="gasOpName"></span>)</h5>
                </div>
                <form action="{{ route('admin.store-usage-rate') }}" method="post" id="gasForm">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Meter Type</th>
                                    <th>Maximum Power</th>
                                    <th>Rate</th>
                                </tr>
                            </thead>
                            <tbody id="gSlabData">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="gasSubmit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Gas Modal -->

    <!-- Gas Measurement Tariff Modal -->
    <div class="modal fade" id="gmtModal" role="dialog" aria-labelledby="gmtModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gmtModalLabel">Manage Tariffs (<span id="gmtOpName"></span>)</h5>
                </div>
                <form action="{{ route('admin.store-gas-measurment-tariffs') }}" method="post" id="gmtForm">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Meter Type</th>
                                    <th>Maximum Power</th>
                                    <th>Rate</th>
                                </tr>
                            </thead>
                            <tbody id="gmtData">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="gmtSubmit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Gas Measurement Tariff Modal -->

    <!--Transport Tariff Modal -->
    <div class="modal fade" id="cttModal" role="dialog" aria-labelledby="cttModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cttModalLabel">Manage Tariffs (<span id="cttOpName"></span>)</h5>
                </div>
                <form action="{{ route('admin.store-capacity-transport-tariffs') }}" method="post" id="cttForm">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Meter Type</th>
                                    <th>Maximum Range</th>
                                    <th>Rate</th>
                                </tr>
                            </thead>
                            <tbody id="cttData">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="cttSubmit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Transport Tariff Modal -->
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

            $(document).on('input', 'input[type="number"]', function() {
                restrictDecimal($(this));
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
        });


        function manageRates(id, types, op_name) {
            // console.log(id + ' ' + key + ' ' + op_name);
            $.ajax({
                url: "{{ route('admin.edit-usage-rate') }}",
                type: 'GET',
                data: {
                    op_id: id,
                    source: types
                },
                success: function(response) {
                    // console.log(response);
                    // return false;
                    if (response.status) {
                        if (types === 'current') {
                            $('#currForm')[0].reset();
                            $('#cSlabData').html(response.htmlData);
                            $('#currOpName').html(op_name);
                            $('#currentModal').modal('show');
                        } else {
                            $('#gasForm')[0].reset();
                            $('#gSlabData').html(response.htmlData);
                            $('#gasOpName').html(op_name);
                            $('#gasModal').modal('show');
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }

        function measurementCosts(id, op_name) {
            $.ajax({
                url: "{{ route('admin.edit-gas-measurment-tariffs') }}",
                type: 'GET',
                data: {
                    op_id: id,
                },
                success: function(response) {
                    // console.log(response);
                    // return false;
                    if (response.status) {
                        $('#gmtForm')[0].reset();
                        $('#gmtData').html(response.htmlData);
                        $('#gmtOpName').html(op_name);
                        $('#gmtModal').modal('show');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }

        function capacityTransportTariff(id, types, op_name) {
            // console.log(id + ',' + op_name + ',' + types);
            $.ajax({
                url: "{{ route('admin.edit-capacity-transport-tariffs') }}",
                type: 'GET',
                data: {
                    op_id: id,
                    source: types
                },
                success: function(response) {
                    console.log(response);
                    // return false;
                    if (response.status) {
                        $('#cttForm')[0].reset();
                        $('#cttData').html(response.htmlData);
                        $('#cttOpName').html(op_name);
                        $('#cttModal').modal('show');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }


        $(document).on('click', '#currSubmit', function(e) {
            if (!validateUsageRates('#currForm')) {
                e.preventDefault();
            }
        });

        $(document).on('click', '#gasSubmit', function(e) {
            if (!validateUsageRates('#gasForm')) {
                e.preventDefault();
            }
        });

        function validateUsageRates(formId) {
            let isAllEmpty = true; // Flag to check if all fields are empty
            let inputs = $(`${formId} input[name="usage_rates[]"]`);

            inputs.each(function() {
                let value = $(this).val().trim(); // Get the input value

                if (value !== "" && !isNaN(value) && parseFloat(value) >= 0) {
                    isAllEmpty = false; // At least one field is valid
                    $(this).removeClass('is-invalid'); // Remove error class if valid
                } else {
                    $(this).addClass('is-invalid'); // Add error class if invalid
                }
            });

            if (isAllEmpty) {
                alert("At least one usage rate must be provided.");
                return false; // Prevent submission
            }
            return true; // Allow submission
        }
    </script>
@endpush
