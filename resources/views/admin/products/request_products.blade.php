@extends('admin.layouts.app')
@section('title', 'PriceWise- Shop Products')
@section('content')

    <style>
        .form-check-box {
            align-items: center;
        }

        .form-check-pr label {
            position: relative;
            cursor: pointer;
        }

        .form-check-pr input {
            padding: 0;
            height: initial;
            width: initial;
            margin-bottom: 0;
            display: none;
            cursor: pointer;
        }

        .form-check-pr label:before {
            content: '';
            -webkit-appearance: none;
            background-color: transparent;
            border: 2px solid #fa9f1d;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05);
            padding: 10px;
            display: inline-block;
            position: relative;
            vertical-align: middle;
            cursor: pointer;
            margin-right: 5px;
        }

        .form-check-pr input:checked+label:after {
            content: '';
            display: block;
            position: absolute;
            top: 3px;
            left: 9px;
            width: 6px;
            height: 14px;
            border: solid #07835d;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .spinner-box {
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: transparent;
        }

        .three-quarter-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid #fb5b53;
            border-top: 3px solid transparent;
            border-radius: 50%;
            animation: spin .5s linear 0s infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0);
            }

            to {
                transform: rotate(359deg);
            }
        }
    </style>

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
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase">Products</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="RequestTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product Name</th>
                                    <th>User Name</th>
                                    <th>Eamil</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($requestP)
                                    @foreach ($requestP as $req)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $req->productDetails->title }}</td>
                                            <td>{{ $req->user_name }}</td>
                                            <td>{{ $req->email }}</td>
                                            <td>{{ $req->qty }}</td>
                                            <td><a href="javascript:;" class="badge text-success"
                                                    onclick="chkRequest('{{ $req->id }}')">Details</a></td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <!-- The Modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="duplicateModal">
            <div class="modal-dialog ">
                <div class="modal-content ">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Requested Product</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        {{-- <div class="spinner-box">
                            <div class="three-quarter-spinner"></div>
                        </div> --}}
                        <div id="rqData"></div>

                    </div>
                    <!-- Modal footer -->
                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div> --}}

                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // fetchData(myParam);
            var table = $('#RequestTable').DataTable({
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
            // updateButtonState();

        });

        function chkRequest(id) {
            alert(id);
            $.ajax({
                url: '{{ route('admin.request_product_details') }}', // Replace with your URL
                method: 'POST', // Use 'POST' if required
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id
                },
                success: function(data) {
                    // Open the modal
                    // Handle the response data here
                    // console.log(data);
                    if (data.status) {
                        $('#rqData').html('');
                        $('#rqData').html(data.htmlData);
                        $('#duplicateModal').modal('show');
                        toastr.success('Data Retrieved Successfully', '');
                    } else {
                        toastr.error('Something went wrong!', '');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle the error here
                    console.error(error);
                }
            });
        }
        // Function to perform the AJAX request
        // let myParam = 1;

        // function fetchData(param) {
        //     $.ajax({
        //         url: '{{ route('admin.request_products') }}', // Replace with your URL
        //         method: 'GET', // Use 'POST' if required
        //         data: {
        //             id: param
        //         },
        //         success: function(data) {
        //             // Handle the response data here
        //             console.log(data);
        //             // Show or hide the button based on the count
        //             if (data.count > 0) {
        //                 $('#viewNotBtn').css('display', 'block');
        //             } else {
        //                 $('#viewNotBtn').css('display', 'none');
        //             }
        //             $('#notify').html('');
        //             $('#notify').html(data.count);
        //             $('#notDY').html('');
        //             $('#notDY').html(data.notify);
        //             // toastr.success(data, 'New Notification Received');
        //         },
        //         error: function(xhr, status, error) {
        //             // Handle the error here
        //             console.error(error);
        //         }
        //     });
        // }

        // // Call fetchData every 10 seconds, passing the parameter
        // setInterval(function() {
        //     fetchData(myParam);
        // }, 10000); // 10000 milliseconds = 10 seconds


        $('#p_status').on('change', function() {
            var sts = $('#p_status').val();
            console.log(sts);

            if (sts == 3) {
                $('#qty').val('');
                $('#qty').attr('readonly', true);
            } else if (sts == 0) {
                $('#qty').val('0');
                $('#qty').attr('readonly', true);
            } else if (sts == 1 || sts == 2) {
                $('#qty').val('');
                $('#qty').attr('readonly', false);
            }
        });
    </script>
@endpush
