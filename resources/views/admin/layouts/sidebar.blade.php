 <nav id="sidebar" aria-label="Main Navigation">
        <!-- Side Header (mini Sidebar mode) -->
        <div class="smini-visible-block">
          <div class="content-header">
            <!-- Logo -->
            <a class="fw-semibold text-white tracking-wide" href="index.html">
              P<span class="opacity-75">W</span>
            </a>
            <!-- END Logo -->
          </div>
        </div>
        <!-- END Side Header (mini Sidebar mode) -->

        <!-- Side Header (normal Sidebar mode) -->
        <div class="smini-hidden">
          <div class="content-header toplogo justify-content-lg-center">
            <!-- Logo -->
            <a class="fw-semibold text-white tracking-wide" href="index.html">
              Energise<span class="opacity-75"></span>
            </a>
            <!-- END Logo -->

            <!-- Options -->
            <div class="d-lg-none">
              <!-- Close Sidebar, Visible only on mobile screens -->
              <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
              <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout" data-action="sidebar_close">
                <i class="fa fa-times-circle"></i>
              </button>
              <!-- END Close Sidebar -->
            </div>
            <!-- END Options -->
          </div>
        </div>
        <!-- END Side Header (normal Sidebar mode) -->

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll">
          <!-- Side Actions -->

          <!-- END Side Actions -->

          <!-- Side Navigation -->
          <div class="content-side">
            <ul class="nav-main">
              <li class="nav-main-item">
                <a class="nav-main-link active" href="{{asset('admin/dashboard')}}">
                  <i class="nav-main-link-icon fa fa-chart-bar"></i>
                  <span class="nav-main-link-name">Dashboard</span>
                </a>
              </li>
              <li class="nav-main-heading">Manage</li>

              <li class="nav-main-item">
                <a class="nav-main-link" href="#">
                <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                <span class="nav-main-link-name">Requests</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                  <i class="nav-main-link-icon fa fa-briefcase"></i>
                  <span class="nav-main-link-name">Products/Services</span>
                </a>
                <ul class="nav-main-submenu">
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.categories.index')}}">
                      <i class="nav-main-link-icon fa-solid fa-list"></i>
                      <span class="nav-main-link-name">Categories</span>
                    </a>                
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="#">
                      <i class="nav-main-link-icon fa-solid fa-bolt-lightning"></i>
                      <span class="nav-main-link-name">Electra</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="#">
                      <i class="nav-main-link-icon fa-brands fa-gripfire"></i>
                      <span class="nav-main-link-name">Gas</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.internet-tv.index')}}">
                      <i class="nav-main-link-icon fa-solid fa-globe"></i>
                      <span class="nav-main-link-name">Internet, Tv & Telephone</span>
                    </a>
                  </li>
                   
                   
                    <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.insurance.index')}}">
                      <i class="nav-main-link-icon fa-solid fa-user-shield"></i>
                      <span class="nav-main-link-name">Insurances</span>
                    </a>
                  </li>
                    <li class="nav-main-item">
                    <a class="nav-main-link" href="service.html">
                      <i class="nav-main-link-icon fa-solid fa-mobile-screen"></i>
                      <span class="nav-main-link-name">Smartphones</span>
                    </a>
                  </li>
                   <li class="nav-main-item">
                    <a class="nav-main-link" href="#">
                      <i class="nav-main-link-icon fa-solid fa-hands-holding-circle"></i>
                      <span class="nav-main-link-name">Exclusive Deal</span>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="{{route('admin.features.index')}}">
                <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                <span class="nav-main-link-name">Features</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="{{route('admin.energy-rate-chat.index')}}">
                <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                <span class="nav-main-link-name">Energy Rate Chat</span>
                </a>
              </li>
               <li class="nav-main-item">
                <a class="nav-main-link" href="{{route('admin.feed-in-costs.index')}}">
                <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                <span class="nav-main-link-name">Feed In Costs</span>
                </a>
              </li>
              <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.reimbursement.index')}}">
                      <i class="nav-main-link-icon fa-solid fa-list"></i>
                      <span class="nav-main-link-name">Reimbursement</span>
                    </a>                
                  </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="{{route('admin.providers.index')}}">
                <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                <span class="nav-main-link-name">Providers</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="{{route('admin.customers.index')}}">
                <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                <span class="nav-main-link-name">Customers</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                  <i class="nav-main-link-icon fa-solid fa-sliders"></i>
                  <span class="nav-main-link-name">Sale/Promotion</span>
                </a>
              <ul class="nav-main-submenu">
              <li class="nav-main-item">
                <a class="nav-main-link" href="#">
                <i class="nav-main-link-icon fa-solid fa-hand-holding-heart"></i>
                <span class="nav-main-link-name">Offers</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="#">
                <i class="nav-main-link-icon fa-solid fa-percent"></i>
                <span class="nav-main-link-name">Discount</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="#">
                <i class="nav-main-link-icon fa-regular fa-clone"></i>
                <span class="nav-main-link-name">Combos</span>
                </a>
              </li>
               <li class="nav-main-item">
                <a class="nav-main-link" href="#">
                <i class="nav-main-link-icon fa-solid fa-circle-plus"></i>
                <span class="nav-main-link-name">Extras</span>
                </a>
              </li>
              </ul>
            </li>
              
             
              <li class="nav-main-item">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                  <i class="nav-main-link-icon fa fa-briefcase"></i>
                  <span class="nav-main-link-name">CMS Pages</span>
                </a>
                <ul class="nav-main-submenu">
                  <li class="nav-main-item">
                      <a class="nav-main-link" href="{{route('admin.pages.index')}}">
                        <i class="nav-main-link-icon fa-regular fa-circle"></i>
                      <span class="nav-main-link-name">Pages</span>
                    </a>                    
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.banners.index')}}">
                      <i class="nav-main-link-icon fa-regular fa-circle"></i>
                      <span class="nav-main-link-name">Banners/Sliders</span>
                    </a>
                    </li>
                    <li class="nav-main-item">
                    <a class="nav-main-link" href="#">
                      <i class="nav-main-link-icon fa-regular fa-circle"></i>
                      <span class="nav-main-link-name">Reviews</span>
                    </a>
                  </li>
                    <li class="nav-main-item">
                    <a class="nav-main-link" href="">
                      <i class="nav-main-link-icon fa-regular fa-circle"></i>
                      <span class="nav-main-link-name">FAQ</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="">
                      <i class="nav-main-link-icon fa-regular fa-circle"></i>
                      <span class="nav-main-link-name">Terms & Agreements</span>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                  <i class="nav-main-link-icon fa-solid fa-ticket"></i>
                  <span class="nav-main-link-name">Tickets</span>
                </a>
                <ul class="nav-main-submenu">
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="">
                      <i class="nav-main-link-icon fa-solid fa-up-right-from-square"></i>
                      <span class="nav-main-link-name">Open Tickets</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="">
                      <i class="nav-main-link-icon fa fa-pencil-alt"></i>
                      <span class="nav-main-link-name">Manage</span>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-main-item">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                  <i class="nav-main-link-icon fa-solid fa-sliders"></i>
                  <span class="nav-main-link-name">Setting</span>
                </a>
                <ul class="nav-main-submenu">
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.business-setting')}}">
                      <i class="nav-main-link-icon fa-regular fa-circle"></i>
                      <span class="nav-main-link-name">Business Setting</span>
                    </a>
                  </li>
                 <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.website-setting')}}">
                      <i class="nav-main-link-icon fa-regular fa-circle"></i>
                      <span class="nav-main-link-name">Website Setting</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.smtp-setting')}}">
                      <i class="nav-main-link-icon fa-regular fa-circle"></i>
                      <span class="nav-main-link-name">SMTP Setting</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.payment-setting')}}">
                      <i class="nav-main-link-icon fa-regular fa-circle"></i>
                      <span class="nav-main-link-name">Payment Setting</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.email-templates.index')}}">
                      <i class="nav-main-link-icon fa-regular fa-circle"></i>
                      <span class="nav-main-link-name">Email Template</span>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                  <i class="nav-main-link-icon fa fa-user"></i>
                  
                  <span class="nav-main-link-name">Access Management</span>
                </a>
                <ul class="nav-main-submenu">
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.users.index')}}">
                      <i class="nav-main-link-icon fa-solid fa-list"></i>
                      <span class="nav-main-link-name">Users</span>
                    </a>                
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.roles.index')}}">
                      <i class="nav-main-link-icon fa-solid fa-list"></i>
                      <span class="nav-main-link-name">Roles</span>
                    </a>                
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('admin.permissions.index')}}">
                      <i class="nav-main-link-icon fa-solid fa-list"></i>
                      <span class="nav-main-link-name">Permissions</span>
                    </a>                
                  </li>
                </ul>
              </li>
            </ul>
          </div>
          <!-- END Side Navigation -->
        </div>
        <!-- END Sidebar Scrolling -->
      </nav>
      <!-- END Sidebar -->