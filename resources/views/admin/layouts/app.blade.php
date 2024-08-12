<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{ asset('assets/media/favicons/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('assets/media/favicons/apple-touch-icon-180x180.png') }}">
    <!-- END Icons -->

    <!-- Stylesheets -->

    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/dashmix.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css"
        integrity="sha512-pVCM5+SN2+qwj36KonHToF2p1oIvoU3bsqxphdOIWMYmgr4ZqD3t5DjKvvetKhXGc/ZG5REYTT6ltKfExEei/Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/mystyle.css') }}" rel="stylesheet">
    <!-- END Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
    <!--Image Cropper-->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.css') }}" />
    <link href="{{ asset('assets/css/plugins/cropper.css') }}" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style type="text/css">
        .image_area:hover .overlay {
            height: 50%;
            cursor: pointer;
        }

        h6 {
            font-size: 22px !important;
        }

        #icon {
            font-family: 'FontAwesome', 'sans-serif' !important;
        }

        input[readonly] {
            background-color: #f8f9fc;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .blink_text {
            animation-name: blinker;
            animation-duration: 2s;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
        }

        @keyframes blinker {
            0% {
                opacity: 1.0;
            }

            50% {
                opacity: 0.0;
            }

            100% {
                opacity: 1.0;
            }
        }


        .rating-bars {
            width: 100%;
        }

        .rating-bar {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .rating-bar .bar {
            height: 10px;
            margin-left: 10px;
            margin-right: 10px;
            background-color: orange;
        }

        .rates {
            color: orange;
        }
    </style>

    @stack('css')
</head>

<body>

    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">
        <!-- Side Overlay-->
        <aside id="side-overlay">
            <!-- Side Header - Close Side Overlay -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <a class="content-header bg-body-light justify-content-center text-danger" data-toggle="layout"
                data-action="side_overlay_close" href="javascript:void(0)">
                <i class="fa fa-2x fa-times-circle"></i>
            </a>
            <!-- END Side Header - Close Side Overlay -->

            <!-- Side Content -->
            <form action="db_modern.html" method="POST" onsubmit="return false;">
                <div class="content-side">
                    <div class="block pull-x">
                        <!-- Personal -->
                        <div class="block-content block-content-sm block-content-full bg-body-dark">
                            <span class="text-uppercase fs-sm fw-bold">Personal</span>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="mb-4">
                                <label class="form-label" for="so-profile-name">Name</label>
                                <input type="text" class="form-control form-control-alt" id="so-profile-name"
                                    name="so-profile-name" value="George Taylor">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="so-profile-email">Email</label>
                                <input type="email" class="form-control form-control-alt" id="so-profile-email"
                                    name="so-profile-email" value="g.taylor@example.com">
                            </div>
                        </div>
                        <!-- END Personal -->

                        <!-- Password Update -->
                        <div class="block-content block-content-sm block-content-full bg-body-dark">
                            <span class="text-uppercase fs-sm fw-bold">Password Update</span>
                        </div>
                        <div class="block-content">
                            <div class="mb-4">
                                <label class="form-label" for="so-profile-password">Current Password</label>
                                <input type="password" class="form-control form-control-alt" id="so-profile-password"
                                    name="so-profile-password">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="so-profile-new-password">New Password</label>
                                <input type="password" class="form-control form-control-alt"
                                    id="so-profile-new-password" name="so-profile-new-password">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="so-profile-new-password-confirm">Confirm New
                                    Password</label>
                                <input type="password" class="form-control form-control-alt"
                                    id="so-profile-new-password-confirm" name="so-profile-new-password-confirm">
                            </div>
                        </div>
                        <!-- END Password Update -->

                        <!-- Submit -->
                        <div class="block-content border-top">
                            <button type="submit" class="btn w-100 btn-alt-primary">
                                <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save
                            </button>
                        </div>
                        <!-- END Submit -->
                    </div>
                </div>
            </form>
            <!-- END Side Content -->
        </aside>
        <!-- END Side Overlay -->

        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        @include('admin.layouts.header')
        <!-- End Sidebar -->
        <!-- Main Container -->
        <main id="main-container">

            <!-- Hero -->
            <div class="content">
                @yield('content')

            </div>

        </main>
        <!-- END Main Container -->
        <!-- Image cropper modal-->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Crop Image Before Upload</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Image cropper modal-->
        <!-- Footer -->
        <footer id="page-footer" class="bg-body-extra-light">
            <div class="content py-0">
                <div class="row fs-sm">

                    <p class="mb-0">Copyright © {{ date('Y') }}. All right reserved.</p>

                </div>
            </div>
        </footer>
        <!-- END Footer -->
    </div>
    <!-- END Page Container -->

    <!--

      Core libraries and functionality
      webpack is putting everything together at assets/_js/main/app.js
    -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <!-- jQuery (required for jQuery Sparkline plugin) -->
    <script src="{{ asset('assets/js/lib/jquery.min.js') }}"></script>
    <!-- Page JS Plugins -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
    <!-- Page JS Code -->
    <script src="{{ asset('assets/js/pages/be_tables_datatables.min.js') }}"></script>

    <!-- Page JS Code -->

    <script src="https://codervent.com/rocker/demo/vertical/assets/plugins/validation/jquery.validate.min.js"></script>
    <script src="https://codervent.com/rocker/demo/vertical/assets/plugins/validation/validation-script.js"></script>

    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/app.js') }}"></script> -->
    <script src="{{ asset('assets/plugins/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/plugins/cropperjs/cropper.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('#datepicker').datepicker();
        });
        $(document).ready(function() {
            var $modal = $('#modal');
            var image = document.getElementById('sample_image');
            var cropper;

            $('#upload_image').change(function(event) {
                var files = event.target.files;
                var done = function(url) {
                    image.src = url;
                    $modal.modal('show');
                };

                if (files && files.length > 0) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
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
            }).on('hidden.bs.modal', function() {
                cropper.destroy();
                cropper = null;
            });

            $('#crop').click(function() {
                canvas = cropper.getCroppedCanvas({
                    // width: 400,
                    // height: 400
                });

                canvas.toBlob(function(blob) {
                    $modal.modal('hide');
                    var url = URL.createObjectURL(blob);
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function() {
                        var base64data = reader.result;
                        $('#uploaded_image').attr('src', base64data);
                        $('#cropped_image').val(base64data);
                    };
                });
            });
        });

        $(document).ready(function() {
            $("#category").on('change', function() {
                var cat_val = $(this).val(); // Get the selected category value

                // Make an AJAX call to fetch subcategories based on the selected category
                $.ajax({
                    url: "{{ route('admin.categories.index') }}", // Replace this with the actual URL to fetch subcategories
                    type: 'GET',
                    data: {
                        category_id: cat_val
                    },
                    success: function(response) {
                        // Clear existing options
                        $("#sub_category").html('');

                        // Populate subcategories dropdown with new options
                        $.each(response.data, function(index, subcategory) {
                            $("#sub_category").append('<option value="' + subcategory
                                .id + '">' + subcategory.title + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle errors here
                    }
                });
            });
        });
    </script>



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    @stack('scripts')
    {!! Toastr::message() !!}
    <script>
        $('.btn-close').on('click', function() {
            $('#modal').modal('hide');
        });
    </script>
</body>

</html>
