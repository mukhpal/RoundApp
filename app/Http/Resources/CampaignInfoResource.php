<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CampaignResource
 *
 * @OA\Schema(
 *   description="Resource CampaignInfo",
 *   required={
 *   },
 * )
 *
 * @OA\Property(
 *   property="tags",
 *   type="array",
 *   format="array",
 *   @OA\Items(ref="#/components/schemas/TagResource"),
 *      description="tags"
 * )
 *
 * @OA\Property(
 *   property="producers",
 *   type="array",
 *   format="array",
 *   @OA\Items(ref="#/components/schemas/ProducerResource"),
 *      description="producers"
 * )
 *
 * @OA\Property(
 *   property="ages",
 *   type="array",
 *   description="ages",
 *   @OA\Items(
 *      @OA\Property(
 *        property="id",
 *        type="string",
 *        description="id"
 *     ),
 *      @OA\Property(
 *        property="text",
 *        type="string",
 *        description="text"
 *     ),
 *   ),
 * )
 *
 * @OA\Property(
 *   property="genders",
 *   type="array",
 *   format="array",
 *   description="gender",
 *   @OA\Items(
 *      @OA\Property(
 *        property="id",
 *        type="string",
 *        description="id"
 *     ),
 *      @OA\Property(
 *        property="text",
 *        type="string",
 *        description="text"
 *     ),
 *   ),
 * )
 *
 * @OA\Property(
 *   property="paymentTypes",
 *   type="array",
 *   format="array",
 *   description="paymentTypes",
 *   @OA\Items(
 *      @OA\Property(
 *        property="id",
 *        type="string",
 *        description="id"
 *     ),
 *      @OA\Property(
 *        property="text",
 *        type="string",
 *        description="text"
 *     ),
 *   ),
 * )
 *
 * @property TagResource[] $tags
 * @property ProducerResource[] $producers
 * @property mixed $ages
 * @property mixed $genders
 * @property mixed $paymentTypes
 */
class CampaignInfoResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return CampaignResource::Meta();
    }
}
