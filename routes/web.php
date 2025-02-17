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

Route::get('/','loginController@viewLogin')->name('home');



Route::get('complete-order', 'completeOrderController@viewOrder')->middleware('login')->name('dong don');

Route::get('show-complete-order', 'completeOrderController@viewShowOrder')->middleware('login')->name('tracking');

Route::get('post-order', 'completeOrderController@post_complete_order')->middleware('login')->name('post order');

Route::get('logout', 'loginController@logout')->name('logout');

Route::get('load-packed', 'completeOrderController@get_data_for_ajax_page')->name('auto_packed_load');



Route::get('show-data-api', 'completeOrderController@get_data_to_pm')->middleware('login');





Route::get('find-user-packaging', 'completeOrderController@SearchDataOfUser')->name('user.packaging');




Route::get('api/show-qualtity', 'sheetApiController@showAPi');

Route::get('test-api', 'sheetApiController@getdataQuantity');

Route::get('test-api2', 'sheetApiController@showComboCodePD');

Route::get('test-api3', 'sheetApiController@show_data_sale');



Route::get('test-api-1', 'sheetApiController@getdataQuantityMN');

Route::get('run-quantity', 'sheetApiController@runQuantity');

Route::get('tracking', 'sheetApiController@getPDTran');

Route::get('update-shop', 'sheetApiController@updateShop');

Route::get('update-id-product', 'sheetApiController@convertIDtoModel');

Route::get('get-data-to-sheet', 'sheetApiController@runPriceToDrive');


Route::group(['prefix' => 'admins'], function() {

    Route::post('get-data-to-sheet', 'sheetApiController@runPriceToDrive')->name('get_data_to_sheet');

    Route::get('kho', 'khoController@index')->name('kho.admin');

    Route::get('order-data', 'Backend\orderController@getdata');



});    


Route::get('get-price', 'sheetApiController@getPrice');

Route::get('crawl-site-tinh-te', 'crawlController@getLink');

Route::get('update-crawl-post', 'crawlController@crawPostTinhTe');

Route::get('list-crawl-post', 'crawlController@viewListCrawl');

Route::get('blog-detail/{id}', 'crawlController@viewDetail')->name('details');

Route::get('/table', 'sheetApiController@getPrice')->name('table');

Route::get('table-post-crawl', 'crawlController@viewListCrawl');

Route::post('update-price-all', 'sheetApiController@updateDataSheetPrice')->name('update-price-all');

Route::post('check-product', 'sheetApiController@checkproduct')->name('checkproduct');

Route::post('post-login', 'adminController@loginAdminUser')->name('post-login-admin');

Route::get('show-data', 'sheetApiController@showDataUpdate');

Route::get('view-qualtily', 'sheetApiController@viewQualtity'); //đẩy dữ liệu qty từ sheet vào db để api lấy dữ liệu

Route::get('show-qualtity', 'sheetApiController@showQualtity');

Route::get('show-model-pd', 'sheetApiController@getModelsToSheet');

// Route::get('crawl-web-site', 'sheetApiController@crawl_web');

// Route::get('crawl-dien-may-xanh', 'sheetApiController@CrawlDienMayxanh');

Route::get('add-detail-product', 'detailsShopOrderController@showIndexDetail');

Route::post('import-container-cost', 'detailsShopOrderController@insertContainerCost')->name('insert-container-cost');

Route::get('container-cost', 'detailsShopOrderController@showIndexContainerCost')->name('container-cost');

Route::post('insert-south', 'detailsShopOrderController@storeDetailsSouth')->name('insert-south');

Route::post('insert-north', 'detailsShopOrderController@storeDetailsNorth')->name('insert-north');

Route::post('login', 'loginController@login')->name('login');

Route::get('login', 'loginController@index')->name('view-login');

Route::get('admin', 'loginController@view_admin')->name('admin-view');

Route::resource('import-quantity', 'importQuantityController');

Route::resource('small-price', 'smallPriceController');

Route::resource('detail-Shop-Order', 'detailsShopOrderController');

