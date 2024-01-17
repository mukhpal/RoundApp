<?php

namespace App\Http\Resources;

use App\Models\Accounting;
use App\Models\Campaign;
use App\Models\Consumer;
use App\Models\File;
use App\Models\PaymentAccount;
use App\Models\Producer;
use App\Models\UserSocial;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TagResource
 *
 * @OA\Schema(
 *   description="Resource User",
 *   required={
 *       "id", "name", "email", "password"
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
 *   property="name",
 *   type="string",
 *   format="string",
 *   description="name",
 *   maxLength=255
 * )
 * @OA\Property(
 *   property="email",
 *   type="string",
 *   format="email",
 *   description="email",
 *   maxLength=255
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
 *
 * @property integer $id
 * @property string $name
 * @property string $role
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property Producer[] $producers
 * @property File[] $files
 * @property Consumer[] $consumers
 * @property Accounting[] $accountings
 * @property PaymentAccount $favouritePaymentAccount
 * @property PaymentAccount[] $paymentAccounts
 * @property UserSocial[] $userSocials
 * @property File $image
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $fileResource = $this->image ? new FileResource($this->image) : null;
        if ($fileResource) {
            $fileResource->withUserResource = false;
        }
        $paymentAccountResource = $this->favouritePaymentAccount ? new PaymentAccountResource($this->favouritePaymentAccount) : null;
        if ($paymentAccountResource) {
            $paymentAccountResource->withUserResource = false;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->role,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'verified' => $this->hasVerifiedEmail(),
            'last_login_at' => $this->last_login_at,
            'last_login_ip' => $this->last_login_ip,
            'image' => $fileResource,
            'favouritePaymentAccount' => $paymentAccountResource
        ];
    }
}
