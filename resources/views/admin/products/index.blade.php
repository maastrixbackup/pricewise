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
                                    <th>Category</th>
                                    <th>Availability</th>
                                    <th>Selling Price</th>
                                    {{-- <th>Product Type</th> --}}
                                    {{-- <th>Featured Product</th> --}}
                                    <th>New Arrival</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($shopProducts)
                                    @foreach ($shopProducts as $record)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $record->title ?? '' }}</td>
                                            <td>{{ $record->categoryDetails->title ?? '' }}</td>
                                            <td>
                                                @if ($record->p_status == 1)
                                                    <span
                                                        class="badge rounded-pill text-success bg-light-success  text-uppercase"
                                                        style="color: #3DB44D;">In
                                                        Stock</span><span>({{ $record->qty }})</span>
                                                @elseif ($record->p_status == 2)
                                                    <span class="badge rounded-pill bg-light-warning  text-uppercase"
                                                        style="color: rgb(255, 30, 0);">Limited
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
                                            <td>{{ '€' . $record->sell_price }}</td>
                                            {{-- <td>
                                                @if ($record->product_type == 'personal')
                                                    <span class="  text-uppercase">Personal</span>
                                                @elseif ($record->product_type == 'commercial')
                                                    <span class="text-uppercase">Commercial</span>
                                                @else
                                                    <span class="  text-uppercase">Large Bussiness</span>
                                                @endif
                                            </td> --}}
                                            {{-- <td>
                                                <input type="checkbox" style="width: 20px; height:20px;"
                                                    id="a_{{ $loop->iteration }}" name="featured[]"
                                                    onclick="chFeatured('{{ $record->id }}', '{{ $loop->iteration }}')"
                                                    value="1" @if ($record->is_featured == 1) checked @endif><label
                                                    for="a"></label>
                                            </td> --}}
                                            <td>
                                                {{-- <div class=" form-check-pr"> --}}
                                                <input type="checkbox" style="width: 20px; height:20px;"
                                                    id="a_{{ $loop->iteration }}" name="new_arrival[]"
                                                    onclick="chArrival('{{ $record->id }}', '{{ $loop->iteration }}')"
                                                    value="1" @if ($record->new_arrival == 1) checked @endif><label
                                                    for="a"></label>
                                                {{-- </div> --}}
                                            </td>
                                            <td>
                                                <div class="col d-flex col d-flex justify-content-evenly">
                                                    <a title="Edit"
                                                        href="{{ route('admin.products.edit', $record->id) }}"
                                                        class="btn1 btn-outline-primary"><i
                                                            class="bx bx-pencil me-0"></i></a>
                                                    <a title="Delete" class="btn1 btn-outline-danger trash remove-package"
                                                        data-id="{{ $record->id }}"
                                                        data-action="{{ route('admin.products.destroy', $record->id) }}"><i
                                                            class="bx bx-trash me-0"></i></a>
                                                    <a href="javascript:;" title="Duplicate"
                                                        onclick="duplicateP('{{ $record->id }}')"><i
                                                            class="fa fa-files-o" aria-hidden="true"></i></a>
                                                    <a href="javascript:;" title="Ratings"
                                                        onclick="showRateings('{{ $record->id }}')"><i
                                                            class="fa fa-star-o" aria-hidden="true"></i>
                                                    </a>
                                                    
                                                    @if ($record->p_status == 1 || $record->p_status == 2)
                                                        <a href="{{ route('admin.combo-deals', $record->id) }}"
                                                            title="Combo Deals"><i class="fa fa-plus-square"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @endif
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

        <!-- The Modal -->
        <div class="modal fade" id="ratingsModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Ratings
                            <i class="fa fa-star rates" aria-hidden="true"></i>
                            <i class="fa fa-star rates" aria-hidden="true"></i>
                            <i class="fa fa-star rates" aria-hidden="true"></i>
                            <i class="fa fa-star-half-o rates" aria-hidden="true"></i>
                            <i class="fa fa-star-o rates" aria-hidden="true"></i>
                        </h4>
                        <span id="avgRating"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <table class="rating-bars" style="width: 100%;" id="rateDetails">
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div> --}}

                </div>
            </div>
        </div>


        <!-- The Modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="duplicateModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Duplicate Product</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        {{-- <div class="spinner-box">
                            <div class="three-quarter-spinner"></div>
                        </div> --}}
                        <form id="productFor" method="post" action="{{ route('admin.store_duplicate_product') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body p-4">
                                    <div id="fData"></div>
                                </div>
                            </div>

                    </div>

                    </form>
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
            // updateButtonState();

        });


        function chArrival(p_id) {
            var arrival = $('#a').val();
            $.ajax({
                url: '{{ route('admin.update_new_arrival') }}',
                method: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: p_id,
                    key: arrival
                },
                cache: false,
                success: function(data) {
                    // console.log(data);
                    // return false;
                    var toastMixin = Swal.mixin({
                        toast: true,
                        icon: 'success',
                        title: 'General Title',
                        animation: false,
                        position: 'top-right',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                    if (data.status) {
                        toastr.success(data.message, '');
                        // toastMixin.fire({
                        //     animation: true,
                        //     title: data.message
                        // });
                    } else {
                        toastr.error(data.message, '');
                        // toastMixin.fire({
                        //     icon:'error',
                        //     animation: true,
                        //     title: data.message
                        // });
                    }
                },
                error: function(e) {
                    toastr.error('Something went wrong. Please try again later!', '');
                }
            });

        }

        // function chFeatured(id) {
        //     alert('Are You want to set as featured product?');
        // }

        function duplicateP(pid) {
            // console.log('Function called with pid:', pid);

            $.ajax({
                url: '{{ route('admin.duplicate_product') }}',
                method: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product_id: pid,
                },
                cache: false,
                success: function(data) {
                    console.log('AJAX Success:', data);

                    // Check if data contains the expected HTML
                    if (data.html) {
                        $('#fData').html(''); // Insert the new data
                        $('#fData').html(data.html); // Insert the new data

                        // Open the modal
                        $('#duplicateModal').modal('show');
                    } else {
                        console.error('Invalid response data:', data);
                        toastr.error('Failed to load data', '', {
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
        }


        function showRateings(pid) {
            $.ajax({
                url: '{{ route('admin.show_product_ratings') }}',
                method: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: pid,
                },
                cache: false,
                success: function(data) {
                    // console.log(data);

                    if (data.success) { // Check for 'success' instead of 'status' as per the backend response
                        $('#rateDetails').html(''); // Clear previous data
                        $('#rateDetails').html(data.rateData); // Insert the new rating data

                        // $('#avgRating').html(''); // Clear previous data
                        // $('#avgRating').html(data.averageRating); // Insert the new rating data

                        // Open the Bootstrap modal
                        $('#ratingsModal').modal('show');
                    } else {
                        toastr.error(data.message, '');
                    }
                },
                error: function(e) {
                    toastr.error('Something went wrong. Please try again later!', '');
                }
            });
        }


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
        // function updateButtonState() {
        //     var qty = parseInt($('#qty').val(), 10); // Convert the value to an integer

        //     if (qty <= 1) {
        //         $('#remB').attr('disabled', true); // Disable the remove button if qty is 1 or less
        //     } else {
        //         $('#remB').attr('disabled', false); // Enable the remove button if qty is greater than 1
        //     }
        // }

        // function addQty() {
        //     var qty = parseInt($('#qty').val(), 10); // Convert the value to an integer
        //     $('#qty').val(qty + 1); // Update the quantity
        //     updateButtonState(); // Update the button state
        // }

        // function removeQty() {
        //     var qty = parseInt($('#qty').val(), 10); // Convert the value to an integer
        //     if (qty > 1) {
        //         $('#qty').val(qty - 1); // Update the quantity
        //         updateButtonState(); // Update the button state
        //     }
        // }
    </script>
@endpush
