<?php

use Illuminate\Support\Facades\Route;

Route::auth();
Route::get('/', function () {
    return view('welcome');
});

//Memeber page : /
Route::get('/member', 'HomeController@index');



//Pages
Route::get('pages/{slug}', array('as' => 'page.show', 'uses' => 'PagesController@show'));
Route::get('pages', array('as' => 'page.about', 'uses' => 'PagesController@about'));

Route::middleware('web', 'auth')
    ->namespace('\\App\\Http\\Controllers\\')
    ->group(function () {



        Route::get('/quotation/preview/{id}', 'QuotationController@preview')->name('preview'); //For test
        Route::get('/quotation/makepdf/{id}', 'QuotationController@makePDF')->name('makepdf');
        Route::get('/charterjob/preview/{id}', 'CharterJobController@preview')->name('preview'); //for test
        Route::get('/charterjob/makepdf/{id}', 'CharterJobController@makePDF')->name('makepdf');
    });
