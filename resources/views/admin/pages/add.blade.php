@extends('admin.layouts.app')
@section('title','Price Compare- Page Create')
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
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.pages.index')}}">Pages</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Add New Page</h5>
            </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="card-body p-4">
            
                    <form method="POST" action="{{ route('admin.pages.store')}}">
                        @csrf
                        
                        <div class="row mb-3">
                            <label class=" col-form-label">Title</label>
                            <input type="text" class="form-control" placeholder="title" name="title" value="">
                        </div>
                        <div class="row mb-3">
                        <label for="input40" class=" col-form-label"><b>Image </b></label>
                        <img src="" id="profile-pic">
                        <div class="mb-3">
                            <input type="file" class="form-control" name="image" id="button-image" accept="image/*">
                        </div>
                        </div>
                        <div class="row mb-3">
                            <label class=" col-form-label">Description</label>
                            <textarea type="text" rows="9" placeholder="description" name="description" id="description"></textarea>
                        </div>
                        <div class="row mb-3">
                        <label for="status" class=" col-form-label">Status</label>
                        <div class="">
                            <select class="form-control" id="status" name="status">
                            <option value="">Select</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                            
                            </select>
                        </div>
                        </div>
                        <div class="row">
                        <label class=" col-form-label"></label>
                        <div class="">
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
            description: "required",
        },
        messages: {
            name: "Title is missing",
            description: "Description is missing",
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
                    toastr.error('Something went wrong . Please try again later!!', '');
                }
            });
            return false;
        }
    });
</script>
@endpush

