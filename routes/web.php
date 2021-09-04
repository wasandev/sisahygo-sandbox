<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

//Route::auth();
Route::get('/', function () {
    return view('welcome');
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
// Route::any('/blogs', 'BlogController@index');
// Route::get('/blogs/{slug}', 'BlogController@show');
// //Pages
// Route::get('pages/{slug}', array('as' => 'page.show', 'uses' => 'PagesController@show'));
// Route::get('pages', array('as' => 'page.about', 'uses' => 'PagesController@about'));

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
        Route::get('/orderheader/report_3/{branch}/{from}/{to}', 'OrderHeaderController@report_3')->name('report_3');
        Route::get('/orderheader/report_4/{branch}/{from}/{to}', 'OrderHeaderController@report_4')->name('report_4');
        Route::get('/orderheader/report_5/{branch}/{from}/{to}', 'OrderHeaderController@report_5')->name('report_5');
        Route::get('/orderheader/report_6/{branch}/{from}/{to}', 'OrderHeaderController@report_6')->name('report_6');
        Route::get('/orderheader/report_7/{branch}/{from}/{to}', 'OrderHeaderController@report_7')->name('report_7');
        Route::get('/orderheader/report_8/{branch}/{from}/{to}/{artype}', 'OrderHeaderController@report_8')->name('report_8');
        Route::get('/orderheader/report_9/{branch}/{from}/{to}/{artype}', 'OrderHeaderController@report_9')->name('report_9');


        Route::get('/waybill/makepdf/{id}', 'WaybillController@makePDF')->name('makepdf');
        Route::get('/waybill/preview/{id}', 'WaybillController@preview')->name('preview');
        //waybill report
        Route::get('/waybill/report_10/{from}/{to}', 'WaybillController@report_10')->name('waybillbydate');

        Route::get('/dropship/makepdf/{id}', 'DropshipController@makePDF')->name('makepdf');
        Route::get('/dropship/preview/{id}', 'DropshipController@preview')->name('preview');

        //Route::get('/delivery/makepdf/{id}', 'DeliveryController@makePDF')->name('makepdf');
        Route::get('/delivery/preview/{id}', 'DeliveryController@preview')->name('preview');

        //car
        Route::get('/car/carpaymentprint/{id}', 'CarController@carpaymentprint')->name('carpaymentprint');
        Route::get('/car/carreceiveprint/{id}', 'CarController@carreceiveprint')->name('carreceivetprint');
        Route::get('/car/report_11/{from}/{to}/{type}', 'CarController@report_11')->name('report_11');
        Route::get('/car/report_12/{from}/{to}', 'CarController@report_12')->name('report_12');
        Route::get('/car/report_13/{car}/{from}/{to}', 'CarController@report_13')->name('report_13');
        Route::get('/car/report_14/{owner}/{from}/{to}', 'CarController@report_14')->name('report_14');
        Route::get('/car/report_15/{to}', 'CarController@report_15')->name('report_15');
        Route::get('/car/printwhtaxform/{id}/{from}/{to}', 'CarController@printwhtaxform')->name('printwhtaxform');

        //ar
        Route::get('/invoice/preview/{id}', 'InvoiceController@preview')->name('preview');
        Route::get('/receipt/preview/{id}', 'ReceiptController@preview')->name('preview');
        Route::get('/ar/report_16/{customer}/{from}/{to}', 'ArController@report_16')->name('report_16');
        Route::get('/ar/report_17/{from}/{to}', 'ArController@report_17')->name('report_17');
        Route::get('/ar/report_18/{from}/{to}', 'ArController@report_18')->name('report_18');
        Route::get('/ar/report_19/{cutomer}/{from}/{to}', 'ArController@report_19')->name('report_19');

        //Branch
        Route::get('/ar/report_20/{branch}/{from}/{to}', 'BranchController@report_20')->name('report_20');
        Route::get('/ar/report_21/{branch}/{from}/{to}', 'BranchController@report_21')->name('report_21');
        Route::get('/ar/report_22/{branch}/{to}', 'BranchController@report_22')->name('report_22');
    });
