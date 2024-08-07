@extends('admin.layouts.app')
@section('title', 'Pricewise- Deals Edit')

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
                            href="{{ route('admin.deals-product.index') }}">Deals</a>
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
                    <h5 class="mb-0">Edit Deal</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.deals-product.update', $deal->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Title<sup class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="title" value="{{ $deal->title }}"
                                    placeholder="Title">
                                @error('title')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Valid Till<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <input type="datetime-local" class="form-control" name="valid_till"
                                    value="{{ $deal->valid_till }}" placeholder="Valid Till">
                                @error('valid_till')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Category<sup class="text-danger">*</sup></label>
                            <div class="">
                                @php
                                    $categories = App\Models\ProductCategory::all();
                                @endphp
                                <select class="form-control" name="category">
                                    <option value="">Select</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $deal->category == $category->id ? 'selected' : '' }}>
                                            {{ $category->title }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Deals Image<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <label for="upload_image">
                                    <img src="{{ asset('storage/images/shops/' . $deal->image) }}" id="uploaded_image"
                                        class="img img-responsive img-circle" width="100" alt="Select image" />

                                    <div class="overlay">
                                        <div>Click to Change Image</div>
                                    </div>
                                    <input type="file" name="image" class="image" id="upload_image"
                                        style="display:none" />
                                    <input type="hidden" name="cropped_image" id="cropped_image">

                                </label>
                                @error('image')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Status<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="status">
                                    <option value="active" {{ $deal->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $deal->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                    <button type="submit" class="btn btn-primary px-4">Upadte</button>
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

            function initializeChoices() {
                var element = document.querySelector("#product");
                if (element) {
                    choices = new Choices(element, {
                        allowHTML: false, // Enable HTML rendering in Choices
                        removeItemButton: true
                    });
                }
            }

            function loadProducts(categoryId) {
                var csrfToken = $('meta[name=csrf-token]').attr('content');

                // Make AJAX request with CSRF token
                $.ajax({
                    url: `{{ route('admin.get-products-categorywise') }}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': csrfToken // Include CSRF token in headers
                    },
                    data: {
                        category_id: categoryId
                    },
                    success: function(response) {
                        choices.destroy();

                        var products = response;
                        var productDropdown = $('#product');
                        productDropdown.empty(); // Empty the dropdown first
                        productDropdown.append('<option value="">Select</option>');
                        $.each(products, function(index, product) {
                            productDropdown.append('<option value="' + product.id + '">' +
                                product.title + '</option>');
                        });
                        // Destroy and reinitialize Choices library
                        initializeChoices();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            // Bind change event to category dropdown
            $('[name=category]').on('change', function() {
                var categoryId = $(this).val();
                loadProducts(categoryId);
            });

            // Initialize Choices library initially
            initializeChoices();
        });
    </script>
@endpush
