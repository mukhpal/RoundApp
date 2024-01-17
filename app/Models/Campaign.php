<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Campaign
 *
 * @OA\Schema(
 *   description="Entity Campaign",
 *   required={
 *       "producer_id", "title", "gender", "budget", "type"
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
 *   property="area_id",
 *   type="integer",
 *   format="integer",
 *   description="area_id",
 * )
 * @OA\Property(
 *   property="producer_id",
 *   type="integer",
 *   format="integer",
 *   description="producer_id",
 * )
 * @OA\Property(
 *   property="video_id",
 *   type="integer",
 *   format="integer",
 *   description="video_id",
 * )
 * @OA\Property(
 *   property="accounting_id",
 *   type="integer",
 *   format="integer",
 *   description="accounting_id",
 * )
 * @OA\Property(
 *   property="title",
 *   type="string",
 *   format="string",
 *   description="title",
 *   maxLength=100
 * )
 * @OA\Property(
 *   property="description",
 *   type="string",
 *   format="text",
 *   description="description",
 * )
 * @OA\Property(
 *   property="geolocation",
 *   type="string",
 *   format="string",
 *   description="geolocation",
 *   maxLength=100
 * )
 * @OA\Property(
 *   property="min_age",
 *   type="integer",
 *   format="integer",
 *   description="min_age",
 * )
 * @OA\Property(
 *   property="max_age",
 *   type="integer",
 *   format="integer",
 *   description="max_age",
 * )
 * @OA\Property(
 *   property="gender",
 *   type="integer",
 *   format="smallint",
 *   description="gender",
 * )
 * @OA\Property(
 *   property="budget",
 *   type="number",
 *   format="decimal",
 *   description="budget",
 * )
 * @OA\Property(
 *   property="reward",
 *   type="number",
 *   format="decimal",
 *   description="reward",
 * )
 * @OA\Property(
 *   property="type",
 *   type="string",
 *   format="string",
 *   description="type",
 *   maxLength=30
 * )
 * @OA\Property(
 *   property="people",
 *   type="integer",
 *   format="integer",
 *   description="people"
 * )
 * @OA\Property(
 *   property="start_date",
 *   type="string",
 *   format="date-time",
 *   description="start_date",
 * )
 * @OA\Property(
 *   property="end_date",
 *   type="string",
 *   format="date-time",
 *   description="end_date",
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
 *   property="area",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/Area"),
 *   description="Area"
 * )
 * @OA\Property(
 *   property="producer",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/Producer"),
 *   description="Producer"
 * )
 * @OA\Property(
 *   property="video",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/Video"),
 *   description="Video"
 * )
 * @OA\Property(
 *   property="accounting",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/Accounting"),
 *   description="Accounting"
 * )
 *
 * @property integer $id
 * @property int $area_id
 * @property int $producer_id
 * @property int $video_id
 * @property int $accounting_id
 * @property string $title
 * @property string $description
 * @property string $geolocation
 * @property int $min_age
 * @property int $max_age
 * @property int $gender
 * @property float $budget
 * @property float $reward
 * @property string $type
 * @property integer $people
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * Relations
 * @property Area $area
 * @property Producer $producer
 * @property Video $video
 * @property Accounting $accounting
 * @property Interaction[] $interactions
 * @property CampaignTag[] $campaignTags
 * @property User $user
 *
 * Accessors/Mutators
 * @property-read boolean $started
 */
class Campaign extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
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
    protected $fillable = ['area_id', 'producer_id', 'video_id', 'accounting_id', 'title', 'description', 'geolocation', 'min_age', 'max_age', 'gender', 'budget', 'reward', 'type', 'people', 'start_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producer()
    {
        return $this->belongsTo('App\Models\Producer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video()
    {
        return $this->belongsTo('App\Models\Video');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accounting()
    {
        return $this->belongsTo('App\Models\Accounting');
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
    public function campaignTags()
    {
        return $this->hasMany('App\Models\CampaignTag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->producer->user();
    }

    /**
     * @return bool
     */
    public function getStartedAttribute()
    {
        $now = time();
        return isset($this->start_date) && $this->start_date->timestamp < $now;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function AgeList()
    {
        return collect(config('constants.pool.age'))->map(function($item, $key) {
            return ['id' => $key, 'text' => $item];
        });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function GenderList()
    {
        return collect(config('constants.pool.gender'))->map(function($item, $key) {
            return ['id' => $key, 'text' => $item['text'], 'value' => $item['value']];
        });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function PaymentTypeList()
    {
        return collect(config('constants.pool.paymentType'))->map(function($item, $key) {
            return ['id' => $key, 'text' => $item];
        });
    }
}
