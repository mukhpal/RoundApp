<?php

namespace App\Http\Resources;

use App\Models\Campaign;
use App\Models\Consumer;
use App\Models\Producer;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * Class TagResource
 *
 * @OA\Schema(
 *   description="Resource File",
 *   required={
 *       "id", "uuid","user_id", "type", "resource", "size", "mime_type", "original_name", "name", "description", "hash_alg", "hash_file"
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
 *   property="uuid",
 *   type="string",
 *   description="uuid",
 * )
 * @OA\Property(
 *   property="url",
 *   type="string",
 *   description="url",
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
 *   property="resource",
 *   type="string",
 *   format="string",
 *   description="resource",
 *   maxLength=2000
 * )
 * @OA\Property(
 *   property="size",
 *   type="integer",
 *   format="integer",
 *   description="size",
 * )
 * @OA\Property(
 *   property="mime_type",
 *   type="string",
 *   format="string",
 *   description="mime_type",
 *   maxLength=100
 * )
 * @OA\Property(
 *   property="original_name",
 *   type="string",
 *   format="string",
 *   description="original_name",
 *   maxLength=300
 * )
 * @OA\Property(
 *   property="name",
 *   type="string",
 *   format="string",
 *   description="name",
 *   maxLength=300
 * )
 * @OA\Property(
 *   property="description",
 *   type="string",
 *   format="text",
 *   description="description",
 * )
 * @OA\Property(
 *   property="hash_alg",
 *   type="string",
 *   format="string",
 *   description="hash_alg",
 *   maxLength=100
 * )
 * @OA\Property(
 *   property="hash_file",
 *   type="string",
 *   format="string",
 *   description="hash_file",
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
 *
 * @property integer $id
 * @property string $uuid
 * @property string $url
 * @property int $user_id
 * @property string $type
 * @property string $path
 * @property int $size
 * @property string $mime_type
 * @property string $original_name
 * @property string $name
 * @property string $description
 * @property string $hash_alg
 * @property string $hash_file
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 * @property Producer[] $producers
 * @property Video $video
 *
 * @property-read string $fullPath
 */
class FileResource extends JsonResource
{
    public $withUserResource = true;

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

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
            'uuid' => $this->uuid,
            'url' => $this->url,
            'user' => $this->withUserResource ? new UserResource($this->user) : $this->user,
            'type' => $this->type,
            'path' => $this->path,
            'fullPath' => $this->fullPath,
            'size' => $this->size,
            'mime_type' => $this->mime_type,
            'original_name' => $this->original_name,
            'name' => $this->name,
            'description' => $this->description,
            'hash_alg' => $this->hash_alg,
            'hash_file' => $this->hash_alg,
        ];
    }
}
