<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_id')->unsigned();
            $table->integer('stop_id')->unsigned();
            $table->integer('route_id')->unsigned();
            $table->tinyInteger('day')->unsigned();
            $table->time('time');
            
            $table->unique([
                'vehicle_id',
                'stop_id',
                'route_id',
                'day',
                'time'
            ]);
            
            $table
                ->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->onDelete('cascade');
            
            $table
                ->foreign('stop_id')
                ->references('id')
                ->on('stops')
                ->onDelete('cascade');
            
            $table
                ->foreign('route_id')
                ->references('id')
                ->on('routes')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
