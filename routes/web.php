<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    dd('su doi lan 11');
//    return view('welcome');
});
Route::get('/dvtinh', function () {
    dd('su doi lan 12');
//    return view('welcome');
});
Route::get('test-jenkin-plus/{a}', function($a){
	dd($a + 1);
});
