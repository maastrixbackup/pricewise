@extends('admin.layouts.app')
@section('title', 'POPTelecom- Default')
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
                            href="{{ route('admin.tv-products.index') }}">TV Products</a></li>
                </ol>
            </nav>
        </div>

    </div>
    <!--end breadcrumb-->
    <form id="productForm" method="post" action="{{ route('admin.tv-default-update', $product->id) }}">
        @csrf
        <div class="row">
            <div class="col-md-9 col-12">
                @if ($product)
                    @php$addonId = explode(',', $product->add_extras);
                    @endphp
                    @if ($addonId)
                        @foreach ($addonId as $key => $add)
                            @php $addons = App\Models\AdditionalCategory::where('id', $add)->get(); @endphp
                            @foreach ($addons as $key => $val)
                                <div class="card">
                                    <div class="card-header px-4 py-3">
                                        <h5 class="mb-0">{{ $val->name }}</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="table-responsive">
                                            <table id="catTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Set As Default</th>
                                                        <th>Set As Mandatory</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <input type="hidden" name="addon" value="addon">
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                                    @php $addons = App\Models\AdditionalInfo::where('add_cat_id', $val->id)->get(); @endphp
                                                    @foreach ($addons as $key => $val1)
                                                        @php//$default = App\Models\DefaultProduct::where('default_product_id', $val1->id)->first();
                                                        @endphp
                                                        @php
                                                            //dd($data)
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $val1->name ? $val1->name : 'NA' }}
                                                            </td>
                                                            <td>
                                                                @if (in_array($val1->id, $data))
                                                                    <button type="button" value="{{ $val1->id }}"
                                                                        data-productid="{{ $product->id }}"
                                                                        class="uncheck btn btn-sm btn-primary"
                                                                        style="float:right;">Uncheck </button>
                                                                @endif
                                                                <input class="form-check-input related{{ $val1->id }}"
                                                                    type="radio" name="addon_ids[{{ $val->id }}]"
                                                                    value="{{ $val1->id }}" <?php if (in_array($val1->id, $data)) {
                                                                        echo 'checked';
                                                                    } ?>>

                                                            </td>
                                                            <td>
                                                                @if (in_array($val1->id, $manda_data))
                                                                    <button type="button" value="{{ $val1->id }}"
                                                                        data-productid="{{ $product->id }}"
                                                                        class="uncheck-manda btn btn-sm btn-primary"
                                                                        style="float:right;">Uncheck </button>
                                                                @endif
                                                                <input class="form-check-input related{{ $val1->id }}"
                                                                    type="radio"
                                                                    name="mandatory_addon_ids[{{ $val->id }}]"
                                                                    value="{{ $val1->id }}" <?php if (in_array($val1->id, $manda_data)) {
                                                                        echo 'checked';
                                                                    } ?>>

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @endif
                @endif
            </div>

            <!----  Product Attributes  ---->
            <div class="col-md-9 col-12">
                @if ($product)
                    @php $relatedId = explode(",", $product->related_products);@endphp
                    @if ($relatedId)
                        @foreach ($relatedId as $key => $add)
                            @php $related = App\Models\ShopCategory::where('id', $add)->get(); @endphp
                            @foreach ($related as $key => $val)
                                <div class="card">
                                    <div class="card-header px-4 py-3">
                                        <h5 class="mb-0">{{ $val->cat_name }}</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="table-responsive">
                                            <table id="catTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Set As Default</th>
                                                        <th>Set As Mandatory</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <input type="hidden" name="related_product" value="related_product">
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                                    @php$related = App\Models\ShopProduct::where(
                                                            'category_id',
                                                            $val->id,
                                                        )->get();
                                                    @endphp
                                                    @foreach ($related as $key => $val1)
                                                        <tr>
                                                            <td>{{ $val1->title ? $val1->title : 'NA' }}
                                                            </td>
                                                            <td>
                                                                @if (in_array($val1->id, $data))
                                                                    <button type="button" value="{{ $val1->id }}"
                                                                        data-productid="{{ $product->id }}"
                                                                        class="uncheck btn btn-sm btn-primary"
                                                                        style="float:right;">Uncheck </button>
                                                                @endif
                                                                <input class="form-check-input" type="radio"
                                                                    name="related_product_ids[{{ $val->id }}]"
                                                                    value="{{ $val1->id }}" <?php if (in_array($val1->id, $data)) {
                                                                        echo 'checked';
                                                                    } ?>>

                                                            </td>
                                                            <td>
                                                                @if (in_array($val1->id, $manda_data))
                                                                    <button type="button" value="{{ $val1->id }}"
                                                                        data-productid="{{ $product->id }}"
                                                                        class="uncheck-manda btn btn-sm btn-primary"
                                                                        style="float:right;">Uncheck </button>
                                                                @endif
                                                                <input class="form-check-input related{{ $val1->id }}"
                                                                    type="radio"
                                                                    name="mandatory_related_product_ids[{{ $val->id }}]"
                                                                    value="{{ $val1->id }}" <?php if (in_array($val1->id, $manda_data)) {
                                                                        echo 'checked';
                                                                    } ?>>

                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @endif
                @endif
            </div>

            <div class="row mb-3">
                <label class=" col-form-label"></label>
                <div class="">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4" name="submit2">Submit</button>
                    </div>
                </div>
            </div>

        </div>
        </div>

        </div>
        </div>
        </div>

    </form>
