<?php


namespace FraSim\MangoPay\Traits;

use MangoPay\MangoPayApi;
use MangoPay\UserLegal;
use MangoPay\UserNatural;

/**
 * Trait UserTrait
 * @package FraSim\MangoPay\Traits
 * @property-read MangoPayApi $mangoPayApi
 */
trait UserTrait
{

    /**
     * @param $userId
     * @return \MangoPay\UserLegal|\MangoPay\UserNatural|null
     */
    public function getUser($userId)
    {
        $user = null;
        try {
            $user = $this->mangoPayApi->Users->GetNatural($userId);
        }
        catch (\MangoPay\Libraries\ResponseException $e) {

        }
        return $user;
    }

    /**
     * @return \MangoPay\UserLegal[]|\MangoPay\UserNatural[]|null
     */
    public function getUsers()
    {
        $users = null;
        // call some API methods...
        try {
            $users = $this->mangoPayApi->Users->GetAll();
        } catch(\MangoPay\Libraries\ResponseException $e) {
            // handle/log the response exception with code $e->GetCode(), message $e->GetMessage() and error(s) $e->GetErrorDetails()
        } catch(\MangoPay\Libraries\Exception $e) {
            // handle/log the exception $e->GetMessage()
        }
        return $users;
    }

    /**
     * @param UserNatural $user
     * @return \MangoPay\UserLegal|UserNatural
     * @throws \MangoPay\Libraries\Exception
     */
    public function createUserNatural(UserNatural $user)
    {
        /*
        $mangoUser = new \MangoPay\UserNatural();
        $mangoUser->PersonType = "NATURAL";
        $mangoUser->FirstName = 'John';
        $mangoUser->LastName = 'Doe';
        $mangoUser->Birthday = 1409735187;
        $mangoUser->Nationality = "FR";
        $mangoUser->CountryOfResidence = "FR";
        $mangoUser->Email = 'john.doe@mail.com';
        */
        return $this->mangoPayApi->Users->Create($user);
    }

    /**
     * @param UserLegal $user
     * @return UserLegal|UserNatural
     * @throws \MangoPay\Libraries\Exception
     */
    public function createLegalUser(UserLegal $user)
    {
        /*
        $User = new \MangoPay\UserLegal();
        $User->Name = "Name";
        $User->LegalPersonType = "BUSINESS";
        $User->HeadquartersAddress = new \MangoPay\Address;
        $User->HeadquartersAddress->AddressLine1 = "1 Mangopay Street";
        $User->HeadquartersAddress->AddressLine2 = "2 Mangopay Street";
        $User->HeadquartersAddress->City = "City";
        $User->HeadquartersAddress->Region = "Region";
        $User->HeadquartersAddress->PostalCode = "800000";
        $User->HeadquartersAddress->Country = "IT";
        $User->LegalRepresentativeAddress = new \MangoPay\Address;
        $User->LegalRepresentativeAddress->AddressLine1 = "1 Mangopay Street";
        $User->LegalRepresentativeAddress->AddressLine2 = "2 Mangopay Street";
        $User->LegalRepresentativeAddress->City = "City";
        $User->LegalRepresentativeAddress->Region = "Region";
        $User->LegalRepresentativeAddress->PostalCode = "800000";
        $User->LegalRepresentativeAddress->Country = "IT";
        $User->Email = "legal2@testmangopay.com";
        $User->LegalRepresentativeFirstName = "Bob2";
        $User->LegalRepresentativeLastName = "Briant2";
        $User->LegalRepresentativeBirthday = 121272;
        $User->LegalRepresentativeNationality = "IT";
        $User->LegalRepresentativeCountryOfResidence = "NA";
        */
        return $this->mangoPayApi->Users->Create($user);
    }
}
