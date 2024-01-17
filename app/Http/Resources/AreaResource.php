<?php

namespace App\Http\Resources;

use App\Models\Campaign;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CampaignResource
 *
 * @OA\Schema(
 *   description="Resource Area",
 *   required={
 *       "id", "type", "address", "latitude", "longitude", "radius", "description", "data"
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
 *   format="string",
 *   description="type",
 *   maxLength=30
 * )
 * @OA\Property(
 *   property="address",
 *   type="string",
 *   format="string",
 *   description="address",
 *   maxLength=300
 * )
 * @OA\Property(
 *   property="latitude",
 *   type="number",
 *   format="decimal",
 *   description="latitude",
 * )
 * @OA\Property(
 *   property="longitude",
 *   type="number",
 *   format="decimal",
 *   description="longitude",
 * )
 * @OA\Property(
 *   property="radius",
 *   type="number",
 *   format="decimal",
 *   description="radius",
 * )
 * @OA\Property(
 *   property="description",
 *   type="string",
 *   format="text",
 *   description="description",
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
 *
 * @property integer $id
 * @property string $type
 * @property string $address
 * @property float $latitude
 * @property float $longitude
 * @property float $radius
 * @property string $description
 * @property mixed $data
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Campaign[] $campaigns
 */
class AreaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'radius' => $this->radius,
            'description' => $this->description,
            'data' => $this->data,
        ];
    }
}
