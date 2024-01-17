<?php


namespace App\Models;

use FraSim\MangoPay\Models\MangoPayUser;
use FraSim\MangoPay\Traits\MangoPayUserTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class User
 *
 * @OA\Schema(
 *   description="Entity User",
 *   required={
 *       "id", "name", "email", "password"
 *   },
 * )
 *
 * @OA\Property(
 *   property="id",
 *   type="integer",
 *   format="bigint",
 *   description="id",
 * )
 * @OA\Property(
 *   property="name",
 *   type="string",
 *   format="string",
 *   description="name",
 *   maxLength=255
 * )
 * @OA\Property(
 *   property="email",
 *   type="string",
 *   format="email",
 *   description="email",
 *   maxLength=255
 * )
 * @OA\Property(
 *   property="email_verified_at",
 *   type="string",
 *   format="date-time",
 *   description="email_verified_at",
 * )
 * @OA\Property(
 *   property="password",
 *   type="string",
 *   format="password",
 *   description="password",
 *   maxLength=255
 * )
 * @OA\Property(
 *   property="remember_token",
 *   type="string",
 *   format="string",
 *   description="remember_token",
 *   maxLength=100
 * )
 * @OA\Property(
 *   property="payment_account_id",
 *   type="integer",
 *   description="payment_account_id",
 * )
 * @OA\Property(
 *   property="someone_id",
 *   type="integer",
 *   description="someone_id",
 * )
 * @OA\Property(
 *   property="someone_type",
 *   type="string",
 *   description="someone_type",
 * )
 * @OA\Property(
 *   property="last_login_at",
 *   type="string",
 *   format="date-time",
 *   description="last_login_at",
 * )
 * @OA\Property(
 *   property="last_login_ip",
 *   type="string",
 *   format="string",
 *   description="last_login_ip",
 * )
 * @OA\Property(
 *   property="created_at",
 *   type="string",
 *   format="date-time",
 *   description="created_at",
 * )
 * @OA\Property(
 *   property="updated_at",
 *   type="string",
 *   format="date-time",
 *   description="updated_at",
 * )
 *
 * Relations
 * @property integer $id
 * @property integer $someone_id
 * @property integer $payment_id
 * @property string $someone_type
 * @property string $name
 * @property string $role
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $last_login_at
 * @property string $last_login_ip
 * @property string $created_at
 * @property string $updated_at
 * @property Producer[] $producers
 * @property File[] $files
 * @property File $image
 * @property Accounting[] $accountings
 * @property PaymentAccount $favouritePaymentAccount
 * @property PaymentAccount[] $paymentAccounts
 * @property UserSocial[] $userSocials
 * @property Campaign[] $campaigns
 * @property Video[] $videos
 * @property Advertiser|Consumer $someone
 *
 * Accessors/Mutators
 * @property-read boolean $isAdmin
 * @property-read MangoPayUser $mangoPay
 */
class User extends \App\User
{

    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use MangoPayUserTrait;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'email_verified_at',
        'last_login_at',
    ];

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'last_login_at', 'last_login_ip', 'someone_id', 'someone_type', 'payment_account_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function producers()
    {
        return $this->hasMany('App\Models\Producer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany('App\Models\File');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image()
    {
        return $this->hasOne('App\Models\File')->where('files.type', '=', 'user.image');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function someone()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|null
     */
    public function advertiser()
    {
        return $this->isAdvertiser() ? $this->someone() : null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|null
     */
    public function consumer()
    {
        return $this->isConsumer() ? $this->someone() : null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountings()
    {
        return $this->hasMany('App\Models\Accounting');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function favouritePaymentAccount()
    {
        return $this->hasOne('App\Models\PaymentAccount');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentAccounts()
    {
        return $this->hasMany('App\Models\PaymentAccount');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userSocials()
    {
        return $this->hasMany('App\Models\UserSocial');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function campaigns()
    {
        return $this->hasManyThrough('App\Models\Campaign', 'App\Models\Producer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function videos()
    {
        return $this->hasManyDeep('App\Models\Video',
            ['App\Models\Producer', 'App\Models\Campaign'],
            ['user_id', 'producer_id', 'id'],
            ['id', 'id', 'video_id']
        );
        /*
    return $this->hasManyThrough('App\Models\Video', 'App\Models\Campaign', 'video_id', 'id')
        ->leftJoin('producers', 'campaign.producer_id');
        */
    }


    public function showcase()
    {

    }

    public function library()
    {
        $library = Campaign::with('video')->get();
            //$this->campaigns()->with('video')->get();
            //->having('count(id)', '>', 1);
        // $library = DB::table('campaigns')->notin;
        //DB::select(DB::raw("SELECT * FROM campaigns"));
        return $library;
        /*
        $users = DB::table('campaigns')
            ->leftJoin('videos', 'campaigns.video_id')
            ->leftJoin('campaign_tag', 'campaigns.video_id')
            ->select('name', 'email as user_email')

            ->get();
        // Todo: filter video already viewed
        return Video::all();
        */
    }


    /**
     * @return bool
     */
    public function getIsAdminAttribute()
    {
        return $this->role === 'admin';
    }

    /**
     * @param $newPassword
     * @param null $currentPassword
     * @param bool $save
     * @return bool
     */
    public function changePassword($newPassword, $currentPassword = null, $save = false)
    {
        $valid = isset($currentPassword) ? Hash::check($currentPassword, $this->password) : true;
        if ($valid) {
            $this->password = Hash::make($newPassword);
            if ($save)
                $this->save();
        }
        return $valid;
    }

    /**
     * Return true if user is an advertiser
     *
     * @return bool
     */
    public function isAdvertiser()
    {
        return $this->someone_type === Advertiser::class;
    }

    /**
     * Return true if user is a consumer
     *
     * @return bool
     */
    public function isConsumer()
    {
        return $this->someone_type === Consumer::class;
    }

    /**
     * @param $attributes
     * @param null $advertiser
     * @return Advertiser|Consumer
     * @throws \Throwable
     */
    public function updateAdvertiser($attributes, &$advertiser = null)
    {
        throw_unless($this->isAdvertiser(), new BadRequestHttpException('This user is not an advertiser'));
        if (!$this->someone) {
            $advertiser = Advertiser::create($attributes);
        } else {
            $advertiser = $this->someone;
            $this->someone->update($attributes);
        }
        $this->someone_id = $advertiser->id;
        return $advertiser;
    }

    /**
     * @param $attributes
     * @param null $consumer
     * @return Advertiser|Consumer|null
     * @throws \Throwable
     */
    public function updateConsumer($attributes, &$consumer = null)
    {
        throw_unless($this->isConsumer(), new BadRequestHttpException('This user is not a consumer'));
        if (!$this->someone) {
            $advertiser = Consumer::create($attributes);
        } else {
            $consumer = $this->someone;
            $this->someone->update($attributes);
        }
        $this->someone_id = $consumer->id;
        return $consumer;
    }
}
