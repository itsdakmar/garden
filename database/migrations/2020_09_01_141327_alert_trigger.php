<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlertTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $trigger = 'CREATE TRIGGER sensor_alert
            AFTER INSERT ON sensor_data FOR EACH ROW
            BEGIN
              IF NEW.air_temp >= 40 THEN
                 INSERT INTO alert( value, sensor, message, timestamp, created_at, updated_at )
                 VALUES ( NEW.air_temp, "DHT22_temp", "Your air temperature have reach over 40 degree Celsius", new.timestamp , NOW(), NOW());
              END IF;
              IF new.air_humid >= 40 THEN
                 INSERT INTO alert( value, sensor, message, timestamp, created_at, updated_at )
                 VALUES ( new.air_humid, "DHT22_hum", "Your air temperature have reach over 40 degree Celsius", new.timestamp, NOW(), NOW());
              END IF;
              IF new.soil_temp >= 40 THEN
                 INSERT INTO alert( value, sensor, message, timestamp, created_at, updated_at )
                 VALUES ( new.soil_temp, "DS18B20_temp", "Your air temperature have reach over 40 degree Celsius", new.timestamp, NOW(), NOW());
              END IF;
              IF new.soil_humid >= 40 THEN
                 INSERT INTO alert( value, sensor, message, timestamp, created_at, updated_at )
                 VALUES ( new.soil_humid, "soilMoister", "Your air temperature have reach over 40 degree Celsius", new.timestamp, NOW(), NOW());
              END IF;
            END;';

        DB::unprepared($trigger);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
