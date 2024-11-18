@extends('admin.layouts.app')
@section('title', 'Pricewise- FAQ Create')
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
                            href="{{ route('admin.general-faqs', $id) }}">General FAQs</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New FAQ</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.store-faqs') }}">
                        @csrf
                        <input type="hidden" name="cat" value="{{ $id }}">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="appData">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="title[]" value=""
                                            placeholder="Title" required>
                                    </td>
                                    <td>
                                        <textarea name="description[]" id="description" class="form-control" cols="30" rows="5"
                                            placeholder="Description" required></textarea>
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="btn  btn-success Add"><i class="fa fa-plus-square-o"
                                                aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.Add').on('click', function() {
                var htmlData = `
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="title[]" value=""
                                        placeholder="Title" required>
                                </td>
                                <td>
                                    <textarea name="description[]" id="description" class="form-control" cols="30" rows="5"
                                        placeholder="Description" required></textarea>
                                </td>
                                <td>
                                    <a href="javascript:;" class="btn  btn-danger removeTr"><i
                                                            class="fa fa-minus-square-o" aria-hidden="true"></i></a>
                                </td>
                            </tr>`;
                $('#appData').append(htmlData);
            });

            $(document).on('click', '.removeTr', function() {
                $(this).closest('tr').remove();
            });
        });

        // tinymce.init({
        //     selector: '#description',
        //     plugins: 'codesample code advlist autolink lists link image charmap print preview hr anchor pagebreak',
        //     toolbar_mode: 'floating',
        //     tinycomments_mode: 'embedded',
        //     tinycomments_author: 'Author name',

        //     toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | codesample code',
        //     codesample_languages: [{
        //             text: 'HTML/XML',
        //             value: 'markup'
        //         },
        //         {
        //             text: 'JavaScript',
        //             value: 'javascript'
        //         },
        //         {
        //             text: 'CSS',
        //             value: 'css'
        //         },
        //         {
        //             text: 'PHP',
        //             value: 'php'
        //         },
        //         {
        //             text: 'Ruby',
        //             value: 'ruby'
        //         },
        //         {
        //             text: 'Python',
        //             value: 'python'
        //         },
        //         {
        //             text: 'Java',
        //             value: 'java'
        //         },
        //         {
        //             text: 'C',
        //             value: 'c'
        //         },
        //         {
        //             text: 'C#',
        //             value: 'csharp'
        //         },
        //         {
        //             text: 'C++',
        //             value: 'cpp'
        //         }
        //     ],

        //     file_picker_callback(callback, value, meta) {
        //         let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
        //             'body')[0].clientWidth
        //         let y = window.innerHeight || document.documentElement.clientHeight || document
        //             .getElementsByTagName('body')[0].clientHeight

        //         tinymce.activeEditor.windowManager.openUrl({
        //             url: '/file-manager/tinymce5',
        //             title: 'Laravel File manager',
        //             width: x * 0.8,
        //             height: y * 0.8,
        //             onMessage: (api, message) => {
        //                 callback(message.content, {
        //                     text: message.text
        //                 })
        //             }
        //         })
        //     }
        // });
    </script>
@endpush