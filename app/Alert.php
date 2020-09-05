<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $value
 * @property string $sensor
 * @property string $message
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 */
class Alert extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alert';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';
    protected $dates = ['timestamp'];

    /**
     * @var array
     */
    protected $fillable = ['value', 'sensor', 'message', 'timestamp', 'created_at', 'updated_at'];

}
