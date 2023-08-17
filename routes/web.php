<?php

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
Route::get('/', 'HomeController@index')->name('home');
Route::get('/carquotation', 'HomeController@car_quotation')->name('car_quotation');
Route::post('/carquotation', 'HomeController@post_car_quotation')->name('car_quotation');
Route::get('/insurancequotation', 'HomeController@insurance_quotation')->name('insurance_quotation');
Route::post('/insurancequotation', 'HomeController@post_insurance_quotation')->name('post_insurance_quotation');
Route::get('/carsearch', 'HomeController@car_search')->name('car_search');
Route::get('/testspincar', 'HomeController@testSpinCar')->name('test_spin_car');
Route::post('/getModel', 'HomeController@getmodel');
Route::post('/getCity', 'HomeController@getcity');
Route::get('/carpage/{id}', 'HomeController@car_page')->name('car_page');
Route::post('/enquired', 'HomeController@enquired')->name('enquired');
Route::get('/aboutus', 'HomeController@aboutUs')->name('about_us');
Route::get('/contactus', 'HomeController@contactUs')->name('contact_us');
Route::post('/contactus', 'HomeController@contactUsPost')->name('contact_us_post');
Route::get('/contactusforvoucher', 'HomeController@contactUsForVoucher')->name('contact_us_for_voucher');
Route::post('/contactusforvoucher', 'HomeController@contactUsForVoucherPost')->name('contact_us_for_voucher_post');
Route::get('/savedcars', 'HomeController@savedCars')->name('saved_cars');
//Route::get('/carlist','HomeController@car_list')->name('car_list');
Route::get('/cardetails/{condition}/{city}/{title}/{id}', 'HomeController@car_details')->name('car_details');
Route::get('/carfilters', 'HomeController@carfilters')->name('carfilters');
Route::get('/titlecar', 'HomeController@getTitleCar')->name('titlecar');
Route::get('/wishList', 'HomeController@wishList')->name('wishList');
Route::post('/wishList/Data', 'HomeController@wishListData')->name('wishListData');

// Compare Cars
Route::get('compare', 'CarComparesController@index')->name('carcompare');
Route::post('compare/add_car', 'CarComparesController@add')->name('carcompare.add');
Route::post('compare/remove_car', 'CarComparesController@remove')->name('carcompare.remove');

// Set Statistics on Viewed car
Route::post('car_viewed', 'HomeController@car_viewed')->name('car_viewed');

//Route::get('/runscript', 'HomeController@runscript')->name('runscript');

//Route::post('/sendRegistrationInfo', 'HomeController@sendRegisterEmail')->name('send_registration_info');
Route::post('/getMerchantByCondition', 'HomeController@getMerchantByCondition');


Route::middleware('auth:customer')->group(function(){;
	Route::any('/logout', 'CustomerController@logout')->name('customer_logout');
});

Route::middleware(['Customer'])->group(function(){
    Route::any('/profile', 'CustomerController@profile')->name('profile');
	Route::post('/photo_upload', 'CustomerController@photo_upload')->name('photo_upload');
	Route::post('/change_password', 'CustomerController@postChangePassword')->name('change_password');
	Route::post('/change_number', 'CustomerController@postChangeNumber')->name('change_number');
    
       //promotion
       Route::any('/promotions', 'PromoController@promotions')->name('promotions');
       Route::any('/myrewards', 'PromoController@myrewards')->name('myrewards');
       Route::post('/redeemcoupon', 'PromoController@redeemcoupon')->name('redeemcoupon');


});

//route for user account
Route::group(['namespace' => 'Auth\Customer', 'prefix' => 'customer'], function()
{
	Route::any('/login', 'CustomerAuthController@login')->name('customer_login');
	Route::any('/register', 'CustomerAuthController@register')->name('customer_register');
	Route::any('/verify/{token}', 'CustomerAuthController@verifyCustomer')->name('customer_registration_verify');
	Route::any('/forget-password', 'CustomerAuthController@forgetPassword')->name('customer_forget_password');
    Route::any('/new-password/{toekn}', 'CustomerAuthController@newPassword')->name('customer_new_password');
    Route::get('social/{provider}', 'CustomerAuthController@redirectToProvider')->name('social');
	Route::get('social/{provider}/callback', 'CustomerAuthController@handleProviderCallback')->name('social_callback');
});

//merchant microsite
Route::get('/merchant/{slug}','MerchantsController@index')->name('site.merchant');
Route::post('/merchant/inquiry/{id}','MerchantsController@store_inquiry')->name('site.merchant.inquiry');

Route::get('/privacypolicy', 'HomeController@privacyPolicy')->name('privacy_policy');
Route::get('/termofservice', 'HomeController@termOfService')->name('term_of_service');

// sale advisor
Route::get('/sale-advisor/{organisation_name}/{sa_slug?}/','SaleAdvisorController@index');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/show','SaleAdvisorController@show');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/show/make','SaleAdvisorController@make');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/show/model/{make_id}','SaleAdvisorController@model');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/show/variants/{model_id}','SaleAdvisorController@variants');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/show/model/{model_id}/items','SaleAdvisorController@items');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/show/variant/{variant_id}/items','SaleAdvisorController@itemsByVariant');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/show/item_detail/{item_id}','SaleAdvisorController@item_detail');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/ask','SaleAdvisorController@ask');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/ask/pricelist','SaleAdvisorController@ask_pricelist');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/ask/brochure','SaleAdvisorController@ask_brochure');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/ask/extrabrochure','SaleAdvisorController@ask_extrabrochure');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/ask/extrapricelist','SaleAdvisorController@ask_extrapricelist');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/keep','SaleAdvisorController@keep');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/verify','SaleAdvisorController@verify');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/campaign','SaleAdvisorController@campaign');
Route::get('/sale-advisor/{organisation_name}/{sa_slug}/campaign/{campaign_id}','SaleAdvisorController@campaigndetail');

// event tracker
Route::post('/eventtracker', 'SaleAdvisorController@EventTracker')->name('eventtracker');

// Route::get('/test', function () {
//     return view('welcome', ['name' => 'James']);
// });
// Route::get('/sa', function () {
//     return view('sales/landing', ['name' => 'James']);
// });
// Route::get('/sa/show', function () {
//     return view('sales/show', ['name' => 'James']);
// });
// Route::get('/sa/ask', function () {
//     return view('sales/ask', ['name' => 'James']);
// });
// Route::get('/sa/keep', function () {
//     return view('sales/keep', ['name' => 'James']);
// });
// Route::get('/sa/verify', function () {
//     return view('sales/verify', ['name' => 'James']);
// });

