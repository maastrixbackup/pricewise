 <!--sidebar wrapper -->
 <div class="sidebar-wrapper" data-simplebar="true">
     <div class="sidebar-header">
         <div>
             <img src="{{ asset('assets/images/Logo.png') }}" class="logo-icon" alt="logo icon">
         </div>

         <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
         </div>
     </div>
     <!--navigation-->
     <ul class="metismenu" id="menu">
         <li>
             <a href="{{ route('admin.dashboard') }}">
                 <div class="parent-icon"><i class='bx bx-home-circle'></i>
                 </div>
                 <div class="menu-title">Dashboard</div>
             </a>
         </li>
         <li>
             <a href="{{ route('admin.sort') }}">
                 <div class="parent-icon"><i class='bx bx-sort'></i>
                 </div>
                 <div class="menu-title">Sorting</div>
             </a>
         </li>
         @if (Auth::guard('admin')->user()->can('orders'))
         <li>
             <a href="javascript:;" class="has-arrow">
                 <div class="parent-icon"><i class="lni lni-cart-full"></i>
                 </div>
                 <div class="menu-title">Orders</div>
             </a>
             <ul>
                 <li> <a href="{{ route('admin.order') }}"><i class="bx bx-right-arrow-alt"></i>Orders</a>
                 </li>
                 <li> <a href="{{ route('admin.order.abandoned') }}"><i class="bx bx-right-arrow-alt"></i>Abandoned
                         Orders</a>
                 </li>
                 <li> <a href="{{ route('admin.order.locked') }}"><i class="bx bx-right-arrow-alt"></i>Locked
                         Orders</a>
                 <li> <a href="{{ route('admin.order.pending-direct-debit') }}"><i class="bx bx-right-arrow-alt"></i>Pending Direct Debit</a>
                 </li>
             </ul>
         </li>
         @endif




         @if (Auth::guard('admin')->user()->can('products'))
         <li>
             <a href="javascript:;" class="has-arrow">
                 <div class="parent-icon"><i class="bx bx-menu"></i>
                 </div>
                 <div class="menu-title">Products</div>
             </a>
             <ul>
                 <li>
                     <a href="javascript:;" class="has-arrow">
                         <div class="parent-icon"><i class="lni lni-world"></i>
                         </div>
                         <div class="menu-title">Broadband</div>
                     </a>
                     <ul>
                         <li><a href="{{ route('admin.services.index') }}"><i class="bx bx-right-arrow-alt"></i>Broadband</a></li>
                         <li><a href="{{ route('admin.features.index') }}"><i class="bx bx-right-arrow-alt"></i>Features</a></li>
                         <li><a href="{{ route('admin.categories.index') }}"><i class="bx bx-right-arrow-alt"></i>Categories</a></li>
                         <li><a href="{{ route('admin.packages.index') }}"><i class="bx bx-right-arrow-alt"></i>Speed</a></li>
                         <li><a href="{{ route('admin.productpricetypes.index') }}"><i class="bx bx-right-arrow-alt"></i>Product Price Types</a></li>
                         <li> <a href="{{ route('admin.broadband-offers.index') }}"><i class="bx bx-right-arrow-alt"></i>Special Offers</a>
                         </li>
                         <li> <a href="{{ route('admin.includes.index') }}"><i class="bx bx-right-arrow-alt"></i>Includes</a>
                         </li>
                         <li> <a href="{{ route('admin.broadband-datas.index') }}"><i class="bx bx-right-arrow-alt"></i>Data</a>
                         </li>
                         <li> <a href="{{ route('admin.broadband-minutes.index') }}"><i class="bx bx-right-arrow-alt"></i>Minutes</a>
                         </li>
                         <li> <a href="{{ route('admin.broadband-contract-lengths.index') }}"><i class="bx bx-right-arrow-alt"></i>Contract Lengths</a>
                         </li>

                     </ul>
                 </li>

                 <li>
                     <a href="javascript:;" class="has-arrow">
                         <div class="parent-icon"><i class="bx bx-phone"></i>
                         </div>
                         <div class="menu-title">Landlines</div>
                     </a>
                     <ul>
                         <li> <a href="{{ route('admin.landline-products.index') }}"><i class="bx bx-right-arrow-alt"></i>Landlines</a>
                         </li>
                         <li> <a href="{{ route('admin.landline-features.index') }}"><i class="bx bx-right-arrow-alt"></i>Features</a>
                         </li>
                         <li> <a href="{{ route('admin.broadband-types.index') }}"><i class="bx bx-right-arrow-alt"></i>Broadband Type</a>
                         </li>
                         <li> <a href="{{ route('admin.landline-packages.index') }}"><i class="bx bx-right-arrow-alt"></i>All Packages</a>
                         </li>
                         <li> <a href="{{ route('admin.landline-offers.index') }}"><i class="bx bx-right-arrow-alt"></i>Special Offers</a>
                         </li>
                         <li> <a href="{{ route('admin.landline-contract-lengths.index') }}"><i class="bx bx-right-arrow-alt"></i>Contract Lengths</a>
                         </li>
                         <li> <a href="{{ route('admin.landline-category.index') }}"><i class="bx bx-right-arrow-alt"></i>Category</a>
                         </li>

                     </ul>
                 </li>
                 <li>
                     <a href="javascript:;" class="has-arrow">
                         <div class="parent-icon"><i class="bx bx-mobile"></i>
                         </div>
                         <div class="menu-title">Mobiles</div>
                     </a>
                     <ul>
                         <li> <a href="{{ route('admin.mobiles.index') }}"><i class="bx bx-right-arrow-alt"></i>Mobiles</a>
                         </li>
                         <li> <a href="{{ route('admin.mobile-features.index') }}"><i class="bx bx-right-arrow-alt"></i>Features</a>
                         </li>
                         <li> <a href="{{ route('admin.mobile-offers.index') }}"><i class="bx bx-right-arrow-alt"></i>Special Offers</a>
                         </li>
                         <li> <a href="{{ route('admin.service-providers.index') }}"><i class="bx bx-right-arrow-alt"></i>Service Provider</a>
                         </li>
                         <li> <a href="{{ route('admin.subscription-months.index') }}"><i class="bx bx-right-arrow-alt"></i>Subscription Month</a>
                         </li>

                         <li> <a href="{{ route('admin.mobile-packages.index') }}"><i class="bx bx-right-arrow-alt"></i>All Packages</a>
                         </li>
                         <li> <a href="{{ route('admin.mobile-datas.index') }}"><i class="bx bx-right-arrow-alt"></i>Data</a>
                         </li>
                         <li> <a href="{{ route('admin.mobile-minutes.index') }}"><i class="bx bx-right-arrow-alt"></i>Minutes</a>
                         </li>
                         <li> <a href="{{ route('admin.mobile-contract-lengths.index') }}"><i class="bx bx-right-arrow-alt"></i>Contract Lengths</a>
                         </li>
                         <li> <a href="{{ route('admin.mobile-category.index') }}"><i class="bx bx-right-arrow-alt"></i>Category</a>
                         </li>
                     </ul>
                 </li>

                 <li>
                     <a href="javascript:;" class="has-arrow">
                         <div class="parent-icon"><i class="bx bx-bar-chart"></i>
                         </div>
                         <div class="menu-title">Top Deal Products</div>
                     </a>
                     <ul>
                         <li> <a href="{{ route('admin.topdeal-products.index') }}"><i class="bx bx-right-arrow-alt"></i>Top Deals</a>
                         </li>
                         <li> <a href="{{ route('admin.topdeal-features.index') }}"><i class="bx bx-right-arrow-alt"></i>Features</a>
                         </li>
                         <li> <a href="{{ route('admin.topdeal-packages.index') }}"><i class="bx bx-right-arrow-alt"></i>All Packages</a>
                         </li>
                         <li> <a href="{{ route('admin.topdeal-offers.index') }}"><i class="bx bx-right-arrow-alt"></i>Special Offers</a>
                         </li>
                         <li> <a href="{{ route('admin.topdeal-contract-lengths.index') }}"><i class="bx bx-right-arrow-alt"></i>Contract Lengths</a>
                         </li>

                     </ul>
                 </li>

                 <li>
                     <a href="javascript:;" class="has-arrow">
                         <div class="parent-icon"><i class="bx bx-slideshow"></i>
                         </div>
                         <div class="menu-title">Tv Products</div>
                     </a>
                     <ul>
                         <li> <a href="{{ route('admin.tv-products.index') }}"><i class="bx bx-right-arrow-alt"></i>Tv</a>
                         </li>
                         <li> <a href="{{ route('admin.tv-contract-lengths.index') }}"><i class="bx bx-right-arrow-alt"></i>Contract Lengths</a></li>

                     </ul>
                 </li>
             </ul>
         </li>
         @endif
         {{--
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="lni lni-briefcase"></i>
             </div>
             <div class="menu-title">Business</div>
         </a>
         <ul>
             <li>
                 <a href="javascript:;" class="has-arrow">
                     <div class="parent-icon"><i class="bx bx-user-pin"></i>
                     </div>
                     <div class="menu-title">Business Products</div>
                 </a>
                 <ul>
                     <li> <a href="{{route('admin.business-products.index')}}"><i class="bx bx-right-arrow-alt"></i>Business</a>
         </li>
         <li> <a href="{{route('admin.business-features.index')}}"><i class="bx bx-right-arrow-alt"></i>Features</a>
         </li>
         <li> <a href="{{route('admin.business-contract-lengths.index')}}"><i class="bx bx-right-arrow-alt"></i>Contract Lengths</a>
         </li>
     </ul>
     </li>

     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-phone-call"></i>
             </div>
             <div class="menu-title">Business Landline</div>
         </a>
         <ul>
             <li> <a href="{{route('admin.business-landline-products.index')}}"><i class="bx bx-right-arrow-alt"></i>Business Landline</a>
             </li>
             <li> <a href="{{route('admin.business-landline-features.index')}}"><i class="bx bx-right-arrow-alt"></i>Features</a>
             </li>
             <li> <a href="{{route('admin.business-landline-packages.index')}}"><i class="bx bx-right-arrow-alt"></i>Package</a>
             </li>
             <li> <a href="{{route('admin.business-landline-offers.index')}}"><i class="bx bx-right-arrow-alt"></i>Offers</a>
             </li>
             <li> <a href="{{route('admin.bsness-landline-contract-lengths.index')}}"><i class="bx bx-right-arrow-alt"></i>Contract Lengths</a>
             </li>
         </ul>
     </li>

     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-unite"></i>
             </div>
             <div class="menu-title">Business Broadband</div>
         </a>
         <ul>
             <li><a href="{{route('admin.business-services.index')}}"><i class="bx bx-right-arrow-alt"></i>Broadband</a></li>
             <li><a href="{{route('admin.business-broad-features.index')}}"><i class="bx bx-right-arrow-alt"></i>Features</a></li>
             <li><a href="{{route('admin.business-categories.index')}}"><i class="bx bx-right-arrow-alt"></i>Categories</a></li>
             <li><a href="{{route('admin.business-packages.index')}}"><i class="bx bx-right-arrow-alt"></i>All Packages</a></li>

             <li><a href="{{route('admin.business-productpricetypes.index')}}"><i class="bx bx-right-arrow-alt"></i>Product Price Types</a></li>

     </li>
     </ul>

     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-mobile-alt"></i>
             </div>
             <div class="menu-title">Business Mobiles</div>
         </a>
         <ul>
             <li> <a href="{{route('admin.business-mobiles.index')}}"><i class="bx bx-right-arrow-alt"></i>Mobiles</a>
             </li>
             <li> <a href="{{route('admin.business-mobile-features.index')}}"><i class="bx bx-right-arrow-alt"></i>Features</a>
             </li>
             <li> <a href="{{route('admin.business-mobile-offers.index')}}"><i class="bx bx-right-arrow-alt"></i>Special Offers</a>
             </li>
             <li> <a href="{{route('admin.business-service-providers.index')}}"><i class="bx bx-right-arrow-alt"></i>Service Provider</a>
             </li>
             <li> <a href="{{route('admin.business-subscription-months.index')}}"><i class="bx bx-right-arrow-alt"></i>Subscription Month</a>
             </li>
             <li> <a href="{{route('admin.business-mobile-vats.index')}}"><i class="bx bx-right-arrow-alt"></i>VAT</a>
             </li>
             <li> <a href="{{route('admin.business-mobile-packages.index')}}"><i class="bx bx-right-arrow-alt"></i>All Packages</a>
             </li>
             <li> <a href="{{route('admin.business-mobile-datas.index')}}"><i class="bx bx-right-arrow-alt"></i>Data</a>
             </li>
             <li> <a href="{{route('admin.business-mobile-minutes.index')}}"><i class="bx bx-right-arrow-alt"></i>Minutes</a>
             </li>
             <li> <a href="{{route('admin.business-mobile-contract-lengths.index')}}"><i class="bx bx-right-arrow-alt"></i>Contract Lengths</a>
             </li>
         </ul>
     </li>

     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-bar-chart"></i>
             </div>
             <div class="menu-title">Business Top Deals</div>
         </a>
         <ul>
             <li> <a href="{{route('admin.business-topdeal-products.index')}}"><i class="bx bx-right-arrow-alt"></i>Top Deals</a>
             </li>
             <li> <a href="{{route('admin.business-topdeal-features.index')}}"><i class="bx bx-right-arrow-alt"></i>Features</a>
             </li>
             <li> <a href="{{route('admin.business-topdeal-packages.index')}}"><i class="bx bx-right-arrow-alt"></i>All Packages</a>
             </li>
             <li> <a href="{{route('admin.business-topdeal-offers.index')}}"><i class="bx bx-right-arrow-alt"></i>Special Offers</a>
             </li>
             <li> <a href="{{route('admin.business-topdeal-contract-lengths.index')}}"><i class="bx bx-right-arrow-alt"></i>Contract Lengths</a>
             </li>
         </ul>
     </li>

     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-diamond"></i>
             </div>
             <div class="menu-title">Business Shop</div>
         </a>
         <ul>
             <li> <a href="{{route('admin.business-shop-products.index')}}"><i class="bx bx-right-arrow-alt"></i>Shop Products</a></li>
             <li> <a href="{{route('admin.business-shop-categories.index')}}"><i class="bx bx-right-arrow-alt"></i>Shop Categories</a></li>
         </ul>
     </li>

     </ul>
     </li>
     --}}

     @if (Auth::guard('admin')->user()->can('shop-products'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-store"></i>
             </div>
             <div class="menu-title">Shop Products</div>
         </a>
         <ul>
             <li> <a href="{{ route('admin.products.index') }}"><i class="bx bx-right-arrow-alt"></i>Shop
                     Products</a></li>
             <li> <a href="{{ route('admin.shop-categories.index') }}"><i class="bx bx-right-arrow-alt"></i>Shop Categories</a></li>
             <li> <a href="{{ route('admin.shop-companies.index') }}"><i class="bx bx-right-arrow-alt"></i>Shop Companies</a></li>
             <li> <a href="{{ route('admin.wifiuse.index') }}"><i class="bx bx-right-arrow-alt"></i>Wifi
                     Use</a></li>
             <li> <a href="{{ route('admin.wifiband.index') }}"><i class="bx bx-right-arrow-alt"></i>Wifi
                     Band</a></li>
             <li> <a href="{{ route('admin.ethernet-speed.index') }}"><i class="bx bx-right-arrow-alt"></i>Ethernet Speed</a></li>
         </ul>
     </li>
     @endif

     @if (Auth::guard('admin')->user()->can('extras'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-repeat"></i>
             </div>
             <div class="menu-title">Extras</div>
         </a>
         <ul>
             <li><a href="{{ route('admin.additional-extras.index') }}"><i class="bx bx-right-arrow-alt"></i>Additional Extras</a></li>
             <li><a href="{{ route('admin.additional-extras-info.index') }}"><i class="bx bx-right-arrow-alt"></i>Additional Extras info</a></li>
         </ul>
     </li>
     @endif

     @if (Auth::guard('admin')->user()->can('discount'))
     <li>
         <a href="{{ route('admin.discounts.index') }}">
             <div class="parent-icon"><i class="lni lni-offer"></i>
             </div>
             <div class="menu-title">Discount</div>
         </a>
     </li>
     @endif

     @if (Auth::guard('admin')->user()->can('affiliate'))
     <li>
         <a href="{{ route('admin.affiliates.index') }}">
             <div class="parent-icon"><i class="lni lni-handshake"></i>
             </div>
             <div class="menu-title">Affiliate</div>
         </a>
     </li>
     @endif

     @if (Auth::guard('admin')->user()->can('cms-setting'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bxs-calendar-minus"></i>
             </div>
             <div class="menu-title">CMS Settings</div>
         </a>
         <ul>
             <li> <a href="{{ route('admin.cmspages.index') }}"><i class="bx bx-right-arrow-alt"></i>Pages</a>
             </li>
             <li> <a href="{{ route('admin.cmstemplates.index') }}"><i class="bx bx-right-arrow-alt"></i>Templates</a>
             </li>
         </ul>
     </li>
     @endif

     @if (Auth::guard('admin')->user()->can('customer'))
     <li>
         <a href="{{ route('admin.customers.index') }}">
             <div class="parent-icon"><i class="lni lni-users"></i>
             </div>
             <div class="menu-title">Customers</div>
         </a>
     </li>
     @endif
     @if (Auth::guard('admin')->user()->can('support'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="lni lni-ticket"></i>
             </div>
             <div class="menu-title">Support</div>
         </a>
         <ul>
             <li>
                 <a href="{{ route('admin.tickets.index') }}">
                     <i class="bx bx-right-arrow-alt"></i>Tickets
                 </a>
             </li>
             <li> <a href="{{ route('admin.ticketservicetype.index') }}"><i class="bx bx-right-arrow-alt"></i>Service Type</a>
             </li>
             <li> <a href="{{ route('admin.ticketpriority.index') }}"><i class="bx bx-right-arrow-alt"></i>Priority</a>
             </li>
             <li> <a href="{{ route('admin.ticketstatus.index') }}"><i class="bx bx-right-arrow-alt"></i>Status</a>
             </li>
         </ul>
     </li>
     @endif
     @if (Auth::guard('admin')->user()->can('task'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-task"></i>
             </div>
             <div class="menu-title">Task</div>
         </a>
         <ul>
             <li>
                 <a href="{{ route('admin.task.index') }}">
                     <i class="bx bx-right-arrow-alt"></i>Task
                 </a>
             </li>
             <li> <a href="{{ route('admin.taskservicetype.index') }}"><i class="bx bx-right-arrow-alt"></i>Service Type</a>
             </li>
             <li> <a href="{{ route('admin.taskpriority.index') }}"><i class="bx bx-right-arrow-alt"></i>Priority</a>
             </li>
     </li>
     <li> <a href="{{ route('admin.taskstatus.index') }}"><i class="bx bx-right-arrow-alt"></i>Status</a>
     </li>
     </ul>
     </li>
     @endif

     @if (Auth::guard('admin')->user()->can('help-support'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-support"></i>
             </div>
             <div class="menu-title">Help & Support</div>
         </a>
         <ul>
             <li> <a href="{{ route('admin.blog.index') }}"><i class="bx bx-right-arrow-alt"></i>Help &
                     Support</a>
             </li>
             <li> <a href="{{ route('admin.blog-categories.index') }}"><i class="bx bx-right-arrow-alt"></i>Category</a>
             </li>
         </ul>
     </li>
     @endif

     @if (Auth::guard('admin')->user()->can('faq'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-help-circle"></i>
             </div>
             <div class="menu-title">FAQs</div>
         </a>
         <ul>
             <li> <a href="{{ route('admin.faq.index') }}"><i class="bx bx-right-arrow-alt"></i>FAQs</a>
             </li>
             <li> <a href="{{ route('admin.faq-categories.index') }}"><i class="bx bx-right-arrow-alt"></i>Category</a>
             </li>
         </ul>
     </li>
     @endif
     @if (Auth::guard('admin')->user()->can('role-list'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-category"></i>
             </div>
             <div class="menu-title">Access Management</div>
         </a>
         <ul>
             <li> <a href="{{ route('admin.users.index') }}"><i class="bx bx-right-arrow-alt"></i>Users</a>
             </li>
             @if (Auth::guard('admin')->user()->can('role-list'))
             <li> <a href="{{ route('admin.roles.index') }}"><i class="bx bx-right-arrow-alt"></i>Roles</a>
             </li>
             @endif
             @if (Auth::guard('admin')->user()->can('permission-list'))
             <li> <a href="{{ route('admin.permissions.index') }}"><i class="bx bx-right-arrow-alt"></i>Permissions</a>
             </li>
             @endif
         </ul>
     </li>
     @endif
     @if (Auth::guard('admin')->user()->can('agent'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-user"></i>
             </div>
             <div class="menu-title">Agent</div>
         </a>
         <ul>
             <li> <a href="{{ route('admin.agents') }}"><i class="bx bx-right-arrow-alt"></i>Agent</a>
             </li>
         </ul>
     </li>
     @endif

     @if (Auth::guard('admin')->user()->can('order-email'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-envelope"></i>
             </div>
             <div class="menu-title">Order Emails</div>
         </a>
         <ul>
             <li> <a href="#"><i class="bx bx-right-arrow-alt"></i>Order Emails</a>
             </li>
         </ul>
     </li>
     @endif
     @if (Auth::guard('admin')->user()->can('referral'))
     <li>
         <a href="#">
             <div class="parent-icon"><i class="lni lni-paperclip"></i>
             </div>
             <div class="menu-title">Referrals</div>
         </a>
     </li>
     @endif

     @if (Auth::guard('admin')->user()->can('mailchimp'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-mail-send"></i>
             </div>
             <div class="menu-title">MailChimp</div>
         </a>
         <ul>
             <li>
                 <a href="{{ route('admin.create-campaign') }}"><i class="bx bx-right-arrow-alt"></i>MailChimp</a>
                 <a href="{{ route('admin.subscribers-list') }}"><i class="bx bx-right-arrow-alt"></i>Subscribers
                     List</a>
                 <a href="{{ route('admin.contacts-list') }}"><i class="bx bx-right-arrow-alt"></i>Contacts
                     List</a>
             </li>
         </ul>
     </li>
     @endif
     @if (Auth::guard('admin')->user()->can('report'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-file"></i>
             </div>
             <div class="menu-title">Report</div>
         </a>
         <ul>
             <li> <a href="{{ route('admin.order-report') }}"><i class="bx bx-right-arrow-alt"></i>Order
                     Report</a>
             </li>
         </ul>
     </li>
     @endif

     @if (Auth::guard('admin')->user()->can('setting'))
     <li>
         <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="bx bx-cog"></i>
             </div>
             <div class="menu-title">Setting</div>
         </a>
         <ul>
             <li>
                 <a href="{{ route('admin.vat') }}"><i class="bx bx-right-arrow-alt"></i>VAT Setting</a>
                 <!-- <a href="{{-- route('admin.referrals.index') --}}"><i class="bx bx-right-arrow-alt"></i>Referral Setting</a> -->
                 <a href="{{ route('admin.website-setting') }}"><i class="bx bx-right-arrow-alt"></i>Website
                     Setting</a>
                 <a href="{{ route('admin.mailchimp-setting') }}"><i class="bx bx-right-arrow-alt"></i>MailChimp
                     Setting</a>
                 <a href="{{ route('admin.newsletter-template') }}"><i class="bx bx-right-arrow-alt"></i>Newsletter Template</a>
                 <a href="{{ route('admin.sagepay-setting') }}"><i class="bx bx-right-arrow-alt"></i>Sagepay
                     Setting</a>
                 <a href="{{ route('admin.talktalk-setting') }}"><i class="bx bx-right-arrow-alt"></i>Talktalk
                     Setting</a>
                 <a href="{{ route('admin.akj-setting') }}"><i class="bx bx-right-arrow-alt"></i>AKJ Setting</a>

             </li>
         </ul>
     </li>
     @endif
     </ul>
     <!--end navigation-->
 </div>
 <!--end sidebar wrapper -->