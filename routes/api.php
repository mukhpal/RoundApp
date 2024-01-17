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

Route::get('/', function() {
    return response()->json([
        'timestamp' => time(),
        'version' => '1.0'
    ]);
});




/** ADVERTISERS ROUTES ************************************************************************************************/

// Advertisers
Route::get('adv', 'Api\AdvertiserController@index')->middleware('client');
Route::get('adv/me', 'Api\AdvertiserController@showMe')->middleware('client');
Route::put('adv/me', 'Api\AdvertiserController@updateMe')->middleware('client');
Route::put('adv/image', 'Api\AdvertiserController@image')->middleware('client');

// Campaigns
Route::get('campaigns/info', 'Api\CampaignController@info')->middleware('client');
Route::get('campaigns/counters', 'Api\CampaignController@counters')->middleware('client');
Route::get('campaigns/dash', 'Api\CampaignController@dash')->middleware('client');
Route::get('campaigns/{campaign}/stats', 'Api\CampaignController@stats')->middleware('client');
Route::resource('campaigns', 'Api\CampaignController')->middleware('client');

// Producers
Route::resource('producers', 'Api\ProducerController')->middleware('client');

/** CONSUMERS ROUTES **************************************************************************************************/
Route::get('consumers/me', 'Api\ConsumerController@show')->middleware('client');
Route::put('consumers/me', 'Api\ConsumerController@update')->middleware('client');

// Video
Route::get('video/showcase', 'Api\VideoController@showcase');//->middleware('client');
Route::get('video', 'Api\VideoController@index')->middleware('client');

/** COMMON ROUTES *****************************************************************************************************/

// Auth
Route::post('/auth/login', "Api\Auth\LoginController@oauthLogin");
Route::post('/auth/logout', "Api\Auth\LoginController@oauthLogout");
Route::post('/auth/register/{type}', "Api\Auth\RegisterController@oauthRegister");
Route::post('/auth/password/forgot', "Api\Auth\ForgotPasswordController@sendResetLinkEmail");
Route::post('/auth/password/reset', "Api\Auth\ResetPasswordController@reset")->name('password.reset');

//Route::get('/auth/email/verify', 'Api\\Auth\VerificationController@show')->name('verification.notice');
Route::get('/auth/email/verify/{id}/{hash}', 'Api\Auth\VerificationController@verify')->name('verification.verify');
Route::get('/auth/email/resend', 'Api\Auth\VerificationController@resend')->name('verification.resend');


// File upload
Route::get('files/{type}/{file:uuid}', 'Api\FileController@show');
Route::post('files/{type}', 'Api\FileController@store')->middleware('client');
Route::delete('files/{type}/delete/{file:uuid}', 'Api\FileController@destroy')->middleware('client');


// Payments
Route::post('payment/register-card', 'Api\PaymentController@registerCard')->middleware('client');
Route::post('payment/confirm-card/{uuid}', 'Api\PaymentController@confirmCard')->middleware('client');

Route::post('payment/in/{type}', 'Api\PaymentController@payIn')->name('payIn'); // ->middleware('client');
Route::post('payment/out', 'Api\PaymentController@payOut')->name('payOut'); // ->middleware('client');

