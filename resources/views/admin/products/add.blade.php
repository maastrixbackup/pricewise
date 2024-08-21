@extends('admin.layouts.app')
@section('title', 'Energise - Insurance Create')
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
    <form id="productFor" method="post" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9 col-lg-9 col-12">
                <div class="card">
                    <div class="card-header px-4 py-3">
                        <h5 class="mb-0">Add New Product</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="input35" class=" col-form-label">Product Name</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Product Name" value="{{ old('title') }}">
                                @error('title')
                                    <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="input37" class="col-form-label">Model</label>
                                <input type="text" class="form-control" id="model" name="model" placeholder="Model"
                                    value="{{ old('model') }}">
                                @error('model')
                                    <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="input37" class="col-form-label">SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku" placeholder="SKU"
                                    value="{{ old('sku') }}">
                                @error('sku')
                                    <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="input37" class="col-form-label">Size</label>
                                <input type="text" class="form-control" id="size" name="size"
                                    placeholder="Ex-16.66 cm (6.56 inch) HD+ Display" value="{{ old('size') }}">
                                @error('size')
                                    <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="input37" class="col-form-label">Brand</label>
                                <select name="brand" id="brand" class="form-select">
                                    <option value="" disabled selected>Select</option>
                                    @foreach ($objBrand as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                    @endforeach
                                </select>
                                @error('brand')
                                    <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="input37" class="col-form-label">Color</label>
                                <select name="color" id="color" class="form-select">
                                    <option value="" disabled selected>Select</option>
                                    @foreach ($objColor as $color)
                                        <option value="{{ $color->id }}">{{ $color->title }}</option>
                                    @endforeach
                                </select>
                                @error('color')
                                    <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="input37" class="col-form-label">Category</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="" disabled selected>Select</option>
                                    @foreach ($objCategory as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="input37" class="col-form-label">Actual Amount</label>
                                <input type="number" class="form-control" id="actual_price" name="actual_price"
                                    placeholder="Ex-906" value="{{ old('actual_price') }}">
                                @error('actual_price')
                                    <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="input37" class="col-form-label">Selling Amount</label>
                                <input type="number" class="form-control" id="selling_price" name="selling_price"
                                    placeholder="Ex-605" value="{{ old('selling_price') }}">
                                @error('selling_price')
                                    <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="input37" class="col-form-label">Excepted Delivery</label>
                                <input type="text" class="form-control" id="exp_delivery" name="exp_delivery"
                                    placeholder="Ex-10 Days" value="{{ old('exp_delivery') }}">
                                @error('exp_delivery')
                                    <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="input35" class=" col-form-label">About Product</label>
                                <textarea class="form-control" name="about" id="description23" placeholder="About Product"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class=" mb-3">
                                    <label for="input37" class="col-form-label">Availability</label>
                                    <select name="p_status" id="p_status" class="form-select">
                                        <option value="" disabled selected>Select</option>
                                        <option value="1">In Stock</option>
                                        <option value="2">Limited Stock</option>
                                        <option value="0">Out of Stock</option>
                                        <option value="3">On Request</option>
                                    </select>
                                </div>
                                <!-- <label for="input35" class="col-form-label"><b>Top 3 Product</b></label> -->
                                <div class="mb-3 add-scroll">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="new_arrival"
                                             value="1">
                                        <label class="form-check-label" for="flexCheckDefault">New Arrival</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class=" mb-3">
                                    <label for="qty" class="col-form-label">Quantity</label>
                                    <input type="number" class="form-control" id="qty" name="qty"
                                        placeholder="Quantity">
                                </div>
                            </div>


                            <div class="col-md-6 col-12">
                                <div class=" mb-3">
                                    <label for="pin_codes" class="col-form-label">Area PIN Codes</label>
                                    <input type="text" class="form-control" id="pin_codes" name="pin_codes"
                                        placeholder="PIN codes with coma(,) separated">
                                </div>
                            </div>


                            <div class="col-md-6 col-12">
                                <div class=" mb-3">
                                    <label for="avg_delivery_time" class=" col-form-label">Deliver Cost</label>
                                    <input type="number" class="form-control" id="delivery_cost" name="delivery_cost"
                                        placeholder="Delivery Cost" min="0">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">

                                <div class="mb-3 form-group">
                                    <label for="input40" class="col-form-label">Product Type
                                    </label>
                                    <select id="product_type" name="product_type" class="select2 form-select">
                                        <option value="personal">Personal</option>
                                        <option value="commercial">Business</option>
                                        <option value="large-business">Large Business</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <!-- <label for="input35" class="col-form-label"><b>Top 3 Product</b></label> -->
                                <div class="mb-3 add-scroll mt-2">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="is_featured"
                                            value="1"> Feature Product</label>
                                    </div>
                                </div>
                                <!-- <label for="input35" class="col-form-label"><b>Top 3 Product</b></label> -->
                                <div class="mb-3 add-scroll">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" >
                                        <input class="form-check-input" type="radio" class="form-control" name="status" value="1">Published</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" >
                                        <input class="form-check-input" type="radio" class="form-control" name="status" checked value="0">Draft</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="input35" class="col-form-label">Banner Image</label>
                            </div>
                            <label for="upload_image" class="mb-3">
    
                                <img src="#" id="uploaded_image" class="img img-responsive img-circle" width="100"
                                    alt="Select image" />
    
                                <div class="overlay" style="cursor: pointer">
                                    <div>Click to Change Image</div>
                                </div>
                                <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                                <input type="hidden" name="cropped_image" id="cropped_image">
    
                            </label>
                            @error('image')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="">
                            <label class=" col-form-label"></label>
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4" name="submit2">Submit</button>
                                <button type="reset" class="btn btn-light px-4">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-3 col-12 col-lg-3">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="mb-3 form-group">
                            <label for="input40" class="col-form-label"><b>Publish Status</b>
                            </label>
                            <select id="status" name="status" class="select2 form-select">
                                <option value="1">Publish</option>
                                <option value="0" selected>Draft</option>

                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="product_type" class="col-form-label"><b>Product Type</b>
                            </label>
                            <select id="product_type" name="product_type" class="select2 form-select">
                                <option value="personal">Personal</option>
                                <option value="business">Business</option>
                                <option value="large-business">Large Business</option>

                            </select>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="no_of_person" class="col-form-label"><b>Number of Persons(Max)</b>
                            </label>
                            <input type="number" class="form-control" id="no_of_person" name="no_of_person"
                                min="0">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="col-form-label"><b>Category</b>
                            </label>

                            <select id="category" name="category" class="select2 form-select">
                                @php
                                    $objCategory = App\Models\ProductCategory::all();
                                @endphp
                                <option>Select</option>
                                @if ($objCategory)
                                    @foreach ($objCategory as $val)
                                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sub_category" class="col-form-label"><b>Sub Category</b>
                            </label>

                            <select id="sub_category" name="sub_category" class="select2 form-select">
                                <option>Select</option>

                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="provider" class="col-form-label"><b>Provider</b>
                            </label>

                            <select id="provider" name="provider" class="select2 form-select">
                                <option>Select</option>
                                @if ($providers)
                                    @foreach ($providers as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label for="upload_image">
                            <img src="#" id="uploaded_image" class="img img-responsive img-circle" width="100"
                                alt="Select image" />

                            <div class="overlay">
                                <div>Click to Change Image</div>
                            </div>
                            <input type="file" name="image" class="image" id="upload_image"
                                style="display:none" />
                            <input type="hidden" name="cropped_image" id="cropped_image">

                        </label>
                        <label for="input35" class="col-form-label"><b>Combo Offers</b></label>
                        <div class="mb-3 add-scroll">
                            @if ($combos)
                                @foreach ($combos as $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="combos[]"
                                            value="{{ $val->id }}">
                                        <label class="form-check-label" for="flexCheckDefault">{{ $val->title }} -
                                            €{{ $val->price }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>



                    </div>
                </div>
            </div> --}}
        </div>

    </form>
@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('description', {
            allowedContent: true,
            extraPlugins: 'colorbutton'
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#affiliate_name').on('change', function() {
                $("temp_old").hide();
                $("#temp").show();
            });
        });
    </script>
    <script type="text/javascript">
        $("#productForm").validate({
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
                title: "required",
                description: "required",

            },
            messages: {
                title: "Title is missing",
                description: "Description is missing",
            },
            submitHandler: function(form) {
                var data = CKEDITOR.instances.description.getData();
                $("#description").val(data);
                var formData = new FormData(form);
                $.ajax({

                    url: form.action,
                    method: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        //success

                        if (data.status) {
                            location.href = data.redirect_location;
                        } else {
                            toastr.error(data.message.message, 'Already Exists!');
                        }
                    },
                    error: function(e) {
                        toastr.error('Something went wrong . Please try again later!!', '');
                    }
                });
                return false;
            }
        });

        $("#title").keyup(function() {
            var title_val = $("#title").val();
            $("#link").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
        $("#title").keydown(function() {
            var title_val = $("#title").val();
            $("#link").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });

        $('#p_status').on('change', function() {
            var sts = $('#p_status').val();
            console.log(sts);

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
