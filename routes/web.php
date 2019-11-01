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

/*Route::get('/', function () {
    return view('welcome');
});*/

//Auth::routes();
Route::get('/', function () {
    return redirect(route('admin.login'));
})->name('home');

Route::get('/home',  function () {
    return redirect(route('admin.dashboard'));
});
// sms API
Route::post('/device/status', 'SMSApiController@deviceStatus')->name('sms.device.status');
Route::post('/device/message', 'SMSApiController@message')->name('sms.device.message');

Route::prefix('admin')->group(function() {
    Route::get('/lang/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'zh-CN', 'jp'])) {
            \Cookie::queue(\Cookie::forever('locale', $locale));
        }
        return back()->withInput();
    });

    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::any('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

    Route::get('/', 'Admin\DashboardController@index')->name('admin.dashboard');
});
