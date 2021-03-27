<?php

use Illuminate\Http\Request;
use App\Http\Middleware\VerifyJWTToken;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'users', 'namespace' => 'Api', 'middleware' => VerifyJWTToken::class], function () {
    Route::post('updategeneral', 'APIUserController@updateGeneral');
    Route::post('updatebank', 'APIUserController@updateBank');
    Route::post('updatepass', 'APIUserController@updatePass');
    Route::get('getuser', 'APIUserController@getUser');
    // Route::get('logout', 'APIUserController@logout');
    Route::post('logout', 'APIUserController@logout');
    Route::post('adddelivery', 'APIUserController@addDelivery');
    Route::post('updateavatar', 'APIUserController@updateAvatar');
    Route::get('updateaddress/{id}', 'APIUserController@updateAddress');
    Route::post('removedelivery/{id}', 'APIUserController@removeDelivery');
    Route::get('getaddress', 'APIUserController@getSendOrReceiveAddress');

    Route::post('location', 'LocationController@location');
    Route::post('turn-on-parttime', 'APIUserController@turnOnParttime');
    Route::post('update/device', 'APIUserController@updateDevice');
    Route::get('shipper/get-online', 'APIUserController@getShipperOnline');
});

Route::group(['prefix' => 'users', 'namespace' => 'Api'], function () {
    // Route::get('location', 'LocationController@location');
    Route::post('login', 'APIUserController@login'); //login customer
    Route::post('login_shipper', 'APIUserController@loginWithPassword');
    Route::post('login_warehouse', 'APIUserController@loginWarehouse');
    Route::post('login_fb', 'APIUserController@loginfb');
    Route::post('login_google', 'APIUserController@loginGG');
    // Route::get('notifications', 'NotificationController@index');
    // Route::get('notifications/detail', 'NotificationController@detail');
    // Route::get('notifications/test', 'NotificationController@test');
    // Route::get('notifications', 'NotificationController@getByUser');
});

Route::group(['prefix' => 'order', 'namespace' => 'API', 'middleware' => VerifyJWTToken::class], function () {
    Route::get('listbook', 'Customer\OrderController@getListBook');
    Route::get('listbook/status={status}&type={type}', 'Customer\OrderController@getListBookType'); // listbook lấy danh sách trong ngày -chilam
    Route::get('deliveryaddress', 'OrderController@getBooking');
    Route::post('booking', 'OrderController@booking'); //tạo mới đơn hàng
    Route::post('updatebook/{id}', 'OrderController@updateBook');
    Route::post('cancelbook/{id}', 'OrderController@cancelBook');
    Route::get('listCOD', 'OrderController@getCOD');
    Route::get('listCOD_details', 'OrderController@getCODDetails');
    Route::get('detail/{id}', 'OrderController@bookDetail');
    Route::get('delete_booking/{id}', 'OrderController@deleteBooking');

    Route::get('total-price', 'WalletController@getTotalPrice');
    Route::get('total-COD', 'WalletController@getTotalCOD');
    Route::get('wallet', 'WalletController@getWallet');
    Route::get('wallet/list-books/{walletId}', 'WalletController@getListBook');
    Route::get('wallet/withdrawal', 'WalletController@withDrawal');
    Route::get('wallet/description', 'WalletController@getWalletDescription');
    Route::get('total-summary', 'WalletController@getTotalSummary');

    Route::group(['prefix' => 'customer'], function () {
        Route::get('filter', 'Customer\OrderController@getFilter'); // bộ lọc
        Route::get('last-book', 'Customer\OrderController@lastedBookSender');
        Route::post('updatebook/{id}/other-note', 'Customer\OrderController@updateNote');
        Route::post('deny/{id}', 'Customer\OrderController@RequestReturn');
        Route::post('update-prioritize', 'OrderController@updatePrioritize'); //Cập nhật đơn hàng ưu tiên 
        //Route::get('listbook', 'Customer\OrderController@getBook');
    });

    Route::group(['prefix' => 'shipper'], function () {
        Route::get('listbook', 'Shipper\OrderController@getListBook');
        Route::post('listbook-wait', 'Shipper\OrderController@getBookShipperWait');
        Route::get('listbook-wait/detail', 'Shipper\OrderController@getBookShipperWaitDetail');
        Route::post('auto-assign', 'OrderController@assignShipperAuto'); // xác nhận lấy/giao/trả của khách hàng
        Route::post('auto-assign-single', 'Shipper\OrderController@assignSingleShipperAuto'); // xác nhận lấy/giao/trả của đơn hàng
        Route::post('update-prioritize', 'OrderController@updatePrioritize'); //Cập nhật đơn hàng ưu tiên 
        Route::post('updatebook/{id}', 'Shipper\OrderController@updateBookShipper');
        Route::post('updatebook/{id}/other-note', 'Shipper\OrderController@updateNote');
        Route::get('detail/{id}', 'OrderController@bookDetailShipper');
        Route::post('upload_image', 'OrderController@uploadImage');
        Route::get('area-scope', 'OrderController@getAreaScope');
        Route::get('area-scope-shipping', 'OrderController@getAreaScopeShipping');
        Route::get('listbook-count', 'Shipper\OrderController@getBookShipperCount');

        Route::post('updatebook/{id}/weight', 'Shipper\OrderController@updateWeightPrice');
    });

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', 'NotificationController@getNotification');
        Route::get('/detail', 'NotificationController@getNotificationDetail');
        Route::get('/unread-count', 'NotificationController@getUnreadCount');
        Route::post('/read-all', 'NotificationController@readAll');
    });

    Route::group(['prefix' => 'warehouse'], function () {
        Route::post('updatebook/{id}', 'WareHouse\OrderController@updateBookWareHouse');
        Route::get('listbook', 'WareHouse\OrderController@getListBook');
    });
    Route::get('count-book/type={type}', 'OrderController@countBook');
});

