<!-- Header -->
      <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
          <!-- Left Section -->
          <div>
            <!-- Toggle Sidebar -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
            <button type="button" class="btn btn-alt-secondary d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
              <i class="fa fa-fw fa-bars"></i>
            </button>
            <!-- END Toggle Sidebar -->

            <!-- Open Search Section -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="header_search_on">
              <i class="fa fa-fw fa-search"></i> <span class="ms-1 d-none d-sm-inline-block">Search..</span>
            </button>
            <!-- END Open Search Section -->
          </div>
          <!-- END Left Section -->

          <!-- Right Section -->
          <div>
            <!-- Notifications Dropdown -->
            <div class="dropdown d-inline-block">
              <button type="button" class="btn btn-alt-secondary" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="far fa-fw fa-flag"></i>
                <span class="badge bg-primary rounded-pill">3</span>
              </button>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                <div class="bg-primary-dark rounded-top fw-semibold text-white text-center p-3">
                  Notifications
                </div>
                <ul class="nav-items my-2">
                  <li>
                    <a class="d-flex text-dark py-2" href="javascript:void(0)">
                      <div class="flex-shrink-0 mx-3">
                        <i class="fa fa-fw fa-check-circle text-success"></i>
                      </div>
                      <div class="flex-grow-1 fs-sm pe-2">
                        <div class="fw-semibold">App was updated to v5.6!</div>
                        <div class="text-muted">3 min ago</div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a class="d-flex text-dark py-2" href="javascript:void(0)">
                      <div class="flex-shrink-0 mx-3">
                        <i class="fa fa-fw fa-user-plus text-info"></i>
                      </div>
                      <div class="flex-grow-1 fs-sm pe-2">
                        <div class="fw-semibold">New Subscriber was added! You now have 2580!</div>
                        <div class="text-muted">10 min ago</div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a class="d-flex text-dark py-2" href="javascript:void(0)">
                      <div class="flex-shrink-0 mx-3">
                        <i class="fa fa-times-circle text-danger"></i>
                      </div>
                      <div class="flex-grow-1 fs-sm pe-2">
                        <div class="fw-semibold">Server backup failed to complete!</div>
                        <div class="text-muted">30 min ago</div>
                      </div>
                    </a>
                  </li>
                </ul>
                <div class="p-2 border-top">
                  <a class="btn btn-alt-primary w-100 text-center" href="javascript:void(0)">
                    <i class="fa fa-fw fa-eye opacity-50 me-1"></i> View All
                  </a>
                </div>
              </div>
            </div>
            <!-- END Notifications Dropdown -->

            <!-- User Dropdown -->
            <div class="dropdown d-inline-block">
              <button type="button" class="btn btn-alt-secondary" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="far fa-fw fa-user-circle"></i>
                <i class="fa fa-fw fa-angle-down ms-1 d-none d-sm-inline-block"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="page-header-user-dropdown">
                <div class="bg-primary-dark rounded-top fw-semibold text-white text-center p-3">
                  @if (Auth::guard('admin')->user()->profileImage != null || Auth::guard('admin')->user()->profileImage != '')
                     <img src="{{asset('images/'.Auth::guard('admin')->user()->profileImage)}}" alt="image" id="pImage" class="img-avatar img-avatar48 img-avatar-thumb">
                     @else
                     <img src="{{asset('assets/media/avatars/avatar10.jpg')}}" class="img-avatar img-avatar48 img-avatar-thumb" alt="user avatar">
                     @endif
                  <!-- <img class="img-avatar img-avatar48 img-avatar-thumb" src="assets/media/avatars/avatar10.jpg" alt=""> -->
                  <div class="pt-2">
                    <a class="text-white fw-semibold" href="be_pages_generic_profile.html">{{Auth::guard('admin')->user()->name}}</a>
                  </div>
                </div>
                <div class="p-2">
                  <a class="dropdown-item" href="{{route('admin.edit-profile',['id' => Auth::guard('admin')->user()->id])}}">
                    <i class="far fa-fw fa-user me-1"></i> Profile
                  </a>
                  <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{route('admin.change-password',['id' => Auth::guard('admin')->user()->id])}}">
                    <span><i class="far fa-fw fa-key me-1"></i> Change Password</span>
                    
                  </a>
                  
                  <!-- END Side Overlay -->

                  <div role="separator" class="dropdown-divider"></div>
                  <form method="POST" action="{{ route('admin.logout') }}">
                             @csrf
                             <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                 <i class='bx bx-log-out-circle'></i><span>Logout</span>
                             </a>
                         </form>
                  <!-- <a class="dropdown-item" href="op_auth_signin.html">
                    <i class="far fa-fw fa-arrow-alt-circle-left me-1"></i> Sign Out
                  </a> -->
                </div>
              </div>
            </div>
            <!-- END User Dropdown -->

            <!-- Toggle Side Overlay -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="side_overlay_toggle">
              <i class="far fa-fw fa-bookmark"></i>
            </button>
            <!-- END Toggle Side Overlay -->
          </div>
          <!-- END Right Section -->
        </div>
        <!-- END Header Content -->

        <!-- Header Search -->
        <div id="page-header-search" class="overlay-header bg-sidebar-dark">
          <div class="content-header">
            <form class="w-100" action="be_pages_generic_search.html" method="POST">
              <div class="input-group">
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-dark" data-toggle="layout" data-action="header_search_off">
                  <i class="fa fa-fw fa-times-circle"></i>
                </button>
                <input type="text" class="form-control border-0" placeholder="Search Application.." id="page-header-search-input" name="page-header-search-input">
              </div>
            </form>
          </div>
        </div>
        <!-- END Header Search -->

        <!-- Header Loader -->
        <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
        <div id="page-header-loader" class="overlay-header bg-primary-darker">
          <div class="content-header">
            <div class="w-100 text-center">
              <i class="fa fa-fw fa-2x fa-sun fa-spin text-white"></i>
            </div>
          </div>
        </div>
        <!-- END Header Loader -->
      </header>
      <!-- END Header -->