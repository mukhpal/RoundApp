<?php

namespace App\Http\Resources;

use App\Models\Campaign;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProducerResource
 *
 * @OA\Schema(
 *   description="Resource Producer",
 *   required={
 *       "id", "user_id", "image_id", "name", "description", "website"
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
 *   property="image_id",
 *   type="integer",
 *   format="integer",
 *   description="image_id",
 * )
 * @OA\Property(
 *   property="name",
 *   type="string",
 *   format="string",
 *   description="name",
 *   maxLength=100
 * )
 * @OA\Property(
 *   property="description",
 *   type="string",
 *   format="text",
 *   description="description",
 * )
 * @OA\Property(
 *   property="website",
 *   type="string",
 *   format="string",
 *   description="website",
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
 * @OA\Property(
 *   property="image",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/File"),
 *   description="File"
 * )
 *
 * @property integer $id
 * @property int $user_id
 * @property int $image_id
 * @property string $name
 * @property string $description
 * @property string $website
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 * @property File $image
 * @property Campaign[] $campaigns
 */
class ProducerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'image' => new FileResource($this->image),
            'name' => $this->name,
            'description' => $this->description,
            'website' => $this->website,
        ];
    }
}
