<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStoredProcedureMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('air_temp');
            $table->string('air_humid');
            $table->string('soil_temp');
            $table->string('soil_humid');
            $table->string('timestamp');
            $table->timestamps();
        });

        Schema::create('alert', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value');
            $table->string('sensor');
            $table->string('message');
            $table->string('timestamp');
            $table->timestamps();
        });

        $procedure = "
            CREATE PROCEDURE `insert_sensor_data_proc`(p_air_temp TEXT, p_air_humid TEXT, p_soil_temp TEXT, p_soil_humid TEXT, p_timestamp TEXT)
            BEGIN
                 insert into sensor_data(air_temp, air_humid, soil_temp, soil_humid, timestamp, created_at, updated_at)
                 values (p_air_temp, p_air_humid, p_soil_temp, p_soil_humid, p_timestamp, NOW(), NOW());
            END";

        DB::unprepared("DROP procedure IF EXISTS insert_sensor_data_proc");
        DB::unprepared($procedure);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stored_procedure_migration');
    }
}
