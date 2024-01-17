<?php

namespace App\Http\Resources;

use App\Models\Accounting;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PaymentAccountResource
 *
 * @OA\Schema(
 *   description="Resource PaymentAccount",
 *   required={
 *       "id", "user_id", "type", "code"
 *   },
 * )
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
 *   property="type",
 *   type="string",
 *   format="string",
 *   description="type",
 *   maxLength=30
 * )
 * @OA\Property(
 *   property="code",
 *   type="string",
 *   format="string",
 *   description="code",
 *   maxLength=50
 * )
 * @OA\Property(
 *   property="check",
 *   type="string",
 *   format="string",
 *   description="check",
 *   maxLength=10
 * )
 * @OA\Property(
 *   property="accountholder",
 *   type="string",
 *   format="string",
 *   description="accountholder",
 *   maxLength=300
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
 * @OA\Property(
 *   property="description",
 *   type="string",
 *   description="description",
 * )
 * @OA\Property(
 *   property="user",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/User"),
 *   description="User"
 * )
 *
 * @property integer $id
 * @property int $user_id
 * @property string $type
 * @property string $code
 * @property string $check
 * @property string $accountholder
 * @property mixed $data
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 * @property Accounting[] $accountings
 *
 * @property-read string $description
 */
class PaymentAccountResource extends JsonResource
{
    public $withUserResource = true;

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
            'user' => $this->withUserResource ? new UserResource($this->user) : $this->user,
            'type' => $this->type,
            'code' => $this->code,
            // 'check' => $this->check,
            'description' => $this->description,
            'accountholder' => $this->accountholder,
            'data' => $this->data
        ];
    }
}
