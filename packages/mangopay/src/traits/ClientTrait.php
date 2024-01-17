<?php
namespace FraSim\MangoPay\Traits;

use MangoPay\MangoPayApi;

/**
 * Trait ClientTrait
 * @package FraSim\MangoPay\Traits
 * @property-read MangoPayApi $mangoPayApi
 */
trait ClientTrait
{
    /**
     * Create Mangopay User
     */
    public function getClient()
    {
        return $this->mangoPayApi->Clients->Get();
    }

    /**
     * @param $file
     * @throws \MangoPay\Libraries\Exception
     */
    public function updateClientLogo($file) {
        $this->mangoPayApi->Clients->UploadLogoFromFile($file);
    }

    /**
     * @return \MangoPay\Wallet[]
     * @throws \MangoPay\Libraries\Exception
     */
    public function getClientWallets() {
        return $this->mangoPayApi->Clients->GetWallets();
    }
}
