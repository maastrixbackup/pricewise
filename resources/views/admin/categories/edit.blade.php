@extends('admin.layouts.app')
@section('title','POPTelecom- Driver Edit')
@section('content')
<style>

        .image_area {
          position: relative;
        }

        img {
            display: block;
            max-width: 100%;
        }

        .preview {
            overflow: hidden;
            width: 160px; 
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }

        .modal-lg{
            max-width: 1000px !important;
        }

        .overlay {
          cursor: pointer;
          bottom: 95px;
          left: 25;
          right: 0;
          background-color: rgba(255, 255, 255, 0.5);          
          height: 0;
          transition: .5s ease;
          width: 100%;
        }

        .image_area:hover .overlay {
          height: 50%;
          cursor: pointer;
        }

        .text {
          color: #333;
          font-size: 20px;
          position: absolute;
          top: 50%;
          left: 50%;
          -webkit-transform: translate(-50%, -50%);
          -ms-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
          text-align: center;
        }
        .input-group-text{
            padding: 12px !important;
        }
        </style>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.categories.index')}}">Categories</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<form id="productForm" method="post" action="{{route('admin.categories.update',$objCategory->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Edit Category</h5>
                </div>
                <div class="card-body p-4">
                    <div class="card-body p-4">
                        <div class=" mb-3">
                            <label for="input35" class=" col-form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{$objCategory->name}}">
                        </div>
                        <div class="mb-3">
                        <label for="input40" class="col-sm-6 col-form-label"><b>Category Image </b></label>
                        
                        <label for="upload_image">
                                <img src="{{asset('storage/images/categories/'. $objCategory->image)}}" id="uploaded_image" class="img img-responsive img-circle" width="100" alt="Select image" />
                                <div class="overlay">
                                    <div>Click to Change Image</div>
                                </div>
                                <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                                <input type="hidden" name="cropped_image" id="cropped_image">

                            </label>
                        </div>
                        <div class="row mb-3">
                        <label for="input_type" class=" col-form-label">Select Icon</label>
                        <div class="form-group">
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa {{$objCategory->icon}}" id="fa_icon"></i></span>
                            </div>
                            <select class="form-control selectpicker" data-live-search="true" name="icon" id="icon">
                                <option value="">Select</option>
                                @include('admin.layouts.icons')
                            </select>
                        </div>
                        </div>
                    </div>
                        

                        <div class="row mb-3">
                            <div class="">
                                <label class=" col-form-label"></label>

                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        
    </div>

</form>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image Before Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img src="" id="sample_image" />
                        </div>
                        <div class="col-md-4">
                            <div class="preview"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="crop" class="btn btn-primary">Crop</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>  
@endsection
@push('scripts')
<script>
    // Get relevant element
    checkBox = document.getElementById('own_vehicle');
    // Check if the element is selected/checked
    if (checkBox.checked) {
        // Respond to the result
        $(".model").show();
    } else {
        $(".model").hide();
    }

    $("#own_vehicle").click(function() {
        if ($(this).is(":checked")) {
            $(".model").show();
        } else {
            $(".model").hide();
        }
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
            name: "required",
            

        },
        messages: {
            name: "Name is missing",
            
        },
        submitHandler: function(form) {

            var formData = new FormData(form);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: form.action,
                method: "post",
                data: formData,
                processData: false,
                contentType: false,
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

$(document).ready(function(){
    var $modal = $('#modal');
    var image = document.getElementById('sample_image');
    var cropper;

    $('#upload_image').change(function(event){
        var files = event.target.files;
        var done = function(url){
            image.src = url;
            $modal.modal('show');
        };

        if(files && files.length > 0)
        {
            var reader = new FileReader();
            reader.onload = function(event)
            {
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);
        }
    });

    $modal.on('shown.bs.modal', function() {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function(){
        cropper.destroy();
        cropper = null;
    });

    $('#crop').click(function(){
        canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400
        });

        canvas.toBlob(function(blob){
            $modal.modal('hide');
            var url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function(){
                var base64data = reader.result;
                $('#uploaded_image').attr('src', base64data);
                $('#cropped_image').val(base64data);
            };
        });
    });
});
</script>
@endpush