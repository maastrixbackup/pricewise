@extends('admin.layouts.app')
@section('title', 'Pricewise- Provider Feature')
@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.dashboard') }}">
                            Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.provider-feature.index') }}">Provider Feature</a>
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
                    <h5 class="mb-0">Provider Feature</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{route('admin.provider-feature.store')}}">
                        @csrf
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Provider<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select name="provider_id" class="form-control" id="provider_id">
                                    <option value="" selected disabled>Select</option>
                                    @if ($sp_provider)
                                        @foreach ($sp_provider as $item => $val)
                                            <option value="{{ $val->id }}"{{$sp_feature->provider_id == $val->id ? 'selected' : ''}}>{{ $val->provider_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('provider')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Mobile Data<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="mobile_data" id="mobile_data"
                                    value="{{ $sp_feature->mobile_data }}" placeholder="e.g- 1GB">
                                @error('mobile_data')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Calling/Texting<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="call_text" id="call_text"
                                    value="{{ $sp_feature->call_text }}" placeholder="Calling/Texting" >
                                @error('call_text')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Price<sup class="text-danger">*</sup></label>
                            <div class="">
                                <input type="number" class="form-control" name="price" id="price"
                                    value="{{ $sp_feature->price }}" placeholder="Price">
                                @error('price')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Valid Till<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <input type="datetime-local" class="form-control" name="valid_till" id="valid_till"
                                    value="{{ $sp_feature->valid_till }}" placeholder="Valid Till" >
                                @error('valid_till')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Description</label>
                            <div class="">
                                <textarea name="description" class="form-control" cols="30" rows="5">{{ $sp_feature->description }}</textarea>
                                @error('description')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Status<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="status">
                                    <option value="active" {{ $sp_feature->status == 'active' ? 'selected' : ''}}>Active</option>
                                    <option value="inactive"  {{ $sp_feature->status == 'inactive' ? 'selected' : ''}}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <label class=" col-form-label"></label>
                            <div class="">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>
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
        $(document).ready(function() {
            // Append new input group when add button is clicked
            $(".add").click(function() {
                $('#appData').append(`
            <div class="input-group mb-3">
                <input type="number" class="form-control" name="discount[]" id="discount" placeholder="Discount">
                <div class="input-group-append">
                    <a href="javascript:void(0);" class="btn btn-danger remove" id="basic-addon2">
                        <i class="fa fa-minus" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        `);
            });

            // Use event delegation to handle click event on dynamically added remove buttons
            $('#appData').on('click', '.remove', function() {
                $(this).closest('.input-group').remove();
            });


        });
    </script>
    <script>
        $("#discount_title").keyup(function() {
            var discount_title_val = $("#discount_title").val();
            $("#discount_url").val(discount_title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
        $("#discount_title").keydown(function() {
            var discount_title_val = $("#discount_title").val();
            $("#discount_url").val(discount_title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
    </script>
@endpush
