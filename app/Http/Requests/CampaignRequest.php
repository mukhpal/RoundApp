<?php

namespace App\Http\Requests;

use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use App\Models\Producer;
use App\Rules\OwnerRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Class CampaignRequest
 *
 * @OA\Schema(
 *   description="Request Campaign",
 *   required={
 *       "area_id", "producer_id", "video_id", "accounting_id", "title", "description", "min_age", "max_age", "gender", "budget", "type"
 *   },
 * )
 *
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
 *   property="type",
 *   type="string",
 *   format="string",
 *   description="type",
 *   maxLength=30
 * )
 * @OA\Property(
 *   property="start_date",
 *   type="string",
 *   format="date-time",
 *   description="start_date",
 * )
 */
class CampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function saveModel(Campaign $campaign = null, &$error = null)
    {
        $attributes = $this->getModelAttributes();
        $tags = $this->getTags();
        $success = false;
        DB::beginTransaction();
        try {
            if(!$this->hasProducer()) {
                $producer = Producer::create($this->getProducerAttributes());
                $attributes['producer_id'] = $producer->id;
            }
            else {
                $producer = Producer::findOrFail($this->getProducerId());
                $producer->update($this->getProducerAttributes());
            }
            if(is_null($campaign)) {
                $attributes['reward'] = config('app.video_reward');
                if(!($campaign = Campaign::create($attributes)))
                    throw new \ErrorException("Unable to create campaign");
            }
            else {
                if(!$campaign->update($attributes))
                    throw new \ErrorException("Unable to update campaign {$campaign->id}");
            }
            if($campaign->video && key_exists('thumbnail_id', $attributes) && $campaign->video->thumbnail_id !== $attributes['thumbnail_id']) {
                $campaign->video->thumbnail_id = $attributes['thumbnail_id'];
                $campaign->video->save();
            }
            $campaign->tags()->sync($tags);
            $success = true;
        }
        catch (\Throwable $e) {
            $error = $e->getMessage();
        }
        if($success)
            DB::commit();
        else
            DB::rollBack();
        return $campaign;
    }

    public function getModelAttributes()
    {
        $keys = [
            'title', 'description', 'budget', 'reward', 'start_date', 'end_date', 'geolocation', 'area_id', 'producer_id', 'accounting_id'
        ];
        $attributes = [];
        foreach ($keys as $k => $v) {
            if(is_string($k)) {
                $key = $k;
                $default = $v;
            }
            else {
                $key = $v;
                $default = null;
            }
            if($this->post($key)) {
                $attributes[$key] = $this->post($key, $default);
            }
        }
        $attributes = array_merge($attributes, $this->normalizedAttributes());
        return $attributes;
    }

    public function getModelId()
    {
        return $this->post('id');
    }

    public function getTags()
    {
        $tags = $this->post('tags');
        return isset($tags) && is_array($tags) ? $this->post('tags') : [];
    }

    public function getProducerId()
    {
        $producer = $this->post('producer');
        return $producer && isset($producer['id']) ? $producer['id'] : null;
    }
    public function getVideoId()
    {
        $video = $this->post('video');
        return isset($video['id']) ? $video['id'] : null;
    }

    public function hasProducer()
    {
        return !is_null($this->getProducerId());
    }

    public function getProducerAttributes()
    {
        $attributes = null;
        if($this->post('producer')) {
            $attributes = [
                'id' => $this->post('producer')['id'] ?? null,
                'user_id' => auth()->user()->id,
                'name' => $this->post('producer')['name'],
                'description' => $this->post('producer')['description'] ?? null,
                'website' => $this->post('producer')['website'] ?? null,
                'image_id' => $this->post('producer')['image_id'] ?? null
            ];
        }
        return $attributes;
    }

    public function normalizedAttributes()
    {
        $attributes = [];
        $age = $this->post('age');
        $gender = $this->post('gender');
        $people = $this->post('people', 0);
        $paymentType = $this->post('paymentType');
        $video = $this->post('video');
        // $videoId = $this->getVideoId();
        $producerId = $this->getProducerId();
        if (isset($age)) {
            list($attributes['min_age'], $attributes['max_age']) = explode('-', $age);
        }
        if (isset($gender)) {
            $attributes['gender'] = config('constants.pool.gender')[$gender]['value'];
        }
        if (isset($people)) {
            $attributes['people'] = $people;
        }
        if (isset($paymentType)) {
            $attributes['type'] = key_exists($paymentType, config('constants.pool.paymentType')) ? $paymentType : null;
        }
        if (isset($producerId)) {
            $attributes['producer_id'] = $producerId;
        }
        if (isset($video)) {
            $attributes['video_id'] = $video['id'];
            $attributes['thumbnail_id'] = $video['thumbnail_id'] ?? null;
        }
        return array_filter($attributes, function($item) {
            return !is_null($item);
        });
    }

    public function prepareForValidation()
    {
        $this->merge($this->normalizedAttributes());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Route $route
     * @return array
     */
    public function rules(Route $route)
    {
        $create = $route->getActionMethod() === 'store';
        return [
            'id' => [Rule::requiredIf(!$create), 'integer'],
            'title' => [Rule::requiredIf($create), 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'geolocation' => ['nullable', 'string'],
            'min_age' => ['integer'],
            'max_age' => ['integer'],
            'gender' => [Rule::requiredIf($create), 'integer'],
            'budget' => [Rule::requiredIf($create), 'numeric'],
            'reward' => ['numeric'],
            'type' => [Rule::requiredIf($create), 'string', 'max:30'],
            'people' => ['integer'],
            'start_date' => ['date'],
            'end_date' => ['date'],
            'area_id' => ['exists:areas,id'],
            'producer_id' => ['exists:producers,id', new OwnerRule()],
            'video_id' => ['exists:videos,id', new OwnerRule(['relation' => 'file'])],
            'accounting_id' => ['exists:accountings,id', new OwnerRule()],
            'producer.name' => [Rule::requiredIf($this->hasProducer()), 'string'],
            'thumbnail_id' => ['integer', 'exists:files,id', new OwnerRule(['entity' => 'File'])],
        ];
    }
}
