<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Advertiser
 *
 * @OA\Schema(
 *   description="Entity Advertiser",
 *   required={
 *       "type"
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
 *   property="type",
 *   type="string",
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
 * @property string $type
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 *
 * @property-read int $profile_completion_percentage
 */
class Advertiser extends Model
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
    protected $fillable = ['type'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function user()
    {
        return $this->morphOne('App\Models\User', 'someone');
    }

    /**
     * Attribute accessor $profile_completion_percentage
     *
     * @return int
     */
    public function getProfileCompletionPercentageAttribute()
    {
        $percentage = 50;
        $producers = $this->user->producers();
        if(isset($producers) && $producers->count() > 0) {
            $percentage += 20;
        }
        if(!empty($this->user->image))
            $percentage += 20;
        if(!empty($this->type))
            $percentage += 10;
        return $percentage;
    }
}
