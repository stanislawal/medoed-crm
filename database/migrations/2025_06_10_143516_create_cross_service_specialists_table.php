<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrossServiceSpecialistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cross_service_specialists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services_project');
            $table->foreignId('specialist_service_id')->constrained('specialist_services');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cross_service_specialists');
    }
}
