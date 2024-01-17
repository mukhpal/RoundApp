<?php


namespace FraSim\MangoPay;


use FraSim\MangoPay\Traits\ClientTrait;
use FraSim\MangoPay\Traits\UserTrait;
use FraSim\MangoPay\Traits\WalletTrait;
use MangoPay\Address;
use MangoPay\CardRegistration;
use MangoPay\CurrencyIso;
use MangoPay\FundsType;
use MangoPay\MangoPayApi;
use MangoPay\PayIn;
use MangoPay\PayInPaymentDetailsCard;
use MangoPay\Transfer;
use MangoPay\User;
use MangoPay\UserLegal;
use MangoPay\UserNatural;
use MangoPay\Wallet;

/**
 * Class CashService
 * @ref https://www.mangopay.com/demo/
 * @package App\Services
 */
class MangoPayService
{
    /* @var UserNatural|UserLegal */
    private $_seller;

    /* @var Wallet */
    private $_wallet;

    protected $mangoPayApi;

    protected $sellerId;
    protected $walletId;
    protected $currency = CurrencyIso::EUR;
    protected $culture = 'IT';

    use ClientTrait;
    use WalletTrait;
    use UserTrait;

    /**
     * MangoPayService constructor.
     * @throws \Throwable
     */
    public function __construct()
    {
        $env = config('mangopay.env', 'dev');
        $tmpFolder = base_path(config('mangopay.tmp_folder', sys_get_temp_dir()));
        if(!is_dir($tmpFolder)) {
            mkdir($tmpFolder, 777, true);
        }
        $environments = config('mangopay.environments');
        throw_unless(key_exists($env, $environments), new \Exception("Invalid environment"));
        throw_unless(!empty(config('mangopay.client_id')), new \Exception("Invalid client_id"));
        throw_unless(!empty(config('mangopay.secret')), new \Exception("Invalid secret"));
        $this->mangoPayApi = new MangoPayApi;
        $this->mangoPayApi->Config->BaseUrl = $environments[$env]['url'];
        $this->mangoPayApi->Config->ClientId = config('mangopay.client_id');
        $this->mangoPayApi->Config->ClientPassword = config('mangopay.secret');
        $this->mangoPayApi->Config->TemporaryFolder = $tmpFolder;
        $this->sellerId = config('mangopay.seller.id');
        $this->walletId = config('mangopay.seller.wallet_id');
        if(!isset($walletId)) {
            $wallet = $this->getOrCreateWallet($this->sellerId, "Seller wallet");
            $this->walletId = $wallet->Id;
        }
    }

    /**
     * @link https://docs.mangopay.com/endpoints/v2.01/users#e260_update-a-natural-user
     * @param array $attributes (PersonType, FirstName, LastName, Birthday, Nationality, CountryOfResidence, Email)
     * @return UserNatural
     * @throws \MangoPay\Libraries\Exception
     * @throws \Throwable
     */
    public function createUserNatural(array $attributes = []): UserNatural
    {
        $attributes['PersonType'] = 'NATURAL';
        $entity = static::CreateEntity(UserNatural::class, $attributes);
        return $this->mangoPayApi->Users->Create($entity);
    }

    /**
     * @link https://docs.mangopay.com/endpoints/v2.01/users#e259_create-a-legal-user
     * @param array $attributes (Name, LegalPersonType, HeadquartersAddress, HeadquartersAddress, HeadquartersAddress, HeadquartersAddress, HeadquartersAddress, HeadquartersAddress, HeadquartersAddress, LegalRepresentativeAddress, LegalRepresentativeAddress, LegalRepresentativeAddress, LegalRepresentativeAddress, LegalRepresentativeAddress, LegalRepresentativeAddress, LegalRepresentativeAddress, Email, LegalRepresentativeFirstName, LegalRepresentativeLastName, LegalRepresentativeBirthday, LegalRepresentativeNationality, LegalRepresentativeCountryOfResidence)
     * @return UserLegal
     * @throws \MangoPay\Libraries\Exception
     * @throws \Throwable
     */
    public function createUserLegal(array $attributes = []): UserLegal
    {
        $attributes['LegalPersonType'] = 'BUSINESS';
        if(isset($attributes['HeadquartersAddress']))
            $attributes['HeadquartersAddress']['class'] = Address::class;
        if(isset($attributes['LegalRepresentativeAddress']))
            $attributes['LegalRepresentativeAddress']['class'] = Address::class;
        $entity = static::CreateEntity(UserLegal::class, $attributes);
        return $this->mangoPayApi->Users->Create($entity);
    }

