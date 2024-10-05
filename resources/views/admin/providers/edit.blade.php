@extends('admin.layouts.app')
@section('title', 'Pricewise- Provider Edit')
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
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.providers', config('constant.category.energy')) }}">Providers</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Edit Provider</h5>
                </div>
                <div class="card-body p-4">
                    <form id="categoryFo" method="POST" action="{{ route('admin.providers.update', $provider->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label for="input35" class=" col-form-label">Name</label>
                                <div class="">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Name" value="{{ $provider->name }}">
                                </div>
                                <input type="hidden" name="category" id="category" class="form-control"
                                    value="{{ $provider->category }}">
                            </div>
                            {{-- <div class="col-md-4 mb-3">
                                <label for="input_type" class=" col-form-label">Category</label>
                                <div class="">
                                    <input type="text" class="form-control" readonly
                                        value="{{ $provider->categoryDetail->name }}">
                                    <select class="form-control" id="category" name="category">
                                        <option value="" disabled selected>Select</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @if ($provider->category == $category->id) selected @endif>{{ $category->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-4 mb-3">
                                <label for="input35" class=" col-form-label">Fixed delivery Cost</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">€</span>
                                    </div>
                                    <input type="number" class="form-control" id="fix_delivery" name="fix_delivery"
                                        placeholder="Fixed delivery Cost" step=".01"
                                        value="{{ $provider->fixed_deliver_cost }}" required>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="input35" class=" col-form-label">Grid Management Cost</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">€</span>
                                    </div>
                                    <input type="number" class="form-control" id="grid_management" name="grid_management"
                                        placeholder="Grid Management Cost" step=".01" required
                                        value="{{ $provider->grid_management_cost }}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="input35" class=" col-form-label">Feed In Tariff (Solar Buy Back)</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">€</span>
                                    </div>
                                    <input type="number" class="form-control" id="feed_in_tariff" name="feed_in_tariff"
                                        placeholder="Solar Buy Back" step=".01" required
                                        value="{{ $provider->feed_in_tariff }}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="status" class=" col-form-label">Status</label>
                                <div class="">
                                    <select class="form-control" id="status" name="status">
                                        <option value="" disabled>Select</option>
                                        <option value="1" @if ($provider->status == 1) selected @endif>Active
                                        </option>
                                        <option value="0" @if ($provider->status == 0) selected @endif>Inactive
                                        </option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="input40" class="col col-form-label mb-1"><b>Provider Logo </b></label>

                                <label for="upload_image">
                                    <img src="{{ asset('storage/images/providers/' . $provider->image) }}"
                                        id="uploaded_image" class="img img-responsive img-circle" width="100"
                                        alt="Select image" />
                                    <div class="overlay">
                                        <div>Click to Change Logo</div>
                                    </div>
                                    <input type="file" name="image" class="image" id="upload_image" accept="image/*"
                                        style="display:none" />
                                    <input type="hidden" name="cropped_image" id="cropped_image">

                                </label>
                            </div>
                        </div>
                        <div class="row mb-3">
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
    <script type="text/javascript">
        $("#categoryForm").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
                $(element).closest('.form-group').addClass("has-success");
            },

            rules: {
                name: "required",
            },
            messages: {
                name: "Name is missing",
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    method: "put",
                    data: $(form).serialize(),
                    success: function(data) {
                        //success
                        console.log(data);


                        if (data.status) {
                            location.href = data.redirect_location;
                        } else {
                            toastr.error(data.message.message, '');
                        }
                    },
                    error: function(e) {
                        toastr.error('Something went wrong . Please try again later!!', '');
                    }
                });
                return false;
            }
        });

        $(document).ready(function() {
            function restrictDecimal(inputField) {
                var value = inputField.val();
                if (value.indexOf('.') !== -1) {
                    var parts = value.split('.');
                    if (parts[1].length > 2) {
                        parts[1] = parts[1].substring(0, 2); // Restrict to two decimal places
                        inputField.val(parts[0] + '.' + parts[1]);
                    }
                }
            }

            $('input[type="number"]').on('keyup', function() {
                restrictDecimal($(this));
            });

        });

        // $('#grid_management').on('keyup', function() {
        //     var inputField = $('#grid_management');
        //     var value = inputField.val();
        //     // console.log(value);

        //     // Check if there's a decimal point in the value
        //     if (value.indexOf('.') !== -1) {
        //         var parts = value.split('.');

        //         // If the length of the decimal part is greater than 2, trim it
        //         if (parts[1].length > 2) {
        //             parts[1] = parts[1].substring(0, 2);
        //             inputField.val(parts[0] + '.' + parts[1]);
        //         }
        //     }
        // });
        // $('#vat').on('input', function() {
        //     if ($(this).val().length > 2) {
        //         $(this).val($(this).val().substring(0, 2)); // Trim input to the first 2 characters
        //     }
        // });
    </script>
@endpush
