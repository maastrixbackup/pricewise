<?php

use App\Http\Controllers\API\CarInsuranceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserDetailController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\InternetTvController;
use App\Http\Controllers\Api\EnergyController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\HealthInsuranceController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\HomeInsuranceController;
use App\Http\Controllers\Api\VehicleInsuranceController;
use App\Http\Controllers\Api\AccidentalInsuranceController;
use App\Http\Controllers\Api\BusinessEquipmentController;
use App\Http\Controllers\Api\BusinessInterruptionController;
use App\Http\Controllers\Api\TravelInsuranceController;
use App\Http\Controllers\Api\FuneralInsuranceController;
use App\Http\Controllers\Api\CyberSecurityController;
use App\Http\Controllers\Api\FarmHouseInsuranceController;
use App\Http\Controllers\Api\LegalCounselInsuranceController;
use App\Http\Controllers\Api\LiabilityInsuranceController;
use App\Http\Controllers\Api\ShopProductController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Basic Auth
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login'])->name('customer-login');
Route::post('/forgot-password', [RegisterController::class, 'forgotPassword']);
Route::post('/reset-password', [UserDetailController::class, 'resetpassword']);
Route::post('/reset-password/{token}', [RegisterController::class, 'resetPassword'])->name('reset-password');
Route::get('get-energy-deals', [RequestController::class, 'getEnergyDeals']);
Route::get('get-energy-data', [RequestController::class, 'getEnergyData']);
Route::get('get-deals-data', [RequestController::class, 'getDealsData']);
Route::get('get-tv-packages', [RequestController::class, 'getTvPackages']);
Route::get('get-exclusive-deal/{id}', [RequestController::class, 'getExclusiveDeal']);
Route::get('get-top-four-deals', [RequestController::class, 'getTopFourDeals']);
Route::get('get-internet-tv-deals', [RequestController::class, 'getInternetTvDeals']);
Route::get('get-internet-tv-package', [RequestController::class, 'getInternetTvPackages']);
Route::get('get-home-insurance-deals', [RequestController::class, 'getHomeInsuranceDeals']);

// House Number Verification
Route::post('verify-house-number', [SettingsController::class, 'getHouseNumber']);
//Health Insurance
Route::post('health-insurance', [HealthInsuranceController::class, 'index']);
Route::post('health-insurance-compare', [HealthInsuranceController::class, 'healthInsuranceCompare']);


//Home Insurance
Route::post('home-insurance', [HomeInsuranceController::class, 'index']);
Route::post('home-insurance-compare', [HomeInsuranceController::class, 'homeInsuranceCompare']);
Route::post('home-recommended-insurance', [HomeInsuranceController::class, 'getRecommendedInsurance']);

//Building Insurance
Route::post('building-insurance', [FarmHouseInsuranceController::class, 'index']);
Route::post('building-insurance-compare', [FarmHouseInsuranceController::class, 'buildingInsuranceCompare']);

//Vehicle Insurance
Route::post('vehicle-insurance', [VehicleInsuranceController::class, 'index']);
Route::post('vehicle-insurance-compare', [VehicleInsuranceController::class, 'vehicleInsuranceCompare']);

//Accidental Insurance
Route::post('accidental-insurance', [AccidentalInsuranceController::class, 'index']);
Route::post('accidental-insurance-compare', [AccidentalInsuranceController::class, 'accidentalInsuranceCompare']);

//Travel Insurance
Route::post('travel-insurance', [TravelInsuranceController::class, 'index']);
Route::post('travel-insurance-compare', [TravelInsuranceController::class, 'travelInsuranceCompare']);

//Funeral Insurance
Route::post('funeral-insurance', [FuneralInsuranceController::class, 'index']);
Route::post('funeral-insurance-compare', [FuneralInsuranceController::class, 'funeralInsuranceCompare']);

//CyeberSecurity Insurance
Route::post('cyber-security-insurance', [CyberSecurityController::class, 'index']);
Route::post('cyber-security-insurance-compare', [CyberSecurityController::class, 'cybersecurityInsuranceCompare']);

//Liability Insurance
Route::post('liability-insurance', [LiabilityInsuranceController::class, 'index']);
Route::post('liability-insurance-compare', [LiabilityInsuranceController::class, 'liabilityInsuranceCompare']);
// Legal Counsel
Route::post('legal-counsel-insurance', [LegalCounselInsuranceController::class, 'index']);
Route::post('legal-counsel-compare', [LiabilityInsuranceController::class, 'legalCounselCompare']);
// Api on events
Route::post('get-events-list', [RequestController::class, 'eventList']);

// Api on Cyber Security
Route::post('get-cyber-security', [RequestController::class, 'cyberSecurity']);

// API on Loan
Route::post('get-loan-details', [RequestController::class, 'getLoanDetails']);
Route::post('get-loan-type', [RequestController::class,  'getPurposeData']);

// API on Nominal Fees
Route::post('get-nominal-fee', [RequestController::class,  'nominalFees']);