    /**
     * @link https://docs.mangopay.com/endpoints/v2.01/cards#e178_create-a-card-registration
     * @param array $attributes (UserId, Currency, CardType)
     * @return CardRegistration
     * @throws \Throwable
     */
    public function registerCard(array $attributes = []): CardRegistration
    {
        $attributes['Currency'] = $attributes['Currency'] ?? $this->currency;
        $entity = static::CreateEntity(CardRegistration::class, $attributes);
        return $this->mangoPayApi->CardRegistrations->Create($entity);
    }

    /**
     * @link https://docs.mangopay.com/endpoints/v2.01/cards#e179_update-a-card-registration
     * @param int $id
     * @param string $data
     * @return CardRegistration
     */
    public function confirmCard(int $id, string $data): CardRegistration
    {
        $cardRegister = $this->mangoPayApi->CardRegistrations->Get($id);
        $cardRegister->RegistrationData = $data;
        return $this->mangoPayApi->CardRegistrations->Update($cardRegister);
    }








    public function createBankAccount($userId)
    {
        $BankAccount = new \MangoPay\BankAccount();
        //$BankAccount->Type = "IBAN";
        //$BankAccount->UserId = $userId;
        $BankAccount->OwnerAddress = new \MangoPay\Address;
        $BankAccount->OwnerAddress->AddressLine1 = "Via vai";
        $BankAccount->OwnerAddress->AddressLine2 = "";
        $BankAccount->OwnerAddress->City = "Napoli";
        $BankAccount->OwnerAddress->Region = "Campania";
        $BankAccount->OwnerAddress->PostalCode = "80133";
        $BankAccount->OwnerAddress->Country = "IT";
        $BankAccount->OwnerName = "Mangopay";

        $BankAccount->Details = new \MangoPay\BankAccountDetailsIBAN();
        $BankAccount->Details->IBAN = "FR7611808009101234567890147";
        $BankAccount->Details->BIC = "CMBRFR2B";
        $result = $this->mangoPayApi->Users->CreateBankAccount($userId, $BankAccount);
        return $result;
    }

    /**
     * @ref https://docs.mangopay.com/endpoints/v2.01/mandates#e231_create-a-mandate
     * @param $userId
     * @param $bankAccountId
     */
    public function createMandate($bankAccountId)
    {
        $mandate = new \MangoPay\Mandate;
        $mandate->BankAccountId = $bankAccountId;
        $mandate->Culture = "IT";
        $mandate->ReturnURL = "https://roundapp.it/";
        return $this->mangoPayApi->Mandates->Create($mandate);
    }

    public function wallet($userId)
    {
        $wallet = new Wallet;
        $wallet->Owners = [$userId];
        $wallet->Description = "Test";
        $wallet->Currency = "EUR";
        return $this->mangoPayApi->Wallets->Create($wallet);
    }


    public function pay($cardId, $walledId)
    {
        $card = $this->mangoPayApi->Cards->Get($cardId);
        $PayIn = new PayIn();
        $PayIn->PaymentType = "CARD";
        $PayIn->AuthorId = $card->UserId;
        $PayIn->CreditedWalletId = $walledId;
        $PayIn->DebitedFunds = new \MangoPay\Money();
        $PayIn->DebitedFunds->Currency = "EUR";
        $PayIn->DebitedFunds->Amount = 24000;
        $PayIn->Fees = new \MangoPay\Money();
        $PayIn->Fees->Currency = "EUR";
        $PayIn->Fees->Amount = 3;
        $PayIn->PaymentDetails = new \MangoPay\PayInPaymentDetailsCard();
        $PayIn->ExecutionType = "DIRECT";
        $PayIn->ExecutionDetails = new \MangoPay\PayInExecutionDetailsDirect();
        $PayIn->ExecutionDetails->SecureModeReturnURL = "https://backend.roundapp.local/secure";
        $PayIn->ExecutionDetails->CardId = $cardId;
        return $this->mangoPayApi->PayIns->Create($PayIn);
    }

