@extends('admin.layouts.app')
@section('title', 'Pricewise- Smartphone Create')
@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.smartphone.index') }}">Smartphone</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Smartphone Provider</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.smartphone.update',$sp_record->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Provider Name<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="provider_name" id="provider_name" value="{{ $sp_record->provider_name }}"
                                    placeholder="Provider Name">
                                @error('provider_name')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Url<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="provider_url" id="provider_url" value="{{ $sp_record->slug }}"
                                    placeholder="Provider Url" readonly>
                                @error('provider_url')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Description</label>
                            <div class="">
                                <textarea name="description" class="form-control" cols="30" rows="5">{{ $sp_record->description }}</textarea>
                                @error('description')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Status<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="type">
                                    <option value="active" {{$sp_record->status == 'active' ? 'selected' : ''}}>Active</option>
                                    <option value="inactive" {{$sp_record->status == 'inactive' ? 'selected' : ''}}>Inactive</option>
                                </select>
                                @error('type')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <label class=" col-form-label"></label>
                            <div class="">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4" name="submit2">update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {

    });
</script>
<script>
    $("#provider_name").keyup(function() {
        var provider_name_val = $("#provider_name").val();
        $("#provider_url").val(provider_name_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
    });
    $("#provider_name").keydown(function() {
        var provider_name_val = $("#provider_name").val();
        $("#provider_url").val(provider_name_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
    });
</script>
@endpush
