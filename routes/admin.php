<?php

Route::get('/clear-cache', function() {
	$exitCode = Artisan::call('cache:clear');
	$exitCode = Artisan::call('config:clear');
	// $exitCode = Artisan::call('config:cache');
});


Route::get('/phpinfo', function() {
	phpinfo();
});



Route::group(['middleware' => ['installer']], function()
{
  Route::get('/not_allowed', function () {
      return view('errors.not_found');
  });

  // admin 
  Route::group(['namespace' => 'AdminControllers','prefix' => 'admin'], function()
  {
    Route::get('/login', 'AdminController@login')->name('admin_login');;
    Route::get('/register', 'AdminController@register')->name('merchant_register');
    Route::post('/checkLogin', 'AdminController@checkLogin');
    Route::post('/registerLogin', 'AdminController@registerLogin');
    Route::get('/user/verify/{token}', 'AdminController@verifyUser');
  });

  Route::get('/home', function () {
    // return redirect('/campaignreport');
      return redirect('/admin/dashboard/this_month');
  });
Route::group(['namespace' => 'AdminControllers','middleware' => 'auth', 'prefix' => 'admin'], function()
{
	Route::post('webPagesSettings/changestatus', 'ThemeController@changestatus');
	Route::post('webPagesSettings/setting/set', 'ThemeController@set');
	Route::post('webPagesSettings/reorder', 'ThemeController@reorder');
  Route::get('/home', function () {
    // return redirect('/campaignreport');
      return redirect('/dashboard/{reportBase}');
  });
	Route::get('/generateKey', 'SiteSettingController@generateKey');


  //log out
  Route::get('/logout', 'AdminController@logout');
	Route::get('/webPagesSettings/{id}', 'ThemeController@index2');
    	Route::get('/dashboard/{reportBase}', 'ReportController@campaignreport');
      //add adddresses against customers
      Route::get('/addaddress/{id}/', 'CustomersController@addaddress')->middleware('add_customer');
      Route::post('/addNewCustomerAddress', 'CustomersController@addNewCustomerAddress')->middleware('add_customer');
      Route::post('/editAddress', 'CustomersController@editAddress')->middleware('edit_customer');
      Route::post('/updateAddress', 'CustomersController@updateAddress')->middleware('edit_customer');
      Route::post('/deleteAddress', 'CustomersController@deleteAddress')->middleware('delete_customer');
      Route::post('/getZones', 'AddressController@getzones');
      Route::post('/getModel', 'ModelController@getmodel');
      Route::post('/getCity', 'CitiesController@getcity');

      //sliders
      Route::get('/sliders', 'AdminSlidersController@sliders')->middleware('website_routes');
      Route::get('/addsliderimage', 'AdminSlidersController@addsliderimage')->middleware('website_routes');
      Route::post('/addNewSlide', 'AdminSlidersController@addNewSlide')->middleware('website_routes');
      Route::get('/editslide/{id}', 'AdminSlidersController@editslide')->middleware('website_routes');
      Route::post('/updateSlide', 'AdminSlidersController@updateSlide')->middleware('website_routes');
      Route::post('/deleteSlider/', 'AdminSlidersController@deleteSlider')->middleware('website_routes');

      //constant banners
      Route::get('/constantbanners', 'AdminConstantController@constantBanners')->middleware('website_routes');
      Route::get('/addconstantbanner', 'AdminConstantController@addconstantBanner')->middleware('website_routes');
      Route::post('/addNewConstantBanner', 'AdminConstantController@addNewconstantBanner')->middleware('website_routes');
      Route::get('/editconstantbanner/{id}', 'AdminConstantController@editconstantbanner')->middleware('website_routes');
      Route::post('/updateconstantBanner', 'AdminConstantController@updateconstantBanner')->middleware('website_routes');
      Route::post('/deleteconstantBanner/', 'AdminConstantController@deleteconstantBanner')->middleware('website_routes');
});

Route::group(['prefix'=>'admin/languages','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'LanguageController@display')->middleware('view_language');
  Route::post('/default', 'LanguageController@default')->middleware('edit_language');
  Route::get('/add', 'LanguageController@add')->middleware('add_language');
  Route::post('/add', 'LanguageController@insert')->middleware('add_language');
  Route::get('/edit/{id}', 'LanguageController@edit')->middleware('edit_language');
  Route::post('/update', 'LanguageController@update')->middleware('edit_language');
  Route::post('/delete', 'LanguageController@delete')->middleware('delete_language');
  Route::get('/filter', 'LanguageController@filter')->middleware('view_language');

});

Route::group(['prefix'=>'admin/media','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'MediaController@display')->middleware('view_media');
  Route::get('/add', 'MediaController@add')->middleware('add_media');
  Route::post('/updatemediasetting', 'MediaController@updatemediasetting')->middleware('edit_media');
  Route::post('/uploadimage', 'MediaController@fileUpload')->middleware('add_media');
  Route::get('/deleteimage/{id}', 'MediaController@deleteimage')->middleware('delete_media');
  Route::get('/detailimage/{id}', 'MediaController@detailimage')->middleware('view_media');
  Route::get('/refresh', 'MediaController@refresh');
});

Route::group(['prefix'=>'admin/theme','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/setting', 'ThemeController@index');
	Route::get('/setting/{id}', 'ThemeController@moveToBanners');
	Route::get('/setting/carousals/{id}', 'ThemeController@moveToSliders');
  Route::post('setting/set', 'ThemeController@set');
	Route::post('setting/setPages', 'ThemeController@setPages');
	Route::post('/setting/updatebanner', 'ThemeController@updatebanner');
	Route::post('/setting/carousals/updateslider', 'ThemeController@updateslider');
	Route::post('/setting/addbanner', 'ThemeController@addbanner');
  Route::post('/reorder', 'ThemeController@reorder');
	Route::post('/setting/changestatus', 'ThemeController@changestatus');
    Route::post('/setting/fetchlanguages', 'LanguageController@fetchlanguages')->middleware('view_language');
});

Route::group(['prefix'=>'admin/make','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'MakeController@display')->middleware('view_make');
  Route::get('/add', 'MakeController@add')->middleware('add_make');
  Route::post('/add', 'MakeController@insert')->middleware('add_make');
  Route::get('/edit/{id}', 'MakeController@edit')->middleware('edit_make');
  Route::post('/update', 'MakeController@update')->middleware('edit_make');
  Route::post('/delete', 'MakeController@delete')->middleware('delete_make');
  Route::get('/filter', 'MakeController@filter')->middleware('view_make');
});

Route::group(['prefix'=>'admin/model','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'ModelController@display')->middleware('view_model');
  Route::get('/add', 'ModelController@add')->middleware('add_model');
  Route::post('/add', 'ModelController@insert')->middleware('add_model');
  Route::get('/edit/{id}', 'ModelController@edit')->middleware('edit_model');
  Route::post('/update', 'ModelController@update')->middleware('edit_model');
  Route::post('/delete', 'ModelController@delete')->middleware('delete_model');
  Route::get('/filter', 'ModelController@filter')->middleware('view_model');
});

//Variants
Route::group(['prefix'=>'admin/variant','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'VariantController@display')->middleware('view_model');
//Route::get('model/ajax/{id}',array('as'=>'myform.ajax','uses'=>'VariantController@ajaxModel'));
  Route::get('/display/ajax/{id}', 'VariantController@ajaxModel');
  Route::get('/edit/ajax/{id}', 'VariantController@ajaxModel');
  Route::get('/add', 'VariantController@add')->middleware('add_model');
  Route::post('/add', 'VariantController@insert')->middleware('add_model');
  Route::get('/edit/{id}', 'VariantController@edit')->middleware('edit_model');
  Route::post('/update', 'VariantController@update')->middleware('edit_model');
  Route::post('/delete', 'VariantController@delete')->middleware('delete_model');
  Route::get('/filter', 'VariantController@filter')->middleware('view_model');
});



Route::group(['prefix'=>'admin/state','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'StateController@display')->middleware('view_state');
  Route::get('/add', 'StateController@add')->middleware('add_state');
  Route::post('/add', 'StateController@insert')->middleware('add_state');
  Route::get('/edit/{id}', 'StateController@edit')->middleware('edit_state');
  Route::post('/update', 'StateController@update')->middleware('edit_state');
  Route::post('/delete', 'StateController@delete')->middleware('delete_state');
  Route::get('/filter', 'StateController@filter')->middleware('view_state');
});

Route::group(['prefix'=>'admin/cities','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'CitiesController@display')->middleware('view_cities');
  Route::get('/add', 'CitiesController@add')->middleware('add_cities');
  Route::post('/add', 'CitiesController@insert')->middleware('add_cities');
  Route::get('/edit/{id}', 'CitiesController@edit')->middleware('edit_cities');
  Route::post('/update', 'CitiesController@update')->middleware('edit_cities');
  Route::post('/delete', 'CitiesController@delete')->middleware('delete_cities');
  Route::get('/filter', 'CitiesController@filter')->middleware('view_cities');
});

Route::group(['prefix'=>'admin/type','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'TypeController@display')->middleware('view_type');
  Route::get('/add', 'TypeController@add')->middleware('add_type');
  Route::post('/add', 'TypeController@insert')->middleware('add_type');
  Route::get('/edit/{id}', 'TypeController@edit')->middleware('edit_type');
  Route::post('/update', 'TypeController@update')->middleware('edit_type');
  Route::post('/delete', 'TypeController@delete')->middleware('delete_type');
  Route::get('/filter', 'TypeController@filter')->middleware('view_type');
});

Route::group(['prefix' => 'admin/itemtype', 'namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'ItemTypeController@display');
  Route::get('/add', 'ItemTypeController@add');
  Route::post('/add', 'ItemTypeController@insert');
  Route::get('/edit/{id}', 'ItemTypeController@edit');
  Route::post('/update', 'ItemTypeController@update');
  Route::get('/delete/{id}', 'ItemTypeController@delete');
});

Route::group(['prefix' => 'admin/itemattribute', 'namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'ItemAttributeController@display');
  Route::get('/add', 'ItemAttributeController@add');
  Route::post('/insert', 'ItemAttributeController@insert');
  Route::get('/edit/{id}', 'ItemAttributeController@edit');
  Route::post('/update', 'ItemAttributeController@update');
  Route::get('/delete/{id}', 'ItemAttributeController@delete');
  Route::get('/deleteAtrributeValue/{id}', 'ItemAttributeController@deleteAttributeValue');
});

Route::group(['prefix'=>'admin/car','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'CarController@display')->middleware('view_car');
  Route::get('/add', 'CarController@add')->middleware('add_car');
  Route::post('/add', 'CarController@insert')->middleware('add_car');
  Route::get('/edit/{id}', 'CarController@edit')->middleware('edit_car');
  Route::post('/update', 'CarController@update')->middleware('edit_car');
  Route::post('/delete', 'CarController@delete')->middleware('delete_car');
  Route::get('/filter', 'CarController@filter')->middleware('view_car');
  Route::post('/getItemAttribute', 'CarController@getItemAttribute')->name('getItemAttribute');
  Route::get('/deletecarimage/{id}', 'CarController@deletecarimage');
  Route::get('/display/{item_type_id}', 'CarController@display');
  Route::get('/add/ajax/{id}', 'CarController@ajaxModel');
  Route::get('/edit/ajax/{id}', 'CarController@ajaxModel');

  Route::group(['prefix'=>'bulk-airtime'], function () {
    Route::get('/get-car', 'CarController@getCarsAirtime')->middleware('view_manage_merchant');
    Route::get('/edit', 'CarController@bulkEditAirtime')->middleware('edit_manage_merchant');
    Route::get('/toggle-airtime/{id}', 'CarController@toggleAirtime')->middleware('edit_manage_merchant');
  });
});

Route::group(['prefix'=>'admin/manufacturers','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'ManufacturerController@display')->middleware('view_manufacturer');
  Route::get('/add', 'ManufacturerController@add')->middleware('add_manufacturer');
  Route::post('/add', 'ManufacturerController@insert')->middleware('add_manufacturer');
  Route::get('/edit/{id}', 'ManufacturerController@edit')->middleware('edit_manufacturer');
  Route::post('/update', 'ManufacturerController@update')->middleware('edit_manufacturer');
  Route::post('/delete', 'ManufacturerController@delete')->middleware('delete_manufacturer');
  Route::get('/filter', 'ManufacturerController@filter')->middleware('view_manufacturer');
});

Route::group(['prefix'=>'admin/newscategories','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'NewsCategoriesController@display')->middleware('view_news');
  Route::get('/add', 'NewsCategoriesController@add')->middleware('add_news');
  Route::post('/add', 'NewsCategoriesController@insert')->middleware('add_news');
  Route::get('/edit/{id}', 'NewsCategoriesController@edit')->middleware('edit_news');
  Route::post('/update', 'NewsCategoriesController@update')->middleware('edit_news');
  Route::post('/delete', 'NewsCategoriesController@delete')->middleware('delete_news');
  Route::get('/filter', 'NewsCategoriesController@filter')->middleware('view_news');
});

Route::group(['prefix'=>'admin/news','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'NewsController@display')->middleware('view_news');
  Route::get('/add', 'NewsController@add')->middleware('add_news');
  Route::post('/add', 'NewsController@insert')->middleware('add_news');
  Route::get('/edit/{id}', 'NewsController@edit')->middleware('edit_news');
  Route::post('/update', 'NewsController@update')->middleware('edit_news');
  Route::post('/delete', 'NewsController@delete')->middleware('delete_news');
  Route::get('/filter', 'NewsController@filter')->middleware('view_news');
});

Route::group(['prefix'=>'admin/categories','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'CategoriesController@display');
  Route::get('/add', 'CategoriesController@add');
  Route::post('/add', 'CategoriesController@insert');
  Route::get('/edit/{id}', 'CategoriesController@edit');
  Route::post('/update', 'CategoriesController@update');
  Route::post('/delete', 'CategoriesController@delete');
  Route::get('/filter', 'CategoriesController@filter');
});

Route::group(['prefix'=>'admin/currencies','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'CurrencyController@display');
  Route::get('/add', 'CurrencyController@add');
  Route::post('/add', 'CurrencyController@insert');
  Route::get('/edit/{id}', 'CurrencyController@edit');
	Route::get('/edit/warning/{id}', 'CurrencyController@warningedit');
  Route::post('/update', 'CurrencyController@update');
  Route::post('/delete', 'CurrencyController@delete');
});

Route::group(['prefix'=>'admin/products','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'ProductController@display')->middleware('view_product');
  Route::get('/add', 'ProductController@add')->middleware('add_product');
  Route::post('/add', 'ProductController@insert')->middleware('add_product');
  Route::get('/edit/{id}', 'ProductController@edit')->middleware('edit_product');
  Route::post('/update', 'ProductController@update')->middleware('edit_product');
  Route::post('/delete', 'ProductController@delete')->middleware('delete_product');
  Route::get('/filter', 'ProductController@filter')->middleware('view_product');
    Route::group(['prefix'=>'inventory'], function () {
    Route::get('/display', 'ProductController@addinventoryfromsidebar')->middleware('view_product');
    // Route::post('/addnewstock', 'ProductController@addinventory')->middleware('view_product');
    Route::get('/ajax_min_max/{id}/', 'ProductController@ajax_min_max')->middleware('view_product');
    Route::get('/ajax_attr/{id}/', 'ProductController@ajax_attr')->middleware('view_product');
    Route::post('/addnewstock', 'ProductController@addnewstock')->middleware('add_product');
    Route::post('/addminmax', 'ProductController@addminmax')->middleware('add_product');
    Route::get('/addproductimages/{id}/', 'ProductController@addproductimages')->middleware('add_product');
    });
    Route::group(['prefix'=>'images'], function () {
    Route::get('/display/{id}/', 'ProductController@displayProductImages')->middleware('view_product');
    Route::get('/add/{id}/', 'ProductController@addProductImages')->middleware('add_product');
    Route::post('/insertproductimage', 'ProductController@insertProductImages')->middleware('add_product');
    Route::get('/editproductimage/{id}', 'ProductController@editProductImages')->middleware('edit_product');
    Route::post('/updateproductimage', 'ProductController@updateproductimage')->middleware('edit_product');
    Route::post('/deleteproductimagemodal', 'ProductController@deleteproductimagemodal')->middleware('edit_product');
    Route::post('/deleteproductimage', 'ProductController@deleteproductimage')->middleware('edit_product');
    });
    Route::group(['prefix'=>'attach/attribute'], function () {
    Route::get('/display/{id}', 'ProductController@addproductattribute')->middleware('view_product');
    Route::group(['prefix'=>'/default'], function () {
      Route::post('/', 'ProductController@addnewdefaultattribute')->middleware('view_product');
      Route::post('/edit', 'ProductController@editdefaultattribute')->middleware('edit_product');
      Route::post('/update', 'ProductController@updatedefaultattribute')->middleware('edit_product');
      Route::post('/deletedefaultattributemodal', 'ProductController@deletedefaultattributemodal')->middleware('edit_product');
      Route::post('/delete', 'ProductController@deletedefaultattribute')->middleware('edit_product');
      Route::group(['prefix'=>'/options'], function () {
         Route::post('/add', 'ProductController@showoptions')->middleware('view_product');
         Route::post('/edit', 'ProductController@editoptionform')->middleware('edit_product');
         Route::post('/update', 'ProductController@updateoption')->middleware('edit_product');
         Route::post('/showdeletemodal', 'ProductController@showdeletemodal')->middleware('edit_product');
         Route::post('/delete', 'ProductController@deleteoption')->middleware('edit_product');
         Route::post('/getOptionsValue', 'ProductController@getOptionsValue')->middleware('edit_product');
         Route::post('/currentstock', 'ProductController@currentstock')->middleware('view_product');

      });

    });

  });

});

Route::group(['prefix'=>'admin/products/attributes','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'ProductAttributesController@display')->middleware('view_product');
  Route::get('/add', 'ProductAttributesController@add')->middleware('view_product');
  Route::post('/insert', 'ProductAttributesController@insert')->middleware('view_product');
  Route::get('/edit/{id}', 'ProductAttributesController@edit')->middleware('view_product');
  Route::post('/update', 'ProductAttributesController@update')->middleware('view_product');
  Route::post('/delete', 'ProductAttributesController@delete')->middleware('view_product');

  Route::group(['prefix'=>'options/values'], function () {
    Route::get('/display/{id}', 'ProductAttributesController@displayoptionsvalues')->middleware('view_product');
    Route::post('/insert', 'ProductAttributesController@insertoptionsvalues')->middleware('edit_product');
    Route::get('/edit/{id}', 'ProductAttributesController@editoptionsvalues')->middleware('edit_product');
    Route::post('/update', 'ProductAttributesController@updateoptionsvalues')->middleware('edit_product');
    Route::post('/delete', 'ProductAttributesController@deleteoptionsvalues')->middleware('edit_product');
    Route::post('/addattributevalue', 'ProductAttributesController@addattributevalue')->middleware('edit_product');
    Route::post('/updateattributevalue', 'ProductAttributesController@updateattributevalue')->middleware('edit_product');
    Route::post('/checkattributeassociate', 'ProductAttributesController@checkattributeassociate')->middleware('edit_product');
    Route::post('/checkvalueassociate', 'ProductAttributesController@checkvalueassociate')->middleware('edit_product');
  });
});



Route::group(['prefix'=>'admin/admin','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/profile', 'AdminController@profile');
  Route::post('/update', 'AdminController@update');
  Route::post('/updatepassword', 'AdminController@updatepassword');
});

