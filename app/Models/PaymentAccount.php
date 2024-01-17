<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentAccount
 *
 * @OA\Schema(
 *   description="Entity PaymentAccount",
 *   required={
 *       "id", "user_id", "type", "code"
 *   },
 * )
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
 *   property="type",
 *   type="string",
 *   format="string",
 *   description="type",
 *   maxLength=30
 * )
 * @OA\Property(
 *   property="code",
 *   type="string",
 *   format="string",
 *   description="code",
 *   maxLength=50
 * )
 * @OA\Property(
 *   property="check",
 *   type="string",
 *   format="string",
 *   description="check",
 *   maxLength=10
 * )
 * @OA\Property(
 *   property="accountholder",
 *   type="string",
 *   format="string",
 *   description="accountholder",
 *   maxLength=300
 * )
 * @OA\Property(
 *   property="data",
 *   type="json",
 *   format="json",
 *   description="data",
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
 *   property="deleted_at",
 *   type="string",
 *   format="date-time",
 *   description="deleted_at",
 * )
 * @OA\Property(
 *   property="description",
 *   type="string",
 *   description="description",
 * )
 *
 * @OA\Property(
 *   property="user",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/User"),
 *   description="User"
 * )
 *
 * @property integer $id
 * @property int $user_id
 * @property string $type
 * @property string $code
 * @property string $check
 * @property string $accountholder
 * @property mixed $data
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 * @property Accounting[] $accountings
 *
 * @property-read string $description
 */
class PaymentAccount extends Model
{
    const PAYMENT_TYPE_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_TYPE_CREDIT_CARD = 'credit_card';
    const PAYMENT_TYPE_DIRECT_DEBIT = 'direct_debit';

    static $paymentTypes = [
        self::PAYMENT_TYPE_BANK_TRANSFER => 'Bonifico Bancario',
        self::PAYMENT_TYPE_CREDIT_CARD => 'Carta di Credito',
        self::PAYMENT_TYPE_DIRECT_DEBIT => 'Addebito in Conto'
    ];

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'type', 'code', 'check', 'accountholder', 'data'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountings()
    {
        return $this->hasMany('App\Models\Accounting');
    }

    /**
     * @return string|null
     */
    public function getDescriptionAttribute()
    {
        return static::$paymentTypes[$this->type] ?? null;
    }
}
