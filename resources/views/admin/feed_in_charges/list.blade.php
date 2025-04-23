@extends('admin.layouts.app')
@section('title', 'Energies - Feed In Charges')

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
                            href="{{ route('admin.providers', config('constant.category.energy')) }}">Providers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $provider->name ?? 'Provider' }}</li>
                </ol>
            </nav>
        </div>
        @php
            if (isset($id)) {
                $feedIn = $feedsIn;
                $url = route('admin.add-feed-in-charges', $id);
            } else {
                $cls = 'd-none';
                $feedIn = $feedIn;
            }
        @endphp
        <div class="ms-auto {{ $cls ?? '' }}">
            {{-- @if (Auth::guard('admin')->user()->can('energy-add')) --}}
            <div class="btn-group ">
                <a href="{{ $url ?? '' }}" class="btn btn-primary">Create</a>
            </div>
            <div class="btn-group d-none">
                <a href="javascript:;" id="excelUpload" class="btn btn-primary" data-toggle="modal"
                    data-target="#uploadExelModal">Upload
                    Excel <i class="fa fa-upload" aria-hidden="true"></i>
                </a>
            </div>
            <div class="btn-group d-none">
                <a href="javascript:;" id="downloadExcel" class="btn btn-primary">Excel Format <i class="fa fa-download"
                        aria-hidden="true"></i>
                </a>
            </div>
            {{-- @endif --}}
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase">{{ $provider->name ?? 'Provider' }} Feed In Charges </h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="" style="overflow-x:auto;">

                        <table id="roleTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Scale</th>
                                    <th>Range From</th>
                                    <th>Range To</th>
                                    <th>Cost/Day (€) </th>
                                    <th>Cost/Year (€)</th>
                                    <th class="{{ $cls ?? '' }}">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($feedIn)
                                    @foreach ($feedIn as $feed)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $feed->scale ?? '' }}</td>
                                            <td>{{ $feed->range_from ?? '' }}</td>
                                            <td>{{ $feed->range_to ?? '' }}</td>
                                            <td>{{ $feed->cost_per_day ?? '' }}</td>
                                            <td>{{ $feed->cost_per_year ?? '' }}</td>
                                            <td class="{{ $cls ?? '' }}">
                                                <a title="Edit" href="{{ route('admin.edit-feed-in-charges', $id ?? '') }}"
                                                    class="btn1 btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>
                                                <a title="Delete" class="btn1 btn-outline-danger trash remove-feed"
                                                    data-id="{{ $feed->id }}"
                                                    data-action="{{ route('admin.delete-feed-in-charges', $feed->id) }}"><i
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
    <div class="modal fade" id="uploadExelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadExelModalLabel">Upload Excel</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
                </div>
                <form action="{{ route('admin.import-feed-in-charges') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="file" class="form-control" name="file" id="file"
                                placeholder="Choose a file" accept=".xlsx" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal End Here -->

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            var table = $('#roleTable').DataTable({
                lengthChange: false,
                buttons: [
                    // {
                    //     extend: 'excelHtml5',
                    //     text: '<i class="far fa-file-excel"></i>',
                    //     exportOptions: {
                    //         columns: [1, 2, 3, 4]
                    //     }
                    // },
                    // {
                    //     extend: 'pdfHtml5',
                    //     text: '<i class="fa fa-file-pdf"></i>',
                    //     orientation: 'landscape',
                    //     pageSize: 'LEGAL',
                    //     exportOptions: {
                    //         columns: [1, 2, 3, 4]
                    //     }
                    // },
                    // {
                    //     extend: 'print',
                    //     text: '<i class="fa fa-print"></i>',
                    //     exportOptions: {
                    //         columns: [1, 2, 3, 4]
                    //     }
                    // },
                ],
                'columnDefs': [{
                    'targets': [4], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            table.buttons().container()
                .appendTo('#roleTable_wrapper .col-md-6:eq(0)');

            $("body").on("click", ".remove-feed", function(event) {
                event.preventDefault();
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
                            "<form class='form-inline remove-form' method='Post' action='" +
                            action +
                            "'></form>");
                        $('body').find('.remove-form').append(
                            '<input name="_method" type="hidden" value="DELETE">');
                        $('body').find('.remove-form').append(
                            '<input name="_token" type="hidden" value="' +
                            token + '">');
                        $('body').find('.remove-form').append(
                            '<input name="id" type="hidden" value="' + id +
                            '">');
                        $('body').find('.remove-form').submit();
                    }
                });
            });

            $("#downloadExcel").on('click', function(e) {
                // alert('Ok');
                fetch('download-excel', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        },
                    })
                    .then(response => {
                        console.log(response);
                        // return false;

                        if (response.ok) {
                            return response.blob();
                        } else {
                            throw new Error('Failed to download the file.');
                        }
                    })
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = 'feed_in_charges.xlsx'; // File name
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                    })
                    .catch(error => console.error('Error:', error));
            });

            $("#excelUpload").on('click', function(e) {
                $("#uploadExelModalLabel").html('');
                $("#uploadExelModalLabel").html('Upload Excel Here');
                $("#uploadExelModal").modal('show');
            });
        });
    </script>
@endpush
