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
Route::get('/home', 'Main\DashboardController@index');

/*--------------------------------------------------------------------------START--*/ 

    // MEMBERSHIP
    Route::group(['prefix' => 'membership'], function()
    {
        Route::get('/','Main\MembershipController@index')->name('membership');
        Route::get('get','Main\MembershipController@get');
        Route::post('doAdd','Main\MembershipController@doAdd');
        Route::post('doEdit','Main\MembershipController@doEdit');
        Route::delete('{id}/delete','Main\MembershipController@delete');
        Route::get('detailAdd','Main\MembershipController@detailAdd');
        Route::get('{id}/detailEdit','Main\MembershipController@detailEdit');
        Route::get('genCardId','Main\MembershipController@generateCardId');
    });
    // CARD
    Route::group(['prefix' => 'card'], function(){
        Route::get('{id}/pdf','Main\CardController@pdf');
    });
    // ACTIVITY
    Route::group(['prefix' => 'activity'], function()
    {
        Route::get('/','Main\ActivityController@index')->name('activity');
        Route::get('get','Main\ActivityController@get');
        Route::post('doAdd','Main\ActivityController@doAdd');
        Route::post('doEdit','Main\ActivityController@doEdit');
        Route::delete('{id}/delete','Main\ActivityController@delete');
        Route::get('detailAdd','Main\ActivityController@detailAdd');
        Route::get('{id}/detailEdit','Main\ActivityController@detailEdit');
    });
    // SCAN
    Route::group(['prefix' => 'scan'], function()
    {
        Route::get('/','Main\ScanController@index');
        Route::get('get','Main\ScanController@get');
        Route::post('doAdd','Main\ScanController@doAdd');
    });
    // MASTER
    Route::group(['prefix' => 'master'], function()
    {
        Route::group(['prefix' => 'app'], function()
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
        
        Route::group(['prefix' => 'data'], function()
        {
            Route::prefix('company')->group(function(){
                Route::get('/','Master\CompanyController@index')->name('master-company');
                Route::get('get','Master\CompanyController@get');
                Route::post('doAdd','Master\CompanyController@doAdd');
                Route::post('doEdit','Master\CompanyController@doEdit');
                Route::delete('{id}/delete','Master\CompanyController@delete');
                Route::get('detailAdd','Master\CompanyController@detailAdd');
                Route::get('{id}/detailEdit','Master\CompanyController@detailEdit');
            });
            Route::prefix('site')->group(function(){
                Route::get('/','Master\SiteController@index')->name('master-site');
                Route::get('get','Master\SiteController@get');
                Route::post('doAdd','Master\SiteController@doAdd');
                Route::post('doEdit','Master\SiteController@doEdit');
                Route::delete('{id}/delete','Master\SiteController@delete');
                Route::get('detailAdd','Master\SiteController@detailAdd');
                Route::get('{id}/detailEdit','Master\SiteController@detailEdit');
            });
            Route::prefix('package')->group(function(){
                Route::get('/','Master\PackageController@index')->name('master-package');
                Route::get('get','Master\PackageController@get');
                Route::post('doAdd','Master\PackageController@doAdd');
                Route::post('doEdit','Master\PackageController@doEdit');
                Route::delete('{id}/delete','Master\PackageController@delete');
                Route::get('detailAdd','Master\PackageController@detailAdd');
                Route::get('{id}/detailEdit','Master\PackageController@detailEdit');
            });
            Route::prefix('member-role')->group(function(){
                Route::get('/','Master\MemberRoleController@index')->name('master-member-role');
                Route::get('get','Master\MemberRoleController@get');
                Route::post('doAdd','Master\MemberRoleController@doAdd');
                Route::post('doEdit','Master\MemberRoleController@doEdit');
                Route::delete('{id}/delete','Master\MemberRoleController@delete');
                Route::get('detailAdd','Master\MemberRoleController@detailAdd');
                Route::get('{id}/detailEdit','Master\MemberRoleController@detailEdit');
            });
            Route::prefix('province')->group(function(){
                Route::get('/','Master\ProvinceController@index')->name('master-province');
                Route::get('get','Master\ProvinceController@get');
                Route::post('doAdd','Master\ProvinceController@doAdd');
                Route::post('doEdit','Master\ProvinceController@doEdit');
                Route::delete('{id}/delete','Master\ProvinceController@delete');
                Route::get('detailAdd','Master\ProvinceController@detailAdd');
                Route::get('{id}/detailEdit','Master\ProvinceController@detailEdit');
            });
            Route::prefix('regency')->group(function(){
                Route::get('/','Master\RegencyController@index')->name('master-regency');
                Route::get('get','Master\RegencyController@get');
                Route::post('doAdd','Master\RegencyController@doAdd');
                Route::post('doEdit','Master\RegencyController@doEdit');
                Route::delete('{id}/delete','Master\RegencyController@delete');
                Route::get('detailAdd','Master\RegencyController@detailAdd');
                Route::get('{id}/detailEdit','Master\RegencyController@detailEdit');
                Route::get('{id}/detailRegency','Master\RegencyController@detailRegency');
            });
            Route::prefix('district')->group(function(){
                Route::get('/','Master\DistrictController@index')->name('master-district');
                Route::get('get','Master\DistrictController@get');
                Route::post('doAdd','Master\DistrictController@doAdd');
                Route::post('doEdit','Master\DistrictController@doEdit');
                Route::delete('{id}/delete','Master\DistrictController@delete');
                Route::get('detailAdd','Master\DistrictController@detailAdd');
                Route::get('{id}/detailEdit','Master\DistrictController@detailEdit');
                Route::get('{id}/detailRegency','Master\DistrictController@detailRegency');
                Route::get('{id}/detailDistrict','Master\DistrictController@detailDistrict');
            });
            Route::prefix('village')->group(function(){
                Route::get('/','Master\VillageController@index')->name('master-village');
                Route::get('get','Master\VillageController@get');
                Route::post('doAdd','Master\VillageController@doAdd');
                Route::post('doEdit','Master\VillageController@doEdit');
                Route::delete('{id}/delete','Master\VillageController@delete');
                Route::get('detailAdd','Master\VillageController@detailAdd');
                Route::get('{id}/detailEdit','Master\VillageController@detailEdit');
            });
        });
    });
    // SELECTION
    Route::group(['prefix' => 'selection'], function()
    {
        Route::post('regency','Main\SelectionController@getList_Regency');
        Route::post('district','Main\SelectionController@getList_District');
        Route::post('village','Main\SelectionController@getList_Village');
        Route::post('package','Main\SelectionController@getList_Package');
    });

    Route::group(['prefix' => 'statistics'], function(){});

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