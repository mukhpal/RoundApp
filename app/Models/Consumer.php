<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Consumer
 *
 * @OA\Schema(
 *   description="Entity Consumer",
 *   required={
 *       "id", "user_id", "name", "surname", "birth_year", "gender"
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
 *   property="name",
 *   type="string",
 *   format="string",
 *   description="name",
 *   maxLength=100
 * )
 * @OA\Property(
 *   property="surname",
 *   type="string",
 *   format="string",
 *   description="surname",
 *   maxLength=100
 * )
 * @OA\Property(
 *   property="birth_year",
 *   type="integer",
 *   format="integer",
 *   description="birth_year",
 * )
 * @OA\Property(
 *   property="gender",
 *   type="integer",
 *   format="smallint",
 *   description="gender",
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
 *   property="user",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/User"),
 *   description="User"
 * )
 *
 * @property integer $id
 * @property int $user_id
 * @property string $name
 * @property string $surname
 * @property int $birth_year
 * @property integer $gender
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 * @property Interaction[] $interactions
 * @property ConsumerTag[] $consumerTags
 */
class Consumer extends Model
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
    protected $fillable = ['user_id', 'name', 'surname', 'birth_year', 'gender'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function user()
    {
        return $this->morphOne('App\Models\User', 'someone');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function interactions()
    {
        return $this->hasMany('App\Models\Interaction');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consumerTags()
    {
        return $this->hasMany('App\Models\ConsumerTag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