//customers
Route::group(['prefix'=>'admin/customers','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'CustomersController@display')->middleware('view_customer');
  Route::get('/add', 'CustomersController@add')->middleware('add_customer');
  Route::post('/add', 'CustomersController@insert')->middleware('add_customer');
  Route::get('/edit/{id}', 'CustomersController@edit')->middleware('edit_customer');
  Route::post('/update', 'CustomersController@update')->middleware('edit_customer');
  Route::post('/delete', 'CustomersController@delete')->middleware('delete_customer');
  Route::get('/filter', 'CustomersController@filter')->middleware('view_customer');
  Route::get('/history/{id}', 'CustomersController@history');
  //add adddresses against customers
  Route::get('/address/display/{id}/', 'CustomersController@diplayaddress')->middleware('add_customer');
  Route::post('/addcustomeraddress', 'CustomersController@addcustomeraddress')->middleware('add_customer');
  Route::post('/editaddress', 'CustomersController@editaddress')->middleware('edit_customer');
  Route::post('/updateaddress', 'CustomersController@updateaddress')->middleware('edit_customer');
  Route::post('/deleteAddress', 'CustomersController@deleteAddress')->middleware('edit_customer');
});

Route::group(['prefix'=>'admin/countries','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/filter', 'CountriesController@filter')->middleware('view_tax');
  Route::get('/display', 'CountriesController@index')->middleware('view_tax');
  Route::get('/add', 'CountriesController@add')->middleware('add_tax');
  Route::post('/add', 'CountriesController@insert')->middleware('add_tax');
  Route::get('/edit/{id}', 'CountriesController@edit')->middleware('edit_tax');
  Route::post('/update', 'CountriesController@update')->middleware('edit_tax');
  Route::post('/delete', 'CountriesController@delete')->middleware('delete_tax');
});


