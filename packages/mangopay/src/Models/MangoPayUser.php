<?php


namespace FraSim\MangoPay\Models;


use App\Models\User;
use FraSim\MangoPay\MangoPayService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MangoPay\CardRegistration;

class MangoPayUser
{
    /* @var MangoPayService */
    protected $service;

    protected $id;

    /* @var User */
    protected $user;
    protected $cards;

    public function __construct(User $user)
    {
        $this->service = app(MangoPayService::class);
        $this->user = $user;
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->id = $this->getOrCreateId();
        $this->cards = $this->getCards();
    }

    public function getId()
    {
        return optional(MangoPayLink::where([
            'user_id' => $this->user->id,
            'entity' => 'user'
        ])->first())->external_id;
    }

    public function getOrCreateId(array $attributes = [])
    {
        if(!($id = $this->getId())) {
            $entity = $this->createUser();
            $id = intval($entity->Id);
        }
        return $id;
    }

    public function getCards()
    {
        return MangoPayLink::where([
            'user_id' => $this->user->id,
            'entity' => 'card'
        ])->get()->pluck('external_id')->toArray();
    }


    public function createUser()
    {
        try {
            $success = false;
            DB::beginTransaction();
            $attributes = [];
            if(true) {
                $attributes = [
                    'FirstName' => $this->user->name,
                    'LastName' => $this->user->name,
                    'Birthday' => strtotime(date('1980-01-01')),
                    'Nationality' => 'IT',
                    'CountryOfResidence' => 'IT',
                    'Email' => $this->user->email
                ];
                $entity = $this->service->createUserNatural($attributes);
            }
            else {
                $entity = $this->service->createUserLegal($attributes);
            }
            $link = MangoPayLink::create([
                'user_id' => $this->user->id,
                'external_id' => intval($entity->Id),
                'entity' => 'user'
            ]);
            $success = true;
        }
        catch (\Throwable $e) {

        }
        if($success)
            DB::commit();
        else
            DB::rollBack();
    }

    /**
     * @param array $attributes
     * @param MangoPayLink|null $link
     * @return CardRegistration
     */
    public function registerCard(array $attributes = [], &$link = null): CardRegistration
    {
        $success = false;
        $entity = null;
        try {
            $attributes['UserId'] = $this->id;
            $entity = $this->service->registerCard($attributes);
            $link = MangoPayLink::create([
                'user_id' => $this->user->id,
                'external_id' => intval($entity->Id),
                'entity' => 'registrationCard',
                'uuid' => Str::uuid()->toString()
            ]);
            $success = true;
        }
        catch (\Throwable $e) {

        }
        if($success)
            DB::commit();
        else
            DB::rollBack();
        return $entity;
    }

    /**
     * @param string $uuid
     * @param string $data
     * @param MangoPayLink|null $link
     * @return CardRegistration
     */
    public function confirmCard(string $uuid, string $data, &$link = null): CardRegistration
    {
        $success = false;
        $entity = null;
        try {
            $link = MangoPayLink::where([
                'user_id' => $this->user->id,
                'entity' => 'registrationCard',
                'uuid' => $uuid
            ])->firstOrFail();
            $cardRegistrationId = $link->external_id;
            $entity = $this->service->confirmCard($cardRegistrationId, $data);
            $link->update([
                // 'external_id' => intval($entity->Id),
                'entity' => 'registrationCard.verified',
                'uuid' => Str::uuid()->toString()
            ]);
            $success = true;
        }
        catch (\Throwable $e) {

        }
        if($success)
            DB::commit();
        else
            DB::rollBack();
        return $entity;
    }
}
