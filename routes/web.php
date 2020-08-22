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

Route::get('/laravel', function () {
    return view('welcome');
});
Route::get('/', 'Auth\LoginController@index');
Route::get('/login', 'Auth\LoginController@index')->name('login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/login/do', 'Auth\LoginController@ajax_doLogin')->name('doLogin');
Route::get('/home', function () {
    return view('_page._main.dashboard');
});

/*--------------------------------------------------------------------------START--*/ 
    Route::group(['prefix' => 'master'], function()
    {
        Route::prefix('users')->group(function(){
            Route::get('/','Master\UsersController@index')->name('master-users');
            Route::get('get','Master\UsersController@get');
            Route::post('doAdd','Master\UsersController@doAdd');
            Route::post('doEdit','Master\UsersController@doEdit');
            Route::delete('{id}/delete','Master\UsersController@delete');
            Route::get('detailAdd','Master\UsersController@detailAdd');
            Route::get('{id}/detailEdit','Master\UsersController@detailEdit');
        });
        Route::prefix('role')->group(function(){
            Route::get('/','Master\RoleController@index')->name('master-role');
            Route::get('get','Master\RoleController@get');
            Route::post('doAdd','Master\RoleController@doAdd');
            Route::post('doEdit','Master\RoleController@doEdit');
            Route::delete('{id}/delete','Master\RoleController@delete');
            Route::get('detailAdd','Master\RoleController@detailAdd');
            Route::get('{id}/detailEdit','Master\RoleController@detailEdit');
        });
        Route::prefix('menu')->group(function(){
            Route::get('/','Master\MenuController@index')->name('master-menu');
            Route::get('get','Master\MenuController@get');
            Route::post('doAdd','Master\MenuController@doAdd');
            Route::post('doEdit','Master\MenuController@doEdit');
            Route::delete('{id}/delete','Master\MenuController@delete');
            Route::get('detailAdd','Master\MenuController@detailAdd');
            Route::get('{id}/detailEdit','Master\MenuController@detailEdit');
        });
        Route::prefix('role-menu')->group(function(){
            Route::get('/','Master\RoleMenuController@index')->name('master-role-menu');
            Route::get('get','Master\RoleMenuController@get');
            Route::post('doEdit','Master\RoleMenuController@doEdit');
            Route::get('{id}/detailEdit','Master\RoleMenuController@detailEdit');
        });
    });
    
    Route::group(['prefix' => 'statistics'], function()
    {
    });

    Route::group(['prefix' => 'sample'], function()
    {
        Route::get('/charts', function () {
            return view('_page.samplePage.sample-charts');
        });
        Route::get('/product', function () {
            return view('_page.samplePage.sample-product');
        });
        Route::get('/category', function () {
            return view('_page.samplePage.sample-category');
        });
        Route::get('/list', function () {
            return view('_page.samplePage.sample-list');
        });
        Route::get('/list-datatable', function () {
            return view('_page.samplePage.sample-list-datatable');
        });
    });
/*--------------------------------------------------------------------------END----*/ 