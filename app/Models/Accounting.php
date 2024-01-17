<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Accounting
 *
 * @OA\Schema(
 *   description="Entity Accounting",
 *   required={
 *       "id", "user_id", "payment_account_id", "transaction_type", "date", "amount", "payment_account_type", "payment_account_code", "payment_account_accountholder"
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
 *   property="user_id",
 *   type="integer",
 *   format="integer",
 *   description="user_id",
 * )
 * @OA\Property(
 *   property="payment_account_id",
 *   type="integer",
 *   format="integer",
 *   description="payment_account_id",
 * )
 * @OA\Property(
 *   property="transaction_type",
 *   type="string",
 *   format="string",
 *   description="transaction_type",
 *   maxLength=30
 * )
 * @OA\Property(
 *   property="date",
 *   type="string",
 *   format="date-time",
 *   description="date",
 * )
 * @OA\Property(
 *   property="amount",
 *   type="number",
 *   format="decimal",
 *   description="amount",
 * )
 * @OA\Property(
 *   property="payment_account_type",
 *   type="string",
 *   format="string",
 *   description="payment_account_type",
 *   maxLength=30
 * )
 * @OA\Property(
 *   property="payment_account_code",
 *   type="string",
 *   format="string",
 *   description="payment_account_code",
 *   maxLength=50
 * )
 * @OA\Property(
 *   property="payment_account_accountholder",
 *   type="string",
 *   format="string",
 *   description="payment_account_accountholder",
 *   maxLength=300
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

 * @OA\Property(
 *   property="user",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/User"),
 *   description="User"
 * )
 * @OA\Property(
 *   property="paymentAccount",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/PaymentAccount"),
 *   description="PaymentAccount"
 * )
 *
 * @property integer $id
 * @property int $user_id
 * @property int $payment_account_id
 * @property string $transaction_type
 * @property string $date
 * @property float $amount
 * @property string $payment_account_type
 * @property string $payment_account_code
 * @property string $payment_account_accountholder
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property PaymentAccount $paymentAccount
 * @property Campaign[] $campaigns
 * @property Interaction[] $interactions
 */
class Accounting extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'payment_account_id', 'transaction_type', 'date', 'amount', 'payment_account_type', 'payment_account_code', 'payment_account_accountholder'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentAccount()
    {
        return $this->belongsTo('App\Models\PaymentAccount');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaigns()
    {
        return $this->hasMany('App\Models\Campaign');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function interactions()
    {
        return $this->hasMany('App\Models\Interaction');
    }
}
