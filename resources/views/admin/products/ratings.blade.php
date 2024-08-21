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
            <h6 class="mb-0 text-uppercase">Ratings</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ExclusiveDealsTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Rating</th>
                                    <th>Review Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($ratings)
                                    @foreach ($ratings as $record)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $record->productDetails->title ?? '' }}</td>
                                            <td>{{ $record->userDetails->name ?? '' }}</td>
                                            <td>{{ $record->email ?? 'NA' }} </td>
                                            <td>{{ $record->rating }}</td>
                                            <td>
                                                @if (Auth::guard('admin'))
                                                    <a href="javascript:;"><span
                                                            class="badge badge-success text-primary text-bold"
                                                            onclick="reviewDetails('{{ $record->id }}')">View</span></a>
                                                @endif
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
        });


        function reviewDetails(rid) {
            var arrival = $('#a').val();
            // console.log(p_id + ', ' + arrival);

            $.ajax({
                url: '{{ route('admin.get_review_details') }}',
                method: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: rid,
                    key: arrival
                },
                cache:false,
                success: function(data) {
                    console.log(data);
                    return false;
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
    </script>
@endpush
