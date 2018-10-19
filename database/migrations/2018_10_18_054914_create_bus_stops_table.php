<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusStopsTable extends Migration
{
    public function up()
    {
        Schema::create('bus_stops', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid', 100)->unique();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bus_stops');
    }
}
