<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Interaction
 *
 * @OA\Schema(
 *   description="Entity Interaction",
 *   required={
 *       "id", "accounting_id", "consumer_id", "campaign_id", "type", "data"
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
 *   property="accounting_id",
 *   type="integer",
 *   format="integer",
 *   description="accounting_id",
 * )
 * @OA\Property(
 *   property="consumer_id",
 *   type="integer",
 *   format="integer",
 *   description="consumer_id",
 * )
 * @OA\Property(
 *   property="campaign_id",
 *   type="integer",
 *   format="integer",
 *   description="campaign_id",
 * )
 * @OA\Property(
 *   property="type",
 *   type="string",
 *   format="string",
 *   description="type",
 *   maxLength=30
 * )
 * @OA\Property(
 *   property="data",
 *   type="json",
 *   format="json",
 *   description="data",
 * )
 * @OA\Property(
 *   property="started_at",
 *   type="string",
 *   format="date-time",
 *   description="started_at",
 * )
 * @OA\Property(
 *   property="finished_at",
 *   type="string",
 *   format="date-time",
 *   description="finished_at",
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
 *   property="accounting",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/Accounting"),
 *   description="Accounting"
 * )
 * @OA\Property(
 *   property="consumer",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/Consumer"),
 *   description="Consumer"
 * )
 * @OA\Property(
 *   property="campaign",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/Campaign"),
 *   description="Campaign"
 * )
 *
 * @property integer $id
 * @property int $accounting_id
 * @property int $consumer_id
 * @property int $campaign_id
 * @property string $type
 * @property mixed $data
 * @property string $started_at
 * @property string $finished_at
 * @property string $created_at
 * @property string $updated_at
 * @property Accounting $accounting
 * @property Consumer $consumer
 * @property Campaign $campaign
 */
class Interaction extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'started_at',
        'finished_at',
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
    protected $fillable = ['accounting_id', 'consumer_id', 'campaign_id', 'type', 'data', 'started_at', 'finished_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accounting()
    {
        return $this->belongsTo('App\Models\Accounting');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consumer()
    {
        return $this->belongsTo('App\Models\Consumer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign');
    }
}
