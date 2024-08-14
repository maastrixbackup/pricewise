@extends('admin.layouts.app')
@section('title', 'Energise- Insurance Edit')
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
                            href="{{ route('admin.products.index') }}">Products</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs nav-success" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#home" role="tab" aria-selected="true">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                            </div>
                            <div class="tab-title">About this Product</div>
                        </div>
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#internet" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class="fa fa-text-width font-18 me-1" aria-hidden="true"></i>

                            </div>
                            <div class="tab-title">Product Description</div>
                        </div>
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#tv" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class="fa fa-list-ul font-18 me-1" aria-hidden="true"></i>
                            </div>
                            <div class="tab-title">Specification</div>
                        </div>
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#serviceInfo" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class="fa fa-file-image-o font-18 me-1" aria-hidden="true"></i>
                            </div>
                            <div class="tab-title">Product Images</div>
                        </div>
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#productRating" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class="fa fa-star-o font-18 me-1" aria-hidden="true"></i>
                            </div>
                            <div class="tab-title">Product Ratings</div>
                        </div>
                    </a>
                </li>
            </ul>

            <div class="tab-content py-3">
                <div class="tab-pane fade show active" id="home" role="tabpanel">
                    <div class="row">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Edit Product Details</h5>
                        </div>
                        <div class="col-md-12 col-lg-12 col-12">
                            <div class="card-body p-4">
                                <form id="productForm" method="post"
                                    action="{{ route('admin.products.update', $objProduct->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')


                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="input35" class=" col-form-label">Product
                                                Name</label>
                                            <input type="text" class="form-control" id="title" name="title"
                                                placeholder="Product Name" value="{{ $objProduct->title }}">
                                            @error('title')
                                                <div class="alert alert-danger mb-3 py-1">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="input37" class="col-form-label">Model</label>
                                            <input type="text" class="form-control" id="model" name="model"
                                                placeholder="Model" value="{{ $objProduct->model }}">
                                            @error('model')
                                                <div class="alert alert-danger mb-3 py-1">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="input37" class="col-form-label">SKU</label>
                                            <input type="text" class="form-control" id="sku" name="sku"
                                                placeholder="SKU" value="{{ $objProduct->sku }}">
                                            @error('sku')
                                                <div class="alert alert-danger mb-3 py-1">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="input37" class="col-form-label">Size</label>
                                            <input type="text" class="form-control" id="size" name="size"
                                                placeholder="Ex-16.66 cm (6.56 inch) HD+ Display"
                                                value="{{ $objProduct->size }}">
                                            @error('size')
                                                <div class="alert alert-danger mb-3 py-1">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="input37" class="col-form-label">Brand</label>
                                            <select name="brand" id="brand" class="form-select">
                                                <option value="" disabled selected>Select</option>
                                                @foreach ($objBrand as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ $objProduct->brand_id == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('brand')
                                                <div class="alert alert-danger mb-3 py-1">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="input37" class="col-form-label">Color</label>
                                            <select name="color" id="color" class="form-select">
                                                <option value="" disabled selected>Select</option>
                                                @foreach ($objColor as $color)
                                                    <option value="{{ $color->id }}"
                                                        {{ $objProduct->color_id == $color->id ? 'selected' : '' }}>
                                                        {{ $color->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('color')
                                                <div class="alert alert-danger mb-3 py-1">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="input37" class="col-form-label">Category</label>
                                            <select name="category" id="category" class="form-select">
                                                <option value="" disabled selected>Select</option>
                                                @foreach ($objCategory as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        {{ $objProduct->category_id == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <div class="alert alert-danger mb-3 py-1">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>


                                        <div class="col-md-6 mb-3">
                                            <label for="input37" class="col-form-label">Actual
                                                Amount</label>
                                            <input type="number" class="form-control" id="actual_price"
                                                name="actual_price" placeholder="Ex-906"
                                                value="{{ $objProduct->actual_price }}">
                                            @error('actual_price')
                                                <div class="alert alert-danger mb-3 py-1">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="input37" class="col-form-label">Selling
                                                Amount</label>
                                            <input type="number" class="form-control" id="selling_price"
                                                name="selling_price" placeholder="Ex-605"
                                                value="{{ $objProduct->sell_price }}">
                                            @error('selling_price')
                                                <div class="alert alert-danger mb-3 py-1">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>


                                        <div class="col-md-6 mb-3">
                                            <label for="input37" class="col-form-label">Excepted
                                                Delivery</label>
                                            <input type="text" class="form-control" id="exp_delivery"
                                                name="exp_delivery" placeholder="Ex-10 Days"
                                                value="{{ $objProduct->exp_delivery }}">
                                            @error('exp_delivery')
                                                <div class="alert alert-danger mb-3 py-1">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="input35" class=" col-form-label">About
                                                Product</label>
                                            <textarea class="form-control" name="about" rows="5" id="description23" placeholder="About Product">{{ $objProduct->about }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class=" mb-3">
                                                <label for="input37" class="col-form-label">Availability</label>
                                                <select name="p_status" id="p_status" class="form-select">
                                                    <option value="" disabled selected>Select
                                                    </option>
                                                    <option value="1"
                                                        {{ $objProduct->p_status == '1' ? 'selected' : '' }}>In Stock
                                                    </option>
                                                    <option value="2"
                                                        {{ $objProduct->p_status == '2' ? 'selected' : '' }}>Limited Stock
                                                    </option>
                                                    <option value="0"
                                                        {{ $objProduct->p_status == '0' ? 'selected' : '' }}>Out of Stock
                                                    </option>
                                                    <option value="3"
                                                        {{ $objProduct->p_status == '3' ? 'selected' : '' }}>On Request
                                                    </option>
                                                </select>
                                            </div>
                                            <!-- <label for="input35" class="col-form-label"><b>Top 3 Product</b></label> -->
                                            <div class="mb-3 add-scroll">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="new_arrival"
                                                        @if ($objProduct->new_arrival == 1) checked @endif value="1">
                                                    <label class="form-check-label" for="flexCheckDefault">New
                                                        Arrival</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class=" mb-3">
                                                <label for="qty" class="col-form-label">Quantity</label>
                                                <input type="number" class="form-control" id="qty" name="qty"
                                                    placeholder="Quantity" value="{{ $objProduct->qty }}">
                                            </div>
                                        </div>


                                        <div class="col-md-6 col-12">
                                            <div class=" mb-3">
                                                <label for="pin_codes" class="col-form-label">Area PIN
                                                    Codes</label>
                                                <input type="text" class="form-control" id="pin_codes"
                                                    name="pin_codes" placeholder="PIN codes with coma(,) separated"
                                                    value="{{ implode(',', json_decode($objProduct->pin_codes)) }}">
                                            </div>
                                        </div>


                                        <div class="col-md-6 col-12">
                                            <div class=" mb-3">
                                                <label for="avg_delivery_time" class=" col-form-label">Deliver
                                                    Cost</label>
                                                <input type="number" class="form-control" id="delivery_cost"
                                                    name="delivery_cost" placeholder="Delivery Cost" min="0"
                                                    value="{{ $objProduct->delivery_cost }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">

                                            <div class="mb-3 form-group">
                                                <label for="input40" class="col-form-label">Product Type</label>
                                                <select id="product_type" name="product_type"
                                                    class="select2 form-select">
                                                    <option value="personal"
                                                        @if ($objProduct->product_type == 'personal') selected @endif>Personal
                                                    </option>
                                                    <option value="commercial"
                                                        @if ($objProduct->product_type == 'commercial') selected @endif>Business
                                                    </option>
                                                    <option value="large-business"
                                                        @if ($objProduct->product_type == 'large-business') selected @endif>Large
                                                        Business</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <!-- <label for="input35" class="col-form-label"><b>Top 3 Product</b></label> -->
                                            <div class="mb-3 add-scroll">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="is_featured"
                                                        @if ($objProduct->is_featured == 1) checked @endif value="1">
                                                    <label class="form-check-label" for="flexCheckDefault">Feature
                                                        Product</label>
                                                </div>
                                            </div>
                                            <!-- <label for="input35" class="col-form-label"><b>Top 3 Product</b></label> -->
                                            <div class="mb-3 add-scroll">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status"
                                                        @if ($objProduct->is_publish == 1) checked @endif value="1">
                                                    <label class="form-check-label"
                                                        for="flexCheckDefault">Published</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status"
                                                        @if ($objProduct->is_publish == 0) checked @endif value="0">
                                                    <label class="form-check-label" for="flexCheckDefault">Draft</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="input35" class="col-form-label">Banneer Image</label>
                                    </div>
                                    <label for="upload_image" class="mb-3">

                                        <img src="{{ asset('storage/images/shops/' . $objProduct->banner_image) }}"
                                            id="uploaded_image" class="img img-responsive img-circle" width="100"
                                            alt="Select image" />

                                        <div class="overlay" style="cursor: pointer">
                                            <div>Click to Change Image</div>
                                        </div>
                                        <input type="file" name="image" class="image" id="upload_image"
                                            style="display:none" />
                                        <input type="hidden" name="cropped_image" id="cropped_image">

                                    </label>
                                    @error('image')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                    @enderror

                                    <div class="">
                                        <label class=" col-form-label"></label>
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-primary px-4"
                                                name="submit2">Submit</button>
                                            <button type="reset" class="btn btn-light px-4">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="internet" role="tabpanel">
                    <form id="internetForm" method="post"
                        action="{{ route('admin.update_product_description', $objProduct->id) }}">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ $objProduct->category_id }}">
                        <div class="row">
                            <div class="card-header px-4 py-3">
                                <h5 class="mb-0">Edit Product Description</h5>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="card-body p-4">
                                    <label for="" class="col-form-label">Product Description</label>
                                    <textarea class="form-control" name="p_description" id="description" placeholder="Product Description">{!! $objProduct->description !!}</textarea>
                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-8">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" id="submitBtn2" class="btn btn-primary px-4"
                                        value="Submit">Save</button>
                                    <button type="reset" class="btn btn-light px-4">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="tv" role="tabpanel">
                    <form id="tvForm" method="post"
                        action="{{ route('admin.update_product_features', $objProduct->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ $objProduct->category }}">
                        {{-- <input type="hidden" name="sub_category" value="{{ $objProduct->sub_category }}"> --}}
                        <div class="row">
                            <div class="card-header px-4 py-3">
                                <h5 class="mb-0"> Specification</h5>
                            </div>
                            <div class="card">
                                @php
                                    $feature = json_decode($objProduct->specification, true);
                                @endphp
                                <span id="rData">
                                    @if (!empty($feature))
                                        @foreach ($feature as $f => $v)
                                            <div class="card-header"><b>{{ $f }}</b></div>
                                            <div class="card-body">
                                                <table class="table table-responsive table-bordered">
                                                    @foreach ($v as $k => $va)
                                                        <tr>
                                                            <th style="width: 25%;">{{ $k }}</th>
                                                            <td style="width:70%;">{{ $va ?? 'NA' }}</td>
                                                            <td class="text-center "><a href="javascript:void(0);"
                                                                    class="text-danger"
                                                                    onclick="removeSpeci('{{ $k }}', '{{ $objProduct->id }}')"><i
                                                                        class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        @endforeach
                                    @endif
                                </span>
                            </div>
                            <div class="col-md-7 col-12">
                                <div class="card-body p-4">
                                    <label for="" class="col-form-label">General</label>
                                    <div class="row">
                                        <div class="col-md-12 mt-3">
                                            <div class="input-group mb-3">
                                                <input type="text" name="key[]" required class="form-control"
                                                    placeholder="Title">
                                                <div class="input-group-append">
                                                    <a href="javascript:void(0);" class="btn btn-primary mx-1 AddF"><i
                                                            class="fa fa-plus-square-o" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <textarea name="value[]" class="form-control" placeholder="Description"></textarea>
                                        </div>

                                        <div id="fData"></div>
                                    </div>
                                </div>

                                <div class="card-body p-4">
                                    <label for="" class="col-form-label">Product Details</label>
                                    <div class="row">
                                        <div class="col-md-12 mt-3">
                                            <div class="input-group mb-3">
                                                <input type="text" name="key1[]" required class="form-control"
                                                    placeholder="Title">
                                                <div class="input-group-append">
                                                    <a href="javascript:void(0);" class="btn btn-primary mx-1 AddFd"><i
                                                            class="fa fa-plus-square-o" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <textarea name="value1[]" id="" class="form-control" placeholder="Description"></textarea>
                                        </div>

                                        <div id="fData1"></div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-8">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" id="submitBtn3" class="btn btn-primary px-4"
                                        value="Submit">Save</button>
                                    <button type="reset" class="btn btn-light px-4">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

                <div class="tab-pane fade" id="serviceInfo" role="tabpanel">
                    <form id="infoForm" method="post"
                        action="{{ route('admin.add_product_images', $objProduct->id) }}" enctype="multipart/form-data">
                        @csrf
                        {{-- <input type="hidden" name="category_id" value="{{ $objProduct->category }}"> --}}
                        <div class="row">
                            <input type="hidden" name="category_id" value="{{ $objProduct->category_id }}">
                            <div class="card-header px-4 py-3">
                                <h5 class="mb-0">Edit Product Image</h5>
                            </div>
                            <div class="col-md-7 col-12">
                                <div class="card-body p-4">
                                    @if (!$objImages->isEmpty())
                                        <div class="row" id="imgData">
                                            @foreach ($objImages as $image)
                                                <div class="col-sm-3 mb-3" id="imdDiv_{{ $image->id }}">
                                                    <div class="card" style="border: 1px solid gray;">
                                                        <span style="top: -5px;position: absolute;right: 0;"><a
                                                                href="javascript:void(0);" id="img_{{ $image->id }}"
                                                                onclick="deleteImage('{{ $image->id }}', '{{ $objProduct->id }}')"><i
                                                                    class="fa fa-times-circle"
                                                                    aria-hidden="true"></i></a></span>
                                                        <img src="{{ asset('storage/images/shops/' . $image->image) }}"
                                                            class="img-fluid" width="200" height="200"
                                                            style="height: 86px">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class=" mb-3">
                                        <label for="internet_guarantee" class=" col-form-label">Images</label>
                                        <div class="input-group mb-3">
                                            <input type="file" name="image[]" required class="form-control"
                                                placeholder="">
                                            <div class="input-group-append">
                                                <a href="javascript:void(0);" class="btn btn-primary mx-1 Add"><i
                                                        class="fa fa-plus-square-o" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div id="appData"></div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-8">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" id="submitBtn4" class="btn btn-primary px-4"
                                        value="Save">Save</button>
                                    <button type="reset" class="btn btn-light px-4">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <div class="tab-pane fade" id="productRating" role="tabpanel">
                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            <div class="card">
                                <div class="card-body">
                                    @php
                                        $totalRatings = array_sum($ratingCount);
                                    @endphp
                                    <table class="rating-bars" style="width: 100%;">
                                        @foreach ($ratingCount as $star => $count)
                                            @php
                                                $percentage = $totalRatings > 0 ? ($count / $totalRatings) * 100 : 0;
                                            @endphp
                                            <tr class="rating-bar">
                                                <td style="width: 10%;">{{ $star }}</td>
                                                <td style="width: 80%;">
                                                    <div class="progress" style="height: 10px;">
                                                        <div class="progress-bar" role="progressbar"
                                                            aria-valuenow="{{ round($percentage) }}" aria-valuemin="0"
                                                            aria-valuemax="100"
                                                            style="width: {{ round($percentage) }}%; background-color: orange;">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="width: 10%;">({{ $count }})</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var sts = $('#p_status').val();

            if (sts == 3) {
                $('#qty').attr('readonly', true);
            } else if (sts == 0) {
                $('#qty').attr('readonly', true);
            } else if (sts == 1 || sts == 2) {
                $('#qty').attr('readonly', false);
            }
        });

        function deleteImage(id, p_id) {

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
                    $.ajax({
                        url: '{{ route('admin.delete_p_image') }}',
                        method: "post",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: id,
                            product_id: p_id
                        },
                        success: function(data) {
                            console.log(data);
                            // return false;

                            var toastMixin = Swal.mixin({
                                toast: true,
                                icon: 'success',
                                title: 'General Title',
                                animation: false,
                                position: 'top-right',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            });
                            if (data.status) {
                                toastr.error(data.message.message, '');
                                $('#imdDiv_' + id).remove();
                                // toastMixin.fire({
                                //     animation: true,
                                //     title: data.message.message
                                // });
                            } else {
                                toastr.error(data.message.message, '');
                                // toastMixin.fire({
                                //     icon: 'error',
                                //     animation: true,
                                //     title: data.message.message
                                // });
                            }
                        },
                        error: function(e) {
                            // toastMixin.fire({
                            //     icon: 'error',
                            //     animation: true,
                            //     title: 'Something went wrong. Please try again later!'
                            // });
                            toastr.error('Something went wrong. Please try again later!', '');
                        }
                    });
                }
            });
        }

        function removeSpeci(key, id) {

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
                    $.ajax({
                        url: '{{ route('admin.delete_p_specification') }}',
                        method: "post",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: id,
                            key: key
                        },
                        success: function(data) {
                            console.log(data.status);

                            if (data.status) {
                                toastr.success(data.message, '');
                                $('#rData').html('');
                                $.each(data.product, function(key, val) {
                                    var tableRows = '';
                                    $.each(val, function(k, v) {
                                        var displayValue = v !== null ? v : 'NA';
                                        tableRows += '<tr>' +
                                            '<th style="width: 25%;">' + k + '</th>' +
                                            '<td style="width:70%;">' + displayValue +
                                            '</td>' +
                                            '<td class="text-center"><a href="javascript:void(0);" class="text-danger" onclick="removeSpeci(\'' +
                                            k + '\', \'' + data.pid +
                                            '\')"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>' +
                                            '</tr>';
                                    });

                                    var cardHtml = '<div class="card">' +
                                        '<div class="card-header"><b>' + key + '</b></div>' +
                                        '<div class="card-body">' +
                                        '<table class="table table-responsive table-bordered">' +
                                        tableRows + '</table>' +
                                        '</div>' +
                                        '</div>';

                                    $('#rData').append(cardHtml);
                                });
                            } else {
                                toastr.error(data.message, 'Already Exists!');
                            }
                        },
                        error: function(e) {
                            toastr.error('Something went wrong. Please try again later!', '');
                        }
                    });

                }
            });

        }

        $(document).on('click', '.AddF', function() {
            var htmlData = `
                    <span class="groupData">
                        <div class="col-md-12 mt-3">
                            <div class="input-group mb-3">
                                <input type="text" name="key[]" required class="form-control" placeholder="Title">
                                <div class="input-group-append">
                                    <a href="javascript:void(0);" class="btn btn-danger mx-1 removeF">
                                        <i class="fa fa-minus-square-o" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <textarea name="value[]" class="form-control" placeholder="Description"></textarea>
                        </div>
                    </span>`;
            $('#fData').append(htmlData);
        });


        $(document).on('click', '.removeF', function() {
            $(this).closest('.groupData').remove();
        });

        $(document).on('click', '.AddFd', function() {
            var htmlData =
                '<span class="gropuData1"><div class="col-md-12 mt-3"><div class="input-group mb-3"><input type="text" name="key1[]" required class="form-control" placeholder="Title"><div class="input-group-append"><a href="javascript:void(0);" class="btn btn-danger mx-1 removeFd"><i class="fa fa-minus-square-o" aria-hidden="true"></i></a></div></div></div><div class="col-md-12"><textarea name="value1[]" id="" class="form-control" placeholder="Description"></textarea></div></span>';
            $('#fData1').append(htmlData);
        });

        $(document).on('click', '.removeFd', function() {
            $(this).closest('.gropuData1').remove();
        });

        $(document).on('click', '.Add', function() {
            var htmlData =
                `<div class="input-group mb-3">
                    <input type="file" name="image[]" class="form-control" placeholder="">
                    <div class="input-group-append">
                        <a href="javascript:void(0);" class="btn btn-danger mx-1 remove">
                            <i class="fa fa-minus-square-o" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>`;
            $('#appData').append(htmlData);
        });

        $(document).on('click', '.remove', function() {
            $(this).closest('.input-group').remove();
        });



        tinymce.init({
            selector: '#description',
            plugins: 'codesample code advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',

            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | codesample code',
            codesample_languages: [{
                    text: 'HTML/XML',
                    value: 'markup'
                },
                {
                    text: 'JavaScript',
                    value: 'javascript'
                },
                {
                    text: 'CSS',
                    value: 'css'
                },
                {
                    text: 'PHP',
                    value: 'php'
                },
                {
                    text: 'Ruby',
                    value: 'ruby'
                },
                {
                    text: 'Python',
                    value: 'python'
                },
                {
                    text: 'Java',
                    value: 'java'
                },
                {
                    text: 'C',
                    value: 'c'
                },
                {
                    text: 'C#',
                    value: 'csharp'
                },
                {
                    text: 'C++',
                    value: 'cpp'
                }
            ],

            file_picker_callback(callback, value, meta) {
                let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth
                let y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight

                tinymce.activeEditor.windowManager.openUrl({
                    url: '/file-manager/tinymce5',
                    title: 'Laravel File manager',
                    width: x * 0.8,
                    height: y * 0.8,
                    onMessage: (api, message) => {
                        callback(message.content, {
                            text: message.text
                        })
                    }
                })
            }
        });

        $('#p_status').on('change', function() {
            var sts = $('#p_status').val();
            // console.log(sts);

            if (sts == 3) {
                $('#qty').val('');
                $('#qty').attr('readonly', true);
            } else if (sts == 0) {
                $('#qty').val('0');
                $('#qty').attr('readonly', true);
            } else if (sts == 1 || sts == 2) {
                $('#qty').val('');
                $('#qty').attr('readonly', false);
            }
        });
    </script>
@endpush