    public function transfer($userId, $walletId)
    {
        $transfer = new Transfer;
        $transfer->AuthorId = $userId;
        $transfer->DebitedFunds = new \MangoPay\Money();
        $transfer->DebitedFunds->Currency = "EUR";
        $transfer->DebitedFunds->Amount = 5;
        $transfer->Fees = new \MangoPay\Money();
        $transfer->Fees->Currency = "EUR";
        $transfer->Fees->Amount = 0;
        $transfer->DebitedWalletId = $walletId;
        $transfer->CreditedWalletId = "CREDIT_EUR";
        return $this->mangoPayApi->Transfers->Create($transfer);
    }

    public function payIn($userId, $walletId, $bankAccountId)
    {
        $PayIn = new \MangoPay\PayIn();
        $PayIn->PaymentType = "DIRECT_DEBIT";
        $PayIn->AuthorId = $userId;
        $PayIn->CreditedWalletId = $walletId;

        $PayIn->DebitedFunds = new \MangoPay\Money();
        $PayIn->DebitedFunds->Currency = "EUR";
        $PayIn->DebitedFunds->Amount = 1;

        $PayIn->Fees = new \MangoPay\Money();
        $PayIn->Fees->Currency = "EUR";
        $PayIn->Fees->Amount = 0;

        $PayIn->PaymentDetails = new \MangoPay\PayInPaymentDetailsDirectDebit;

        $PayIn->ExecutionType = "DIRECT";
        $PayIn->ExecutionDetails = new \MangoPay\PayInExecutionDetailsDirect();
        //$PayIn->ExecutionDetails->SecureModeReturnURL = "http".(isset($_SERVER['HTTPS']) ? "s" : null)."://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?stepId=".($stepId+1);
        $PayIn->ExecutionDetails->CardId = $_SESSION["MangoPayDemo"]["Card"];
        $result = $this->mangoPayApi->PayIns->Create($PayIn);
    }


    public function payOut($userId, $walletId, $bankAccountId)
    {
        $PayOut = new \MangoPay\PayOut();
        $PayOut->AuthorId = $userId;
        $PayOut->DebitedWalletId = $walletId;
        $PayOut->DebitedFunds = new \MangoPay\Money();
        $PayOut->DebitedFunds->Currency = "EUR";
        $PayOut->DebitedFunds->Amount = 610;
        $PayOut->Fees = new \MangoPay\Money();
        $PayOut->Fees->Currency = "EUR";
        $PayOut->Fees->Amount = 125;
        $PayOut->PaymentType = "BANK_WIRE";
        $PayOut->MeanOfPaymentDetails = new \MangoPay\PayOutPaymentDetailsBankWire();
        $PayOut->MeanOfPaymentDetails->BankAccountId = $bankAccountId;
        $result = $this->mangoPayApi->PayOuts->Create($PayOut);
    }

    /**
     * @param $class
     * @param array $attributes
     * @return mixed
     * @throws \Throwable
     */
    public static function CreateEntity($class, array $attributes)
    {
        $instance = new $class;
        foreach ($attributes as $k => $v) {
            throw_unless(property_exists($instance, $k), new \Exception("Property \"{$k}\" doesn't exist."));
            if(!is_scalar($v)) {
                throw_unless(key_exists('class', $v), new \Exception("Array property requires a \"class\" key."));
                $subClass = $v['class'];
                unset($v['class']);
                $instance->$k = static::CreateEntity($subClass, $v);
            }
            $instance->$k = $v;
        }
        return $instance;
    }
}
