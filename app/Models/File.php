<?php

namespace App\Models;

use App\Events\FileDeleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Krlove\EloquentModelGenerator\Model\EloquentModel;

/**
 * Class File
 *
 * @OA\Schema(
 *   description="Entity File",
 *   required={
 *       "id", "uuid", "user_id", "type", "path", "size", "mime_type", "original_name", "name", "description", "hash_alg", "hash_file"
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
 *   property="path",
 *   type="string",
 *   format="string",
 *   description="path",
 *   maxLength=2000
 * )
 * @OA\Property(
 *   property="fullPath",
 *   type="string",
 *   format="string",
 *   description="fullPath",
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
 * @property-read  string $fullPath
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
 * @mixin EloquentModel
 */
class File extends Model
{
    const TYPE_VIDEO = 'video';
    const TYPE_VIDEO_THUMBNAIL = 'video.thumbnail';
    const TYPE_USER_IMAGE = 'user.image';
    const TYPE_ADVERTISER_IMAGE = 'advertiser.image';
    const TYPE_PRODUCER_IMAGE = 'producer.image';
    const TYPE_CONSUMER_IMAGE = 'consumer.image';

    static $paths = [
        self::TYPE_VIDEO => '',
        self::TYPE_VIDEO_THUMBNAIL => '',
        self::TYPE_USER_IMAGE => '',
        self::TYPE_ADVERTISER_IMAGE => '',
        self::TYPE_PRODUCER_IMAGE => '',
        self::TYPE_CONSUMER_IMAGE => '',
    ];

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'uuid', 'type', 'path', 'size', 'mime_type', 'original_name', 'name', 'description', 'hash_alg', 'hash_file'];

    /*
    protected $dispatchesEvents = [
        'deleted' => FileDeleted::class,
    ];
    */

    public function getUrlAttribute()
    {
        return URL::to(config('app.api_url') . '/files/' . Str::kebab($this->type) . '/' . $this->uuid);
    }

    public function getFullPathAttribute()
    {
        return storage_path('app/' . $this->path);
    }

    /**
     * Unlink file specified in fullPath attribute
     * @return bool
     */
    public function unlink()
    {
        return is_file($this->fullPath) && unlink($this->fullPath);
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return is_file($this->fullPath) && file_exists($this->fullPath);
    }

    /**
     * Unlink file and delete file record
     *
     * @return bool
     * @throws \Exception
     */
    public function remove()
    {
        return (!$this->exists() || $this->unlink()) && $this->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function producers()
    {
        return $this->hasMany('App\Models\Producer', 'image_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function video()
    {
        return $this->hasOne('App\Models\Video');
    }

    public static function Path(string $type): string
    {
        return key_exists($type, static::$paths) ? static::$paths[$type] . $type : $type;
    }

    /**
     * @param UploadedFile $file
     * @param string $type
     * @return File|null
     */
    public static function Upload(UploadedFile $file, string $type)
    {
        $path = $file->store(static::Path($type));
        $attributes = [
            'uuid' => Str::uuid()->toString(),
            'user_id' => auth()->user()->id,
            'type' => $type,
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'original_name' => $file->getClientOriginalName(),
            'name' => $file->getClientOriginalName(),
            'description' => $file->getClientOriginalName(),
            'hash_alg' => 'md5',
            'hash_file' => md5_file($file->getRealPath())
        ];
        return static::create($attributes);
    }
}
