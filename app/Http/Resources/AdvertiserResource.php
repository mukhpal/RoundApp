<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AdvertiserResource
 *
 * @OA\Schema(
 *   description="Entity Advertiser Resource",
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
class AdvertiserResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'type' => $this->type,
            'profile_completion_percentage' => $this->profile_completion_percentage
        ];
    }
}
