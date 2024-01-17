<?php

namespace App\Http\Resources;


use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CampaignsCountersResource
 *
 * @OA\Schema(
 *   description="Resource CampaignsCountersResource",
 * )
 * @OA\Property(
 *   property="videos",
 *   type="integer",
 *   format="bigint"
 * )
 * @OA\Property(
 *   property="producers",
 *   type="integer",
 *   format="bigint"
 * )
 * @OA\Property(
 *   property="costs",
 *   type="integer",
 *   format="bigint"
 * )
 * @OA\Property(
 *   property="views_global",
 *   type="integer",
 *   format="bigint"
 * )
 * @OA\Property(
 *   property="views_paid",
 *   type="integer",
 *   format="bigint"
 * )
 * @OA\Property(
 *   property="views_free",
 *   type="integer",
 *   format="bigint"
 * )
 * @OA\Property(
 *   property="clicks",
 *   type="integer",
 *   format="bigint"
 * )
 */
class CampaignsCountersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /* @var User $user */
        $user = $request->user();
        return [
            'videos' => $user->campaigns()->count(),
            'producers' => $user->producers()->count(),
            'costs' => rand(0, 999),
            'views_global' => rand(99, 9999),
            'views_paid' => rand(99, 9999),
            'views_free' => rand(99, 9999),
            'clicks' => rand(99, 9999),
        ];
    }
}
