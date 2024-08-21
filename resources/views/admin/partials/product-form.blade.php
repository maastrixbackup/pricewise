<div class="row">
    <div class="col-md-6 mb-3">
        <label for="input35" class="col-form-label">Product Name</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Product Name"
            value="{{ $objProduct->title }}">
        @error('title')
            <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="input37" class="col-form-label">Model</label>
        <input type="text" class="form-control" id="model" name="model" placeholder="Model"
            value="{{ $objProduct->model }}">
        @error('model')
            <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="input37" class="col-form-label">SKU</label>
        <input type="text" class="form-control" id="sku" name="sku" placeholder="SKU"
            value="{{ $objProduct->sku }}">
        @error('sku')
            <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="input37" class="col-form-label">Size</label>
        <input type="text" class="form-control" id="size" name="size"
            placeholder="Ex-16.66 cm (6.56 inch) HD+ Display" value="{{ $objProduct->size }}">
        @error('size')
            <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="input37" class="col-form-label">Brand</label>
        <select name="brand" id="brand" class="form-select">
            <option value="" disabled selected>Select</option>
            @foreach ($objBrand as $brand)
                <option value="{{ $brand->id }}" {{ $objProduct->brand_id == $brand->id ? 'selected' : '' }}>
                    {{ $brand->title }}
                </option>
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
                <option value="{{ $color->id }}" {{ $objProduct->color_id == $color->id ? 'selected' : '' }}>
                    {{ $color->title }}
                </option>
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
                <option value="{{ $cat->id }}" {{ $objProduct->category_id == $cat->id ? 'selected' : '' }}>
                    {{ $cat->title }}
                </option>
            @endforeach
        </select>
        @error('category')
            <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="input37" class="col-form-label">Actual Amount</label>
        <input type="number" class="form-control" id="actual_price" name="actual_price" placeholder="Ex-906"
            value="{{ $objProduct->actual_price }}">
        @error('actual_price')
            <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="input37" class="col-form-label">Selling Amount</label>
        <input type="number" class="form-control" id="selling_price" name="selling_price" placeholder="Ex-605"
            value="{{ $objProduct->sell_price }}">
        @error('selling_price')
            <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="input37" class="col-form-label">Expected Delivery</label>
        <input type="text" class="form-control" id="exp_delivery" name="exp_delivery" placeholder="Ex-10 Days"
            value="{{ $objProduct->exp_delivery }}">
        @error('exp_delivery')
            <div class="alert alert-danger mb-3 py-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label for="input35" class="col-form-label">About Product</label>
        <textarea class="form-control" name="about" rows="5" id="description23" placeholder="About Product">{{ $objProduct->about }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-12">
        <div class="mb-3">
            <label for="input37" class="col-form-label">Availability</label>
            <select name="p_status" id="p_status" class="form-select">
                <option value="" disabled selected>Select</option>
                <option value="1" {{ $objProduct->p_status == '1' ? 'selected' : '' }}>In Stock</option>
                <option value="2" {{ $objProduct->p_status == '2' ? 'selected' : '' }}>Limited Stock</option>
                <option value="0" {{ $objProduct->p_status == '0' ? 'selected' : '' }}>Out of Stock</option>
                <option value="3" {{ $objProduct->p_status == '3' ? 'selected' : '' }}>On Request</option>
            </select>
        </div>
        <div class="mb-3 add-scroll">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="new_arrival"
                    @if ($objProduct->new_arrival == 1) checked @endif value="1">
                <label class="form-check-label" for="flexCheckDefault">New Arrival</label>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-3">
            <label for="qty" class="col-form-label">Quantity</label>
            <input type="number" class="form-control" id="qty" name="qty" placeholder="Quantity"
                value="{{ $objProduct->qty }}">
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-3">
            <label for="pin_codes" class="col-form-label">Area PIN Codes</label>
            <input type="text" class="form-control" id="pin_codes" name="pin_codes"
                placeholder="PIN codes with comma(,) separated"
                value="{{ implode(',', json_decode($objProduct->pin_codes)) }}">
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-3">
            <label for="avg_delivery_time" class="col-form-label">Delivery Cost</label>
            <input type="number" class="form-control" id="delivery_cost" name="delivery_cost"
                placeholder="Delivery Cost" min="0" value="{{ $objProduct->delivery_cost }}">
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-3 form-group">
            <label for="input40" class="col-form-label">Product Type</label>
            <select id="product_type" name="product_type" class="select2 form-select">
                <option value="personal" {{ $objProduct->product_type == 'personal' ? 'selected' : '' }}>Personal
                </option>
                <option value="commercial" {{ $objProduct->product_type == 'commercial' ? 'selected' : '' }}>Business
                </option>
                <option value="large-business" {{ $objProduct->product_type == 'large-business' ? 'selected' : '' }}>
                    Large Business</option>
            </select>
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="mb-3 add-scroll">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_featured"
                    @if ($objProduct->is_featured == 1) checked @endif value="1">
                <label class="form-check-label" for="flexCheckDefault">Feature Product</label>
            </div>
        </div>

        <div class="mb-3 add-scroll">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status"
                    @if ($objProduct->is_publish == 1) checked @endif value="1">
                <label class="form-check-label" for="flexCheckDefault">Published</label>
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
    <label for="input35" class="col-form-label">Banner Image</label>
</div>
<label for="upload_image" class="mb-3">
    <div class="mb-3">
        <img src="#" id="uploaded_image" class="img img-responsive img-circle" width="100"
            alt="Select image" />
        <br />
    </div>
    <div class="overlay" style="cursor: pointer">
        {{-- <div>Click to Change Image</div> --}}
    </div>
    <input type="file" name="image" class="image" id="upload_imagea" />
    <input type="hidden" name="cropped_image" id="cropped_image">
</label>
@error('image')
    <div class="alert alert-danger mt-1">{{ $message }}</div>
@enderror



<div class="">
    <label class=" col-form-label"></label>
    <div class="d-md-flex d-grid align-items-center gap-3">
        <button type="submit" class="btn btn-primary px-4" name="submit2">Submit</button>
        <button type="reset" class="btn btn-light px-4">Reset</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle file input change event
        $('#fileInput').on('change', function(event) {
            var input = event.target;
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    // Set the image src to the file data URL
                    $('#preview').attr('src', e.target.result).show();
                }

                // Read the image file as a data URL
                reader.readAsDataURL(input.files[0]);
            }
        });
    });
    // Handle file input change event
    $('#upload_imagea').on('change', function(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                // Set the image src to the file data URL
                $('#uploaded_image').attr('src', e.target.result).show();
            }

            // Read the image file as a data URL
            reader.readAsDataURL(input.files[0]);
        }
    });
</script>