//Commercial Insurance
// Business Interruption Insurance
Route::post('business-interruption-insurance', [BusinessInterruptionController::class, 'index']);

// Business Equipment Insurance
Route::post('business-equipment-insurance', [BusinessEquipmentController::class, 'index']);

// API on Shop Products
Route::post('shop-products', [ShopProductController::class, 'index']);
Route::post('compare-shop-product', [ShopProductController::class, 'shopProductCompare']);
Route::get('product-details/{id}', [ShopProductController::class, 'productDetails']);
Route::post('shop-filter', [ShopProductController::class, 'shopFilter']);
Route::post('category-filter', [ShopProductController::class, 'categoryFilter']);
Route::get('category-product/{slug}', [ShopProductController::class, 'categoryWiseProducts']);

// API on Product Order
Route::post('place-order', [ShopProductController::class, 'placeOrderCreate']);
Route::post('make-order-payment', [ShopProductController::class, 'makeOrderPayment']);


// API on Wish List
Route::post('add-to-wishlist', [ShopProductController::class, 'addToWishList']);
Route::post('remove-from-wishlist', [ShopProductController::class, 'removeFromWishList']);
Route::post('wishlist-products', [ShopProductController::class, 'productWishLists']);

// API on Cart
Route::post('add-to-cart', [ShopProductController::class, 'addToCart']);
Route::post('view-cart', [ShopProductController::class, 'viewCart']);
Route::post('remove-cart', [ShopProductController::class, 'removeCart']);
Route::post('add-quantity', [ShopProductController::class, 'addMoreQty']);
Route::post('remove-quantity', [ShopProductController::class, 'removeQty']);

Route::any('store-product-review', [ShopProductController::class, 'storeProductReviews']);
Route::post('filter-reviews', [ShopProductController::class, 'filteredReviews']);
Route::post('save-product-requests', [ShopProductController::class, 'storeProductRequest']);
Route::post('product-notification-request', [ShopProductController::class, 'saveProductNotifyRequest']);
Route::post('send-email-notification', [ShopProductController::class, 'productNotifySend']);



//Frontend Guest
// SmartPhone Deals
Route::any('get-smart-phone-deals', [RequestController::class, 'getSmartPhoneDeals']);

// SmartPhone Deals
Route::any('get-search-data', [RequestController::class, 'getSearchData']);
//Internet TvR
Route::post('internet-tv', [InternetTvController::class, 'index']);
Route::post('internet-tv-compare', [InternetTvController::class, 'internetCompare']);
//API routs for energy
Route::post('energy', [EnergyController::class, 'index']);
Route::post('energy-compare', [EnergyController::class, 'energyCompare']);
Route::get('suppliers', [SettingsController::class, 'getSupliers']);
Route::get('house-type', [SettingsController::class, 'houseTypes']);
Route::post('top-energy-deals', [EnergyController::class, 'topEnergyDeals']);
Route::post('get-consumption-value', [EnergyController::class, 'getEnergyConsumption']);
Route::get('get-energy-general-faqs', [EnergyController::class, 'getEnergyGeneralFaqs']);
Route::get('get-regulatory-faqs', [EnergyController::class, 'getEnergyRegulatories']);
Route::get('get-step-plan-faqs', [EnergyController::class, 'getEnergyStepPlans']);
Route::get('view-exclusive-deals/{id}', [EnergyController::class, 'viewExclusiveDeals']);
Route::post('save-exclusive-deals-email', [EnergyController::class, 'saveExclusiveDeals']);
Route::post('rate-energy-product', [EnergyController::class, 'rateEnergyProducts']);

Route::post('save-energy-request', [RequestController::class, 'store']);
//Frontend === Auth
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [RegisterController::class, 'logout']);
    //API route for user profile
    Route::get('/profile-details', [UserDetailController::class, 'index']);
    Route::post('/profile-update', [UserDetailController::class, 'update']);
    Route::post('/change-password', [UserDetailController::class, 'changepassword']);
    Route::post('/email-update', [UserDetailController::class, 'emailUpdate']);
    //API route for user data
    Route::post('get-user-data', [RequestController::class, 'getUserData']);
    Route::post('save-user-data', [RequestController::class, 'saveUserData']);
    //API route for Customer Creadential
    Route::post('/update-credentials', [UserDetailController::class, 'updateCredentials']);
    Route::get('/get-credentials', [UserDetailController::class, 'getCredentials']);
    //API route for user request
    // Route::post('save-energy-request', [RequestController::class, 'store']);
    Route::post('get-user-request', [RequestController::class, 'index']);

    Route::get('show-user-request/{request_id}', [RequestController::class, 'show']);
    Route::get('view-order/{order_no}', [RequestController::class, 'viewOrder']);

    //Reviews
    Route::post('review-list', [RequestController::class, 'reviewList']);
    Route::post('review-save', [RequestController::class, 'reviewSave']);
    Route::post('review-show', [RequestController::class, 'reviewShow']);
});
