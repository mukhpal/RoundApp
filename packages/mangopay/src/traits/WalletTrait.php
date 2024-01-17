<?php


namespace FraSim\MangoPay\Traits;

use MangoPay\MangoPayApi;
use MangoPay\Wallet;

/**
 * Trait WalletTrait
 * @package FraSim\MangoPay\Traits
 * @property enum $currency
 * @property-read MangoPayApi $mangoPayApi
 */
trait WalletTrait
{
    /**
     * @param $userId
     * @param string $description
     * @return Wallet
     */
    public function createWallet($userId, $description = "Wallet for user {id}")
    {
        $wallet = new Wallet;
        $wallet->Owners = [$userId];
        $wallet->Description = strtr($description, ['{id}' => $userId]);
        $wallet->Currency = $this->currency;
        return $this->mangoPayApi->Wallets->Create($wallet);
    }

    /**
     * @param $userId
     * @return \MangoPay\Wallet[]
     */
    public function getWallets($userId) {
        return $this->mangoPayApi->Users->GetWallets($userId);
    }

    /**
     * @param $walletId
     * @return \MangoPay\Wallet
     */
    public function getWallet($walletId) {
        return $this->mangoPayApi->Wallets->Get($walletId);
    }

    /**
     * @param $userId
     * @param null $description
     * @return Wallet
     */
    public function getOrCreateWallet($userId, $description = null) {
        $wallets = $this->getWallets($userId);
        if(!empty($wallets)) {
            $wallet = $wallets[0];
        } else {
            $wallet = $this->createWallet($userId, $description);
        }
        return $wallet;
    }
}
