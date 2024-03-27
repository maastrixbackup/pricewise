 <!--sidebar wrapper -->
 <div class="sidebar-wrapper" data-simplebar="true">
     <div class="sidebar-header">
         <!-- <div>
                    <img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
                </div> -->
         <div>
             <h4 class="logo-text">Price Compare</h4>
         </div>
         <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
         </div>
     </div>
     <!--navigation-->
     <ul class="metismenu" id="menu">
         <li>
             <a href="{{route('admin.dashboard')}}">
                 <div class="parent-icon"><i class='bx bx-home-circle'></i>
                 </div>
                 <div class="menu-title">Dashboard</div>
             </a>

         </li>

         <li>
             <a href="javascript:;" class="has-arrow">
                 <div class="parent-icon"><i class="lni lni-users"></i>
                 </div>
                 <div class="menu-title">Customers</div>
             </a>
             <ul>
                 <li> <a href="{{route('admin.customers.index')}}"><i class="bx bx-right-arrow-alt"></i>Customers</a>
                 </li>
                 {{-- <li> <a href="{{route('admin.approve-customers')}}"><i class="bx bx-right-arrow-alt"></i>Approved Customers</a>
                 </li>
                 <li> <a href="{{route('admin.reject-customers')}}"><i class="bx bx-right-arrow-alt"></i>Rejected Customers</a>
                 </li> --}}
             </ul>
         </li>
         @if(Auth::guard('admin')->user()->can('providers-list'))
          <li>
             <a href="{{route('admin.providers.index')}}">
                 <div class="parent-icon"><i class='bx bx-face'></i>
                 </div>
                 <div class="menu-title">Providers</div>
             </a>

         </li>
         @endif
         @if(Auth::guard('admin')->user()->can('category-list'))
         <li>
             <a href="{{route('admin.categories.index')}}">
                 <div class="parent-icon"><i class='bx bx-face'></i>
                 </div>
                 <div class="menu-title">Categories</div>
             </a>

         </li>
         @endif
         @if(Auth::guard('admin')->user()->can('feature-list'))
         <li>
             <a href="{{route('admin.features.index')}}">
                 <div class="parent-icon"><i class='bx bx-face'></i>
                 </div>
                 <div class="menu-title">Features</div>
             </a>

         </li>
         @endif
         @if(Auth::guard('admin')->user()->can('page-list'))
         <li>
             <a href="{{route('admin.pages.index')}}">
                 <div class="parent-icon"><i class='bx bx-face'></i>
                 </div>
                 <div class="menu-title">Pages</div>
             </a>

         </li>
         @endif
         @if(Auth::guard('admin')->user()->can('banner-list'))
         <li>
             <a href="{{route('admin.banners.index')}}">
                 <div class="parent-icon"><i class='bx bx-face'></i>
                 </div>
                 <div class="menu-title">Banners</div>
             </a>

         </li>
         @endif
         <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Products/Services</div>
            </a>
            <ul>
                @if(Auth::guard('admin')->user()->can('internet-tv-list'))
                <li> <a href="{{route('admin.internet-tv.index')}}"><i class="bx bx-right-arrow-alt"></i>Internet TV</a>
                </li>
                @endif                
            </ul>
        </li>
         <!-- <li class="menu-label">ADMINISTRATION</li> -->
         <li>
             <a href="javascript:;" class="has-arrow">
                 <div class="parent-icon"><i class="bx bx-category"></i>
                 </div>
                 <div class="menu-title">Access Management</div>
             </a>
             <ul>
                @if(Auth::guard('admin')->user()->can('user-list'))
                 <li> <a href="{{route('admin.users.index')}}"><i class="bx bx-right-arrow-alt"></i>Users</a>
                 </li>
                 @endif
                 @if(Auth::guard('admin')->user()->can('role-list'))
                 <li> <a href="{{route('admin.roles.index')}}"><i class="bx bx-right-arrow-alt"></i>Roles</a>
                 </li>
                 @endif
                 @if(Auth::guard('admin')->user()->can('permission-list'))
                 <li> <a href="{{route('admin.permissions.index')}}"><i class="bx bx-right-arrow-alt"></i>Permissions</a>
                 </li>
                 @endif
             </ul>
         </li>
         <!-- <li>
             <a href="javascript:;" class="has-arrow">
                 <div class="parent-icon"><i class="bx bx-mail-send"></i>
                 </div>
                 <div class="menu-title">MailChimp</div>
             </a>
             <ul>
                 <li>
                     <a href="{{route('admin.create-campaign')}}"><i class="bx bx-right-arrow-alt"></i>MailChimp</a>
                     <a href="{{route('admin.subscribers-list')}}"><i class="bx bx-right-arrow-alt"></i>Subscribers List</a>
                     <a href="{{route('admin.contacts-list')}}"><i class="bx bx-right-arrow-alt"></i>Contacts List</a>
                 </li>
             </ul>
         </li> -->
         <li>
             <a href="javascript:;" class="has-arrow">
                 <div class="parent-icon"><i class="bx bx-cog"></i>
                 </div>
                 <div class="menu-title">Setting</div>
             </a>
             <ul>
                 <li>
                     <a href="{{route('admin.website-setting')}}"><i class="bx bx-right-arrow-alt"></i>Website Setting</a>
                     <a href="{{route('admin.email-templates.index')}}"><i class="bx bx-right-arrow-alt"></i>Email Templates</a>
                     <a href="{{route('admin.smtp-setting')}}"><i class="bx bx-right-arrow-alt"></i>SMTP Setting</a>
                     <a href="{{route('admin.payment-setting')}}"><i class="bx bx-right-arrow-alt"></i>Payment Setting</a>
                 </li>
             </ul>
         </li>
     </ul>
     <!--end navigation-->
 </div>
 <!--end sidebar wrapper -->