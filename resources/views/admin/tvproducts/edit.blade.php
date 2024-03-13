@extends('admin.layouts.app')
@section('title','POPTelecom- Tv Product Edit')
@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.tv-products.index')}}">Tv Products</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<!-- <form id="productForm" method="PUT" action="{{--route('admin.tv-products.update',$objTv->id)--}}" enctype="multipart/form-data"> -->
<form id="productForm" method="post" action="{{ route('admin.tv-product-update', $objTv->id) }}" enctype="multipart/form-data">
@csrf
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Edit Tv Product</h5>
                </div>
                <div class="card-body p-4">
                    <div class=" mb-3">
                        <label for="input35" class=" col-form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{$objTv->title}}">
                    </div>

                    <div class=" mb-3">
                        <label for="input37" class="col-form-label">URL</label>

                        <!-- <div class="input-group mb-3"> <span class="input-group-text">/product/</span> -->
                        <a href="{{url('/tv/')}}/{{$objTv->url}}/{{$objTv->affiliate}}" target="_blank">{{url('/tv/')}}/{{$objTv->url}}/{{$objTv->affiliate}}</a>
                        <input type="text" class="form-control" id="link" name="link" value="{{$objTv->url}}" readonly>
                        <!-- </div> -->

                    </div>

                    <div class=" mb-3">
                        <label for="input35" class=" col-form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Product Description">{{$objTv->content}}</textarea>
                    </div>

                    <div class=" mb-3">
                        <label for="input35" class=" col-form-label">Average Download Speed</label>

                        <input type="number" class="form-control" id="avg_download_speed" name="avg_download_speed" value="{{$objTv->avg_download_speed}}">

                    </div>
                    <div class=" mb-3">
                        <label for="input35" class=" col-form-label">Average Upload Speed</label>

                        <input type="number" class="form-control" id="avg_upload_speed" name="avg_upload_speed" value="{{$objTv->avg_upload_speed}}">

                    </div>


                    <div class=" mb-3">
                        <label for="input37" class="col-form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" value="{{$objTv->price}}">
                    </div>


                    <div class="mb-3">
                        <label for="input40" class="col-sm-3 col-form-label">Contract Length</label>

                        <select id="contract_length_id" name="contract_length_id" class="select2 form-select">
                            @if($objContract)
                            @foreach($objContract as $val)
                            <option value="{{$val->id}}" @if($val->id == $objTv->contract_length_id) selected @endif>{{$val->contract_length}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class=" mb-3">
                                <label for="input40" class=" col-form-label">Commission
                                </label>
                                <input type="number" class="form-control" id="commission" name="commission" value="{{$objTv->commission}}">
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class=" mb-3">
                                <label for="input40" class=" col-form-label">Commission Type
                                </label>
                                <select id="commission_type" name="commission_type" class="select2 form-select">
                                    @if($objCommission)
                                    @foreach($objCommission as $val)
                                    <option value="{{$val->id}}" @if($val->id == $objTv->commission_type) selected @endif>{{$val->commission_type}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                  

                    <div class=" mb-3">
                        <label for="input37" class="col-form-label">AKJ Product ID</label>

                        <input type="text" class="form-control" id="akj_product_id" name="akj_product_id" value="{{$objTv->akj_product_id}}">
                    </div>
                    <div class="mb-3">
                        <label for="input37" class="col-form-label">AKJ Discount ID</label>
                        <input type="text" class="form-control" id="akj_discount_id" name="akj_discount_id" value="{{$objTv->akj_discount_id}}">
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <!-- <label for="input35" class="col-form-label"><b>Top 3 Product</b></label> -->
                            <div class="mb-3 add-scroll">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1"  @if($objTv->is_featured==1
                                    )checked @endif>
                                    <label class="form-check-label" for="flexCheckDefault">Feature product</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="mb-3 add-scroll">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="order_process" value="1" @if($objTv->is_order_process == 1) checked @endif>
                                    <label class="form-check-label" for="flexCheckDefault">Show on Order Process</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="mb-3 add-scroll">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_page" value="1" @if($objTv->is_page == 1) checked @endif>
                                    <label class="form-check-label" for="flexCheckDefault">Show on TV Page</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <label class=" col-form-label"></label>
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>

                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="col-md-3 col-12">
            <div class="card">

                <div class="card-body p-4">
                    <div class="mb-3 form-group">
                        <label for="input40" class="col-form-label"><b>Publish Status</b>
                        </label>
                        <select id="online_status" name="online_status" class="select2 form-select">

                            <option value="1" @if($objTv->status == 1) selected @endif>Publish</option>
                            <option value="0" @if($objTv->status == 0) selected @endif>Draft</option>
                            <option value="2" @if($objTv->status == 2) selected @endif>Legacy</option>
                        </select>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="input40" class="col-form-label"><b>Product Type</b>
                        </label>
                        <select id="product_type" name="product_type" class="select2 form-select">
                            <option value="consumer" @if($objTv->product_type == "consumer") selected @endif>Consumer</option>
                            <option value="business" @if($objTv->product_type == "business") selected @endif>Business</option>
                            <option value="upgrade" @if($objTv->product_type == "upgrade") selected @endif>Upgrade</option>
                            <option value="affiliate" @if($objTv->product_type == 'affiliate') selected @endif>Affiliate</option>
                            <option value="cln" @if($objTv->product_type == 'cln') selected @endif>CLN</option>
                        
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="input40" class="col-form-label"><b>Type</b>
                        </label>

                        <select id="category" name="category" class="select2 form-select">
                            @if($objCategory)
                            @foreach($objCategory as $val)
                            <option value="{{$val->id}}" @if($val->id == $objTv->category_id) selected @endif>{{$val->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="input40" class="col-form-label"><b>Order Type</b>
                        </label>
                        <select class="select2 form-select" id="order_types" name="order_types">
                            <option value=" ">Select</option>
                            <option value="New" @if($objTv->order_type == 'New') selected @endif>New</option>
                            <option value="CLN" @if($objTv->order_type == 'CLN') selected @endif>CLN</option>
                            <option value="Transfer" @if($objTv->order_type == 'Transfer') selected @endif>Transfer</option>
                        </select>
                    </div>

                    @if($objTv)
                    @if($objTv->category_id == 10)
                    <div class="mb-3 form-group" id="mpf_code">
                        <label for="input40" class="col-form-label"><b>MPF Product Code</b>
                        </label>
                        <input type="text" class="form-control" id="mpf_product" name="mpf_product" placeholder="MPF Product Code" value="{{$objTv->mpf_product}}">
                    </div>
                    @endif
                    @endif

                    <div class="mb-3 form-group">
                        <label for="input40" class=" col-form-label"><b>Affiliate</b>
                        </label>
                        <select id="affiliate_name" name="affiliate_name" class="select2 form-select">
                            <option value=" ">Select</option>
                            @if($objAffiliates)
                            @foreach($objAffiliates as $val)
                            <option value="{{$val->affiliate_name}}" @if($val->affiliate_name == $objTv->affiliate) selected @endif>{{$val->affiliate_name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @if($objTv->affiliate)
                    <div class="mb-3 form-group" id="temp_old">
                        <label for="input40" class=" col-form-label"><b>Template</b>
                        </label>
                        <select id="template" name="template" class="template form-select">
                            <option value="Default" @if($objTv->template == 'Default') selected @endif>Default</option>
                        </select>
                    </div>
                    @else
                    <div class="mb-3 form-group" id="temp" style="display:none">
                        <label for="input40" class=" col-form-label"><b>Template</b>
                        </label>
                        <select id="template" name="template" class="template form-select">
                            <option value="Default" @if($objTv->template == 'Default') selected @endif>Default</option>
                        </select>
                    </div>
                    @endif

                    <div class="mb-3 form-group">
                        <label for="input40" class="col-form-label"><b data-bs-toggle="tooltip" data-bs-title="Catalogue Name for Speed">Catalogue Name</b>
                        </label>
                        <input type="text" class="form-control" id="catalogue_name" name="catalogue_name" placeholder="Catalogue Name" value="{{$objTv->catalogue_name}}">
                    </div>

                    <div class="mb-3 form-group">
                        <label for="input40" class="col-form-label"><b data-bs-toggle="tooltip" data-bs-title="Product Name for Api">Product Name</b>
                        </label>
                        <input type="text" class="form-control" id="product_name_api" name="product_name_api" placeholder="Product Name for Api" value="{{$objTv->product_name_api}}">
                    </div>

                    @php
                    $features = explode(",",$objTv->feature_id);
                    $add_extras = explode(",",$objTv->add_extras);
                    $related_products = explode(",",$objTv->related_products);
                    @endphp
                    <label for="input35" class="col-form-label"><b>Addons</b></label>
                    <div class="mb-3 add-scroll">
                        @if($objAdditionalCategories)
                        @foreach($objAdditionalCategories as $val)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="additional_extras[]" value="{{$val->id}}" @if(in_array($val->id, $add_extras)) checked @endif>
                            <label class="form-check-label" for="flexCheckDefault">{{$val->internal_name}}</label>
                        </div>
                        @endforeach
                        @endif
                    </div>



                    <label for="input35" class="col-form-label"><b>Related Products</b></label>
                    <div class="mb-3 add-scroll">
                        @if($objRelatedProducts)
                        @foreach($objRelatedProducts as $val)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="related_products[]" value="{{$val->id}}" @if(in_array($val->id, $related_products)) checked @endif>
                            <label class="form-check-label" for="flexCheckDefault">{{$val->cat_name}}</label>
                        </div>
                        @endforeach
                        @endif
                    </div>

                    <label for="input35" class="col-form-label"><b>Features</b></label>
                    <div class="mb-3 add-scroll">
                        @if($objFeature)
                        @foreach($objFeature as $val)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[]" value="{{$val->id}}" @if(in_array($val->id, $features)) checked @endif>
                            <label class="form-check-label" for="flexCheckDefault">{{$val->features}}</label>
                        </div>
                        @endforeach
                        @endif
                    </div>

                    <label for="input40" class="col-sm-6 col-form-label"><b>Product Image </b></label>
                    <div class="mb-3">
                        @if ($objTv->product_image != null || $objTv->product_image != '')
                        <img src="{{asset('images/'.$objTv->product_image)}}" alt="image" id="pImage" style="width:20%">
                        @endif
                        <input type="file" class="form-control" name="image" id="image" accept="image/*">
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
    CKEDITOR.replace('description', {
        allowedContent: true,
        extraPlugins: 'colorbutton'
    });
</script>
<script type="text/javascript">
     $(document).ready(function() {
   $('#affiliate_name').on('change', function() {
            $("temp_old").hide();
            $("#temp").show();
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
</script>
@endpush