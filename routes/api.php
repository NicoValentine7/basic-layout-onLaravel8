<?php

use Illuminate\Http\Request;
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

//  * ログインログアウト登録など認証系はLaravelのライブラリを検討するので後回しにしている
//  * その他設計待ちのものもコメントアウトしている
//  * 登録解除、などが一緒になっているAPIはそのままでいいのか 明確にしたほうがいい気もする
//  * 


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// ログイン前
Route::get('/before_login', 'BeforeLoginController@index');

// ログイン
Route::group(['prefix' => 'login', 'as' => 'login.'], function () {
    Route::post('login/user', 'LoginController@loginOnUser');
    Route::post('login/shop', 'LoginController@loginOnShop');
});

// ログアウト shopログアウト userログアウト
Route::group(['prefix' => 'logout', 'as' => 'logout.'], function () {
    Route::post('logout/user', 'LogoutController@logoutOnUser');
    Route::post('logout/shop', 'LogoutController@loginOnShop');
});

// パスワード再発行
Route::get('/reissue_password', 'ReissuePasswordController@Reissue');

// 会員登録
Route::post('/register', 'RegisterController@register');

// 認証系 電話番号認証 sms認証
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('auth/sms_send', 'AuthController@verifySms');
    Route::post('auth/sms_verify', 'AuthController@verifySms');
    Route::post('auth/number', 'AuthController@verifyNumber');
});

Route::post('/register_udid', 'UdidController@register');

//商品
Route::group(['prefix' => 'terms', 'as' => 'terms.'], function () {
    Route::get('/', 'TermController@index');
    Route::get('/agree', 'TermController@agree');
});

//ショップ
Route::group(['prefix' => 'shop', 'as' => 'shop.'], function () {
    Route::get('/', 'ShopController@show');
    Route::get('/search/name', 'ShopController@searchName');
    Route::get('/search/position', 'ShopController@searchPosition');
    //新規ユーザ導入ショップの登録/解除
});

//商品
Route::group(['prefix' => 'item', 'as' => 'item.'], function () {
    Route::get('/', 'ItemController@index');
    Route::get('{item}', 'ItemController@show');
    Route::delete('{item}', 'ItemController@destroy');
});

//お気に入り
Route::group(['prefix' => 'favorite', 'as' => 'favorite.'], function () {
    Route::get('items', 'FavoriteController@getFavoriteItems');
    Route::post('item/{item}', 'FavoriteController@addToFavoriteList');
    Route::delete('item/{item}', 'FavoriteController@destroy');
});

//メールマガジン
Route::group(['prefix' => 'mailmag', 'as' => 'mailmag.'], function () {
    Route::get('/', 'MailmagController@index');
    Route::post('/', 'Mailmag@verifyNumber'); //登録と解除一緒？
});

//アンケート
Route::group(['prefix' => 'question', 'as' => 'question.'], function () {
    Route::get('/', 'QuestionController@index');
    Route::post('/', 'QuestionController@answer');
});

//利用規約?

//チケット
Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
    Route::get('/', 'TicketController@index');
    Route::post('/', 'TicketController@answer');
});

//ショップのスタンプカード情報を取得する
//スタンプを利用する
