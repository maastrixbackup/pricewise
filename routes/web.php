<?php

use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\VacancyController;
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
    // Route::get('view-order/{order_no}', 'RequestController@viewOrder')->name('request.view_order');
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
        Route::post('/upload', 'RequestController@imageUploads');
        Route::middleware('admin')->group(function () {
            Route::get('file-manager', 'FileManagerController@index');
            Route::post('/upload-image', 'RequestController@imageUpload')->name('upload_image');
            Route::resource('email-templates', 'EmailTemplateController');
            Route::get('dashboard', 'HomeController@index')->name('dashboard');
            Route::get('admin-test', 'HomeController@adminTest')->name('admintest');
            Route::get('editor-test', 'HomeController@editorTest')->name('editortest');
            Route::resource('posts', 'PostController');
            Route::resource('pages', 'PageController');
            Route::resource('users', 'UserController');
            Route::resource('providers', 'ProviderController');
            Route::get('providers/{id}', 'ProviderController@show')->name('providers');
            // Energy Consumption
            Route::get('/consumptions/{id}', 'EnergyController@viewConsumptions')->name('consumptions');
            Route::get('/consumptions-create', 'EnergyController@addConsumption')->name('consumptions.add');
            Route::get('/consumptions-edit/{id}', 'EnergyController@editConsumption')->name('consumptions.edit');
            Route::post('/consumptions-store', 'EnergyController@storeConsumption')->name('consumptions.store');
            Route::post('/consumptions-update/{id}', 'EnergyController@updateConsumption')->name('consumptions.update');
            Route::post('/check-house-type', 'EnergyController@checkHouseType')->name('check_house_type');

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

            // Smartphone
            Route::resource('smartphone', 'SmartPhoneController');
            Route::resource('smartphone-faq', 'SmartPhoneFaqController');
            Route::resource('provider-discount', 'ProviderDiscountController');
            Route::resource('provider-feature', 'ProviderFeatureController');
            // Common Data
            Route::resource('post-codes', 'PostalCodeController');
            Route::prefix('house-numbers')->group(function () {
                Route::get('/', 'PostalCodeController@houseNumberIndex')->name('house-numbers.index');
                Route::get('/create', 'PostalCodeController@houseNumberCreate')->name('house-numbers.create');
                Route::post('/store', 'PostalCodeController@houseNumberStore')->name('house-numbers.store');
                Route::get('/edit/{id}', 'PostalCodeController@houseNumberEdit')->name('house-numbers.edit');
                Route::post('/update/{id}', 'PostalCodeController@houseNumberUpdate')->name('house-numbers.update');
                Route::post('/destroy', 'PostalCodeController@houseNumberDestroy')->name('house-numbers.destroy');
            });

            Route::post('get-postal-code-data', 'PostalCodeController@postalCodeData')->name('get-postal-code-data');


            //Bank
            Route::resource('banks', 'BankController');

            Route::get('upload-documents/{id}', 'CommonController@uploadDocument')->name('upload-documents');
            Route::post('upload-documents-store', 'CommonController@uploadDocumentStore')->name('upload-documents-store');
            Route::post('/delete-document/{id}', 'CommonController@deleteUploadDocument')->name('delete-document');
            // Spending Purpose
            Route::prefix('purposes')->group(function () {
                Route::get('/', 'CommonController@purposes_index')->name('purposes.index');
                Route::get('/create', 'CommonController@purposes_create')->name('purposes.create');
                Route::post('/store', 'CommonController@purposes_store')->name('purposes.store');
                Route::get('/edit/{id}', 'CommonController@purposes_edit')->name('purposes.edit');
                Route::post('/update/{id}', 'CommonController@purposes_update')->name('purposes.update');
                Route::post('/destroy', 'CommonController@purposes_destroy')->name('purposes.destroy');
            });
            // Spending Purpose
            Route::prefix('loan-type')->group(function () {
                Route::get('/', 'CommonController@loanType_index')->name('loan-type.index');
                Route::get('/create', 'CommonController@loanType_create')->name('loan-type.create');
                Route::post('/store', 'CommonController@loanType_store')->name('loan-type.store');
                Route::get('/edit/{id}', 'CommonController@loanType_edit')->name('loan-type.edit');
                Route::post('/update/{id}', 'CommonController@loanType_update')->name('loan-type.update');
                Route::post('/destroy', 'CommonController@loanType_destroy')->name('loan-type.destroy');
            });

            // House Type
            Route::prefix('house-type')->group(function () {
                Route::get('/', 'CommonController@houseType_index')->name('house-type.index');
                Route::get('/create', 'CommonController@houseType_create')->name('house-type.create');
                Route::post('/store', 'CommonController@houseType_store')->name('house-type.store');
                Route::get('/edit/{id}', 'CommonController@houseType_edit')->name('house-type.edit');
                Route::post('/update/{id}', 'CommonController@houseType_update')->name('house-type.update');
                Route::post('/destroy', 'CommonController@houseType_destroy')->name('house-type.destroy');
            });

            // Loans
            Route::resource('loans', 'LoanController');

            // Shop
            Route::resource('products', 'ProductController');
            Route::post('/add-product-images/{id}', 'ProductController@add_product_images')->name('add_product_images');
            Route::post('/update-product-description/{id}', 'ProductController@update_product_description')->name('update_product_description');
            Route::post('/update-new-arrival', 'ProductController@update_new_arrival')->name('update_new_arrival');
            Route::post('/update-product-features/{id}', 'ProductController@update_product_features')->name('update_product_features');
            Route::post('/delete-product-images', 'ProductController@delete_product_images')->name('delete_p_image');
            Route::post('/delete-product-specification', 'ProductController@delete_p_specification')->name('delete_p_specification');
            Route::post('/duplicate-product', 'ProductController@duplicateProduct')->name('duplicate_product');
            Route::post('/store-product', 'ProductController@storeDuplicateProduct')->name('store_duplicate_product');
            Route::post('/add-product-highlights/{id}', 'ProductController@storeProductHighlight')->name('add_product_highlights');
            Route::post('/delete-product-highlight', 'ProductController@delete_p_highlight')->name('delete_p_highlight');
            Route::get('/request-products', 'ProductController@requestedProduct')->name('request_products');
            Route::get('/notify-products', 'ProductController@notificationProduct')->name('notify_products');
            Route::post('/request-product-details', 'ProductController@checkRequestDetails')->name('request_product_details');
            Route::get('/combo-deals/{id}', 'ProductController@comboDealsCreate')->name('combo-deals');
            Route::post('/store-product-deals', 'ProductController@comboDealsStore')->name('store_product_deals');
            Route::post('/update-deals-status', 'ProductController@comboStatusUpdate')->name('update_deals_status');
            Route::post('/delete-product-deal', 'ProductController@comboDealsDelete')->name('delete_product_deals');

            // Notification
            Route::get('/notifications', 'ProductController@checkNotification')->name('notifications');
            // Product Category
            Route::prefix('product-color')->group(function () {
                Route::get('/', 'ProductController@color_index')->name('product-color.index');
                Route::get('/create', 'ProductController@color_create')->name('product-color.create');
                Route::post('/store', 'ProductController@color_store')->name('product-color.store');
                Route::get('/edit/{id}', 'ProductController@color_edit')->name('product-color.edit');
                Route::post('/update/{id}', 'ProductController@color_update')->name('product-color.update');
                Route::post('/destroy', 'ProductController@color_destroy')->name('product-color.destroy');
            });

            // Product Category
            Route::prefix('product-category')->group(function () {
                Route::get('/', 'ProductController@pCategory_index')->name('product-category.index');
                Route::get('/create', 'ProductController@pCategory_create')->name('product-category.create');
                Route::post('/store', 'ProductController@pCategory_store')->name('product-category.store');
                Route::get('/edit/{id}', 'ProductController@pCategory_edit')->name('product-category.edit');
                Route::post('/update/{id}', 'ProductController@pCategory_update')->name('product-category.update');
                Route::post('/destroy', 'ProductController@pCategory_destroy')->name('product-category.destroy');
            });

            // Product Promotion
            Route::prefix('product-promotion')->group(function () {
                Route::get('/', 'ProductController@promotion_index')->name('product-promotion.index');
                Route::get('/create', 'ProductController@promotion_create')->name('product-promotion.create');
                Route::post('/store', 'ProductController@promotion_store')->name('product-promotion.store');
                Route::get('/edit/{id}', 'ProductController@promotion_edit')->name('product-promotion.edit');
                Route::post('/update/{id}', 'ProductController@promotion_update')->name('product-promotion.update');
                Route::post('/destroy', 'ProductController@promotion_destroy')->name('product-promotion.destroy');
            });

            // Product Promotion
            Route::prefix('product-brands')->group(function () {
                Route::get('/', 'ProductController@brand_index')->name('product-brands.index');
                Route::get('/create', 'ProductController@brand_create')->name('product-brands.create');
                Route::post('/store', 'ProductController@brand_store')->name('product-brands.store');
                Route::get('/edit/{id}', 'ProductController@brand_edit')->name('product-brands.edit');
                Route::post('/update/{id}', 'ProductController@brand_update')->name('product-brands.update');
                Route::post('/destroy', 'ProductController@brand_destroy')->name('product-brands.destroy');
            });

            // Deals Product
            Route::prefix('deals-product')->group(function () {
                Route::get('/', 'ProductController@deals_index')->name('deals-product.index');
                Route::get('/create', 'ProductController@deals_create')->name('deals-product.create');
                Route::post('/store', 'ProductController@deals_store')->name('deals-product.store');
                Route::get('/edit/{id}', 'ProductController@deals_edit')->name('deals-product.edit');
                Route::post('/update/{id}', 'ProductController@deals_update')->name('deals-product.update');
                Route::post('/destroy', 'ProductController@deals_destroy')->name('deals-product.destroy');
            });

            // Product Ratings
            Route::get('ratings', 'ProductController@ratingsView')->name('ratings');
            Route::post('show-product-ratings', 'ProductController@viewRatings')->name('show_product_ratings');
            Route::post('get-review-details', 'ProductController@reviewDetails')->name('get_review_details');


            // Route::prefix('shop-settings')->group(function () {
            //     Route::get('/', 'ProductController@shop_index')->name('shop-settings.index');
            //     Route::get('/create', 'ProductController@shop_create')->name('shop-settings.create');
            //     Route::post('/store', 'ProductController@shop_store')->name('shop-settings.store');
            //     Route::get('/edit/{id}', 'ProductController@shop_edit')->name('shop-settings.edit');
            //     Route::post('/update/{id}', 'ProductController@shop_update')->name('shop-settings.update');
            //     Route::post('/destroy', 'ProductController@shop_destroy')->name('shop-settings.destroy');
            // });

            // Cyber Security
            Route::resource('cyber-security', 'CyberSecurityController');
            // Security Provider
            Route::prefix('security-provider')->group(function () {
                Route::get('/', 'CyberSecurityController@sProvider_index')->name('security-provider.index');
                Route::get('/create', 'CyberSecurityController@sProvider_create')->name('security-provider.create');
                Route::post('/store', 'CyberSecurityController@sProvider_store')->name('security-provider.store');
                Route::get('/edit/{id}', 'CyberSecurityController@sProvider_edit')->name('security-provider.edit');
                Route::post('/update/{id}', 'CyberSecurityController@sProvider_update')->name('security-provider.update');
                Route::post('/destroy', 'CyberSecurityController@sProvider_destroy')->name('security-provider.destroy');
            });

            // Security Provider
            Route::prefix('security-feature')->group(function () {
                Route::get('/', 'CyberSecurityController@sFeatures_index')->name('security-feature.index');
                Route::get('/create', 'CyberSecurityController@sFeatures_create')->name('security-feature.create');
                Route::post('/store', 'CyberSecurityController@sFeatures_store')->name('security-feature.store');
                Route::get('/edit/{id}', 'CyberSecurityController@sFeatures_edit')->name('security-feature.edit');
                Route::post('/update/{id}', 'CyberSecurityController@sFeatures_update')->name('security-feature.update');
                Route::post('/destroy', 'CyberSecurityController@sFeatures_destroy')->name('security-feature.destroy');
            });



            Route::get('/create-new-job', [VacancyController::class, 'show'])->name('post-new-vacancy');


            Route::get('/job_role', [VacancyController::class, 'job_role'])->name('job_role');
            Route::post('/submit-role', [VacancyController::class, 'role_submit'])->name('vacancy.role.submit');
            Route::post('/delete-job-role/{id}', [VacancyController::class, 'destroy'])->name('deleteJobRole');
            Route::post('/submit-vacancy-form', [VacancyController::class, 'submit'])->name('vacancy.form.submit');
            Route::get('/job_type', [VacancyController::class, 'job_type'])->name('job_type');
            Route::post('/submit-job-type', [VacancyController::class, 'job_type_submit'])->name('vacancy.jobtype.submit');
            Route::delete('/delete-row', [VacancyController::class, 'deleteRow'])->name('deleteRow');
            Route::get('/job_industry', [VacancyController::class, 'job_industry'])->name('job_industry');
            Route::post('/submit-industry-type', [VacancyController::class, 'industry_type_submit'])->name('vacancy.jobindustry.submit');
            Route::delete('/delete-industry', [VacancyController::class, 'deleteIndustry'])->name('deleteIndustry');
            Route::get('/work_experience', [VacancyController::class, 'work_experience'])->name('work_exp');
            Route::post('/submit-job-exp', [VacancyController::class, 'job_exp_submit'])->name('job.exp.submit');
            Route::delete('/delete-experience', [VacancyController::class, 'delete_exp'])->name('deleteExp');
            Route::get('/educational_qualificaton', [VacancyController::class, 'educational_qualification'])->name('edu-qual');
            Route::delete('/delete-education', [VacancyController::class, 'delete_education'])->name('deleteEducation');
            Route::post('/add-new-qual', [VacancyController::class, 'add_new_qual'])->name('add-qualification');
            Route::get('/set-salary', [VacancyController::class, 'set_salary'])->name('set_salary');
            Route::delete('/delete-pph', [VacancyController::class, 'delete_pph'])->name('deletePph');
            Route::post('/add-pph', [VacancyController::class, 'add_pph'])->name('addpph');
            Route::get('/job-location', [VacancyController::class, 'job_location'])->name('job_location');
            Route::post('/add-location', [VacancyController::class, 'add_location'])->name('addlocation');
            Route::post('/delete-location', [VacancyController::class, 'delete_location'])->name('deletelocation');
            Route::get('/get-job-roles/{industry_id}', [VacancyController::class, 'getJobRoles']);
            Route::get('/list-all-jobs', [VacancyController::class, 'list_all_jobs'])->name('list-all-jobs');
            Route::delete('/delete-job', [VacancyController::class, 'delete_job'])->name('deleteJob');

            Route::get('/edit-job/{id}', [VacancyController::class, 'edit'])->name('edit');
            Route::post('/update-job/{id}', [VacancyController::class, 'update'])->name('vacancy.update');








            //Requests
            Route::get('/fetch/requests', 'RequestController@getRequests')->name('get.requests');
            Route::post('/update_status/{id}', 'RequestController@updateStatus')->name('request.update_status');
            // Route::get('/requests/edit/', 'RequestController@index')->name('get.request');
            Route::resource('requests', 'RequestController');

            //Caterer
            Route::get('/caterer/list', 'CatererController@listcaterer')->name('list.caterer');
            Route::get('/caterer/create', 'CatererController@addcaterer')->name('add.caterer');
            Route::post('/caterer/post', 'CatererController@postcaterer')->name('post.caterer');
            Route::any('/caterer/edit/{id}', 'CatererController@editcaterer')->name('edit.caterer');
            Route::post('/caterer/update/{id}', 'CatererController@updatecaterer')->name('update.caterer');
            Route::any('/caterer/delete/{id}', 'CatererController@deletecaterer')->name('delete.caterer');


            // New Events
            // Route::get('/events/list', 'CatererController@listcaterer')->name('list.caterer');
            // Route::get('/caterer/create', 'CatererController@addcaterer')->name('add.caterer');
            // Route::post('/caterer/post', 'CatererController@postcaterer')->name('post.caterer');
            // Route::any('/caterer/edit/{id}', 'CatererController@editcaterer')->name('edit.caterer');
            // Route::post('/caterer/update/{id}', 'CatererController@updatecaterer')->name('update.caterer');
            // Route::any('/caterer/delete', 'CatererController@deletecaterer')->name('delete.caterer');

            Route::post('/get-provider', 'CommonController@getProviderData')->name('get_provider');
            Route::get('/global-energy-setting', 'CommonController@globalEnergySetting')->name('global-energy-setting');
            Route::post('/global-setting-store', 'CommonController@globalEnergySettingStore')->name('global-setting-store');

            //Energy
            Route::get('/fetch/energy', 'EnergyController@getenergyproducts')->name('get.energy');
            Route::resource('energy', 'EnergyController');
            Route::post('/doc-update/{id}', 'EnergyController@energy_doc_update')->name('doc_update');
            Route::post('/doc-delete/{id}', 'EnergyController@energy_doc_delete')->name('doc_delete');
            Route::post('/energy-feature-update/{id}', 'EnergyController@energy_feature_update')->name('energy_feature_update');
            Route::post('/energy-price-update/{id}', 'EnergyController@energy_price_update')->name('energy.pricing');
            Route::post('/energy-status-update/{id}', 'EnergyController@energyStatusUpdate')->name('energy-status-update');
            Route::get('/energy/{id}', 'EnergyController@default')->name('energy-default');
            Route::get('duplicate-energy/{id}', 'TvProductController@duplicate')->name('duplicate-energy');
            //Features
            Route::resource('features', 'FeatureController');
            //Route::resource('tv-contract-lengths', 'TvContractLengthController');

            // General Faqs
            Route::get('general-faqs/{c_id}', 'FAQController@generalFaqs')->name('general-faqs');
            Route::get('add-faqs/{id}', 'FAQController@generalFaqsAdd')->name('add-faqs');
            Route::post('store-faqs', 'FAQController@generalFaqsStore')->name('store-faqs');
            Route::get('edit-faqs/{id}', 'FAQController@generalFaqsEdit')->name('edit-faqs');
            Route::post('update-faqs', 'FAQController@generalFaqsUpdate')->name('update-faqs');
            Route::get('delete-faqs/{id}', 'FAQController@generalFaqsDelete')->name('delete-faqs');

            // Energy Regulatory Faqs
            Route::get('energy-regulatory', 'FAQController@regulatoryFaqs')->name('energy-regulatory');
            Route::get('add-regulatory', 'FAQController@regulatoryAdd')->name('add-regulatory');
            Route::post('store-regulatory', 'FAQController@regulatoryStore')->name('store-regulatory');
            Route::get('edit-regulatory/{id}', 'FAQController@regulatoryEdit')->name('edit-regulatory');
            Route::post('update-regulatory', 'FAQController@regulatoryUpdate')->name('update-regulatory');
            Route::get('delete-regulatory/{id}', 'FAQController@regulatoryDelete')->name('delete-regulatory');

            // Energy Regulatory Faqs
            Route::get('step-by-step-plans', 'FAQController@stepPlanFaqs')->name('step-by-step-plans');
            Route::get('add-step-plans', 'FAQController@stepPlansAdd')->name('add-step-plans');
            Route::post('store-step-plans', 'FAQController@stepPlansStore')->name('store-step-plans');
            Route::get('edit-step-plans/{id}', 'FAQController@stepPlansEdit')->name('edit-step-plans');
            Route::post('update-step-plans', 'FAQController@stepPlansUpdate')->name('update-step-plans');
            Route::get('delete-step-plans/{id}', 'FAQController@stepPlansDelete')->name('delete-step-plans');

            // Provider Faqs
            Route::get('provider-faqs/{c_id}', 'FAQController@providerFaqs')->name('provider-faqs');
            Route::get('add-provider-faqs/{id}', 'FAQController@providerFaqsAdd')->name('add-provider-faqs');
            Route::post('store-provider-faqs', 'FAQController@providerFaqsStore')->name('store-provider-faqs');
            Route::get('edit-provider-faqs/{id}', 'FAQController@providerFaqsEdit')->name('edit-provider-faqs');
            Route::post('update-provider-faqs', 'FAQController@providerFaqsUpdate')->name('update-provider-faqs');
            Route::post('check-existing-p-data', 'FAQController@pFaqsExitingData')->name('check-existing-p-data');
            Route::get('delete-provider-faqs/{id}', 'FAQController@providerFaqsDelete')->name('delete-provider-faqs');

            // Switching Plan Faqs
            Route::get('switching-plan-faqs/{id}', 'ProviderController@switchingPlanFaqs')->name('switching-plan-faqs');
            Route::get('add-switching-plan-faqs/{id}', 'ProviderController@switchingPlanFaqsAdd')->name('add-switching-plan-faqs');
            Route::post('store-switching-plan-faqs', 'ProviderController@switchingPlanFaqsStore')->name('store-switching-plan-faqs');
            Route::get('edit-switching-plan-faqs/{id}', 'ProviderController@switchingPlanFaqsEdit')->name('edit-switching-plan-faqs');
            Route::post('update-switching-plan-faqs', 'ProviderController@switchingPlanFaqsUpdate')->name('update-switching-plan-faqs');
            Route::get('delete-switching-plan-faqs/{id}', 'ProviderController@switchingPlanFaqsDelete')->name('delete-switching-plan-faqs');

            // FAQ
            Route::get('FAQ-list', 'FAQController@FAQList')->name('FAQ-list');
            Route::get('FAQ-add', 'FAQController@FAQAdd')->name('FAQ-add');
            Route::post('FAQ-store', 'FAQController@FAQStore')->name('FAQ-store');
            Route::get('FAQ-edit/{id}', 'FAQController@FAQEdit')->name('FAQ-edit');
            Route::post('FAQ-update', 'FAQController@FAQupdate')->name('FAQ-update');
            Route::get('FAQ-delete/{id}', 'FAQController@FAQDelete')->name('FAQ-delete');

            //Tv Channel
            Route::resource('tv-channel', 'TvChannelController');

            // Tv Package
            Route::resource('tv-packages', 'TvPackageController');

            // Exclusive Deals
            Route::resource('exclusive-deals', 'ExclusiveDealController');
            Route::post('get-products-categorywise', 'ExclusiveDealController@getProductsCategoryWise')->name('get-products-categorywise');

            //Combos
            Route::resource('combos', 'ComboController');

            //Tv Options
            Route::resource('tv-options', 'TvOptionController');

            //Insurance Coverages
            Route::resource('insurance-coverages', 'InsuranceCoverageController');

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
            //SubCategories
            Route::resource('sub-categories', 'SubCategoryController');
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
            Route::resource('events_type', 'EventTypeController');
            Route::resource('room_type', 'EventRoomController');
            Route::resource('event_theme', 'EventThemeController');

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

            // Shop Setting
            Route::get('shop-settings', 'ProductController@shopEdit')->name('shop-settings');
            Route::post('shop-store', 'ProductController@shopStore')->name('shop-store');

            //Website Setting
            Route::get('website-setting', 'SettingController@websiteEdit')->name('website-setting');
            Route::post('website-store', 'SettingController@websiteStore')->name('website-store');
            Route::get('business-setting', 'SettingController@businessEdit')->name('business-setting');
            Route::post('business-store', 'SettingController@businessStore')->name('business-store');
            Route::get('smtp-setting', 'SettingController@smtpEdit')->name('smtp-setting');
            Route::post('smtp-store', 'SettingController@smtpStore')->name('smtp-store');
            Route::get('payment-setting', 'SettingController@paymentEdit')->name('payment-setting');
            Route::post('payment-store', 'SettingController@paymentStore')->name('payment-store');
            Route::get('nominal-fees-setting', 'SettingController@nominalFeesEdit')->name('nominal-fees-setting');
            Route::post('nominal-fees-store', 'SettingController@nominalFeesStore')->name('nominal-fees-store');

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
