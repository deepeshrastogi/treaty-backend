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
Route::group(['middleware' => ['localization'], 'prefix' => 'customer'], function () {

    Route::post('login', 'Users\UserAuthController@login');
    Route::post('otp/login', 'Users\UserAuthController@loginTwoFactor');
    Route::post('forgetPassword', 'Users\UserAuthController@forgetPassword');
    Route::post('createPassword', 'Users\UserAuthController@createPassword');
    Route::post('resetpasswordTokenCheck', 'Users\UserAuthController@resetPasswordTokenCheck');
    Route::post('suspiciousMailSend', 'Users\UserAuthController@suspiciousMailSend');
    Route::get('detail', 'Users\UserAuthController@getProfileDetail');
    Route::post('updatePassword', 'Users\UserAuthController@updatePassword');
    Route::post('changePassword', 'Users\UserAuthController@changePassword');
    /*
     * customer contoller routes
     */
    Route::post('managers', 'Customers\CustomerController@getCustomerManagers');
    Route::post('talkToExperts', 'Customers\CustomerController@talkToExperts');

    /*
     * location contoller routes
     */
    Route::post('orts', 'Locations\LocationController@getOrtLists');
});

/*
 * Order related routes
 */

Route::group(['middleware' => ['localization'], 'prefix' => 'order'], function () {
    Route::post('create', 'Orders\OrderController@store');
    Route::post('upload', 'Orders\OrderController@orderUploadFiles');
    Route::post('delete', 'Orders\OrderController@destroy');
    Route::match(['GET', 'POST'], 'list', 'Orders\OrderController@list');
    Route::post('file/delete', 'Orders\OrderController@deleteUploadedFiles');
    Route::get('update-order-code', 'Orders\OrderController@updateOrderCode');
});
/*
 * Language controller route
 *
 */
Route::get('getLang', 'Languages\LanguagesController@getLangugageJson');

Route::group(['middleware' => ['localization'], 'prefix' => 'order-types'], function () {
    Route::get('/', 'Orders\OrderTypeController@orderTypes');
    Route::get('{id}/topics', 'Orders\OrderTypeController@orderTypeTopics');
    Route::post('/', 'Orders\OrderTypeController@store');
    Route::put('{id}', 'Orders\OrderTypeController@update');
    Route::delete('{id}', 'Orders\OrderTypeController@destroy');
});

Route::group(['middleware' => ['localization'], 'prefix' => 'subsidiaries'], function () {
    Route::get('/', 'Subsidiaries\SubsidiaryController@list');
    Route::post('create', 'Subsidiaries\SubsidiaryController@store');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
 * Orders Files related routes
 */

Route::group(['middleware' => ['localization'], 'prefix' => 'orders'], function () {
    Route::post('files', 'Files\FilesController@store');
    Route::delete('files/{fileId}', 'Files\FilesController@destroy');
    Route::get('file/{fileId}/download', 'Files\FilesController@downloadFile');
    Route::get('{orderId}/files/download-all', 'Files\FilesController@downloadAllFiles');
});

Route::group(['middleware' => ['localization'], 'prefix' => 'mdts'], function () {
    Route::get('/', 'Mdts\MdtController@list');
    Route::get('{id}/locations', 'Locations\LocationController@mdtLocations');
});

Route::group(['middleware' => ['localization'], 'prefix' => 'file-types'], function () {
    Route::get('/', 'Files\FileTypeController@fileTypes');
});