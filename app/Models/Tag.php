<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 *
 * @OA\Schema(
 *   description="Entity Tag",
 *   required={
 *       "id", "code"
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
 *   property="code",
 *   type="string",
 *   format="string",
 *   description="code",
 *   maxLength=200
 * )
 * @OA\Property(
 *   property="description",
 *   type="string",
 *   format="string",
 *   description="description",
 *   maxLength=200
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
 *
 * @property integer $id
 * @property string $code
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property ConsumerTag[] $consumerTags
 * @property CampaignTag[] $campaignTags
 * @property Consumer[] $consumers
 * @property Campaign[] $campaigns
 */
class Tag extends Model
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
    protected $fillable = ['code', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consumerTags()
    {
        return $this->hasMany('App\Models\ConsumerTag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaignTags()
    {
        return $this->hasMany('App\Models\CampaignTag');
    }
}