Route::group(['prefix'=>'admin/zones','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'ZonesController@index')->middleware('view_tax');
  Route::get('/filter', 'ZonesController@filter')->middleware('view_tax');
  Route::get('/add', 'ZonesController@add')->middleware('add_tax');
  Route::post('/add', 'ZonesController@insert')->middleware('add_tax');
  Route::get('/edit/{id}', 'ZonesController@edit')->middleware('edit_tax');
  Route::post('/update', 'ZonesController@update')->middleware('edit_tax');
  Route::post('/delete', 'ZonesController@delete')->middleware('delete_tax');
});

Route::group(['prefix'=>'admin/tax','middleware' => 'auth','namespace' => 'AdminControllers'], function () {

  Route::group(['prefix'=>'/taxclass'], function () {
    Route::get('/filter', 'TaxController@filtertaxclass')->middleware('view_tax');
    Route::get('/display', 'TaxController@taxindex')->middleware('view_tax');
    Route::get('/add', 'TaxController@addtaxclass')->middleware('add_tax');
    Route::post('/add', 'TaxController@inserttaxclass')->middleware('add_tax');
    Route::get('/edit/{id}', 'TaxController@edittaxclass')->middleware('edit_tax');
    Route::post('/update', 'TaxController@updatetaxclass')->middleware('edit_tax');
    Route::post('/delete', 'TaxController@deletetaxclass')->middleware('delete_tax');
  });

  Route::group(['prefix'=>'/taxrates'], function () {
    Route::get('/display', 'TaxController@displaytaxrates')->middleware('view_tax');
    Route::get('/filter', 'TaxController@filtertaxrates')->middleware('view_tax');
    Route::get('/add', 'TaxController@addtaxrate')->middleware('add_tax');
    Route::post('/add', 'TaxController@inserttaxrate')->middleware('add_tax');
    Route::get('/edit/{id}', 'TaxController@edittaxrate')->middleware('edit_tax');
    Route::post('/update', 'TaxController@updatetaxrate')->middleware('edit_tax');
    Route::post('/delete', 'TaxController@deletetaxrate')->middleware('delete_tax');
  });

});

