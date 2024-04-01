<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

     <title>@yield('title')</title>

    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{ asset('assets/media/favicons/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/media/favicons/apple-touch-icon-180x180.png') }}">
    <!-- END Icons -->

    <!-- Stylesheets -->    
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/app.min.css') }}">

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/xwork.min.css"> -->
    <!-- END Stylesheets -->
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/mystyle.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.css')}}" />
        <link href="{{ asset('assets/css/plugins/cropper.css')}}" rel="stylesheet"/>
    <style type="text/css">
      #main-container{
    margin-top: 30px;
    padding: 10px;
}
h6{
    font-size:22px !important;
}
    </style>

    @stack('css')
  </head>

  <body>
    
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">
      <!-- Side Overlay-->
      <aside id="side-overlay">
        <!-- Side Header - Close Side Overlay -->
        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
        <a class="content-header bg-body-light justify-content-center text-danger" data-toggle="layout" data-action="side_overlay_close" href="javascript:void(0)">
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
                  <input type="text" class="form-control form-control-alt" id="so-profile-name" name="so-profile-name" value="George Taylor">
                </div>
                <div class="mb-4">
                  <label class="form-label" for="so-profile-email">Email</label>
                  <input type="email" class="form-control form-control-alt" id="so-profile-email" name="so-profile-email" value="g.taylor@example.com">
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
                  <input type="password" class="form-control form-control-alt" id="so-profile-password" name="so-profile-password">
                </div>
                <div class="mb-4">
                  <label class="form-label" for="so-profile-new-password">New Password</label>
                  <input type="password" class="form-control form-control-alt" id="so-profile-new-password" name="so-profile-new-password">
                </div>
                <div class="mb-4">
                  <label class="form-label" for="so-profile-new-password-confirm">Confirm New Password</label>
                  <input type="password" class="form-control form-control-alt" id="so-profile-new-password-confirm" name="so-profile-new-password-confirm">
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
      <!--
        Sidebar Mini Mode - Display Helper classes

        Adding 'smini-hide' class to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
        Adding 'smini-show' class to an element will make it visible (opacity: 1) when the sidebar is in mini mode
          If you would like to disable the transition animation, make sure to also add the 'no-transition' class to your element

        Adding 'smini-hidden' to an element will hide it when the sidebar is in mini mode
        Adding 'smini-visible' to an element will show it (display: inline-block) only when the sidebar is in mini mode
        Adding 'smini-visible-block' to an element will show it (display: block) only when the sidebar is in mini mode
      -->
     @include('admin.layouts.sidebar')

     @include('admin.layouts.header')

      <!-- Main Container -->
      <main id="main-container">

        <!-- Hero -->
        @yield('content')
        
      </main>
      <!-- END Main Container -->
      <!-- Footer -->
      <footer id="page-footer" class="bg-body-extra-light">
        <div class="content py-0">
          <div class="row fs-sm">
            
            <p class="mb-0">Copyright © {{date('Y')}}. All right reserved.</p>
       
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
    <script src="{{ asset('assets/js/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/chart.js/chart.umd.js') }}"></script>

     <!-- Page JS Code -->
    <script src="{{ asset('assets/js/pages/be_pages_dashboard_v1.min.js') }}"></script>

    <!-- <script src="{{ asset('assets/js/index.js')}}"></script> -->
    <script src="https://codervent.com/rocker/demo/vertical/assets/plugins/validation/jquery.validate.min.js"></script>
    <script src="https://codervent.com/rocker/demo/vertical/assets/plugins/validation/validation-script.js"></script>

    <script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
    <!-- Page JS Code -->
    <script src="{{ asset('assets/js/pages/db_analytics.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    
    <!-- Page JS Helpers (jQuery Sparkline Plugin) -->
    <script>Dashmix.helpersOnLoad('jq-sparkline');</script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/app.js')}}"></script> -->
    
    <script src="{{ asset('assets/js/plugins/dropzone/dropzone.js')}}"></script>
    <script src="{{ asset('assets/js/plugins/cropperjs/cropper.min.js')}}"></script>
    <script type="text/javascript">
        $(function() {
            $('#datepicker').datepicker();
        });
    </script>
    @stack('scripts')
    {!! Toastr::message() !!}
  </body>
</html>
