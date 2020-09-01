<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $air_temp
 * @property string $air_humid
 * @property string $soil_temp
 * @property string $soil_humid
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 */
class SensorData extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sensor_data';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['air_temp', 'air_humid', 'soil_temp', 'soil_humid', 'timestamp', 'created_at', 'updated_at'];

}
