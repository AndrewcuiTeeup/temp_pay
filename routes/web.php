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
    Route::get('/order-list', 'Admin\OrderController@index')->name('admin.order.list');
    Route::post('/order/ajax', 'Admin\OrderController@ajax_log_table')->name('admin.order.ajax');
    Route::get('/order-detail/{id}', 'Admin\OrderController@detail')->name('admin.order.detail');

    Route::get('/setting/bank', 'Admin\SettingController@bankList')->name('admin.setting.bank-list');
    Route::get('/setting/bank/edit/{id}', 'Admin\SettingController@bankEditForm')->name('admin.setting.bank.editForm');
    Route::post('/setting/bank/edit', 'Admin\SettingController@bankEdit')->name('admin.setting.bank.edit');
    Route::post('/setting/bank/status', 'Admin\SettingController@bankStatus')->name('admin.setting.bank-status');
    Route::post('/setting/bank/add', 'Admin\SettingController@bankAdd')->name('admin.setting.bank.add');

    Route::get('/setting/sms-log', 'Admin\SettingController@smslog')->name('admin.setting.sms-log');
    Route::post('/setting/sms-log/ajax', 'Admin\SettingController@sms_ajax_table')->name('admin.setting.sms-log-ajax');


});
