<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;


//Route::view('/neworder','neworder')->name('neworder');
//Route::view('/neworder-success','neworder-success')->name('neworder.success');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/order-tracking', function () {
    return view('partials.tracking');
});
Route::get('/service-price', function () {
    return view('partials.serviceprice');
});

Route::get('/service-area', function () {
    return view('partials.servicearea');
});
Route::get('/branchlist', function () {
    return view('partials.branchlist');
});
Route::middleware('web')
    ->namespace('\\App\\Http\\Controllers\\')
    ->group(function () {

        //Route::get('/test', 'TestController@test');
        Route::get('pages/{slug}', array('as' => 'page.show', 'uses' => 'PagesController@show'));
        Route::get('pages', array('as' => 'page.about', 'uses' => 'PagesController@about'));
        Route::any('/blogs', 'BlogController@index');
        Route::get('/blogs/{slug}', 'BlogController@show');
    });


Route::middleware('web', 'auth')
    ->namespace('\\App\\Http\\Controllers\\')
    ->group(function () {

        Route::get('/quotation/preview/{id}', 'QuotationController@preview')->name('preview'); //For test
        Route::get('/quotation/makepdf/{id}', 'QuotationController@makePDF')->name('makepdf');
        Route::get('/charterjob/preview/{id}', 'CharterJobController@preview')->name('preview'); //for test
        Route::get('/charterjob/makepdf/{id}', 'CharterJobController@makePDF')->name('makepdf');
        //order_header
        Route::get('/orderheader/makepdf/{id}', 'OrderHeaderController@makePDF')->name('makepdf');
        Route::get('/orderheader/preview/{id}', 'OrderHeaderController@preview')->name('preview');
        //report
        Route::get('/orderheader/report_1/{branch}/{orderdate}', 'OrderHeaderController@report_1')->name('report_1');
        Route::get('/orderheader/report_2/{branch}/{from}/{to}/{cancelflag}', 'OrderHeaderController@report_2')->name('report_2');
        Route::get('/orderheader/report_2s/{branch}/{from}/{to}/{cancelflag}', 'OrderHeaderController@report_2s')->name('report_2s');

        Route::get('/orderheader/report_3/{branch}/{from}/{to}', 'OrderHeaderController@report_3')->name('report_3');
        Route::get('/orderheader/report_4/{branch}/{from}/{to}', 'OrderHeaderController@report_4')->name('report_4');
        Route::get('/orderheader/report_4m/{branch}/{month}/{year}', 'OrderHeaderController@report_4m')->name('report_4m');

        Route::get('/orderheader/report_5/{branch}/{from}/{to}', 'OrderHeaderController@report_5')->name('report_5');
        Route::get('/orderheader/report_6/{branch}/{paytype}/{from}/{to}/{cancelflag}', 'OrderHeaderController@report_6')->name('report_6');
        Route::get('/orderheader/report_7/{branch}/{paytype}/{from}/{to}/{cancelflag}', 'OrderHeaderController@report_7')->name('report_7');
        Route::get('/orderheader/report_8/{branch}/{from}/{to}/{artype}/{cancelflag}', 'OrderHeaderController@report_8')->name('report_8');
        Route::get('/orderheader/report_9/{branch}/{from}/{to}/{artype}/{cencelflag}', 'OrderHeaderController@report_9')->name('report_9');


        Route::get('/waybill/makepdf/{id}', 'WaybillController@makePDF')->name('makepdf');
        Route::get('/waybill/preview/{id}', 'WaybillController@preview')->name('preview');
        //waybill report
        Route::get('/waybill/report_10/{routetobranch}/{from}/{to}', 'WaybillController@report_10')->name('waybillbydate');
        Route::get('/waybill/report_w1/{routetobranch}/{from}/{to}', 'WaybillController@report_w1')->name('waybillbydate2');

        Route::get('/dropship/makepdf/{id}', 'DropshipController@makePDF')->name('makepdf');
        Route::get('/dropship/preview/{id}', 'DropshipController@preview')->name('preview');

        //Route::get('/delivery/makepdf/{id}', 'DeliveryController@makePDF')->name('makepdf');
        Route::get('/delivery/preview/{id}', 'DeliveryController@preview')->name('preview');

        //car
        Route::get('/car/carpaymentprint/{item}', 'CarController@carpaymentprint')->name('carpaymentprint');
        //Route::post('/car/carpaymentprint/{items}', 'CarController@carpaymentprint')->name('carpaymentprint');
        Route::get('/car/carreceiveprint/{id}', 'CarController@carreceiveprint')->name('carreceivetprint');
        Route::get('/car/report_11/{from}/{to}/{type}', 'CarController@report_11')->name('report_11');
        Route::get('/car/report_12/{from}/{to}', 'CarController@report_12')->name('report_12');
        Route::get('/car/report_13/{car}/{from}/{to}', 'CarController@report_13')->name('report_13');
        Route::get('/car/report_14/{owner}/{from}/{to}', 'CarController@report_14')->name('report_14');
        Route::get('/car/report_15/{to}', 'CarController@report_15')->name('report_15');
        Route::get('/car/report_23/{from}/{to}', 'CarController@report_23')->name('report_23');
        Route::get('/car/report_24/{from}/{to}/{type}', 'CarController@report_24')->name('report_24');
        Route::get('/car/report_c25/{from}/{to}/{branch}', 'CarController@report_c25')->name('report_c25');

        //ar
        Route::get('/invoice/preview/{id}', 'InvoiceController@preview')->name('preview');
        Route::get('/receipt/preview/{id}', 'ReceiptController@preview')->name('preview');
        Route::get('/ar/report_16/{customer}/{from}/{to}', 'ArController@report_16')->name('report_16');
        Route::get('/ar/report_17/{branch}/{from}/{to}', 'ArController@report_17')->name('report_17');
        Route::get('/ar/report_18/{branch}/{from}/{to}', 'ArController@report_18')->name('report_18');
        Route::get('/ar/report_19/{branch}/{customer}/{from}/{to}', 'ArController@report_19')->name('report_19');

        //Branch
        Route::get('/ar/report_20/{branch}/{from}/{to}', 'BranchController@report_20')->name('report_20');
        Route::get('/ar/report_21/{branch}/{from}/{to}', 'BranchController@report_21')->name('report_21');
        Route::get('/ar/report_22/{branch}/{to}', 'BranchController@report_22')->name('report_22');

        //banktransfer
        Route::get('/orderheader/report_t1/{from}', 'OrderHeaderController@report_t1')->name('report_t1');
        //สินค้าค้างส่ง
        Route::get('/orderheader/report_s1/{branch}/{to}', 'OrderHeaderController@report_s1')->name('report_s1');
    });
