<?php

namespace App\Http\Resources;

use App\Models\Campaign;
use App\Models\Consumer;
use App\Models\File;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class VideoResource
 *
 * @OA\Schema(
 *   description="Resource Video",
 *   required={
 *       "id", "title", "url"
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
 *   property="title",
 *   type="string",
 *   format="string",
 *   description="title",
 *   maxLength=255
 * )
 * @OA\Property(
 *   property="url",
 *   type="string",
 *   format="string",
 *   description="url",
 *   maxLength=255
 * )
 *
 * @OA\Property(
 *   property="file",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/File"),
 *   description="File"
 * )
 * @OA\Property(
 *   property="thumbnail",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/File"),
 *   description="File"
 * )
 *
 * @property integer $id
 * @property int $file_id
 * @property int $thumbnail_id
 * @property string $title
 * @property string $url
 * @property float $duration
 * @property mixed $data
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property File $file
 * @property File $thumbnail
 * @property Campaign[] $campaigns
 */
class VideoResource extends JsonResource
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
            'title' => $this->title,
            'url' => $this->url ?? $this->file->url,
            'file' => new FileResource($this->file),
            'image' => new FileResource($this->thumbnail)
        ];
    }
}