@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('description');
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".uncheck").click(function() {
                var active_id = $(this).val();
                var product_id = $(this).attr("data-productid");

                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('admin.default-remove') }}",
                    method: "post",
                    data: {
                        active_id: active_id,
                        product_id: product_id
                    },
                    success: function(data) {
                        // alert(data);
                        console.log(data);
                        //success
                        $("#productForm").trigger("reset");
                        if (data.status) {
                            toastr.success(data.msg, data.title);
                            $('.related' + active_id).removeAttr('checked');
                            $('.uncheck').hide();
                            // if ($('.related' + active_id).is(":checked")) {
                            //     $('.uncheck').show();
                            // }
                        } else {
                            toastr.error(data.msg, data.title);
                        }
                    },
                    error: function(e) {
                        toastr.error('Something went wrong . Please try again later!!', '');
                    }
                });

            });
        });

        $(document).ready(function() {
            $(".uncheck-manda").click(function() {
                var active_id = $(this).val();
                var product_id = $(this).attr("data-productid");

                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('admin.mandatory-remove') }}",
                    method: "post",
                    data: {
                        active_id: active_id,
                        product_id: product_id
                    },
                    success: function(data) {
                        // alert(data);
                        console.log(data);
                        //success
                        $("#productForm").trigger("reset");
                        if (data.status) {
                            toastr.success(data.msg, data.title);
                            $('.related' + active_id).removeAttr('checked');
                            $('.uncheck-manda').hide();
                            // if ($('.related' + active_id).is(":checked")) {
                            //     $('.uncheck').show();
                            // }
                        } else {
                            toastr.error(data.msg, data.title);
                        }
                    },
                    error: function(e) {
                        toastr.error('Something went wrong . Please try again later!!', '');
                    }
                });

            });
        });
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
                sku: "required",
                regular_price: "required",
                new_install_cost: "required",
                price_type: "required",
                contract_length: "required"
            },
            messages: {
                name: "Title is missing",
                description: "Description is missing",
                sku: "SKU is missing",
                regular_price: "Regular Price is missing",
                new_install_cost: "Installation Cost is required",
                price_type: "Price Type is missing",
                contract_length: "Contract Length is required"

            },
            submitHandler: function(form) {
                var data = CKEDITOR.instances.description.getData();
                $("#description").val(data);
                $.ajax({
                    url: form.action,
                    method: "PUT",
                    data: $(form).serialize(),
                    success: function(data) {
                        //success
                        $("#productForm").trigger("reset");
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

        $("#title").keyup(function() {
            var title_val = $("#title").val();
            $("#link").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
        $("#title").keydown(function() {
            var title_val = $("#title").val();
            $("#link").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });

        $('#multiple-select-field').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            closeOnSelect: false,
        });

        //     $('#category').change(function() {
        //     $('#catTable').show();
        //     //$('#catTable' + $(this).val()).show();
        // });
    </script>
@endpush
