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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



    Route::name('files.')->group(function (){
    Route::get('files', 'FileController@index')->name('index');
    Route::get('files/upload', 'FileController@create')->name('create');
    Route::post('files', 'FileController@store')->name('upload')->middleware('auth');
    Route::get('files/{file}', 'FileController@show')->name('show');
    Route::get('files/{file}/download','FileController@download')->name('download');
    Route::delete('files/{file}/delete', 'FileController@destroy')->name('delete');
    });
