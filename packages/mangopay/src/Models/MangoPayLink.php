<?php

namespace FraSim\MangoPay\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class MangoPayLink
 * @package FraSim\MangoPay\Models
 * @property integer $id
 * @property integer $external_id
 * @property string $entity
 * @property string $uuid
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class MangoPayLink extends Model
{

    protected $table = 'mangopay_link';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'external_id', 'entity', 'uuid'];

}
