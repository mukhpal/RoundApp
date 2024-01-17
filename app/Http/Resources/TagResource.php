<?php

namespace App\Http\Resources;

use App\Models\Campaign;
use App\Models\Consumer;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TagResource
 *
 * @OA\Schema(
 *   description="Resource Tag",
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
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Consumer[] $consumers
 * @property Campaign[] $campaigns
 */
class TagResource extends JsonResource
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
            'code' => $this->code
        ];
    }
}
