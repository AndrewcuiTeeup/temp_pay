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
/*Route::get('/admin', function () {
   return redirect(route('admin.login'));
})->name('home');*/

Route::get('/home',  function () {
    return redirect(route('admin.dashboard'));
});
// sms API
Route::post('/device/status', 'SMSApiController@deviceStatus')->name('sms.device.status');
Route::post('/device/message', 'SMSApiController@message')->name('sms.device.message');
// payment
Route::any('/payment/order', 'PaymentController@generate')->name('payment.generate');
Route::post('/payment/order-status', 'PaymentController@orderStatus')->name('payment.status');
Route::get('/payment/order_detail/{id}', 'PaymentController@show')->name('payment.order');
Route::get('/payment/order_expired/{id}', 'PaymentController@orderExpired')->name('payment.order.expired');
Route::get('/payment/order_success/{id}', 'PaymentController@orderSuccess')->name('payment.order.success');
Route::post('/payment/notify', 'PaymentController@notify')->name('payment.notify');
Route::get('/test', 'TestController@index')->name('test');


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
    Route::get('/setting/shop/{id}', 'Admin\SettingController@shopEdit')->name('admin.setting.shop.edit');
    Route::post('/setting/shop', 'Admin\SettingController@shopUpdate')->name('admin.setting.shop.update');

    Route::get('/setting/sms-log', 'Admin\SettingController@smslog')->name('admin.setting.sms-log');
    Route::post('/setting/sms-log/ajax', 'Admin\SettingController@sms_ajax_table')->name('admin.setting.sms-log-ajax');
    Route::get('/setting/device', 'Admin\DeviceController@deviceList')->name('admin.setting.device-list');

    // user
    Route::get('/setting/admin/list', 'Admin\SettingController@userList')->name('admin.setting.admin.list');
    Route::get('/setting/admin/edit/{id}', 'Admin\SettingController@userEditForm')->name('admin.setting.admin.editForm');;
    Route::post('/setting/admin/add', 'Admin\SettingController@userAdd')->name('admin.setting.admin.add');
    Route::post('/setting/admin/update', 'Admin\SettingController@userUpdate')->name('admin.setting.admin.update');
    Route::post('/setting/admin/delete', 'Admin\SettingController@userDelete')->name('admin.setting.admin.delete');


});