Route::group(['prefix'=>'admin/shippingmethods','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  //shipping setting
  Route::get('/display', 'ShippingMethodsController@display')->middleware('view_shipping');
  Route::get('/upsShipping', 'ShippingMethodsController@upsShipping')->middleware('view_shipping');
  Route::post('/updateupsshipping', 'ShippingMethodsController@updateupsshipping')->middleware('edit_shipping');
  Route::get('/flateRate', 'ShippingMethodsController@flateRate')->middleware('view_shipping');
  Route::post('/updateflaterate', 'ShippingMethodsController@updateflaterate')->middleware('edit_shipping');
  Route::post('/defaultShippingMethod', 'ShippingMethodsController@defaultShippingMethod')->middleware('edit_shipping');
  Route::get('/detail/{table_name}', 'ShippingMethodsController@detail')->middleware('edit_shipping');
  Route::post('/update', 'ShippingMethodsController@update')->middleware('edit_shipping');

  Route::get('/shppingbyweight', 'ShippingByWeightController@shppingbyweight')->middleware('view_shipping');
  Route::post('/updateShppingWeightPrice', 'ShippingByWeightController@updateShppingWeightPrice')->middleware('edit_shipping');

});

Route::group(['prefix'=>'admin/paymentmethods','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
	Route::get('/index', 'PaymentMethodsController@index')->middleware('view_payment');
	Route::get('/display/{id}', 'PaymentMethodsController@display')->middleware('view_payment');
	Route::post('/update', 'PaymentMethodsController@update')->middleware('edit_payment');
	Route::post('/active', 'PaymentMethodsController@active')->middleware('edit_payment');
});

