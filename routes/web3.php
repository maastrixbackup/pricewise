<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\TalktalkController;
use Illuminate\Support\Facades\Artisan;
/*business-landline-products
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'poptelecom'], function () {
    // Route::get('/', function () {
    //     return view('welcome');
    // });
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');
    Route::get('update_user_id', 'CronController@update_user_id');
    Route::get('cron_get_state', 'Admin\OrderController@getCronState')->name('cron_get_state');
    Route::get('cron_get_nga_state', 'Admin\OrderController@getCronNGAState')->name('cron_get_nga_state');
    require __DIR__ . '/auth.php';
    Route::get('/migrate', function () {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:cache');
        // return what you want
    });

    Route::get('check-abandon-status', 'CronController@check_abandon_status')->name('check_abandon_status');

    // Admin
    Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
        Route::namespace('Auth')->middleware('guest:admin')->group(function () {
            // login route
            Route::get('login', 'AuthenticatedSessionController@create')->name('login');
            Route::post('login', 'AuthenticatedSessionController@store')->name('adminlogin');
            Route::post('generateOtp', 'AuthenticatedSessionController@generateOtp')->name('generateOtp');

            Route::get('forgot-password', 'PasswordResetLinkController@create')->name('password.request');
            Route::post('forgot-password', 'PasswordResetLinkController@store')->name('password.email');
            Route::get('reset-password/{token}', 'NewPasswordController@create')->name('password.reset');
            Route::post('reset-password', 'NewPasswordController@store')->name('password.update');
        });
        Route::middleware('admin')->group(function () {
            Route::get('dashboard', 'HomeController@index')->name('dashboard');
            Route::get('/get-list', 'HomeController@getOrders')->name('get.list');
            Route::get('admin-test', 'HomeController@adminTest')->name('admintest');
            Route::get('editor-test', 'HomeController@editorTest')->name('editortest');
            Route::resource('posts', 'PostController');
            Route::resource('users', 'UserController');
            Route::get('/users/view/{id}', 'UserController@view')->name('users.view');
            Route::resource('services', 'ServiceController');
            Route::post('/service-update/{id}', 'ServiceController@service_update')->name('service-update');
            Route::get('/default/{id}', 'ServiceController@default')->name('default');
            Route::post('/default-update', 'ServiceController@default_update')->name('default-update');
            Route::post('/default-remove', 'ServiceController@default_remove')->name('default-remove');
            Route::post('/mandatory-remove', 'ServiceController@mandatory_remove')->name('mandatory-remove');
            Route::resource('products', 'ProductController');
            Route::get('duplicate/{id}', 'ServiceController@duplicate')->name('duplicate');
            Route::get('/fetch/shop_products', 'ProductController@getproducts')->name('get.shop_products');
            Route::get('/fetch/products', 'ServiceController@getproducts')->name('get.products');
            Route::post('/product_update/{id}', 'ProductController@product_update')->name('product_update');
            Route::resource('shop-categories', 'ShopCatController');
            Route::post('/shop_cat_update/{id}', 'ShopCatController@shop_cat_update')->name('shop_cat_update');
            Route::resource('shop-companies', 'ShopCompanyController');
            Route::resource('wifiuse', 'WifiUseController');
            Route::resource('wifiband', 'WifiBandController');
            Route::resource('ethernet-speed', 'EthernetSpeedController');

            Route::resource('categories', 'CategoryController');
            Route::resource('productpricetypes', 'ProductPriceTypeController');
            Route::resource('options', 'OptionController');
            Route::resource('options-attributes', 'OptionAttributeController');
            Route::resource('features', 'FeatureController');
            Route::resource('packages', 'PackageController');
            Route::resource('includes', 'IncludeController');

            //Broadband
            Route::resource('broadband-offers', 'BroadbandOfferController');
            Route::resource('broadband-datas', 'BroadbandDataController');
            Route::resource('broadband-minutes', 'BroadbandMinuteController');
            Route::resource('broadband-contract-lengths', 'BroadbandContractLengthController');

            //Mobile Product
            Route::get('/fetch/mobile-products', 'MobileController@getmobileproducts')->name('get.mobile_products');
            Route::resource('mobiles', 'MobileController');
            Route::post('/mobile-product-update/{id}', 'MobileController@mobile_update')->name('mobile-product-update');
            Route::get('duplicate-mobile/{id}', 'MobileController@duplicate')->name('duplicate-mobile');
            Route::resource('imports', 'ImportController');
            Route::resource('mobile-features', 'MobileFeatureController');
            Route::resource('mobile-offers', 'MobileOfferController');
            Route::resource('service-providers', 'ServiceProviderController');
            Route::post('/sp_update/{id}', 'ServiceProviderController@sp_update')->name('sp_update');
            Route::resource('subscription-months', 'SubscriptionMonthController');
            Route::resource('mobile-vats', 'MobileVatController');

            //Business VAT Setting
            Route::get('vat', 'MobileVatController@edit')->name('vat');
            Route::post('vat-update', 'MobileVatController@update')->name('vat_update');

            Route::resource('mobile-packages', 'MobilePackageController');
            Route::resource('mobile-category', 'MobileCategoryController');
            Route::resource('mobile-datas', 'MobileDataController');
            Route::resource('mobile-minutes', 'MobileMinuteController');
            Route::resource('mobile-sms', 'MobileSmsController');
            Route::resource('mobile-contract-lengths', 'MobileContractLengthController');
            Route::get('/mobile-default/{id}', 'MobileController@default')->name('mobile-default');
            Route::post('/mobile-default-update', 'MobileController@default_update')->name('mobile-default-update');

            //Landline Product
            Route::get('/fetch/landline-products', 'LandlineProductController@getlandlineproducts')->name('get.landline_products');
            Route::get('/landline-default/{id}', 'LandlineProductController@default')->name('landline-default');
            Route::post('/landline-default-update', 'LandlineProductController@default_update')->name('landline-default-update');
            Route::resource('landline-products', 'LandlineProductController');
            Route::post('/landline-product-update/{id}', 'LandlineProductController@landline_update')->name('landline-product-update');
            Route::get('duplicate-landline/{id}', 'LandlineProductController@duplicate')->name('duplicate-landline');
            Route::resource('landline-packages', 'LandlinePackageController');
            Route::resource('landline-features', 'LandlineFeatureController');
            Route::resource('broadband-types', 'BroadbandTypeController');
            Route::resource('landline-offers', 'LandlineOfferController');
            Route::resource('landline-contract-lengths', 'LandlineContractLengthController');
            Route::resource('landline-category', 'LandLineCategory');


            //Top-Deal Product
            Route::get('/fetch/topdeal-products', 'TopdealProductController@gettopdealproducts')->name('get.topdeal_products');
            Route::resource('topdeal-products', 'TopdealProductController');
            Route::post('/topdeal-product-update/{id}', 'TopdealProductController@topdeal_update')->name('topdeal-product-update');
            Route::resource('topdeal-packages', 'TopdealPackageController');
            Route::resource('topdeal-features', 'TopdealFeatureController');
            Route::resource('topdeal-offers', 'TopdealOfferController');
            Route::resource('topdeal-contract-lengths', 'TopdealContractLengthController');
            Route::get('/topdeal-default/{id}', 'TopdealProductController@default')->name('topdeal-default');
            Route::post('/topdeal-default-update', 'TopdealProductController@default_update')->name('topdeal-default-update');
            Route::get('duplicate-topdeal/{id}', 'TopdealProductController@duplicate')->name('duplicate-topdeal');

            //Tv Product
            Route::get('/fetch/tv-products', 'TvProductController@gettvproducts')->name('get.tv_products');
            Route::resource('tv-products', 'TvProductController');
            Route::post('/tv-product-update/{id}', 'TvProductController@tv_update')->name('tv-product-update');
            Route::resource('tv-features', 'TvFeatureController');
            Route::resource('tv-contract-lengths', 'TvContractLengthController');
            Route::get('/tv-default/{id}', 'TvProductController@default')->name('tv-default');
            Route::post('/tv-default-update', 'TvProductController@default_update')->name('tv-default-update');
            Route::get('duplicate-tv/{id}', 'TvProductController@duplicate')->name('duplicate-tv');

            //Business Product
            Route::get('/fetch/business-products', 'BusinessProductController@getbusinessproducts')->name('get.business_products');
            Route::resource('business-products', 'BusinessProductController');
            Route::resource('business-features', 'BusinessFeatureController');
            Route::resource('business-contract-lengths', 'BusinessContractLengthController');

            //Business Landline Product
            Route::get('/fetch/business-landline-products', 'BusinessLandlineProductController@getbusinesslandlineproducts')->name('get.business_landline_products');
            Route::resource('business-landline-products', 'BusinessLandlineProductController');
            Route::resource('business-landline-packages', 'BusinessLandlinePackageController');
            Route::resource('business-landline-features', 'BusinessLandlineFeatureController');
            Route::resource('business-landline-offers', 'BusinessLandlineOfferController');
            Route::resource('bsness-landline-contract-lengths', 'BusinessLandlineContractLengthController');

            //Business Broadband Product
            Route::resource('business-services', 'BusinessServiceController');
            Route::get('/fetch/broadband', 'BusinessServiceController@getproducts')->name('get.broadband-products');

            Route::resource('business-categories', 'BusinessCategoryController');
            Route::resource('business-productpricetypes', 'BusinessProductPriceTypeController');
            Route::resource('business-broad-features', 'BusinessbroadFeatureController');
            Route::resource('business-packages', 'BusinessPackageController');

            //Business Mobile Product
            Route::get('/fetch/business-mobile-products', 'BusinessMobileController@getmobileproducts')->name('get.business_mobile_products');
            Route::resource('business-mobiles', 'BusinessMobileController');
            Route::resource('business-mobile-features', 'BusinessMobileFeatureController');
            Route::resource('business-mobile-offers', 'BusinessMobileOfferController');
            Route::resource('business-service-providers', 'BusinessServiceProviderController');
            Route::post('/business_sp_update/{id}', 'BusinessServiceProviderController@sp_update')->name('business_sp_update');
            Route::resource('business-subscription-months', 'BusinessSubscriptionMonthController');
            Route::resource('business-mobile-vats', 'BusinessMobileVatController');
            Route::resource('business-mobile-packages', 'BusinessMobilePackageController');
            Route::resource('business-mobile-datas', 'BusinessMobileDataController');
            Route::resource('business-mobile-minutes', 'BusinessMobileMinuteController');
            Route::resource('business-mobile-contract-lengths', 'BusinessMobileContractLengthController');

            //Business Top-Deal Product
            Route::get('/fetch/business-topdeal-products', 'BusinessTopdealProductController@getbusinesstopdealproducts')->name('get.business_topdeal_products');
            Route::resource('business-topdeal-products', 'BusinessTopdealProductController');
            Route::resource('business-topdeal-packages', 'BusinessTopdealPackageController');
            Route::resource('business-topdeal-features', 'BusinessTopdealFeatureController');
            Route::resource('business-topdeal-offers', 'BusinessTopdealOfferController');
            Route::resource('business-topdeal-contract-lengths', 'BusinessTopdealContractLengthController');

            //Business Shop Products
            Route::resource('business-shop-products', 'BusinessShopProductController');
            Route::get('/fetch/business_shop_products', 'BusinessShopProductController@getproducts')->name('get.business_shop_products');
            Route::post('/business_product_update/{id}', 'BusinessShopProductController@product_update')->name('business_product_update');
            Route::resource('business-shop-categories', 'BusinessShopCatController');
            Route::post('/business_shop_cat_update/{id}', 'BusinessShopCatController@shop_cat_update')->name('business_shop_cat_update');

            Route::get('options-attributes/add/{option_id}', 'OptionAttributeController@add_option_attribute')->name('options-attributes.add');

            //Customer Settings
            Route::resource('customers', 'CustomerController');
            Route::get('/get-customers', 'CustomerController@getCustomers')->name('get.customers');
            Route::get('/customer/view/{id}', 'CustomerController@view')->name('customer.view');
            Route::get('customers/pw_generate/{id}', 'CustomerController@pw_generate')->name('customers.pw-generate');
            Route::post('customer/pw_update/{id}', 'CustomerController@pw_update')->name('customers.pw-update');



            //Manage Order
            Route::get('/orders', 'OrderController@index')->name('order');
            Route::get('/orders/view/{order_id}/{not_id?}', 'OrderController@view')->name('order.view');

            Route::post('/orders/duplicate', 'OrderController@duplicate')->name('order.duplicate');
            Route::post('/mark-as-ok', 'OrderController@markAsOk')->name('order.mark-as-ok');
            Route::post('assign-to-agent/{id}', 'OrderController@assignAgent')->name('assign-agent');
            Route::post('change-status/{id}', 'OrderController@changeStatus')->name('change-status');
            Route::post('order-type/{id}', 'OrderController@orderType')->name('order-type');
            Route::post('/destroy', 'OrderController@destroy')->name('order.destroy');
            Route::get('/order-edit/{id}', 'OrderController@editOrders')->name('orders.edit');
            Route::post('/order-chk-edit', 'OrderController@editChkOrders')->name('orders.chk.edit');
            Route::get('/get-orders', 'OrderController@getOrders')->name('get.orders');
            Route::post('/order-update/{id}', 'OrderController@updateOrders')->name('orders.update');
            Route::get('/order/edit-product-new/{orderid}/{productid}', 'OrderController@editProductOrder')->name('orders.product.edit');
            Route::get('sendmail', 'ConfirmationMailController@index')->name('sendmail');
            Route::post('/order-product-update/{orderid}/{productid}', 'OrderController@updateProductOrders')->name('orderproduct.update');
            Route::get('/order-log/{id}', 'OrderController@orderLog')->name('orders.order_log');

            Route::post('/get-product-order', 'OrderController@getProductOrder')->name('get.product.order');

            //Status duplicate to checkout complete

            Route::post('/order-status-change/{status}', 'OrderController@orderStatusChange')->name('orders.status');


            //Abandoned Order
            Route::get('/abandoned-orders', 'AbandonedOrderController@index')->name('order.abandoned');
            Route::get('/get-abandoned-orders', 'AbandonedOrderController@getOrders')->name('get.abandoned-orders');

            //Locked Order
            Route::get('/locked-orders', 'LockedOrderController@index')->name('order.locked');
            Route::get('/get-locked-orders', 'LockedOrderController@getOrders')->name('get.locked-orders');

            //Pending Direct Debit
            Route::get('/pending-direct-debit', 'DirectDebitController@index')->name('order.pending-direct-debit');
            Route::get('/direct-debit-view/{id}', 'DirectDebitController@view')->name('order.direct-debit-view');
            Route::post('/direct-debit-approve/{id}', 'DirectDebitController@approve')->name('order.direct-debit-approve');
            Route::post('/direct-debit-reject/{id}', 'DirectDebitController@reject')->name('order.direct-debit-reject');

            //support ticket

            Route::get('/ticket', 'TicketController@index')->name('tickets.index');
            Route::get('/ticket/view/{id}', 'TicketController@view')->name('ticket.view');
            Route::post('/ticket/update/{id}', 'TicketController@update')->name('ticket.update');
            Route::get('/get-tickets', 'TicketController@getTickets')->name('get.tickets');
            Route::post('/ticket/ats_update/{order_no}/{ticket_no}', 'TicketController@sts_update')->name('ticket.sts.update');

            //Tasks
            Route::get('/task', 'TaskController@index')->name('task.index');
            Route::get('/task/view/{id}', 'TaskController@view')->name('task.view');
            Route::post('/task/update/{id}', 'TaskController@update')->name('task.update');
            Route::get('/get-tasks', 'TaskController@getTasks')->name('get.tasks');

            //Blog
            Route::resource('blog', 'BlogController');
            Route::resource('blog-categories', 'BlogCategoryController');

            //Faq
            Route::resource('faq', 'FaqPageController');
            Route::resource('faq-categories', 'FaqCategoryController');


            //CMS Settings
            Route::resource('cmspages', 'CmsSettingController');

            //CMS Banner
            Route::post('banner-store', 'CmsSettingController@bannerStore')->name('banner-store');
            Route::get('banner-edit/{id}', 'CmsSettingController@bannerEdit')->name('banner-edit');
            Route::post('banner-update/{id}', 'CmsSettingController@bannerUpdate')->name('banner-update');
            Route::post('banner-destroy/{id}', 'CmsSettingController@bannerDestroy')->name('banner-destroy');

            //CMS Article feature
            Route::post('article-store', 'ArticlefeatureController@articlefeatureStore')->name('article-store');
            Route::get('articlefeature_edit/{id}', 'ArticlefeatureController@articleEdit')->name('articlefeature_edit');
            Route::post('articlefeature_update/{id}', 'ArticlefeatureController@articleUpdate')->name('articlefeature_update');
            Route::post('article-destory/{id}', 'ArticlefeatureController@articleDestroy')->name('article-Destroy');

            //CMS Facility
            Route::post('facility-store', 'CmsSettingControllearticle-storer@facilityStore')->name('facility-store');
            Route::get('facility-edit/{id}', 'CmsSettingController@facilityEdit')->name('facility-edit');
            Route::post('facility-update/{id}', 'CmsSettingController@facilityUpdate')->name('facility-update');
            Route::post('facility-destroy/{id}', 'CmsSettingController@facilityDestroy')->name('facility-destroy');

            //CMS Testimonial
            Route::post('testimonial-store', 'CmsSettingController@testimonialStore')->name('testimonial-store');
            Route::get('testimonial-edit/{id}', 'CmsSettingController@testimonialEdit')->name('testimonial-edit');
            Route::post('testimonial-update/{id}', 'CmsSettingController@testimonialUpdate')->name('testimonial-update');
            Route::post('testimonial-destroy/{id}', 'CmsSettingController@testimonialDestroy')->name('testimonial-destroy');

            //CMS FAQs
            Route::post('faq-store', 'FaqController@store')->name('faq-store');
            Route::get('faq-edit/{id}', 'FaqController@edit')->name('faq-edit');
            Route::post('faq-update/{id}', 'FaqController@update')->name('faq-update');
            Route::post('faq-destroy/{id}', 'FaqController@destroy')->name('faq-destroy');

            Route::post('home-update/{id}', 'CmsSettingController@homeUpdate')->name('home-update');
            Route::post('business-update/{id}', 'CmsSettingController@businessUpdate')->name('business-update');
            Route::post('shop-update/{id}', 'ShopController@shopUpdate')->name('shop-update');
            Route::post('mobile-update/{id}', 'MobilePageController@mobileUpdate')->name('mobile-update');
            Route::post('topdeal-update/{id}', 'TopDealController@topdealUpdate')->name('topdeal-update');
            Route::post('landline-update/{id}', 'LandlineController@landlineUpdate')->name('landline-update');
            Route::post('broadband-update/{id}', 'BroadbandController@broadbandUpdate')->name('broadband-update');
            Route::post('offer-update/{id}', 'OfferPageController@offerUpdate')->name('offer-update');

            //CMS Support page
            Route::post('support-update/{id}', 'SupportController@SupportUpdate')->name('support-update');
            // Route::post('service-store', 'SupportController@serviceStore')->name('service-store');
            // Route::get('service-edit/{id}', 'SupportController@serviceEdit')->name('service-edit');
            // Route::post('service-update/{id}', 'SupportController@serviceUpdate')->name('service-update');
            // Route::post('service-destroy/{id}', 'SupportController@serviceDestroy')->name('service-destroy');

            //CMS FAQ page
            Route::post('faqpage-update/{id}', 'FaqPageController@faqPageUpdate')->name('faqpage-update');

            Route::post('landing-update/{id}', 'LandingController@landingUpdate')->name('landing-update');
            //CMS TV Link
            Route::post('tv-update/{id}', 'TvController@tvUpdate')->name('tv-update');
            Route::post('tvLink-store', 'TvController@tvLinkStore')->name('tvLink-store');
            Route::get('tvLink-edit/{id}', 'TvController@tvLinkEdit')->name('tvLink-edit');
            Route::post('tvLink-update/{id}', 'TvController@tvLinkUpdate')->name('tvLink-update');
            Route::post('tvLink-destroy/{id}', 'TvController@tvLinkDestroy')->name('tvLink-destroy');

            //CMS PopTv Bundle
            Route::post('poptv-store', 'TvController@poptvStore')->name('poptv-store');
            Route::get('poptv-edit/{id}', 'TvController@poptvEdit')->name('poptv-edit');
            Route::post('poptv-update/{id}', 'TvController@poptvUpdate')->name('poptv-update');
            Route::post('poptv-destroy/{id}', 'TvController@poptvDestroy')->name('poptv-destroy');

            //CMS What's Included
            Route::post('include-store', 'TvController@includeStore')->name('include-store');
            Route::get('include-edit/{id}', 'TvController@includeEdit')->name('include-edit');
            Route::post('include-update/{id}', 'TvController@includeUpdate')->name('include-update');
            Route::post('include-destroy/{id}', 'TvController@includeDestroy')->name('include-destroy');

            //CMS Discover The Bset
            Route::post('discover-store', 'TvController@discoverStore')->name('discover-store');
            Route::get('discover-edit/{id}', 'TvController@discoverEdit')->name('discover-edit');
            Route::post('discover-update/{id}', 'TvController@discoverUpdate')->name('discover-update');
            Route::post('discover-destroy/{id}', 'TvController@discoverDestroy')->name('discover-destroy');

            //CMS Business Feature
            Route::post('feature-store', 'CmsSettingController@featureStore')->name('feature-store');
            Route::get('feature-edit/{id}', 'CmsSettingController@featureEdit')->name('feature-edit');
            Route::post('feature-update/{id}', 'CmsSettingController@featureUpdate')->name('feature-update');
            Route::post('feature-destroy/{id}', 'CmsSettingController@featureDestroy')->name('feature-destroy');

            //CMS Privacy
            Route::post('privacy-page-update/{id}', 'PrivacyController@privacyUpdate')->name('privacy-page-update');
            Route::post('privacy-store', 'PrivacyController@store')->name('privacy-store');
            Route::get('privacy-edit/{id}', 'PrivacyController@edit')->name('privacy-edit');
            Route::post('privacy-update/{id}', 'PrivacyController@update')->name('privacy-update');
            Route::post('privacy-destroy/{id}', 'PrivacyController@destroy')->name('privacy-destroy');

            // Cms Business boardband testimonial by khushi
            // Route::post('business-testimonial-store', 'BusinessBroadbandTestimonialController@store')->name('business-testimonial-store');
            // Route::get('business-testimonial-edit/{id}', 'BusinessBroadbandTestimonialController@edit')->name('business-testimonial-edit');
            // Route::post('business-testimonial-update/{id}', 'BusinessBroadbandTestimonialController@update')->name('business-testimonial-update');
            // Route::post('business-testimonial-destroy/{id}', 'BusinessBroadbandTestimonialController@destroy')->name('business-testimonial-destroy');


            // Cms Business boardband FAQ by khushi
            // Route::post('business-broadband-Query-store', 'BusinessBroadbandFAQController@Querystore')->name('business-broadband-Query-store');
            // Route::get('business-broadband-Query-edit/{id}', 'BusinessBroadbandFAQController@Queryedit')->name('business-broadband-Query-edit');
            // Route::post('business-broadband-Query-update/{id}', 'BusinessBroadbandFAQController@Queryupdate')->name('business-broadband-Query-update');
            // Route::post('business-broadband-faq-update/{id}', 'BusinessBroadbandFAQController@update')->name('business-broadband-faq-update');
            // Route::post('business-broadband-Query-destroy/{id}', 'BusinessBroadbandFAQController@Querydestroy')->name('business-broadband-Query-destroy');



            //CMS Contact us page
            Route::post('contact-update/{id}', 'ContactUsController@update')->name('contact-update');

            //CMS Contact Reach Us
            Route::post('reach-store', 'ReachUsController@store')->name('reach-store');
            Route::get('reach-edit/{id}', 'ReachUsController@edit')->name('reach-edit');
            Route::post('reach-update/{id}', 'ReachUsController@update')->name('reach-update');
            Route::post('reach-destroy/{id}', 'ReachUsController@destroy')->name('reach-destroy');

            Route::post('contract-update/{id}', 'ContractInstallationController@update')->name('contract-update');

            //CMS Term and Condition page
            Route::post('term-update/{id}', 'TermConditionController@termUpdate')->name('term-update');

            //CMS Legal page
            Route::post('legal-update/{id}', 'LegalController@legalUpdate')->name('legal-update');

            //CMS LogIn page
            Route::post('login-update/{id}', 'LoginPageController@loginUpdate')->name('login-update');

            //CMS Business Home
            Route::post('business-home-update/{id}', 'BusinessHomeController@homeUpdate')->name('business-home-update');

            //CMS Business Landline
            Route::post('business-landline-update/{id}', 'BusinessLandlineController@landlineUpdate')->name('business-landline-update');

            //CMS Business Mobile
            Route::post('business-mobile-page-update/{id}', 'BusinessMobilePageController@mobileUpdate')->name('business-mobile-page-update');

            //CMS Business Broadband
            Route::post('business-broadband-update/{id}', 'BusinessBroadbandController@broadbandUpdate')->name('business-broadband-update');

            //CMS Business Testimonial
            Route::post('business-testimonial-store', 'BusinessTestimonialController@testimonialStore')->name('business-testimonial-store');
            Route::get('business-testimonial-edit/{id}', 'BusinessTestimonialController@testimonialEdit')->name('business-testimonial-edit');
            Route::post('business-testimonial-update/{id}', 'BusinessTestimonialController@testimonialUpdate')->name('business-testimonial-update');
            Route::post('business-testimonial-destroy/{id}', 'BusinessTestimonialController@testimonialDestroy')->name('business-testimonial-destroy');

            //CMS Business FAQs
            Route::post('business-faq-store', 'BusinessFaqController@store')->name('business-faq-store');
            Route::get('business-faq-edit/{id}', 'BusinessFaqController@edit')->name('business-faq-edit');
            Route::post('business-faq-update/{id}', 'BusinessFaqController@update')->name('business-faq-update');
            Route::post('business-faq-destroy/{id}', 'BusinessFaqController@destroy')->name('business-faq-destroy');

            //CMS Business FAQs
            Route::post('business-shop-update/{id}', 'BusinessShopController@shopUpdate')->name('business-shop-update');

            //CMS Business landing page by khushi
            // Route::post('business-landing-page-update/{id}', 'BusinessBroadbandController@businessUpdate')->name('business-landing-page-update');


            // by khushi
            // Route::get('', 'BusinessBroadbandPageController@index');
            // Route::post('/business-broadband-update/{id}', 'BusinessBroadbandPageController@broadbandUpdate')->name('business-broadband-page-update');
            // Route::post('/business-broadband-testimonial-update/{id}', 'BusinessBroadbandPageController@broadbandUpdate')->name('business-broadband-testimonial-update');
            // by khushi


            //CMS Business TopDeal
            Route::post('business-topdeal-update/{id}', 'BusinessTopDealController@topdealUpdate')->name('business-topdeal-update');

            Route::resource('cmstemplates', 'TemplateController');
            Route::resource('additional-extras', 'AdditionalExtraController');

            Route::post('/ae_update/{id}', 'AdditionalExtraController@ae_update')->name('ae_update');

            Route::resource('additional-extras-info', 'AdditionalExtraInfoController');

            Route::post('additional-extras-info-update/{id}', 'AdditionalExtraInfoController@additional_update')->name('additional-extras-info-update');
            // Route::post('set-default/{id}', 'AdditionalExtraInfoController@default')->name('set-default');
            // Route::post('remove-default/{id}', 'AdditionalExtraInfoController@removeDefault')->name('remove-default');

            //MailChimp Subscribers
            Route::get('create-subscriber', 'MailChimpController@createSubscriber')->name('create-subscriber');
            Route::post('store-subscriber', 'MailChimpController@storeSubscriber')->name('store-subscriber');
            Route::get('edit-subscriber/{id}', 'MailChimpController@editSubscriber')->name('edit-subscriber');
            Route::post('update-subscriber/{id}', 'MailChimpController@updateSubscriber')->name('update-subscriber');

            Route::get('subscribers-list', 'MailChimpController@subscribersList')->name('subscribers-list');
            Route::post('delete-subscriber', 'MailChimpController@deleteSubscriber')->name('delete-subscriber');
            Route::post('ckeditor/upload', 'MailChimpController@upload')->name('ckeditor.upload');

            //MailChimp Lists
            Route::get('create-contact-list', 'MailChimpController@createList')->name('create-list');
            Route::get('contacts-list', 'MailChimpController@contactsList')->name('contacts-list');
            Route::post('store-contact-list', 'MailChimpController@storeContactList')->name('store-contact-list');

            //MailChimp Campaign
            Route::get('create-campaign', 'MailChimpController@createCampaign')->name('create-campaign');
            Route::post('send-campaign', 'MailChimpController@sendCampaign')->name('send-campaign');
            Route::get('get-template', 'MailChimpController@getTemplate')->name('get-template');

            //Setting

            Route::resource('status', 'StatusController');

            Route::resource('referrals', 'ReferralController');

            Route::resource('email-template', 'EmailTemplateController');


            Route::get('website-setting', 'SettingController@websiteEdit')->name('website-setting');
            Route::post('website-store', 'SettingController@websiteStore')->name('website-store');
            Route::post('/bcc-destroy', 'SettingController@bccdestroy')->name('bccmail.destroy');
            Route::post('/cc-destroy', 'SettingController@ccdestroy')->name('ccmail.destroy');

            Route::get('mailchimp-setting', 'SettingController@mailchimpEdit')->name('mailchimp-setting');
            Route::post('mailchimp-store', 'SettingController@mailchimpStore')->name('mailchimp-store');
            Route::get('sagepay-setting', 'SettingController@sagepayEdit')->name('sagepay-setting');
            Route::post('sagepay-update', 'SettingController@sagepayUpdate')->name('sagepay-update');
            Route::get('talktalk-setting', 'SettingController@talktalkEdit')->name('talktalk-setting');
            Route::post('talktalk-update', 'SettingController@talktalkUpdate')->name('talktalk-update');
            Route::get('akj-setting', 'SettingController@akjEdit')->name('akj-setting');
            Route::post('akj-update', 'SettingController@akjUpdate')->name('akj-update');
            Route::get('fasttrack-setting', 'SettingController@fasttrackEdit')->name('fasttrack-setting');
            Route::post('fasttrack-update', 'SettingController@fasttrackUpdate')->name('fasttrack-update');
            Route::get('order-report', 'ReportController@index')->name('order-report');
            Route::get('affiliate-order-report', 'ReportController@affiliateOrder')->name('affiliate-order-report');
            Route::get('/get-reports', 'ReportController@getReport')->name('get.reports');
            Route::get('/get-affiliate-reports', 'ReportController@getAffiliateReport')->name('get.affiliate.report');
            Route::post('mark-as-aff-paid', 'ReportController@markAsAffPaid')->name('mark-as-aff-paid');

            Route::post('mark-as-agent-paid/{id}', 'AgentController@markAsAgentPaid')->name('mark-as-agent-paid');

            Route::get('/get-agent-reports/{id}', 'AgentController@getAgentReport')->name('get.agent.report');



            Route::get('pay-bill-report', 'ReportController@payBill')->name('pay-bill-report');
            Route::get('/get-pay-bill-reports', 'ReportController@getPayBillReport')->name('get.pay-bill-reports');
            Route::post('/paybill/destroy', 'ReportController@payBillDestroy')->name('paybill.destroy');
            // Route::get('/get-reports', 'ReportController@Report')->name('get-reports');

            // Role
            Route::prefix('roles')->group(function () {
                Route::get('/index', 'RoleController@index')->name('roles.index');
                Route::get('/create', 'RoleController@create')->name('roles.create');
                Route::post('/store', 'RoleController@store')->name('roles.store');
                Route::get('/edit/{id}', 'RoleController@edit')->name('roles.edit');
                Route::post('/update/{id}', 'RoleController@update')->name('roles.update');
                Route::post('/destroy', 'RoleController@destroy')->name('roles.destroy');
            });

            // Permission
            Route::prefix('permissions')->group(function () {
                Route::get('/index', 'PermissionController@index')->name('permissions.index');
                Route::get('/create', 'PermissionController@create')->name('permissions.create');
                Route::post('/store', 'PermissionController@store')->name('permissions.store');
                Route::get('/edit/{id}', 'PermissionController@edit')->name('permissions.edit');
                Route::post('/update/{id}', 'PermissionController@update')->name('permissions.update');
                Route::post('/destroy', 'PermissionController@destroy')->name('permissions.destroy');
            });



            //Edit Profile
            Route::get('edit-profile/{id}', 'EditProfileController@edit')->name('edit-profile');
            Route::post('profile-update/{id}', 'EditProfileController@update')->name('profile-update');

            //Change Password
            Route::get('change-password/{id}', 'EditProfileController@passwordEdit')->name('change-password');
            Route::post('password-update/{id}', 'EditProfileController@passwordUpdate')->name('password-update');

            //Theme Change
            Route::post('theme-store', 'ThemeController@store')->name('theme-store');
            Route::get('theme-destroy/{id}', 'ThemeController@destroy')->name('theme-destroy');

            //Newsletter Template
            Route::get('newsletter-template', 'NewsletterTemplateController@index')->name('newsletter-template');
            Route::get('newsletter-template-view/{id}', 'NewsletterTemplateController@show')->name('newsletter-template-view');
            Route::get('newsletter-template-edit/{id}', 'NewsletterTemplateController@edit')->name('newsletter-template-edit');
            Route::post('store-newsletter', 'NewsletterTemplateController@store')->name('store-newsletter');
            Route::get('create-newsletter-template', 'NewsletterTemplateController@create')->name('create-newsletter-template');

            Route::post('newsletter-template-store', 'NewsletterTemplateController@store_newsletter_template')->name('store-newsletter-template');
            //Agent listing
            Route::get('agents', 'AgentController@index')->name('agents');
            Route::get('agents-details/{id}', 'AgentController@details')->name('agents-details');
            Route::post('agents-payment/{id}', 'AgentController@payment')->name('agents-payment');

            Route::post('send_akj', 'OrderController@send_akj')->name('send_akj');

            Route::post('akj_renewal', 'OrderController@akjRenewal')->name('akj_renewal');

            //Discount
            Route::resource('discounts', 'DiscountController');

            //Affliate
            Route::resource('affiliates', 'AffiliateController');

            //Affliate Type
            Route::resource('affiliatetypes', 'AffiliateTypeController');

            //Ticket Support System
            Route::resource('ticketservicetype', 'TicketServiceTypeController');
            Route::resource('ticketpriority', 'TicketPriorityController');
            Route::resource('ticketstatus', 'TicketStatusController');

            //Tasks Support System
            Route::resource('taskservicetype', 'TaskServiceTypeController');
            Route::resource('taskpriority', 'TaskPriorityController');
            Route::resource('taskstatus', 'TaskStatusController');

            //Sorting
            Route::get('sorting', 'SortingController@index')->name('sort');
            Route::get('set_order', 'SortingController@setOrder')->name('setOrder');
            Route::get('set_order_fttp', 'SortingController@setOrderFttp')->name('setOrder_fttp');
            Route::get('set_order_broadband', 'SortingController@setOrderBroadband')->name('setOrderBroadband');
            Route::get('set_order_landline', 'SortingController@setOrderLandline')->name('setOrderLandline');
            Route::get('set_order_mobile', 'SortingController@setOrderMobile')->name('setOrderMobile');
            Route::get('set_order_tv', 'SortingController@setOrderTv')->name('setOrderTv');
            Route::get('set_order_topdeal', 'SortingController@setOrderTopdeal')->name('setOrderTopdeal');
            Route::get('set_order_router', 'SortingController@setOrderRouter')->name('setOrderRouter');
            Route::get('set_order_process', 'SortingController@setOrderProcess')->name('setOrderProcess');

            //Sorting Business Product
            Route::get('business/sorting', 'SortingController@businessIndex')->name('business.sort');

            //Sorting Shop Product
            Route::get('shop/sorting', 'SortingController@shopIndex')->name('shop.sort');

            //get appointment 

            Route::post('get_appointment', 'OrderController@getAppointment')->name('get_appointment');

            //Sorting Order Process
            Route::get('order_process/sorting', 'SortingController@orderProcessIndex')->name('orderProcess.sort');

            //talktalk state
            Route::post('get_state', 'OrderController@getState')->name('get_state');

            //create AKJ Account
            Route::post('create_akjaccount', 'OrderController@createAKJAccount')->name('create_akjaccount');

            //assign menu

            Route::get('assign_menu/{id}', 'UserController@assign_menu')->name('assign_menu');

            Route::post('menu/store', 'UserController@menu_store')->name('menus.store');

            Route::delete('menu-destroy', 'UserController@menu_destroy')->name('menu.destroy');

            //contact-list

            Route::get('contact_lists', 'ContactUsController@contact_lists')->name('contact_lists');

            //Address not found
            Route::resource('address-not-found', 'AddressNotFoundController');
            Route::resource('tasksummary', 'TaskSummaryController');
        });
        Route::post('logout', 'Auth\AuthenticatedSessionController@destroy')->name('logout');
    });


    // Route::get('/talktalk', [TalktalkController::class, 'getAddressDetails']);
});
