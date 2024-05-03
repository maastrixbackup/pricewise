<?php

use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/', function () {
//     return view('welcome');
// });
Route::namespace('Admin')->name('admin.')->middleware('admin')->group(function () {
Route::get('file-manager', 'FileManagerController@index');
});
Route::group(['prefix' => 'pricewise'], function () {
	Route::get('/run-command', function () {
    // Call the Artisan command
    Artisan::call('optimize');
    //Artisan::call('config:cache');
     Artisan::call('permission:cache-reset');
    return 'Command executed successfully!';
});
	Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

// Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']],  function () {
// 		    Lfm::routes();
// 		});
// Admin 
Route::get('view-order/{order_no}', 'RequestController@viewOrder')->name('request.view_order');
Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {

    Route::namespace('Auth')->middleware('guest:admin')->group(function () {
        // login route
        Route::get('login', 'AuthenticatedSessionController@create')->name('login');
        Route::post('login', 'AuthenticatedSessionController@store')->name('adminlogin');

        Route::get('forgot-password', 'PasswordResetLinkController@create')->name('password.request');
        Route::post('forgot-password', 'PasswordResetLinkController@store')->name('password.email');
        Route::get('reset-password/{token}', 'NewPasswordController@create')->name('password.reset');
        Route::post('reset-password', 'NewPasswordController@store')->name('password.update');

        
    });
    Route::middleware('admin')->group(function () {
    	Route::get('file-manager', 'FileManagerController@index');
    	Route::resource('email-templates', 'EmailTemplateController');
        Route::get('dashboard', 'HomeController@index')->name('dashboard');
        Route::get('admin-test', 'HomeController@adminTest')->name('admintest');
        Route::get('editor-test', 'HomeController@editorTest')->name('editortest');
        Route::resource('posts', 'PostController');
        Route::resource('pages', 'PageController');
        Route::resource('users', 'UserController');
        Route::resource('providers', 'ProviderController');
        //Tv Product
        Route::get('/fetch/internet-tv', 'TvInternetController@gettvproducts')->name('get.internet-tv');
        Route::resource('internet-tv', 'TvInternetController');
        Route::post('/tv-feature-update/{id}', 'TvInternetController@tv_feature_update')->name('tv_feature_update');
        Route::post('/internet-feature-update/{id}', 'TvInternetController@internet_feature_update')->name('internet_feature_update');
        Route::post('/telephone-feature-update/{id}', 'TvInternetController@tele_feature_update')->name('tele_feature_update');
        Route::post('/service-info-update/{id}', 'TvInternetController@service_info_update')->name('service_info_update');
        Route::get('/tv-default/{id}', 'TvProductController@default')->name('tv-default');
        Route::post('/tv-default-update', 'TvProductController@default_update')->name('tv-default-update');
        Route::get('duplicate-tv/{id}', 'TvProductController@duplicate')->name('duplicate-tv');
        //Insurance
        Route::get('/fetch/insurance', 'InsuranceController@getinsuranceproducts')->name('get.insurance');
        Route::resource('insurance', 'InsuranceController');
        Route::post('/insurance-feature-update/{id}', 'InsuranceController@insurance_feature_update')->name('insurance_feature_update');
        Route::post('/insurance-reimburse-update/{id}', 'InsuranceController@insurance_reimburse_update')->name('insurance_reimburse_update');
        Route::get('/insurance/{id}', 'InsuranceController@default')->name('insurance-default');
        //Route::post('/telephone-feature-update/{id}', 'TvInternetController@tele_feature_update')->name('tele_feature_update');
        //Route::post('/service-info-update/{id}', 'TvInternetController@service_info_update')->name('service_info_update');

        //Requests
        Route::get('/fetch/requests', 'RequestController@getRequests')->name('get.requests');
        Route::post('/update_status/{id}', 'RequestController@updateStatus')->name('request.update_status');
        // Route::get('/requests/edit/', 'RequestController@index')->name('get.request');
        Route::resource('requests', 'RequestController');

        //Energy
        Route::get('/fetch/energy', 'EnergyController@getenergyproducts')->name('get.energy');
        Route::resource('energy', 'EnergyController');
        Route::post('/doc-update/{id}', 'EnergyController@energy_doc_update')->name('doc_update');
        Route::post('/doc-delete/{id}', 'EnergyController@energy_doc_delete')->name('doc_delete');
        Route::post('/energy-feature-update/{id}', 'EnergyController@energy_feature_update')->name('energy_feature_update');
        Route::post('/energy-price-update/{id}', 'EnergyController@energy_price_update')->name('energy.pricing');
        Route::get('/energy/{id}', 'EnergyController@default')->name('energy-default');
        Route::get('duplicate-energy/{id}', 'TvProductController@duplicate')->name('duplicate-energy');
        //Features
        Route::resource('features', 'FeatureController');
        //Route::resource('tv-contract-lengths', 'TvContractLengthController');

        //FAQ
        Route::get('FAQ-list', 'FAQController@FAQList')->name('FAQ-list');
        Route::get('FAQ-add', 'FAQController@FAQAdd')->name('FAQ-add');
        Route::post('FAQ-store', 'FAQController@FAQStore')->name('FAQ-store');
        Route::get('FAQ-edit/{id}', 'FAQController@FAQEdit')->name('FAQ-edit');
        Route::post('FAQ-update', 'FAQController@FAQupdate')->name('FAQ-update');
        Route::get('FAQ-delete/{id}', 'FAQController@FAQDelete')->name('FAQ-delete');

        //Tv Channel
        Route::resource('tv-channel', 'TvChannelController');

        
        //Customers
        Route::resource('customers', 'CustomerController');
        Route::post('status-change/{id}', 'CustomerController@statusChange')->name('statusChange');
        Route::get('approve-customers', 'CustomerController@approve')->name('approve-customers');
        Route::get('reject-customers', 'CustomerController@reject')->name('reject-customers');

        //Edit Profile
        Route::get('edit-profile/{id}', 'EditProfileController@edit')->name('edit-profile');
        Route::post('profile-update/{id}', 'EditProfileController@update')->name('profile-update');

        //Change Password
        Route::get('change-password/{id}', 'EditProfileController@passwordEdit')->name('change-password');
        Route::post('password-update/{id}', 'EditProfileController@passwordUpdate')->name('password-update');

        //Categories
        Route::resource('categories', 'CategoryController');
        //Banners
        Route::resource('banners', 'BannerController');
        //Reimbursement
        Route::resource('reimbursement', 'ReimbursementController');
        //Energy Rate Chat
        Route::resource('energy-rate-chat', 'EnergyRateChatController');
        //Feed In-Costs
        Route::resource('feed-in-costs', 'FeedInCostController');
        //Events
        Route::resource('events', 'EventController');

        //MailChimp Subscribers
        Route::get('create-subscriber', 'MailChimpController@createSubscriber')->name('create-subscriber');
        Route::post('store-subscriber', 'MailChimpController@storeSubscriber')->name('store-subscriber');
        Route::get('edit-subscriber/{id}', 'MailChimpController@editSubscriber')->name('edit-subscriber');
        Route::post('update-subscriber/{id}', 'MailChimpController@updateSubscriber')->name('update-subscriber');

        Route::get('subscribers-list', 'MailChimpController@subscribersList')->name('subscribers-list');
        Route::post('delete-subscriber', 'MailChimpController@deleteSubscriber')->name('delete-subscriber');

        //MailChimp Lists
        Route::get('create-contact-list', 'MailChimpController@createList')->name('create-list');
        Route::get('contacts-list', 'MailChimpController@contactsList')->name('contacts-list');
        Route::post('store-contact-list', 'MailChimpController@storeContactList')->name('store-contact-list');

        //MailChimp Campaign
        Route::get('create-campaign', 'MailChimpController@createCampaign')->name('create-campaign');
        Route::post('send-campaign', 'MailChimpController@sendCampaign')->name('send-campaign');
        Route::get('get-template', 'MailChimpController@getTemplate')->name('get-template');

        //Website Setting
        Route::get('website-setting', 'SettingController@websiteEdit')->name('website-setting');        
        Route::post('website-store', 'SettingController@websiteStore')->name('website-store');
        Route::get('business-setting', 'SettingController@businessEdit')->name('business-setting');
        Route::post('business-store', 'SettingController@businessStore')->name('business-store');
        Route::get('smtp-setting', 'SettingController@smtpEdit')->name('smtp-setting');
        Route::post('smtp-store', 'SettingController@smtpStore')->name('smtp-store');
        Route::get('payment-setting', 'SettingController@paymentEdit')->name('payment-setting');
        Route::post('payment-store', 'SettingController@paymentStore')->name('payment-store');
        //Newsletter Template
        Route::get('newsletter-template', 'NewsletterTemplateController@index')->name('newsletter-template');
        Route::get('newsletter-template-view/{id}', 'NewsletterTemplateController@show')->name('newsletter-template-view');
        Route::get('newsletter-template-edit/{id}', 'NewsletterTemplateController@edit')->name('newsletter-template-edit');
        Route::post('store-newsletter', 'NewsletterTemplateController@store')->name('store-newsletter');

        //Mailchimp Setting
        Route::get('mailchimp-setting', 'SettingController@mailchimpEdit')->name('mailchimp-setting');
        Route::post('mailchimp-store', 'SettingController@mailchimpStore')->name('mailchimp-store');


        // Role
        Route::prefix('roles')->group(function () {
            Route::get('/', 'RoleController@index')->name('roles.index');
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
    });
    Route::post('logout', 'Auth\AuthenticatedSessionController@destroy')->name('logout');
});
});