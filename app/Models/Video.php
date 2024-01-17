<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Video
 *
 * @OA\Schema(
 *   description="Entity Video",
 *   required={
 *       "id", "file_id", "thumbnail_id", "title", "url", "duration", "data"
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
 *   property="file_id",
 *   type="integer",
 *   format="integer",
 *   description="file_id",
 * )
 * @OA\Property(
 *   property="thumbnail_id",
 *   type="integer",
 *   format="integer",
 *   description="thumbnail_id",
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
 * @OA\Property(
 *   property="duration",
 *   type="number",
 *   format="decimal",
 *   description="duration",
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
class Video extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['file_id', 'thumbnail_id', 'title', 'url', 'duration', 'data'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo('App\Models\File');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thumbnail()
    {
        return $this->belongsTo('App\Models\File', 'thumbnail_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaigns()
    {
        return $this->hasMany('App\Models\Campaign');
    }
}
