@extends('admin.layouts.app')
@section('title', 'PriceWise- Document Uploads')
@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">Providers</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.providers.edit', $p->id) }}">Go Back</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">

        <form action="{{ route('admin.upload-documents-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mb-3">
                <div class="card-header">
                    <b>Upload Documents</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="" class="form-label">Choose Documents</label> <small>(Upload
                                    Multiple Files)</small>
                                <input type="file" class="form-control" multiple name="files[]" id="files"
                                    accept=".pdf" required onchange="checkFileSizes()">
                                @error('files')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                                <input type="hidden" name="p_id" value="{{ $p->id }}">
                                <input type="hidden" name="category" value="{{ $p->category }}">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="btn-group mt-4 pt-1">
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                            <div class="btn-group mt-4 pt-1">
                                <button type="reset" class="btn btn-light px-4">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-12 col-lg-12">
            <h6 class="mb-0 text-uppercase">Documents</h6>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Document</th>
                                    {{-- <th>Category</th> --}}
                                    @if (Auth::guard('admin')->user()->can('providers-edit'))
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($documents)
                                    @foreach ($documents as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if (pathinfo($val->filename, PATHINFO_EXTENSION) === 'pdf')
                                                    <a href="{{ asset('storage/documents/' . $val->filename) }}"
                                                        target="_blank">
                                                        <i class="fa fa-file-pdf-o text-danger" aria-hidden="true"></i>
                                                        {{ $val->type }}</a>
                                                @else
                                                    <a href="{{ asset('storage/documents/' . $val->filename) }}"><i
                                                            class="fa fa-file-text text-primary" aria-hidden="true"></i>
                                                        {{ $val->filename }}</a>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="col">
                                                    @if (Auth::guard('admin')->user()->can('providers-delete'))
                                                        <a title="Delete"
                                                            class="btn btn-outline-danger trash remove-document"
                                                            data-id="{{ $val->id }}"
                                                            data-action="{{ route('admin.delete-document', $val->id) }}"><i
                                                                class="bx bx-trash me-0"></i></a>
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
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#userTable').DataTable({
                lengthChange: false,
            });

            // table.buttons().container()
            //     .appendTo('#userTable_wrapper .col-md-6:eq(0)');


            $("body").on("click", ".remove-document", function() {
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
                            '<input name="_method" type="hidden" value="post">');
                        $('body').find('.remove-form').append(
                            '<input name="_token" type="hidden" value="' + token + '">');
                        $('body').find('.remove-form').append(
                            '<input name="id" type="hidden" value="' + id + '">');
                        $('body').find('.remove-form').submit();
                    }
                });
            });
        });

        function checkFileSizes() {
            const fileInput = document.getElementById('files');
            const files = fileInput.files;
            const maxSize = 10 * 1024 * 1024; // 10 MB in bytes
            console.log(maxSize);

            // let valid = true;
            // Create a new DataTransfer object to store valid files
            const dataTransfer = new DataTransfer();

            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                if (file.size <= maxSize) {
                    // Add valid files to DataTransfer
                    dataTransfer.items.add(file);
                } else {
                    alert(`File "${file.name}" is too large and will be removed.`);
                }
            }
            // Update the file input with the new filtered FileList
            fileInput.files = dataTransfer.files;

            if (dataTransfer.files.length === files.length) {
                alert("All files are within the allowed size limit.");
            } else if (dataTransfer.files.length === 0) {
                alert("All selected files exceeded the size limit.");
            } else {
                alert("Files exceeding the size limit have been removed.");
            }
        }
    </script>
@endpush