Route::group(['prefix' => 'order', 'namespace' => 'API'], function () {
    Route::post('pricing', 'OrderController@pricingBook');
    Route::post('check_province', 'OrderController@checkProvince');
    Route::post('search', 'OrderController@pricingBook');
    Route::get('searchbook/{id}', 'OrderController@searchBook');
    Route::get('listagency', 'OrderController@loadAgency');
});
Route::group(['prefix' => 'place', 'namespace' => 'API'], function () {
    Route::get('province', 'PlaceController@getProvince');
    Route::get('district/{id}', 'PlaceController@getDistrict');
    Route::get('ward/{id}', 'PlaceController@getWard');
    Route::get('agencies', 'PlaceController@getAgency');
});

Route::group(['prefix' => 'setting', 'namespace' => 'API'], function () {
    Route::get('version', 'SettingController@getVersion');
    Route::get('transaction', 'SettingController@getTransaction');
});

Route::group(['prefix' => 'policy', 'namespace' => 'API'], function () {
    Route::get('/', 'PolicyController@getPolicy');
});
//--------RAYMOND------
Route::group(['prefix' => 'qrcode', 'namespace' => 'API'], function () {
    Route::group(['prefix' => 'customer'], function () {
        Route::post('check-new', 'QRCodeController@checkNew'); //1 check qrcode tạo đơn hàng
    });
    Route::group(['prefix' => 'shipper'], function () {
        Route::post('receive', 'QRCodeController@receiveOrder'); //2 lấy đơn
        Route::post('sender', 'QRCodeController@senderOrder'); //4 nhận đơn giao
    });
    Route::group(['prefix' => 'warehouse'], function () {
        Route::post('into', 'QRCodeController@intoWarehouse'); //3 nhập đơn mới vào kho
        Route::post('fail', 'QRCodeController@failWarehouse'); //5 nhập đơn hủy vào kho
    });
});
Route::group(['prefix' => 'order', 'namespace' => 'API'], function () {
    Route::group(['prefix' => 'customer'], function () {
        Route::post('create', 'OrderController@create'); //1.1 nhập COD,image_order,qrcode
    });
});
