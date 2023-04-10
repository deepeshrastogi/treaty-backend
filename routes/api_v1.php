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

Route::controller(AuthController::class)->middleware('localization')->prefix('login')->group(function () {
    Route::post('login', 'login');
    Route::post('password/reset', 'forgetPassword');
});

Route::controller(AuthController::class)->middleware('localization')->prefix('users')->group(function () {
    Route::post('users/{userId}/password/', 'updatePassword');
});


Route::controller(AuthController::class)->middleware('localization')->group(function () {

    Route::post('login', 'login');
    Route::post('otp/login', 'loginTwoFactor');
    Route::post('forgetPassword', 'forgetPassword');
    Route::post('updatePassword', 'updatePassword');
    Route::post('resetpasswordTokenCheck', 'resetpasswordTokenCheck');
    Route::get('getLang', 'getLangugageJson');

});


Route::controller(CustomerAuthController::class)->prefix('customer')->middleware('localization')->group(function () {
    Route::post('login', 'login');
    Route::post('otp/login', 'loginTwoFactor');
    Route::post('forgetPassword', 'forgetPassword');
    Route::post('updatePassword', 'updatePassword');
    Route::post('resetpasswordTokenCheck', 'resetpasswordTokenCheck');
    Route::post('suspiciousMailSend', 'suspiciousMailSend');
    Route::get('getLang', 'getLangugageJson');
    Route::get('detail', 'getCustomerDetail');
});


Route::controller(CustomerController::class)->middleware('localization')->prefix('customer')->group(function () {
    Route::post('orts', 'getORTLists');
    Route::post('orts', 'getORTLists');
});



Route::controller(CustomerController::class)->middleware('localization')->prefix('customer')->group(function () {
    Route::post('managers', 'getCustomerManagers');
    Route::get('talkToExperts', 'talkToExperts');
});


Route::controller(OrderController::class)->middleware('localization')->prefix('order')->group(function () {
    Route::post('create', 'store');
    Route::post('upload', 'orderUploadFiles');
    Route::post('delete', 'destroy');
    Route::get('list', 'list');
    Route::post('file/delete', 'deleteUploadedFiles');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});