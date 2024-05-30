@extends('admin.layouts.app')
@section('title','Price Compare- Banner Create')
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
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.banners.index')}}">Banners</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Add New Banner/Slider</h5>
            </div>
            <div class="card-body p-4">
                <form id="featureForm" method="post" action="{{route('admin.banners.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <label for="title" class="col-sm-3 col-form-label">Title</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                        </div>
                    </div>
                    <!-- Add the mobile number field -->
                    <div class="row mb-3">
                        <label for="mobile" class="col-sm-3 col-form-label">Mobile No</label>
                        <div class="col-sm-9">
                            <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile No" pattern="[0-9]{10}" title="Mobile number must be exactly 10 digits">
                        </div>
                    </div>
                    <!-- End mobile number field -->
                    <div class="row mb-3">
                        <label for="title" class="col-sm-3 col-form-label">Link</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="link" name="link" placeholder="Link">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="input40" class="col-sm-6 col-form-label"><b>Image </b></label>
                        <label for="upload_image">
                                <img src="#" id="uploaded_image" class="img img-responsive img-circle" width="100" alt="Select image" />

                                <div class="overlay">
                                    <div>Click to Change Image</div>
                                </div>
                                <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                                <input type="hidden" name="cropped_image" id="cropped_image">

                            </label>
                    </div>
                    <div class="row mb-3">
                        <label for="input40" class="col-sm-6 col-form-label"><b>Description </b></label>
                    <textarea id="description" name="description" placeholder="Description"></textarea>
                    </div>                    
                    <div class="row mb-3">
                        <label for="type" class="col-sm-3 col-form-label">Type</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="type" name="type">
                            <option value="">Select</option>
                            <option value="banner">Banner</option>
                            <option value="slider">Slider</option>                            
                            </select>
                        </div>
                    </div>                    
                    <div class="row mb-3">
                        <label for="slider_id" class="col-sm-3 col-form-label"><b>Slider Id </b></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="slider_id" id="slider_id">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="type" class="col-sm-3 col-form-label">Page</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="page" name="page">
                            <option value="">Select</option>
                            @foreach($pages as $page)
                            <option value="{{$page->slug}}">{{$page->title}}</option>
                            @endforeach                          
                            </select>
                        </div>
                    </div> 
                    <div class="row mb-3">
                        <label for="section" class="col-sm-3 col-form-label">Section</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="section" id="section">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="status" name="status">
                            <option value="1">Active</option>
                            <option value="0" selected>Inactive</option>
                            
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4" name="submit2">Submit</button>
                                <button type="reset" class="btn btn-light px-4">Reset</button>
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
    
    $("#featureForm").validate({
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
            type: "required",
            mobile: {
                required: true,
                pattern: /[0-9]{10}/,
            }
        },
        messages: {
            title: "Title is required",
            type: "Type is required",
            mobile: {
                required: "Mobile number is required",
                pattern: "Please enter a valid 10-digit mobile number"
            }
        },
        submitHandler: function(form) {
            tinymce.triggerSave();
            $.ajax({
                url: form.action,
                method: "post",
                data: $(form).serialize(),
                success: function(data) {
                    //success
                    
                    if (data.status) {
                        location.href = data.redirect_location;
                    } else {
                        toastr.error(data.message.message, '');
                    }
                },
                error: function(e) {
                    toastr.error('Something went wrong. Please try again later!', '');
                }
            });
            return false;
        }
    });


</script>
@endpush
