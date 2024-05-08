@extends('admin.layouts.app')
@section('title', 'Pricewise- Tv Package Edit')

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
                            href="{{ route('admin.exclusive-deals.index') }}">Exclusive Deals</a>
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
                    <form method="post" action="{{ route('admin.exclusive-deals.update',$deal->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
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
                            <label for="input_type" class=" col-form-label">Icon<sup class="text-danger">*</sup></label>
                            <div class="">
                                <input type="file" class="form-control" name="icon" placeholder="Icon">
                                @error('icon')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Category<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="category">
                                    <option value="">Select</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $deal->category == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Products<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control choices-multiple" name="products[]" id="product" multiple>
                                    <option value="">Select</option>
                                </select>
                                @error('products')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Status<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="status">
                                    <option value="active">active</option>
                                    <option value="active" selected>inactive</option>
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
                                    <button type="submit" class="btn btn-primary px-4">Update</button>
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
            var dealCategory = @json($deal->category);
            var dealProducts = JSON.parse(@json($deal->products));

            loadProducts(dealCategory)

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
                            var option =
                                `<option value="${product.id}">${product.title}</option>`;
                            if (dealProducts.includes(product.id.toString())) {
                                option = $(option).attr('selected', true);
                            }
                            productDropdown.append(option);
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
