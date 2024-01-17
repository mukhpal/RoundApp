<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


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

//\Illuminate\Support\Facades\Auth::routes(['confirm' => true, 'verify' => true]);

Route::get('', function () {
    return view('welcome');
});

//Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');



Route::get('mail', function () {
    $user = \App\Models\User::find(1);

    return (new App\Notifications\VerifyEmail($user))
        ->toMail($user);
});



Route::get('/cardreg', function(Request $request, \FraSim\MangoPay\MangoPayService $service) {
    // $res = $service->card($userId);
    $id = $request->session()->pull('cardRegistrationId');
    $data = '';
    $success = false;
    if($request->has('data')) {
        $data = $request->get('data');
        $success = true;
    }
    elseif($request->has('data')) {
        $data = $request->get('errorCode');
    }
    $res = $service->updateCard($id, $data, $success);
    if(isset($res->CardId) && $res->Status === \MangoPay\CardRegistrationStatus::Validated) {
        $service->pay($res->CardId);
    }
});

Route::get('/secure', function(Request $request, \FraSim\MangoPay\MangoPayService $service) {
   echo "secure";
});

Route::get('/test', function(Request $request, \FraSim\MangoPay\MangoPayService $service) {
    /* @var FraSim\MangoPay\MangoPayService $service */
    $userId = 81801332;
    $userLegalId = 81801377;
    $ibanId = 81801825;
    $cardId = 86365132;
    $walletId = 86365648;
    // $wal = $service->wallet($userId);
    //$pay = $service->pay($cardId, $walletId);
    //$transfer = $service->transfer($userId, $walletId);

    $res = $service->registerCard(['UserId' => $userId]);
    $request->session()->put('cardRegistrationId', $res->Id);
    $url = $res->CardRegistrationURL;
    $returnUrl = 'https://backend.roundapp.local/cardreg';
    $data = [
        'AccessKey' => $res->AccessKey,
        'PreregistrationData' => $res->PreregistrationData,
        // 'RegistrationData' => $res->RegistrationData,
    ];
    return <<<HTML
<form action="{$url}" method="post">
    <b>{$res->CardRegistrationURL}</b>
    <hr>
    <input type="text" name="accessKeyRef" value="{$res->AccessKey}">
    <input type="text" name="data" value="{$res->PreregistrationData}">
    <input type="text" name="returnURL" value="{$returnUrl}">

    <hr>

    <label for="cardNumber">Card Number</label>
    <input type="text" name="cardNumber" value="4972485830400064" />
    <div class="clear"></div>

    <label for="cardExpirationDate">Expiration Date</label>
    <input type="text" name="cardExpirationDate" value="1220" />
    <div class="clear"></div>

    <label for="cardCvx">CVV</label>
    <input type="text" name="cardCvx" value="123" />
    <div class="clear"></div>
    <input type="submit" value="Pay" />
</form>
HTML;
    $res1 = \Illuminate\Support\Facades\Http::withHeaders([
        'Accept=application/json'
        // 'Accept' => 'application/json'
    ])->post($url, $data);
    return $res1;
    var_dump($service->getClient());
    var_dump($service->createWallet(['Description' => 'test']));
    var_dump("________________________________________________________");
    //var_dump($service->getWallets('81790067'));
    //var_dump("________________________________________________________");
    //var_dump($service->getWallet('81790800'));
    exit;
    var_dump($service);

    //var_dump($legal);


    //$legal = $cash->createLegalUser();
    //echo "createBankAccount:\n";
    //$result = $cash->createBankAccount($userLegalId);
    //dd($result);


    echo "getUsers:\n";
    $users = $service->getUsers();
    var_dump($users);
    echo "\n---------------------------\n";
    echo "getUSer:\n";
    $user = $service->getUser($userId);
    var_dump($user);
    echo "\n---------------------------\n";
    echo "createMandate:\n";
    $result = $service->createMandate($ibanId);
    var_dump($result);

    /*
    echo "\n---------------------------\n";
    echo "createWallet:\n";
    $result = $service->createWallet([$userId]);
    var_dump($result);
    */
});
