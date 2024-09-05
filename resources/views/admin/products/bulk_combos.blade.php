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
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.products.index') }}">Products</a></li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">

            <div class="btn-group">
                <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#dealsProductModal">Add Deal</a>

            </div>

        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase">Bulk Deals</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ExclusiveDealsTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Title</th>
                                    <th>Selling Price</th>
                                    <th>Publish/Draft</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($comboProducts)
                                    @foreach ($comboProducts as $record)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $record->comboProductDetails->title ?? '' }}</td>

                                            <td>{{ $record->deal_price }}</td>
                                            <td>
                                                <input type="checkbox" style="width: 20px; height:20px;"
                                                    id="a_{{ $loop->iteration }}" name="status[]"
                                                    onclick="stsUpdate('{{ $record->id }}', this.checked ? 1 : 0)"
                                                    value="{{ $record->status }}"
                                                    @if ($record->status == 1) checked @endif>
                                                <label for="a_{{ $loop->iteration }}"></label>
                                            </td>
                                            <td>
                                                <div class="col d-flex col d-flex justify-content-evenly">
                                                    {{-- <a title="Edit" href=""
                                                        class="btn1 btn-outline-primary"><i
                                                            class="bx bx-pencil me-0"></i></a> --}}
                                                    <a title="Delete" class="btn1 btn-outline-danger trash remove-package"
                                                        data-id="{{ $record->id }}"
                                                        data-action="{{ route('admin.delete_product_deals', $record->id) }}"><i
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


        <!-- The Modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="dealsProductModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Create Deal</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <form id="productFor" method="post" action="{{ route('admin.store_product_deals') }}"
                        enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="row">
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <div class="col-md-6">
                                            <label for="form-label">Deal Product <span class="text-danger">*</span></label>
                                            <select name="deal_id" id="deal_id" class="form-select" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach ($shopProduct as $sP)
                                                    @php
                                                        $found = false;
                                                    @endphp
                                                    @foreach ($comboProducts as $cP)
                                                        @if ($cP->deal_id == $sP->id && $cP->product_id == $id)
                                                            @php
                                                                $found = true;
                                                            @endphp
                                                            <option value="{{ $sP->id }}" disabled>
                                                                {{ $sP->title }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                    @if (!$found)
                                                        <option value="{{ $sP->id }}">{{ $sP->title }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="form-label">Deal Price <span class="text-danger">*</span></label>
                                            <input type="number" name="deal_price" id="deal_price" step=".01"
                                                onkeyup="enforceTwoDecimalPlaces()" class="form-control"
                                                placeholder="Deal Price" value="" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 mt-3 add-scroll">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" class="form-control"
                                                    name="status" value="1">Published</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" class="form-control"
                                                    name="status" checked value="0">Draft</label>
                                        </div>
                                    </div>


                                    <div class="">
                                        <label class=" col-form-label"></label>
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-primary px-4"
                                                name="submit2">Submit</button>
                                            <button type="reset" class="btn btn-light px-4">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal footer -->
                            {{-- <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div> --}}

                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip()
                var table = $('#ExclusiveDealsTable').DataTable({
                    lengthChange: false,
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
                                '<input name="_method" type="hidden" value="POST">');
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


            function stsUpdate(d_id, sts) {
                // console.log(d_id + ',' + sts);

                $.ajax({
                    url: '{{ route('admin.update_deals_status') }}',
                    method: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: d_id,
                        status: sts
                    },
                    cache: false,
                    success: function(data) {
                        if (data.status) {
                            toastr.success(data.message, '');
                        } else {
                            toastr.error(data.message, '');
                        }
                    },
                    error: function(e) {
                        toastr.error('Something went wrong. Please try again later!', '');
                    }
                });
            }


            $('#deal_id').on('change', function() {
                $('#deal_price').val('');
            });

            function enforceTwoDecimalPlaces() {
                var inputField = $('#deal_price');
                var value = inputField.val();
                // console.log(value);

                // Check if there's a decimal point in the value
                if (value.indexOf('.') !== -1) {
                    var parts = value.split('.');

                    // If the length of the decimal part is greater than 2, trim it
                    if (parts[1].length > 2) {
                        parts[1] = parts[1].substring(0, 2);
                        inputField.val(parts[0] + '.' + parts[1]);
                    }
                }
            }
        </script>
    @endpush
