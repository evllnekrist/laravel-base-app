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
Route::get('/', function () {
    return view('_page._main.dashboard');
});
Route::get('/home', function () {
    return view('_page._main.dashboard');
});
Route::get('/login', function () {
    return view('_page._auth.login');
});

/*--------------------------------------------------------------------------START--*/ 
    Route::group(['prefix' => 'master'], function()
    {
        Route::prefix('users')->group(function(){
            Route::get('/','Master\UsersController@index');
            Route::get('add','Master\UsersController@add');
            Route::post('doAdd','Master\UsersController@doAdd');
            Route::post('doEdit','Master\UsersController@doEdit');
            Route::post('delete','Master\UsersController@delete');
            Route::get('detail/{id}','Master\UsersController@detail');
        });
        Route::prefix('role-menu')->group(function(){
            Route::get('/','Master\RoleMenuController@index');
            Route::get('mapping/{id}','Master\RoleMenuController@mapping');
            Route::post('doMap','Master\RoleMenuController@doMap');
            Route::post('doEdit','Master\RoleMenuController@doEdit');
            Route::post('delete','Master\RoleMenuController@delete');
            Route::get('detail/{id}','Master\RoleMenuController@detail');
        });
        Route::prefix('role')->group(function(){
            Route::get('/','Master\RoleController@index');
            Route::get('add','Master\RoleController@add');
            Route::post('doAdd','Master\RoleController@doAdd');
            Route::post('doEdit','Master\RoleController@doEdit');
            Route::post('delete','Master\RoleController@delete');
            Route::get('detail/{id}','Master\RoleController@detail');
        });
        Route::prefix('menu')->group(function(){
            Route::get('/','Master\MenuController@index');
            Route::get('add','Master\MenuController@add');
            Route::post('doAdd','Master\MenuController@doAdd');
            Route::post('doEdit','Master\MenuController@doEdit');
            Route::post('delete','Master\MenuController@delete');
            Route::get('detail/{id}','Master\MenuController@detail');
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