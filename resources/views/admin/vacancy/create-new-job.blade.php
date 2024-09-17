@extends('admin.layouts.app')
@section('title', 'Pricewise : List all jobs')

@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
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
    @php
        use Illuminate\Support\Facades\DB;
        $qry_set1 = DB::table('job_industry')->get();
        $qry_set3 = $locations = DB::table('job_location')
                ->orderBy('job_location', 'asc') // or 'desc' for descending order
                ->get();
        $qry_set4 = DB::table('job_type')->get();
        $qry_set5 = DB::table('job_exp')->get();
        $qry_set6 = DB::table('job_qualification')->get();
        $qry_set7 = DB::table('job_price')->get();
    @endphp
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New Job</h5>
                </div>
                <div class="card-body" style="padding-bottom: 0">
                    <div class="row">
                        <div class="col-md-12">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-body p-4">
                    <form method="post" id="new_job" action="{{ route('admin.vacancy.form.submit') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Job Industry</label>
                                <select id="job_industry" class="form-control form-select" name="job_industry">
                                    <option value="" disabled="" selected="">Select</option>
                                    @foreach ($qry_set1 as $qry1)
                                        <option value="{{ $qry1->job_industry }}">{{ $qry1->job_industry }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Job Role</label>
                                <select id="job_role" class="form-control form-select" name="job_role">
                                    <option value="">Select Role</option>
                                    <!-- Job roles will be populated here -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Location</label>
                                <select id="job_location" class="form-control form-select" name="job_location">
                                    <option value="" disabled="" selected="">Select</option>
                                    @foreach ($qry_set3 as $qry3)
                                        <option value="{{ $qry3->job_location }}">{{ $qry3->job_location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Job Type</label>
                                <select id="job_type" class="form-control form-select" name="job_type">
                                    <option value="" disabled="" selected="">Select</option>
                                    @foreach ($qry_set4 as $qry4)
                                        <option value="{{ $qry4->job_type }}">{{ $qry4->job_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Experience Level</label>
                                <select id="job_exp" class="form-control form-select" name="job_exp">
                                    <option value="" disabled="" selected="">Select</option>
                                    @foreach ($qry_set5 as $qry5)
                                        <option value="{{ $qry5->exp }}">{{ $qry5->exp }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Educational Qualification</label>
                                <select id="edu_qual" class="form-control form-select" name="edu_qual">
                                    <option value="" disabled="" selected="">Select</option>
                                    @foreach ($qry_set6 as $qry6)
                                        <option value="{{ $qry6->qual }}">{{ $qry6->qual }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Price(EURO per Hour)</label>
                                <select id="salary" class="form-control form-select" name="salary">
                                    <option value="" disabled="" selected="">Select</option>
                                    @foreach ($qry_set7 as $qry7)
                                        <option value="{{ $qry7->pph }}">{{ $qry7->pph }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="input_type" class="col-form-label">Job Title </label>
                                <input type="text" id="job_title" class="form-control" name="job_title">
                            </div>
                            <div class="col-12">
                                <label for="input_type" class="col-form-label">Description</label><br>
                                <textarea id="editor" name="job_desc"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Is this a remote job</label>
                                <select id="remote_job" class="form-control form-select" name="remote_job">
                                    <option value="" disabled="" selected="">Select</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="input_type" class="col-form-label">Job Status</label>
                                <select id="job_status" class="form-control form-select" name="job_status">
                                    <option value="Draft" selected="">Draft</option>
                                    <option value="Published">Published</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-form-label"></label>
                            <div class="">
                                <div class="field_error"></div>
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4">Submit</button>
                                    <button type="reset" class="btn btn-light px-4">Reset</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                    'undo', 'redo'
                ]
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    <style>
        .ck-editor__editable_inline {
            min-height: 300px;
        }

        .is-invalid {
            border-color: #dc3545;
        }
        .field_error{
            color: #ff0000;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#new_job').on('submit', function(e) {
                var isValid = true;
                var jobIndustry = $('#job_industry').val();

                // Clear any previous errors
                $('#job_industry').removeClass('is-invalid');
                $('.field_error').text('');

                // Validate job industry field
                if (jobIndustry === null || jobIndustry === '') {
                    isValid = false;
                    $('#job_industry').addClass('is-invalid');
                    $('.field_error').text('Please Check for Errors.');
                }

                // If form is not valid, prevent submission
                if (!isValid) {
                    e.preventDefault();
                }
            });
            $('#job_industry').on('change', function() {
                var industryId = $(this).val();
                if (industryId) {
                    $.ajax({
                        url: 'get-job-roles/' + industryId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#job_role').empty();
                            $('#job_role').append(
                                '<option value="" disabled="" selected="">Select Role</option>'
                            );
                            $.each(data, function(key, value) {
                                $('#job_role').append('<option value="' + value
                                    .job_role + '">' + value.job_role + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            $('#job_role').empty();
                            $('#job_role').append(
                                '<option value="" disabled="" selected="">Select Role</option>'
                            );
                            console.error('Not Found:', status, error);
                        }
                    });
                } else {
                    $('#job_role').empty();
                    $('#job_role').append('<option value="">Select Role err</option>');
                }
            });
        });
    </script>


@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Choices(document.querySelector(".choices-multiple"), {
                removeItemButton: true
            });
        });
    </script>
@endpush
