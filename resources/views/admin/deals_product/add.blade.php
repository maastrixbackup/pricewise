@extends('admin.layouts.app')
@section('title', 'Pricewise- Tv Package Create')

@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.deals-product.index') }}">Deals</a>
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
                    <h5 class="mb-0">Add New Deal</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.deals-product.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Title<sup class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="title" id="tt" value="{{ old('title') }}"
                                    placeholder="Title">
                                @error('title')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Valid Till<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <input type="datetime-local" class="form-control" name="valid_till"
                                    value="{{ old('valid_till') }}" placeholder="Valid Till">
                                @error('valid_till')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Category<sup class="text-danger">*</sup></label>
                            <div class="">
                                @php
                                    $categories = App\Models\ProductCategory::all();
                                @endphp
                                <select class="form-control" name="category">
                                    <option value="">Select</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->title }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Deals Image<sup
                                    class="text-danger">*</sup></label>
                            <div class="">
                                <label for="upload_image">
                                    <img src="#" id="uploaded_image" class="img img-responsive img-circle"
                                        width="100" alt="Select image" />

                                    <div class="overlay">
                                        <div>Click to Change Image</div>
                                    </div>
                                    <input type="file" name="image" class="image" id="upload_image"
                                        style="display:none" />
                                    <input type="hidden" name="cropped_image" id="cropped_image">

                                </label>
                                @error('image')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Status<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive" selected>Inactive</option>
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
                                    <button type="submit" class="btn btn-primary px-4">Submit</button>
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
    <script>
        $(document).ready(function() {

            function initializeChoices() {
                var element = document.querySelector("#product");
                if (element) {
                    choices = new Choices(element, {
                        allowHTML: false, // Enable HTML rendering in Choices
                        removeItemButton: true
                    });
                }
            }

            function loadProducts(categoryId) {
                var csrfToken = $('meta[name=csrf-token]').attr('content');

                // Make AJAX request with CSRF token
                $.ajax({
                    url: `{{ route('admin.get-products-categorywise') }}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': csrfToken // Include CSRF token in headers
                    },
                    data: {
                        category_id: categoryId
                    },
                    success: function(response) {
                        choices.destroy();

                        var products = response;
                        var productDropdown = $('#product');
                        productDropdown.empty(); // Empty the dropdown first
                        productDropdown.append('<option value="">Select</option>');
                        $.each(products, function(index, product) {
                            productDropdown.append('<option value="' + product.id + '">' +
                                product.title + '</option>');
                        });
                        // Destroy and reinitialize Choices library
                        initializeChoices();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            // Bind change event to category dropdown
            $('[name=category]').on('change', function() {
                var categoryId = $(this).val();
                loadProducts(categoryId);
            });

            // Initialize Choices library initially
            initializeChoices();
        });

        tinymce.init({
            selector: '#tt',
            license_key: 'gpl',
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

            setup: function(editor) {
                const maxChars = 50; // Set your character limit here

                // Create a div element to display the character count
                const charCountSpan = document.createElement('span');
                charCountSpan.id = 'charCount';
                charCountSpan.style.display = 'block';
                charCountSpan.style.textAlign = 'right';
                charCountSpan.style.marginTop = '5px';
                charCountSpan.textContent = `Characters: 0/${maxChars}`;
                editor.on('init', function() {
                    editor.getContainer().appendChild(charCountSpan);
                });

                function updateCharCount() {
                    const content = editor.getContent({
                        format: 'text'
                    });
                    const charCount = content.length;
                    charCountSpan.textContent = `Characters: ${charCount}/${maxChars}`;
                }

                editor.on('input', function() {
                    const content = editor.getContent({
                        format: 'text'
                    });
                    if (content.length > maxChars) {
                        editor.setContent(content.substring(0, maxChars));
                    }
                    updateCharCount();
                });

                editor.on('keydown', function(e) {
                    const content = editor.getContent({
                        format: 'text'
                    });
                    if (content.length >= maxChars && e.key !== 'Backspace' && e.key !== 'Delete') {
                        e.preventDefault();
                    }
                });

                editor.on('init', function() {
                    updateCharCount(); // Initialize the character count display
                });

            },

            file_picker_callback(callback, value, meta) {
                let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth
                let y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight

                tinymce.activeEditor.windowManager.openUrl({
                    url: '/file-manager/tinymce5',
                    title: 'Laravel File manager',
                    width: x * 0.2,
                    height: y * 0.2,
                    onMessage: (api, message) => {
                        callback(message.content, {
                            text: message.text
                        })
                    }
                })
            }


        });
    </script>
@endpush
