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
        Route::get('/orderheader/report_8/{branch}/{from}/{to}', 'OrderHeaderController@report_8')->name('report_8');
        Route::get('/orderheader/report_9/{branch}/{from}/{to}', 'OrderHeaderController@report_9')->name('report_9');


        Route::get('/waybill/makepdf/{id}', 'WaybillController@makePDF')->name('makepdf');
        Route::get('/waybill/preview/{id}', 'WaybillController@preview')->name('preview');
        //waybill report
        Route::get('/waybill/report_10/{from}/{to}', 'WaybillController@report_10')->name('waybillbydate');

        Route::get('/dropship/makepdf/{id}', 'DropshipController@makePDF')->name('makepdf');
        Route::get('/dropship/preview/{id}', 'DropshipController@preview')->name('preview');

        Route::get('/delivery/makepdf/{id}', 'DeliveryController@makePDF')->name('makepdf');
        Route::get('/delivery/preview/{id}', 'DeliveryController@preview')->name('preview');

        //car
        Route::get('/car/carpaymentprint/{id}', 'CarController@carpaymentprint')->name('carpaymentprint');
        Route::get('/car/carreceiveprint/{id}', 'CarController@carreceiveprint')->name('carreceivetprint');
        Route::get('/car/report_11/{from}/{to}/{type}', 'CarController@report_11')->name('report_11');
        Route::get('/car/report_12/{from}/{to}', 'CarController@report_12')->name('report_12');
    });
