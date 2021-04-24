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
        Route::get('/orderheader/makepdf/{id}', 'OrderHeaderController@makePDF')->name('makepdf');
        Route::get('/orderheader/preview/{id}', 'OrderHeaderController@preview')->name('preview');
        Route::get('/waybill/makepdf/{id}', 'WaybillController@makePDF')->name('makepdf');
        Route::get('/waybill/preview/{id}', 'WaybillController@preview')->name('preview');
        Route::get('/waybill/waybillbydate', 'WaybillController@waybillbydate')->name('waybillbydate');
        Route::get('/waybill/waybillbydatepreview', 'WaybillController@waybillbydatePreview')->name('preview');

        Route::get('/dropship/makepdf/{id}', 'DropshipController@makePDF')->name('makepdf');
        Route::get('/dropship/preview/{id}', 'DropshipController@preview')->name('preview');

        Route::get('/delivery/makepdf/{id}', 'DeliveryController@makePDF')->name('makepdf');
        Route::get('/delivery/preview/{id}', 'DeliveryController@preview')->name('preview');
    });
