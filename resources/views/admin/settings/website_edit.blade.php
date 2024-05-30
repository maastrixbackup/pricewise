@extends('admin.layouts.app')
@section('title','Energise - Website Setting')
@section('content')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

    <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Website Setting</h5>
            </div>
            <form id="websiteForm" method="post" action="{{route('admin.website-store')}}">
                    @csrf
    <div class="row">
    
    <div class="col-12 col-lg-6 col-md-6">        
            <div class="card-body p-4">   
                    <div class="mb-3">
                        <label for="site_title" class="col-form-label">Website Title</label>
                        <input type="text" class="form-control" id="site_title" name="site_title" placeholder="Site Title" value="{{$website->site_title}}">
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Website Description</label>
                        <textarea type="text" class="form-control" id="description" name="description" placeholder="Description.." > {{$website->description}}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Address</label>
                        <textarea type="text" class="form-control" id="address" name="address" placeholder="Address">{{$website->address}}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone No." value="{{$website->phone}}">
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{$website->email}}">
                    </div>

                    

                    <label class="col-form-label"></label>

                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>

                    </div>
                
            </div>
        </div>
    
    <div class="col-12 col-lg-6 col-md-6">        
            <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="input40" class="col-sm-6 col-form-label"><b>Logo </b></label>
                        <label for="upload_image">
                                <img src="{{asset('storage/images/website/'.$website->logo)}}" id="uploaded_image" class="img img-responsive img-circle" width="100" alt="Select image" />

                                <div class="overlay">
                                    <div>Click to Change Logo</div>
                                </div>
                                <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                                <input type="hidden" name="cropped_image" id="cropped_image">

                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Facebook</label>
                        <input type="text" class="form-control" id="facebook" name="facebook" placeholder="Facebook" value="{{$website->facebook}}">
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Twitter</label>
                        <input type="text" class="form-control" id="twitter" name="twitter" placeholder="Twitter" value="{{$website->twitter}}">
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Instagram</label>
                        <input type="text" class="form-control" id="instagram" name="instagram" placeholder="Instagram" value="{{$website->instagram}}">
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">LinkedIn</label>
                        <input type="text" class="form-control" id="linkedin" name="linkedin" placeholder="LinkedIn" value="{{$website->linkedin}}">
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Telegram</label>
                        <input type="text" class="form-control" id="telegram" name="telegram" placeholder="Telegram" value="{{$website->telegram}}">
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">YouTube</label>
                        <input type="text" class="form-control" id="YouTube" name="youTube" placeholder="YouTube" value="{{$website->youTube}}">
                    </div>
            </div>
                   
    </div>
</div>
 </form>
</div>
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
<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function() {

    document.getElementById('button-image').addEventListener('click', (event) => {
      event.preventDefault();

      window.open('http://192.168.1.44:8000/file-manager/fm-button', 'fm', 'width=1400,height=800');
    });
  });

  // set file link
  function fmSetLink($url) {
    document.getElementById('image_label').value = $url;
    document.getElementById('profile-pic').src = $url;
  }
    //CKEDITOR.replace('description', {
   // allowedContent: true
//});

    $("#websiteForm").validate({
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
            description: "required",
            address: "required",
        },
        messages: {
            description: "Description is missing",
            address: "Address is missing",
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                method: "post",
                data: $(form).serialize(),
                success: function(data) {
                    //success
                    $("#websiteForm").trigger("reset");
                    if (data.status) {
                        toastr.success(data.message.message, '');
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
            // aspectRatio: 1,
            // viewMode: 3,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function(){
        cropper.destroy();
        cropper = null;
    });

    $('#crop').click(function(){
        canvas = cropper.getCroppedCanvas();

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