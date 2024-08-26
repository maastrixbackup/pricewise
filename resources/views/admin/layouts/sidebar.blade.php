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
             <a class="fw-semibold text-white tracking-wide" href="{{ url('/') }}">
                 <img src="{{ asset('storage/images/website/') . '/' . siteSettings()->logo }}" alt="Energiser"><span
                     class="opacity-75"></span>
             </a>
             <!-- END Logo -->

             <!-- Options -->
             <div class="d-lg-none">
                 <!-- Close Sidebar, Visible only on mobile screens -->
                 <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                 <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout"
                     data-action="sidebar_close">
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
                     <a class="nav-main-link active" href="{{ asset('pricewise/admin/dashboard') }}">
                         <i class="nav-main-link-icon fa fa-chart-bar"></i>
                         <span class="nav-main-link-name">Dashboard</span>
                     </a>
                 </li>

                 <li class="nav-main-item">
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                         aria-expanded="false" href="#">
                         <i class="nav-main-link-icon fa fa-briefcase"></i>
                         <span class="nav-main-link-name">Products/Services</span>
                     </a>
                     <ul class="nav-main-submenu">
                         <li class="nav-main-item">
                             <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                                 aria-expanded="false" href="#">
                                 <i class="nav-main-link-icon fa fa-briefcase"></i>
                                 <span class="nav-main-link-name">Energy</span>
                             </a>
                             <ul class="nav-main-submenu">
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.energy.index') }}">
                                         <i class="nav-main-link-icon fa-solid fa-bolt-lightning"></i>
                                         <span class="nav-main-link-name">Energy Products</span>
                                     </a>
                                 </li>
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.energy-rate-chat.index') }}">
                                         <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                         <span class="nav-main-link-name">Energy Rate Chat</span>
                                     </a>
                                 </li>
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.feed-in-costs.index') }}">
                                         <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                         <span class="nav-main-link-name">Feed In Costs</span>
                                     </a>
                                 </li>
                             </ul>
                         </li>

                         <li class="nav-main-item">
                             <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                                 aria-expanded="false" href="#">
                                 <i class="nav-main-link-icon fa fa-briefcase"></i>
                                 <span class="nav-main-link-name">Internet, Tv & Telephone</span>
                             </a>
                             <ul class="nav-main-submenu">
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.internet-tv.index') }}">
                                         <i class="nav-main-link-icon fa-solid fa-bolt-lightning"></i>
                                         <span class="nav-main-link-name">Internet & Tv Products</span>
                                     </a>
                                 </li>
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.tv-channel.index') }}">
                                         <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                         <span class="nav-main-link-name">Tv Channels</span>
                                     </a>
                                 </li>
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.tv-packages.index') }}">
                                         <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                         <span class="nav-main-link-name">Tv Packages</span>
                                     </a>
                                 </li>
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.tv-options.index') }}">
                                         <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                         <span class="nav-main-link-name">Tv Options</span>
                                     </a>
                                 </li>
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.tv-options.index') }}">
                                         <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                         <span class="nav-main-link-name">Phone Options</span>
                                     </a>
                                 </li>
                             </ul>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                                 aria-expanded="false" href="#">
                                 <i class="nav-main-link-icon fa fa-briefcase"></i>
                                 <span class="nav-main-link-name">Insurance</span>
                             </a>
                             <ul class="nav-main-submenu">
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.insurance.index') }}">
                                         <i class="nav-main-link-icon fa-solid fa-user-shield"></i>
                                         <span class="nav-main-link-name">Insurance Products</span>
                                     </a>
                                 </li>
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.insurance-coverages.index') }}">
                                         <i class="nav-main-link-icon fa-solid fa-user-shield"></i>
                                         <span class="nav-main-link-name">Insurance Coverages</span>
                                     </a>
                                 </li>
                             </ul>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu"
                                 aria-haspopup="true" aria-expanded="false" href="#">
                                 <i class="nav-main-link-icon fa-solid fa-mobile-screen"></i>
                                 <span class="nav-main-link-name">Smartphones</span>
                             </a>
                             <ul class="nav-main-submenu">
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.smartphone.index') }}">
                                         <i class="nav-main-link-icon fa-solid fa-users"></i>
                                         <span class="nav-main-link-name">SmartPhone Providers</span>
                                     </a>
                                 </li>
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.provider-discount.index') }}">
                                         <i class="nav-main-link-icon fa-solid fa-percent"></i>
                                         <span class="nav-main-link-name">Provider Discounts</span>
                                     </a>
                                 </li>
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.provider-feature.index') }}">
                                         <i class="nav-main-link-icon fa-solid fa-file-text"></i>
                                         <span class="nav-main-link-name">Provider Features</span>
                                     </a>
                                 </li>
                                 <li class="nav-main-item">
                                     <a class="nav-main-link" href="{{ route('admin.smartphone-faq.index') }}">
                                         <i class="nav-main-link-icon fa-solid fa-comments "></i>
                                         <span class="nav-main-link-name">FAQ</span>
                                     </a>
                                 </li>
                             </ul>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.exclusive-deals.index') }}">
                                 <i class="nav-main-link-icon fa-solid fa-hands-holding-circle"></i>
                                 <span class="nav-main-link-name">Exclusive Deals</span>
                             </a>
                         </li>
                     </ul>
                 </li>

                 <li class="nav-main-item">
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                         aria-expanded="false" href="#">
                         <i class="nav-main-link-icon fa fa-cart-plus"></i>
                         <span class="nav-main-link-name">Shop</span>
                     </a>
                     <ul class="nav-main-submenu">
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.product-category.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                 <span class="nav-main-link-name">Product Category</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.product-promotion.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa-percent"></i>
                                 <span class="nav-main-link-name">Promotion Product</span>
                             </a>
                         </li>

                         <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('admin.deals-product.index') }}">
                                <i class="nav-main-link-icon fa-solid fa-list"></i>
                                <span class="nav-main-link-name">Deals Product</span>
                            </a>
                        </li>

                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.product-brands.index') }}">
                                 <i class="nav-main-link-icon fa-solid fa-list"></i>
                                 <span class="nav-main-link-name">Brands</span>
                             </a>
                         </li>

                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.product-color.index') }}">
                                 <i class="nav-main-link-icon fa-solid fa-paint-brush"></i>
                                 <span class="nav-main-link-name"> Colors</span>
                             </a>
                         </li>

                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.products.index') }}">
                                 <i class="nav-main-link-icon fa-solid fa-list-alt"></i>
                                 <span class="nav-main-link-name">Shop Product</span>
                             </a>
                         </li>

                         <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('admin.ratings') }}">
                             <i class="nav-main-link-icon fa-solid fa-star-o" aria-hidden="true"></i>
                                <span class="nav-main-link-name">Ratings</span>
                            </a>
                        </li>

                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.shop-settings') }}">
                                 <i class="nav-main-link-icon fa-solid fa-cogs"></i>
                                 <span class="nav-main-link-name">Shop Settings</span>
                             </a>
                         </li>
                     </ul>
                 </li>

                 <li class="nav-main-item">
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                         aria-expanded="false" href="#">
                         <i class="nav-main-link-icon fa fa-money "></i>
                         <span class="nav-main-link-name">Loans</span>
                     </a>
                     <ul class="nav-main-submenu">
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.banks.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa fa-university"></i>
                                 <span class="nav-main-link-name">Loan Provider</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.purposes.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa fa-paper-plane-o"></i>
                                 <span class="nav-main-link-name">Spending Purpose</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.loan-type.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa fa-money"></i>
                                 <span class="nav-main-link-name">Loan Type</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.loans.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa fa-product-hunt"></i>
                                 <span class="nav-main-link-name">Loan Products</span>
                             </a>
                         </li>
                     </ul>
                 </li>

                 <li class="nav-main-item">
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                         aria-expanded="false" href="#">
                         <i class="nav-main-link-icon fa fa-calendar"></i>
                         <span class="nav-main-link-name">Event Management</span>
                     </a>
                     <ul class="nav-main-submenu">
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.room_type.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                 <span class="nav-main-link-name">Room Type</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.list.caterer') }}">
                                 <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                 <span class="nav-main-link-name">Caterer </span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.event_theme.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                 <span class="nav-main-link-name">Theme Type</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.events_type.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                 <span class="nav-main-link-name">Event Type</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.events.index') }}">
                                 <i class="nav-main-link-icon fa-solid fa-list"></i>
                                 <span class="nav-main-link-name">Events</span>
                             </a>
                         </li>
                     </ul>
                 </li>

                 <li class="nav-main-item">
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                         aria-expanded="false" href="#">
                         <i class="nav-main-link-icon fa fa-shield"></i>
                         <span class="nav-main-link-name">Cyber Security</span>
                     </a>
                     <ul class="nav-main-submenu">
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.security-provider.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa fa-user-secret"></i>
                                 <span class="nav-main-link-name">Provider</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.security-feature.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa fa-list-alt"></i>
                                 <span class="nav-main-link-name">Features</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.cyber-security.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa fa-lock"></i>
                                 <span class="nav-main-link-name">Cyber Security Products</span>
                             </a>
                         </li>
                     </ul>
                 </li>

                 <li class="nav-main-item">
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                         aria-expanded="false" href="#">
                         <i class="nav-main-link-icon fa fa-shield"></i>
                         <span class="nav-main-link-name">Vacancy</span>
                     </a>
                     <ul class="nav-main-submenu">
                         <li class="nav-main-item">
                            <a class="nav-main-link" href="#">
                                 <i class="nav-main-link-icon fa-regular fa fa-user-secret"></i>
                                 <span class="nav-main-link-name">List All</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('admin.post-new-vacancy') }}">
                                <i class="nav-main-link-icon fa-regular fa fa-list-alt"></i>
                                <span class="nav-main-link-name">Post New</span>
                            </a>
                        </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="#">
                                 <i class="nav-main-link-icon fa-regular fa fa-list-alt"></i>
                                 <span class="nav-main-link-name">Location</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('admin.job_type') }}">
                                 <i class="nav-main-link-icon fa-regular fa fa-lock"></i>
                                 <span class="nav-main-link-name">Type</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('admin.job_industry') }}">
                                 <i class="nav-main-link-icon fa-regular fa fa-lock"></i>
                                 <span class="nav-main-link-name">Industry</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('admin.job_role') }}">
                                <i class="nav-main-link-icon fa-regular fa fa-lock"></i>
                                <span class="nav-main-link-name">Role</span>
                            </a>
                        </li>
                         <li class="nav-main-item">
                            <a class="nav-main-link" href="#">
                                <i class="nav-main-link-icon fa-regular fa fa-lock"></i>
                                <span class="nav-main-link-name">Exp Level</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="#">
                                <i class="nav-main-link-icon fa-regular fa fa-lock"></i>
                                <span class="nav-main-link-name">Educational Qualification</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="#">
                                <i class="nav-main-link-icon fa-regular fa fa-lock"></i>
                                <span class="nav-main-link-name">Pay per Hour</span>
                            </a>
                        </li>
                     </ul>
                 </li>


                 <li class="nav-main-item">
                     <a class="nav-main-link" href="{{ route('admin.requests.index') }}">
                         <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                         <span class="nav-main-link-name">Leads</span>
                     </a>
                 </li>

                 <li class="nav-main-item">
                     <a class="nav-main-link" href="{{ route('admin.customers.index') }}">
                         <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                         <span class="nav-main-link-name">Users</span>
                     </a>
                 </li>

                 <li class="nav-main-item">
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                         aria-expanded="false" href="#">
                         <i class="nav-main-link-icon fa fa-briefcase"></i>
                         <span class="nav-main-link-name">Catalog</span>
                     </a>
                     <ul class="nav-main-submenu">
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.features.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                 <span class="nav-main-link-name">Features</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.providers.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                 <span class="nav-main-link-name">Companies</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.house-type.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                 <span class="nav-main-link-name">House Types</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="#">
                                 <i class="nav-main-link-icon fa-regular fa-file-lines"></i>
                                 <span class="nav-main-link-name">Options</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.categories.index') }}">
                                 <i class="nav-main-link-icon fa-solid fa-list"></i>
                                 <span class="nav-main-link-name">Categories</span>
                             </a>
                         </li>

                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.sub-categories.index') }}">
                                 <i class="nav-main-link-icon fa-solid fa-list"></i>
                                 <span class="nav-main-link-name">Sub Categories</span>
                             </a>
                         </li>
                     </ul>
                 </li>

                 <li class="nav-main-item">
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                         aria-expanded="false" href="#">
                         <i class="nav-main-link-icon fa fa-briefcase"></i>
                         <span class="nav-main-link-name">Marketings
                         </span>
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
                             <a class="nav-main-link" href="{{ route('admin.combos.index') }}">
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
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="#">
                                 <i class="nav-main-link-icon fa-solid fa-gift"></i>
                                 <span class="nav-main-link-name">Coupon</span>
                             </a>
                         </li>
                     </ul>
                 </li>

                 <li class="nav-main-item">
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                         aria-expanded="false" href="#">
                         <i class="nav-main-link-icon fa fa-briefcase"></i>
                         <span class="nav-main-link-name">CMS</span>
                     </a>
                     <ul class="nav-main-submenu">
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.pages.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa-circle"></i>
                                 <span class="nav-main-link-name">Pages</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.banners.index') }}">
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
                             <a class="nav-main-link" href="{{ route('admin.FAQ-list') }}">
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
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                         aria-expanded="false" href="#">
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
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                         aria-expanded="false" href="#">
                         <i class="nav-main-link-icon fa-solid fa-sliders"></i>
                         <span class="nav-main-link-name">Setting</span>
                     </a>
                     <ul class="nav-main-submenu">
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.business-setting') }}">
                                 <i class="nav-main-link-icon fa-regular fa-circle"></i>
                                 <span class="nav-main-link-name">Business Setting</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.website-setting') }}">
                                 <i class="nav-main-link-icon fa-regular fa-circle"></i>
                                 <span class="nav-main-link-name">Website Setting</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.smtp-setting') }}">
                                 <i class="nav-main-link-icon fa-regular fa-circle"></i>
                                 <span class="nav-main-link-name">SMTP Setting</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.payment-setting') }}">
                                 <i class="nav-main-link-icon fa-regular fa-circle"></i>
                                 <span class="nav-main-link-name">Payment Setting</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.email-templates.index') }}">
                                 <i class="nav-main-link-icon fa-regular fa-circle"></i>
                                 <span class="nav-main-link-name">Email Template</span>
                             </a>
                         </li>
                     </ul>
                 </li>

                 <li class="nav-main-item">
                     <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                         aria-expanded="false" href="#">
                         <i class="nav-main-link-icon fa fa-user"></i>

                         <span class="nav-main-link-name">Access Management</span>
                     </a>
                     <ul class="nav-main-submenu">
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.users.index') }}">
                                 <i class="nav-main-link-icon fa-solid fa-list"></i>
                                 <span class="nav-main-link-name">Users</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.roles.index') }}">
                                 <i class="nav-main-link-icon fa-solid fa-list"></i>
                                 <span class="nav-main-link-name">Roles</span>
                             </a>
                         </li>
                         <li class="nav-main-item">
                             <a class="nav-main-link" href="{{ route('admin.permissions.index') }}">
                                 <i class="nav-main-link-icon fa-solid fa-list"></i>
                                 <span class="nav-main-link-name">Permissions</span>
                             </a>
                         </li>
                     </ul>
                 </li>

                 {{-- <li class="nav-main-item">
                     <a class="nav-main-link" href="{{ route('admin.reimbursement.index') }}">
                         <i class="nav-main-link-icon fa-solid fa-list"></i>
                         <span class="nav-main-link-name">Reimbursement</span>
                     </a>
                 </li> --}}
             </ul>
         </div>
         <!-- END Side Navigation -->
     </div>
     <!-- END Sidebar Scrolling -->
 </nav>
 <!-- END Sidebar -->
