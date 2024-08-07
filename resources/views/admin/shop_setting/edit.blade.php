@extends('admin.layouts.app')
@section('title', 'Pricewise- Product Category Create')
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
                    {{-- <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.product-category.index') }}">Product Category</a>
                    </li> --}}
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Shop setting</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.shop-store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Free Delivery On above</label>
                            <div class="">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">&#8364;</span>
                                    </div>
                                    <input type="number" class="form-control" name="order_above"
                                        value="{{ $shopSetting->order_above ?? '' }}" placeholder="Ex-299">
                                    @error('order_above')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Order Before (Time)</label>
                            <div class="">
                                <input type="datetime-local" class="form-control" name="order_time"
                                    value="{{ $shopSetting->order_time ?? '' }}" placeholder="">
                                @error('order_time')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Reflection Period</label>
                            <div class="">
                                <div class="input-group">
                                    <input type="number" class="form-control" name="period" id="period"
                                        value="{{ $shopSetting->period ?? '' }}" placeholder="Ex-10 Days">
                                    @error('period')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="input-group-append">
                                        <span class="input-group-text">Days</span>
                                    </div>
                                </div>
                                <div id="alert1" class="alert  my-1 py-1" style="display: none; color:red;"></div>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Limited Stock Quantity</label>
                            <div class="">
                                <input type="number" class="form-control" name="limited_stock" id="limited_stock"
                                    value="{{ $shopSetting->limited_stock ?? '' }}" placeholder="Quantity">
                                @error('limited_stock')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                                <div id="alert" class="alert  my-1 py-1" style="display: none; color:red;"></div>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input35" class="col-form-label">Accepted Payment Methods</label>
                        </div>
                        <label for="upload_image" class="mb-3">

                            <img src="{{ asset('storage/images/shops/' . $shopSetting->image) }}" id="uploaded_image"
                                class="img img-responsive img-circle" width="" alt="Select image" />

                            <div class="overlay" style="cursor: pointer">
                                <div>Click to Change Image</div>
                            </div>
                            <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                            <input type="hidden" name="cropped_image" id="cropped_image">

                        </label>
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
            selector: '#description',
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

        $('#limited_stock').on('keyup', function () {
            var qty = $('#limited_stock').val();
            // console.log(qty);
            if (qty < 0 ) {
                console.log('Invalid Input');
                $('#alert').html('Invalid Input. Please input valid Value');
                $('#alert').show();
                $('#limited_stock').val('');

            } else {
                $('#alert').hide();

            }
            
        });
        $('#period').on('keyup', function () {
            var qty = $('#period').val();
            // console.log(qty);
            if (qty < 0 ) {
                console.log('Invalid Input');
                $('#alert1').html('Invalid Input. Please input valid Value');
                $('#alert1').show();
                $('#period').val('');

            } else {
                $('#alert1').hide();

            }
            
        });
    </script>
@endpush
