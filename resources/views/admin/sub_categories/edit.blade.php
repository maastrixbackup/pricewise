@extends('admin.layouts.app')
@section('title', 'Pricewise- Provider Feature')
@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.dashboard') }}">
                            Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.sub-categories.index') }}">Sub Category</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Sub Category</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.sub-categories.update', $subCat->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Sub Category<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="title" id="title"
                                    value="{{ $subCat->title }}" placeholder="Sub Category">
                                @error('title')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Url<sup class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="slug" id="slug"
                                    value="{{ $subCat->slug }}" placeholder="Url" readonly>
                                @error('slug')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Parent Category<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <select name="category_id" class="form-control" id="category_id">
                                    <option value="" selected disabled>Select</option>
                                    @if ($category)
                                        @foreach ($category as $item => $val)
                                            <option value="{{ $val->id }}"
                                                {{ $subCat->category_id == $val->id ? 'selected' : '' }}>{{ $val->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('provider')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <label for="upload_image" class="mb-3">

                            <img src="{{ asset('storage/images/sub_categories/' . $subCat->image) }}" id="uploaded_image"
                                class="img img-responsive img-circle" width="100" alt="Select image" />

                            <div class="overlay" style="cursor: pointer">
                                <div>Click to Change Image</div>
                            </div>
                            <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                            <input type="hidden" name="cropped_image" id="cropped_image">

                        </label>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Status<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="status">
                                    <option value="active" {{ $subCat->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $subCat->status == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <label class=" col-form-label"></label>
                            <div class="">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>
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
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#descriptions',
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
    </script>
    <script>
        $("#title").keyup(function() {
            var title_val = $("#title").val();
            $("#slug").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
        $("#title").keydown(function() {
            var title_val = $("#title").val();
            $("#slug").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
    </script>
@endpush