Route::group(['prefix'=>'admin/coupons','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'CouponsController@display')->middleware('view_coupon');
  Route::get('/add', 'CouponsController@add')->middleware('add_coupon');
  Route::post('/insert', 'CouponsController@insert')->middleware('add_coupon');
  Route::get('/edit/{id}', 'CouponsController@edit')->middleware('edit_coupon');
  Route::post('/update', 'CouponsController@update')->middleware('edit_coupon');
  Route::post('/delete', 'CouponsController@delete')->middleware('delete_coupon');
  Route::get('/filter', 'CouponsController@filter')->middleware('view_coupon');
});
Route::group(['prefix'=>'admin/devices','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'NotificationController@devices')->middleware('view_notification');
  Route::get('/viewdevices/{id}', 'NotificationController@viewdevices')->middleware('view_notification');
  Route::post('/notifyUser/', 'NotificationController@notifyUser')->middleware('edit_notification');
  Route::get('/notifications/', 'NotificationController@notifications')->middleware('view_notification');
  Route::post('/sendNotifications/', 'NotificationController@sendNotifications')->middleware('edit_notification');
  Route::post('/customerNotification/', 'NotificationController@customerNotification')->middleware('view_notification');
  Route::post('/singleUserNotification/', 'NotificationController@singleUserNotification')->middleware('edit_notification');
  Route::post('/deletedevice/', 'NotificationController@deletedevice')->middleware('view_notification');
});

Route::group(['prefix'=>'admin/devices','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/', 'NotificationController@devices')->middleware('view_notification');
  Route::get('/viewdevices/{id}', 'NotificationController@viewdevices')->middleware('view_notification');
  Route::post('/notifyUser/', 'NotificationController@notifyUser')->middleware('edit_notification');
  Route::get('/notifications/', 'NotificationController@notifications')->middleware('view_notification');
  Route::post('/sendNotifications/', 'NotificationController@sendNotifications')->middleware('edit_notification');
  Route::post('/customerNotification/', 'NotificationController@customerNotification')->middleware('view_notification');
  Route::post('/singleUserNotification/', 'NotificationController@singleUserNotification')->middleware('edit_notification');
  Route::post('/deletedevice/', 'NotificationController@deletedevice')->middleware('view_notification');
});

Route::group(['prefix'=>'admin/orders','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/display', 'OrdersController@display')->middleware('view_order');
  Route::get('/vieworder/{id}', 'OrdersController@vieworder')->middleware('view_order');
  Route::post('/updateOrder', 'OrdersController@updateOrder')->middleware('edit_order');
  Route::post('/deleteOrder', 'OrdersController@deleteOrder')->middleware('edit_order');
  Route::get('/invoiceprint/{id}', 'OrdersController@invoiceprint')->middleware('view_order');
  Route::get('/orderstatus', 'SiteSettingController@orderstatus')->middleware('view_order');
  Route::get('/addorderstatus', 'SiteSettingController@addorderstatus')->middleware('edit_order');
  Route::post('/addNewOrderStatus', 'SiteSettingController@addNewOrderStatus')->middleware('edit_order');
  Route::get('/editorderstatus/{id}', 'SiteSettingController@editorderstatus')->middleware('edit_order');
  Route::post('/updateOrderStatus', 'SiteSettingController@updateOrderStatus')->middleware('edit_order');
  Route::post('/deleteOrderStatus', 'SiteSettingController@deleteOrderStatus')->middleware('edit_order');

});

