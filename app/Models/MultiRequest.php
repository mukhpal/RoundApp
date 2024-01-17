<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * Class Glom
 * @package App\Models
 * @property string $uuid
 * @property string $operation
 * @property string $step
 * @property integer $counter
 * @property array $data
 * @property boolean $completed
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 */
class MultiRequest extends Model
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
    protected $fillable = ['uuid', 'operation', 'step', 'counter', 'data', 'completed'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'data' => 'array',
    ];

    /**
     * @param string $operation
     * @param string $step
     * @param array $data
     * @return MultiRequest|Model
     */
    public static function start(string $operation, string $step, $data = [])
    {
        // $url = URL::temporarySignedRoute();
        $attributes = [
            'uuid' => Str::uuid(),
            'operation' => $operation,
            'step' => $step,
            // 'next' => $url,
            'data' => $data
        ];
        return static::create($attributes);
    }

    public static function progress(string $uuid, string $step, array $data = [])
    {
        $model = static::firstWhere('uuid', $uuid);
        $attributes['counter'] = $model->counter + 1;
        $attributes['step'] = $step;
        if(!empty($data))
            $attributes['data'] = $data;
        return $model->update($attributes);
    }

    public static function end(string $uuid, array $data = [])
    {
        $model = static::firstWhere('uuid', $uuid);
        $attributes['counter'] = $model->counter + 1;
        $attributes['completed'] = true;
        if(!empty($data))
            $attributes['data'] = $data;
        return $model->update($attributes);
    }
}
