<?php


namespace FraSim\MangoPay\Traits;

use MangoPay\MangoPayApi;
use MangoPay\Wallet;

/**
 * Trait Card
 * @package FraSim\MangoPay\Traits
 * @property-read MangoPayApi $mangoPayApi
 */
trait Card
{
    public function createCard($userId, $description = "Wallet for user {id}")
    {
        $wallet = new Wallet;
        $wallet->Owners = [$userId];
        $wallet->Description = strtr($description, ['{id}' => $userId]);
        $wallet->Currency = $this->currency;
        return $this->mangoPayApi->Wallets->Create($wallet);
    }
}
