<?php

namespace App\Http\Resources;

use App\Models\Accounting;
use App\Models\Area;
use App\Models\Campaign;
use App\Models\Interaction;
use App\Models\Producer;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CampaignResource
 *
 * @OA\Schema(
 *   description="Resource Campaign",
 *   required={
 *   },
 * )
 *
 * @OA\Property(
 *   property="id",
 *   type="integer",
 *   format="bigint",
 *   description="id"
 * )
 *
 * @OA\Property(
 *   property="title",
 *   type="string",
 *   format="string",
 *   description="title",
 *   maxLength=100
 * )
 *
 * @OA\Property(
 *   property="description",
 *   type="string",
 *   format="text",
 *   description="description"
 * )
 *
 * @OA\Property(
 *   property="tags",
 *   type="array",
 *   format="array",
 *   @OA\Items(ref="#/components/schemas/Tag"),
 *      description="tags"
 * )
 *
 * @OA\Property(
 *   property="age",
 *   type="integer",
 *   format="integer",
 *   description="age"
 * )
 *
 * @OA\Property(
 *   property="gender",
 *   type="string",
 *   enum={"male", "female"},
 *   description="gender"
 * )
 *
 * @OA\Property(
 *   property="area",
 *   type="string",
 *   description="area"
 * )
 *
 * @OA\Property(
 *   property="paymentType",
 *   type="string",
 *   enum={"one", "all", "one_a_day"},
 *   description="paymentType"
 * )
 *
 * @OA\Property(
 *   property="people",
 *   type="integer",
 *   description="people"
 * )
 *
 * @OA\Property(
 *   property="producer",
 *   ref="#/components/schemas/ProducerResource"
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
 * @property string $start_date
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Area $area
 * @property Producer $producer
 * @property Video $video
 * @property Accounting $accounting
 * @property Interaction[] $interactions
 * @property Tag[] $tags
 */
class CampaignResource extends JsonResource
{

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->additional = ['pool' => new CampaignInfoResource($resource)];
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $gender = Campaign::GenderList()->map(function($v, $k) {
            return $v['value'];
        })->flip()->get($this->gender);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'video' => new VideoResource($this->video),
            'budget' => (float)$this->budget,
            'reward' => (float)$this->reward,
            'tags' => TagResource::collection($this->tags),
            'age' => "{$this->min_age}-{$this->max_age}",
            'gender' => $gender,
            //'area' => new AreaResource($this->area),
            'geolocation' => $this->geolocation,
            'paymentType' => $this->type,
            'people' => $this->people,
            'producer' => new ProducerResource($this->producer)
        ];
    }

    public static function Meta()
    {
        return [
            'tags' => TagResource::collection(Tag::all()),
            'producers' => ProducerResource::collection(auth()->user()->producers),
            'ages' => Campaign::AgeList()->values(),
            'genders' => Campaign::GenderList()->values(),
            'paymentTypes' => Campaign::PaymentTypeList()->values(),
            'reward' => config('app.video_reward'),
        ];
    }

    /**
     * Create new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        $collection = parent::collection($resource);
        $collection->additional = ['pool' => static::Meta()];
        return $collection;
    }
}