Route::group(['prefix'=>'admin/banners','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
  Route::get('/', 'BannersController@banners')->middleware('view_app_setting');
  Route::get('/add', 'BannersController@addbanner')->middleware('edit_app_setting');
  Route::post('/insert', 'BannersController@addNewBanner')->middleware('edit_app_setting');
  Route::get('/edit/{id}', 'BannersController@editbanner')->middleware('edit_app_setting');
  Route::post('/update', 'BannersController@updateBanner')->middleware('edit_app_setting');
  Route::post('/delete', 'BannersController@deleteBanner')->middleware('edit_app_setting');
  Route::get('/filter', 'BannersController@filterbanners')->middleware('edit_app_setting');


});


  Route::group(['prefix'=>'admin','middleware' => 'auth','namespace' => 'AdminControllers'], function () {

      Route::get('/statscustomers', 'ReportsController@statsCustomers')->middleware('report');
			Route::get('/statsproductspurchased', 'ReportsController@statsProductsPurchased')->middleware('report');
			Route::get('/statsproductsliked', 'ReportsController@statsProductsLiked')->middleware('report');
			Route::get('/outofstock', 'ReportsController@outofstock')->middleware('report');
			Route::get('/lowinstock', 'ReportsController@lowinstock')->middleware('report');
			Route::get('/stockin', 'ReportsController@stockin')->middleware('report');
			Route::post('/productSaleReport', 'ReportsController@productSaleReport')->middleware('report');
////////////////////////////////////////////////////////////////////////////////////
//////////////     APP ROUTES
////////////////////////////////////////////////////////////////////////////////////
      //app pages controller
      Route::get('/pages', 'PagesController@pages')->middleware('view_app_setting', 'application_routes');
      Route::get('/addpage', 'PagesController@addpage')->middleware('edit_app_setting', 'application_routes');
      Route::post('/addnewpage', 'PagesController@addnewpage')->middleware('edit_app_setting', 'application_routes');
      Route::get('/editpage/{id}', 'PagesController@editpage')->middleware('edit_app_setting', 'application_routes');
      Route::post('/updatepage', 'PagesController@updatepage')->middleware('edit_app_setting', 'application_routes');
      Route::get('/pageStatus', 'PagesController@pageStatus')->middleware('edit_app_setting', 'application_routes');
      Route::get('/filterpages', 'PagesController@filterpages')->middleware('view_app_setting', 'application_routes');
      //manageAppLabel
      Route::get('/listingAppLabels', 'AppLabelsController@listingAppLabels')->middleware('view_app_setting', 'application_routes');
      Route::get('/addappkey', 'AppLabelsController@addappkey')->middleware('edit_app_setting', 'application_routes');
      Route::post('/addNewAppLabel', 'AppLabelsController@addNewAppLabel')->middleware('edit_app_setting', 'application_routes');
      Route::get('/editAppLabel/{id}', 'AppLabelsController@editAppLabel')->middleware('edit_app_setting', 'application_routes');
      Route::post('/updateAppLabel/', 'AppLabelsController@updateAppLabel')->middleware('edit_app_setting', 'application_routes');
      Route::get('/applabel', 'AppLabelsController@manageAppLabel')->middleware('view_app_setting', 'application_routes');

      Route::get('/admobSettings', 'SiteSettingController@admobSettings')->middleware('view_app_setting', 'application_routes');
      Route::get('/applicationapi', 'SiteSettingController@applicationApi')->middleware('view_app_setting', 'application_routes');
      Route::get('/appsettings', 'SiteSettingController@appSettings')->middleware('view_app_setting', 'application_routes');


////////////////////////////////////////////////////////////////////////////////////
//////////////     SITE ROUTES
////////////////////////////////////////////////////////////////////////////////////
      //site pages controller
      Route::get('/webpages', 'PagesController@webpages')->middleware('view_web_setting', 'website_routes');
      Route::get('/addwebpage', 'PagesController@addwebpage')->middleware('edit_web_setting', 'website_routes');
      Route::post('/addnewwebpage', 'PagesController@addnewwebpage')->middleware('edit_web_setting', 'website_routes');
      Route::get('/editwebpage/{id}', 'PagesController@editwebpage')->middleware('edit_web_setting', 'website_routes');
      Route::post('/updatewebpage', 'PagesController@updatewebpage')->middleware('edit_web_setting', 'website_routes');
      Route::get('/pageWebStatus', 'PagesController@pageWebStatus')->middleware('view_web_setting', 'website_routes');

      Route::get('/webthemes', 'SiteSettingController@webThemes')->middleware('view_web_setting', 'website_routes');
      Route::get('/themeSettings', 'SiteSettingController@themeSettings')->middleware('edit_web_setting', 'website_routes');

      Route::get('/seo', 'SiteSettingController@seo')->middleware('view_web_setting', 'website_routes');
      Route::get('/customstyle', 'SiteSettingController@customstyle')->middleware('view_web_setting', 'website_routes');
      Route::post('/updateWebTheme', 'SiteSettingController@updateWebTheme')->middleware('edit_web_setting', 'website_routes');
      Route::get('/websettings', 'SiteSettingController@webSettings')->middleware('view_web_setting', 'website_routes');


/////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
//////////////     GENERAL ROUTES
////////////////////////////////////////////////////////////////////////////////////

//units
      Route::get('/units', 'SiteSettingController@units')->middleware('view_general_setting');
      Route::get('/addunit', 'SiteSettingController@addunit')->middleware('edit_general_setting');
      Route::post('/addnewunit', 'SiteSettingController@addnewunit')->middleware('edit_general_setting');
      Route::get('/editunit/{id}', 'SiteSettingController@editunit')->middleware('edit_general_setting');
      Route::post('/updateunit', 'SiteSettingController@updateunit')->middleware('edit_general_setting');
      Route::post('/deleteunit', 'SiteSettingController@deleteunit')->middleware('edit_general_setting');


      Route::get('/orderstatus', 'SiteSettingController@orderstatus')->middleware('view_general_setting');
			Route::get('/addorderstatus', 'SiteSettingController@addorderstatus')->middleware('edit_general_setting');
			Route::post('/addNewOrderStatus', 'SiteSettingController@addNewOrderStatus')->middleware('edit_general_setting');
			Route::get('/editorderstatus/{id}', 'SiteSettingController@editorderstatus')->middleware('edit_general_setting');
			Route::post('/updateOrderStatus', 'SiteSettingController@updateOrderStatus')->middleware('edit_general_setting');
			Route::post('/deleteOrderStatus', 'SiteSettingController@deleteOrderStatus')->middleware('edit_general_setting');

      Route::get('/facebooksettings', 'SiteSettingController@facebookSettings')->middleware('website_routes');
      Route::get('/googlesettings', 'SiteSettingController@googleSettings')->middleware('website_routes');
      //pushNotification
			Route::get('/pushnotification', 'SiteSettingController@pushNotification')->middleware('view_general_setting');
      Route::get('/alertsetting', 'SiteSettingController@alertSetting')->middleware('view_general_setting');
			Route::post('/updateAlertSetting', 'SiteSettingController@updateAlertSetting');
      Route::get('/setting', 'SiteSettingController@setting')->middleware('edit_general_setting');
			Route::post('/updateSetting', 'SiteSettingController@updateSetting')->middleware('edit_general_setting');

      //admin managements
      Route::get('/admins', 'AdminController@admins')->middleware('view_manage_admin');
      Route::get('/addadmins', 'AdminController@addadmins')->middleware('add_manage_admin');
      Route::post('/addnewadmin', 'AdminController@addnewadmin')->middleware('add_manage_admin');
      Route::get('/editadmin/{id}', 'AdminController@editadmin')->middleware('edit_manage_admin');
      Route::post('/updateadmin', 'AdminController@updateadmin')->middleware('edit_manage_admin');
      Route::post('/deleteadmin', 'AdminController@deleteadmin')->middleware('delete_manage_admin');

      //admin managements
      Route::get('/manageroles', 'AdminController@manageroles')->middleware('manage_role');
      Route::get('/addrole/{id}', 'AdminController@addrole')->middleware('manage_role');
      Route::post('/addnewroles', 'AdminController@addnewroles')->middleware('manage_role');
      Route::get('/addadmintype', 'AdminController@addadmintype')->middleware('add_admin_type');
      Route::post('/addnewtype', 'AdminController@addnewtype')->middleware('add_admin_type');
      Route::get('/editadmintype/{id}', 'AdminController@editadmintype')->middleware('edit_admin_type');
      Route::post('/updatetype', 'AdminController@updatetype')->middleware('edit_admin_type');
      Route::post('/deleteadmintype', 'AdminController@deleteadmintype')->middleware('delete_admin_type');

      //merchant managements
      Route::get('/merchants', 'AdminController@merchants')->middleware('view_manage_merchant');
      Route::get('/addmerchants', 'AdminController@addmerchants')->middleware('add_manage_merchant');
      Route::post('/addnewmerchant', 'AdminController@addnewmerchant')->middleware('add_manage_merchant');
      Route::get('/editmerchant/{id}', 'AdminController@editmerchant')->middleware('edit_manage_merchant');
      Route::post('/updatemerchant', 'AdminController@updatemerchant')->middleware('edit_manage_merchant');
      Route::post('/deletemerchant', 'AdminController@deletemerchant')->middleware('delete_manage_merchant');
      //add branch against merchant
      Route::get('/merchants/branch/display/{id}/', 'AdminController@displaybranch')->middleware('add_car');
      Route::get('/merchants/addsaleadvisor', 'AdminController@addsaleadvisor');
      Route::get('/merchants/editsaleadvisor', 'AdminController@editsaleadvisor');
      Route::post('/merchants/addmerchantbranch', 'AdminController@addmerchantbranch')->middleware('add_car');
      Route::post('/merchants/editbranch', 'AdminController@editbranch')->middleware('add_car');
      Route::post('/merchants/updatebranch', 'AdminController@updatebranch')->middleware('add_car');
      Route::post('/merchants/deleteBranch', 'AdminController@deleteBranch')->middleware('add_car');
      Route::get('/merchants/copyCars', 'AdminController@copyMerchantCars')->name('copy_merchant_cars')->middleware('add_car');
 //    Route::post('/merchants/copyCars', 'AdminController@copyBranchCars')->name('copy_branch_cars')->middleware('add_car');
      Route::post('/merchants/deleteDocument', 'AdminController@deleteDocument');
      Route::get('/filtermerchant', 'AdminController@merchants');
      Route::get('/merchantsSegment', 'AdminController@merchants');
      Route::post('/merchants/deleteFile', 'AdminController@deleteFile');
      Route::post('/merchants/updateDocManager', 'AdminController@updateDocManager');

      //Organisation duplicate item
      Route::post('/duplicateItems', 'CarController@duplicateItem')->name('duplicate_item');

      // sales advisor
      Route::get('/saleAdvisor/{id}/', 'SaleAdvisorController@saleAdvisor');
      Route::get('/addsaleadvisor/{id}', 'SaleAdvisorController@addSaleAdvisor');
      Route::post('/insertsaleadvisor', 'SaleAdvisorController@insertSaleAdvisor');
      Route::get('/editsaleadvisor/{id}', 'SaleAdvisorController@editSaleAdvisor');
      Route::get('/editfiltersalesreport/{id}', 'SaleAdvisorController@editSaleAdvisor');
      Route::post('/updatesaleadvisor', 'SaleAdvisorController@updateSaleAdvisor');
      Route::get('/filtersaleAdvisor', 'SaleAdvisorController@saleAdvisor');

      Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

      // Promotion
      Route::get('/promotions', 'PromotionController@promotions');
      Route::get('/addpromotions', 'PromotionController@addpromotions');
      Route::get('/redeemlisting/{id}', 'PromotionController@redemptions');
      Route::post('/insertPromotions', 'PromotionController@insertPromotions');     
      Route::get('/editpromotion/{id}', 'PromotionController@editpromotions');
      Route::get('/editredemptions/{id}', 'PromotionController@editredemptions');
      Route::post('/updatepromotions', 'PromotionController@updatepromotions');
      Route::post('/deletepromotions', 'PromotionController@deletepromotions');
      Route::post('/deleteredeems', 'PromotionController@deleteredeems');
      Route::get('/editredeems/{id}', 'PromotionController@editredeems');
      Route::post('/updateredeem', 'PromotionController@updateredeem');
      Route::get('/emailtemplate', 'PromotionController@emailtemplate');
      Route::post('/updateemailtemplate', 'PromotionController@updateemailtemplate');

      Route::get('/filterpromotions', 'PromotionController@promotions');
      Route::get('/filterRedemption', 'PromotionController@redemptions');

      // Campaign
      Route::get('/campaigns', 'CampaignController@campaigns');
      Route::get('/changeStatus', 'CampaignController@changeStatus');
      Route::get('/syncCampaign', 'CampaignController@syncCampaign');
      Route::get('/addcampaigns', 'CampaignController@addcampaigns');
      Route::post('/insertcampaigns', 'CampaignController@insertcampaigns');     
      Route::get('/editcampaigns/{id}', 'CampaignController@editcampaigns');
      Route::post('/updatecampaigns', 'CampaignController@updatecampaigns');
      Route::post('/deletecampaigns', 'CampaignController@deletecampaigns');
      Route::get('/filtercampaign', 'CampaignController@campaigns');
      Route::get('/campaignemailtemplate', 'CampaignController@campaignemailtemplate');
      Route::post('/updatecampaignemailtemplate', 'CampaignController@updatecampaignemailtemplate');

      // Reports
      Route::get('/organisationreport', 'ReportController@organisationreport');
      Route::get('/filterorganisationreport', 'ReportController@organisationreport');
      Route::get('/organisationreport/{org_id}', 'ReportController@organisationreport');

      Route::get('/promotionreport', 'ReportController@promotionreport');
      Route::get('/filterpromotionreport', 'ReportController@promotionreport');

      Route::get('/itemreport', 'ReportController@itemreport');
      Route::get('/filteritemreport', 'ReportController@itemreport');

      Route::get('/salesreport', 'ReportController@salesreport');
      Route::get('/filtersalesreport', 'ReportController@salesreport');

      Route::get('/campaignreport', 'ReportController@campaignreport');
      Route::get('/filtercampaignreport', 'ReportController@campaignreport');

      Route::get('/campaignfullreport', 'ReportController@campaignfullreport');
      Route::get('/filtercampaignfullreport', 'ReportController@campaignfullreport');

      Route::get('/campaignfullreportorg', 'ReportController@campaignfullreportorg');
      Route::get('/filtercampaignfullreportorg', 'ReportController@campaignfullreportorg');

      Route::get('/campaignfullreportsa', 'ReportController@campaignfullreportsa');
      Route::get('/filtercampaignfullreportsa', 'ReportController@campaignfullreportsa');

      Route::get('/campaignsresponse', 'ReportController@campaignsresponse');
      Route::get('/filtercampaignsresponse', 'ReportController@campaignsresponse');
      
      // template management
      Route::get('/templates', 'AdminController@templates');
      Route::get('/addtemplates', 'AdminController@addtemplates');
      Route::post('/insertTemplate', 'AdminController@insertTemplate');
      Route::get('/edittemplate/{id}', 'AdminController@edittemplate');
      Route::post('/updateTemplate', 'AdminController@updateTemplate');
      Route::post('/deleteTemplate', 'AdminController@deleteTemplate');

      // bank managment
      Route::get('/banks', 'BankController@banks');
      Route::get('/addbanks', 'BankController@addbanks');
      Route::post('/insertBanks', 'BankController@insertBanks');
      Route::get('/editbanks/{id}', 'BankController@editbanks');
      Route::post('/updateBanks', 'BankController@updateBanks');
      Route::post('/deleteBank', 'BankController@deleteBank');
      Route::get('/filterBank', 'BankController@filter');

      // segment managment
      Route::get('/segments', 'SegmentController@segments');
      Route::get('/addsegments', 'SegmentController@addsegments');
      Route::post('/insertSegments', 'SegmentController@insertSegments');
      Route::get('/editsegments/{id}', 'SegmentController@editsegments');
      Route::post('/updateSegments', 'SegmentController@updateSegments');
      Route::post('/deletesegments', 'SegmentController@deleteSegment');
      Route::get('/filterSegment', 'SegmentController@filter');

      //manager managements
      Route::get('/managers', 'AdminController@managers')->middleware('view_manage_manager');
      Route::get('/addmanagers', 'AdminController@addmanagers')->middleware('add_manage_manager');
      Route::post('/addnewmanager', 'AdminController@addnewmanager')->middleware('add_manage_manager');
      Route::get('/editmanager/{id}', 'AdminController@editmanager')->middleware('edit_manage_manager');
      Route::post('/updatemanager', 'AdminController@updatemanager')->middleware('edit_manage_manager');
      Route::post('/deletemanager', 'AdminController@deletemanager')->middleware('delete_manage_manager');
      // agent
      Route::get('/agent', 'AdminController@agents')->middleware('view_agent_manager');
      Route::get('/add-agent', 'AdminController@addAgent')->middleware('add_agent_manager');
      Route::post('/add-agent', 'AdminController@addAgent')->middleware('add_agent_manager');
      Route::get('/edit-agent/{id}', 'AdminController@editAgent')->middleware('edit_agent_manager');
      Route::post('/update-agent', 'AdminController@updateAgent')->middleware('edit_agent_manager');
      Route::post('/delete-agent', 'AdminController@deleteAgent')->middleware('delete_agent_manager');

      //Car Statistics report
      Route::get('statistics','CarsStatisticsController@index')->name('car_statistics');

    });

    Route::group(['prefix'=>'admin/managements','middleware' => 'auth','namespace' => 'AdminControllers'], function () {
      Route::get('/merge', 'ManagementsController@merge')->middleware('edit_management');
			Route::get('/backup', 'ManagementsController@backup')->middleware('edit_management');
			Route::post('/take_backup', 'ManagementsController@take_backup')->middleware('edit_management');
			Route::get('/import', 'ManagementsController@import')->middleware('edit_management');
			Route::post('/importdata', 'ManagementsController@importdata')->middleware('edit_management');
      Route::post('/mergecontent', 'ManagementsController@mergecontent')->middleware('edit_management');
      Route::get('/updater', 'ManagementsController@updater')->middleware('edit_management');
      Route::post('/checkpassword', 'ManagementsController@checkpassword')->middleware('edit_management');
      Route::post('/updatercontent', 'ManagementsController@updatercontent')->middleware('edit_management');
    });

  // sa route
  Route::group(['namespace' => 'AdminControllers','prefix' => 'sale_advisor'], function()
  {
    Route::get('/login', 'SaleAdvisorController@login')->name('sa_login');
    Route::post('/checkLogin', 'SaleAdvisorController@checkLogin');
    // Route::get('/user/verify/{token}', 'AdminController@verifyUser');
    Route::get('/logout', 'SaleAdvisorController@logout');
  });
  Route::group(['namespace' => 'AdminControllers','middleware' => 'auth:saleadvisor'], function()
  {
    Route::get('admin/sale_advisors/dashboard', 'SaleAdvisorController@campaignreport');

    // Campaign
    Route::get('admin/sale_advisors/campaigns', 'SaleAdvisorController@campaigns');
    Route::get('admin/sale_advisors/addcampaigns', 'SaleAdvisorController@addcampaigns');
    Route::post('admin/sale_advisors/insertcampaigns', 'SaleAdvisorController@insertcampaigns');     
    Route::get('admin/sale_advisors/editcampaigns/{id}', 'SaleAdvisorController@editcampaigns');
    Route::post('admin/sale_advisors/updatecampaigns', 'SaleAdvisorController@updatecampaigns');
    Route::post('admin/sale_advisors/deletecampaigns', 'SaleAdvisorController@deletecampaigns');
    Route::get('admin/sale_advisors/filtercampaign', 'SaleAdvisorController@campaigns');
    Route::post('admin/sale_advisors/{renew}/request/{id}', 'SaleAdvisorController@changeVerification');

    // Campaign report
    Route::get('admin/sale_advisors/campaignreport', 'SaleAdvisorController@campaignreport');
    Route::get('admin/sale_advisors/filtercampaignreport', 'SaleAdvisorController@campaignreport');
    Route::get('admin/sale_advisors/campaignfullreport', 'SaleAdvisorController@campaignfullreport');
    Route::get('admin/sale_advisors/filtercampaignfullreport', 'SaleAdvisorController@campaignfullreport');
    Route::get('admin/sale_advisors/campaignsresponse', 'SaleAdvisorController@campaignsresponse');
    Route::get('admin/sale_advisors/filtercampaignsresponse', 'SaleAdvisorController@campaignsresponse');

    // report
    Route::get('admin/sale_advisors/salesreport', 'SaleAdvisorController@salesreport');
    Route::get('admin/sale_advisors/filtersalesreport', 'SaleAdvisorController@salesreport');
    Route::get('admin/sale_advisors/promotionreport', 'SaleAdvisorController@promotionreport');
    Route::get('admin/sale_advisors/filterpromotionreport', 'SaleAdvisorController@promotionreport');
    Route::get('admin/sale_advisors/itemreport', 'SaleAdvisorController@itemreport');
    Route::get('admin/sale_advisors/filteritemreport', 'SaleAdvisorController@itemreport');
  });

});

Route::group(['namespace' => 'AdminControllers','prefix' => 'agent'], function()
{
   Route::get('login', 'AgentController@login');
   Route::get('logout', 'AgentController@logout');
    Route::post('checkLogin', 'AgentController@checkLogin');
   Route::get('dashboard', 'AgentController@dashboard');
   Route::get('sales-advisor', 'AgentController@salesAdvisorList');
   Route::get('sales-advisor-add', 'AgentController@addSalesAdvisor');
   Route::post('sales-advisor-insert', 'AgentController@insertSaleAdvisor');
    Route::get('/sales-advisor-edit/{id}', 'AgentController@editSaleAdvisor');
    Route::post('/sales-advisor-update', 'AgentController@updateSaleAdvisor');
    Route::post('/sales-advisor-delete', 'AgentController@deleteBranch');
    Route::get('/filtersaleAdvisor', 'AgentController@saleAdvisor');
});
