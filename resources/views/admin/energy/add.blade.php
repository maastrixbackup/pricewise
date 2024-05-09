@extends('admin.layouts.app')
@section('title','PriceWise- Energy Create')
@section('content')
<style type="text/css">
    .form-check-box {
    display: flex;
    align-items: center;
}

.form-check-pr label {
    position: relative;
    cursor: pointer;
}
.form-check-pr input {
    padding: 0;
    height: initial;
    width: initial;
    margin-bottom: 0;
    display: none;
    cursor: pointer;
}
.form-check-pr label:before {
    content: '';
    -webkit-appearance: none;
    background-color: transparent;
    border: 2px solid #fa9f1d;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05);
    padding: 10px;
    display: inline-block;
    position: relative;
    vertical-align: middle;
    cursor: pointer;
    margin-right: 5px;
}
.form-check-pr input:checked + label:after {content: '';display: block;position: absolute;top: 7px;left: 9px;width: 6px;height: 14px;border: solid #0079bf;border-width: 0 2px 2px 0;transform: rotate(45deg);}
</style>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.energy.index')}}">Energy Products</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<form id="productForm" method="post" action="{{route('admin.energy.store')}}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-9 col-lg-9 col-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New Energy Product</h5>
                </div>
                <div class="card-body p-4">
                    <div class=" mb-3">
                        <label for="input35" class=" col-form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Product Title">
                    </div>

                    <div class=" mb-3">
                        <label for="input37" class="col-form-label">URL</label>

                        <!-- <div class="input-group mb-3"> <span class="input-group-text">/product/</span> -->
                        <input type="text" class="form-control" id="link" name="link" readonly>
                        <!-- </div> -->

                    </div>

                    <div class="">
                        <label for="description" class=" col-form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Product Description"></textarea>
                    </div>
                    <div class="row">
                     <div class="col-md-4 mb-3">
                    <label for="provider" class="col-form-label"><b>Provider</b>
                    </label>

                    <select id="provider" name="provider" class="select2 form-select">
                        <option>Select</option>
                        @if($providers)
                        @foreach($providers as $provider)
                        <option value="{{$provider->id}}">{{$provider->name}}</option>
                        @endforeach
                        @endif
                    </select>
                    </div>
                    
                        
                        <div class="col-md-4 col-12">
                            <div class=" mb-3">
                                <label for="valid_till" class="col-form-label">Offer Valid Till</label>
                                <input type="date" class="form-control" id="valid_till" name="valid_till" placeholder="Valid Till">
                        </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class=" mb-3">
                        <label for="avg_delivery_time" class=" col-form-label">Average delivery time</label>
                        <input type="number" class="form-control" id="avg_delivery_time" name="avg_delivery_time" placeholder="Average Delivery Time">
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-md-12 col-12">
                            <div class=" mb-3">
                                <label for="pin_codes" class="col-form-label">Area PIN Codes</label>
                                <input type="text" class="form-control" id="pin_codes" name="pin_codes" placeholder="PIN codes with coma separated">
                        </div>
                        </div>
                        <div class="col-md-4 col-12">
                        <div class=" mb-3">
                            <label for="input35" class=" col-form-label">Transfer Service</label>

                            <div class="mb-3 add-scroll">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="transfer_service" value="1" >
                                    <label class="form-check-label" for="transfer_service">Available</label>
                                </div>
                            </div>

                        </div>
                        </div>

                
                
                <div class="col-md-4 col-12">
                    <div class=" mb-3">
                        <label for="input40" class=" col-form-label">Contract Length
                        </label>
                        <input type="number" class="form-control" id="contract_length" name="contract_length">
                    </div>
                </div>
                <div class="col-md-4 col-12">
                <div class="mb-3">
                    <label for="input40" class="col-form-label">Contract Type</label>

                    <select id="contract_length_id" name="contract_type" class="select2 form-select">
                        <option value="month">Monthly</option>
                        <option value="year">Yearly</option>
                    </select>
                </div>
                </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class=" mb-3">
                            <label for="input40" class=" col-form-label">Commission
                            </label>
                            <input type="number" class="form-control" id="commission" name="commission">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class=" mb-3">
                            <label for="input40" class=" col-form-label">Commission Type
                            </label>
                            <select id="commission_type" name="commission_type" class="select2 form-select">
                                <option value="flat">Flat</option>
                                <option value="prct">Percentage</option>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-md-6 col-12">
                        
                        <div class="mb-3 add-scroll">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" >
                                <label class="form-check-label" for="flexCheckDefault">Feature Product</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        
                        <div class="mb-3 add-scroll">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="no_gas" value="1" >
                                <label class="form-check-label" for="flexCheckDefault">No Gas</label>
                            </div>
                        </div>
                    </div>
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
    
    <div class="col-md-3 col-12 col-lg-3">
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
                    <label for="meter_type" class="col-form-label"><b>Meter Type</b>
                    </label>
                    <select id="meter_type" name="meter_type" class="select2 form-select">
                        <option value="">Select</option>
                        <option>Single Meter</option>
                        <option>Double Meter</option>
                        
                    </select>
                </div>
                <div class="mb-3 form-group">
                    <label for="no_of_person" class="col-form-label"><b>Number of Persons(Max)</b>
                    </label>
                    <input type="number" class="form-control" id="no_of_person" name="no_of_person">
                </div>
                <div class="mb-3">
                    <label for="category" class="col-form-label"><b>Category</b>
                    </label>

                    <select id="category" name="category" class="select2 form-select">
                        <option>Select</option>
                        @if($objCategory)
                        @foreach($objCategory as $val)
                        <option value="{{$val->id}}">{{$val->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <label for="input40" class="col-sm-6 col-form-label"><b>Energy Label </b></label>
                <div class="col-lg-12"><div class="form-check-box"><div class="form-group form-check-pr"><input type="checkbox" id="a" name="energy_label[]" value="A"><label for="a">A</label></div><div class="form-group form-check-pr"><input type="checkbox" id="b" name="energy_label[]" value="B"><label for="b">B</label></div><div class="form-group form-check-pr"><input type="checkbox" id="c" name="energy_label[]" value="C"><label for="c">C</label></div><div class="form-group form-check-pr"><input type="checkbox" id="d" name="energy_label[]" value="D"><label for="d">D</label></div><div class="form-group form-check-pr"><input type="checkbox" id="e" name="energy_label[]" value="E"><label for="e">E</label></div><div class="form-group form-check-pr"><input type="checkbox" id="f" name="energy_label[]" value="F"><label for="f">F</label></div><div class="form-group form-check-pr"><input type="checkbox" id="g" name="energy_label[]" value="G"><label for="g">G</label></div></div></div>
                <label for="input40" class="col-sm-6 col-form-label"><b>Product Image </b></label>
                <div class="mb-3">
                    
                        
                        <label for="upload_image">
                                <img src="#" id="uploaded_image" class="img img-responsive img-circle" width="100" alt="Select image" />

                                <div class="overlay">
                                    <div>Click to Change Image</div>
                                </div>
                                <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                                <input type="hidden" name="cropped_image" id="cropped_image">

                            </label>
                </div>
                
                <label for="input35" class="col-form-label"><b>Combo Offers</b></label>
                <div class="mb-3 add-scroll">
                    @if($combos)
                    @foreach($combos as $val)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="combos[]" value="{{$val->id}}">
                        <label class="form-check-label" for="flexCheckDefault">{{$val->title}} -  €{{$val->price}}</label>
                    </div>
                    @endforeach
                    @endif
                </div>

                
                
            </div>
        </div>
    </div>
    </div>

</form>
@endsection
@push('scripts')
<script src="{{ asset('assets/plugins/tinymce/tinymce.min.js')}}"></script>
<script>
    tinymce.init({
        selector: '#description',
        plugins: 'codesample code advlist autolink lists link image charmap print preview hr anchor pagebreak',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | codesample code',
            codesample_languages: [
                { text: 'HTML/XML', value: 'markup' },
                { text: 'JavaScript', value: 'javascript' },
                { text: 'CSS', value: 'css' },
                { text: 'PHP', value: 'php' },
                { text: 'Ruby', value: 'ruby' },
                { text: 'Python', value: 'python' },
                { text: 'Java', value: 'java' },
                { text: 'C', value: 'c' },
                { text: 'C#', value: 'csharp' },
                { text: 'C++', value: 'cpp' }
            ],
       
         file_picker_callback (callback, value, meta) {
        let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth
        let y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight

        tinymce.activeEditor.windowManager.openUrl({
          url : '/file-manager/tinymce5',
          title : 'Laravel File manager',
          width : x * 0.8,
          height : y * 0.8,
          onMessage: (api, message) => {
            callback(message.content, { text: message.text })
          }
        })
      }
    });

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

    
</script>
@endpush