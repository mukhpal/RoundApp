<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use FraSim\MangoPay\Models\MangoPayUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use MangoPay\CardRegistrationStatus;

class PaymentController extends Controller
{

    protected static $actions = [
        'card' => [
            'register' => false,
            'confirm' => true,
            'pay-preview' => false,
            'pay' => false,
        ],
        'direct-debit' => [

        ]
    ];

    /**
     * Payment IN
     * @param Request $request
     * @param $type
     * @return array
     */
    public function payIn(Request $request, $type, $action)
    {
        if($this->requireSignatureValidation($type, $action) && !$request->hasValidSignature()) {
            abort(401, "Invalid signature");
        }
        $method = "payInWith" . ((string)Str::of($type)->camel()->ucfirst());
        if(!method_exists($this, $method))
            abort(401, "Method {$method} doesn't exist");
        return $this->$method($request, $action);
    }

    /**
     * Payment OUT
     *
     * @return \Illuminate\Http\Response
     */
    public function payOut()
    {
        //
    }

    protected function registerCard(Request $request)
    {
        /* @var MangoPayUser $mangoPay */
        $user = auth()->user();
        $mangoPay = $user->mangoPay;
        $cardRegistration = $mangoPay->registerCard([], $link);
        $uuid = $link->uuid;
        return [
            'uuid' => $uuid,
            'url' => $cardRegistration->CardRegistrationURL,
            'accessKeyRef' => $cardRegistration->AccessKey,
            'data' => $cardRegistration->PreregistrationData,
        ];
    }

    protected function confirmCard(Request $request, string $uuid)
    {
        /* @var MangoPayUser $mangoPay */
        $user = auth()->user();
        $mangoPay = $user->mangoPay;
        throw_unless($request->has('data'), new \Exception("Required variable data"));
        $data = $request->get('data');
        $entity = $mangoPay->confirmCard($uuid, $data);
        if(!isset($entity->CardId) || $entity->Status !== CardRegistrationStatus::Validated) {
            abort(401);
        }
    }

    protected function requireSignatureValidation($type, $action)
    {
        return static::$actions[$type][$action];
    }

    protected function validateSignature(Request $request)
    {

    }


    protected function payInWithCard(Request $request, $action, $uuid)
    {
        $method = "cardAction" . ((string)Str::of($action)->camel()->ucfirst());
        $this->$method();

        if($action === 'register') {
            $this->registerCard($request);
            $signedUrl = URL::temporarySignedRoute(
                'payIn', now()->addMinutes(10), ['type' => 'card', 'uuid' => $uuid]
            );
        }
        elseif($action === 'confirm') {
            $this->confirmCard($request);
        }
        elseif($action === 'pay') {

        }
        elseif($action === 'confirm-pay') {

        }
    }

    protected function cardActionRegister()
    {

    }

    protected function cardActionConfirm()
    {

    }

    protected function cardActionPayPreview()
    {

    }
}
