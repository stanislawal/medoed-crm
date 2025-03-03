<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lids', function (Blueprint $table) {
            $table->id();
            $table->string('advertising_company', 255);
            $table->date('date_receipt');
            $table->unsignedBigInteger('resource_id');
            $table->string('name_link', 500);
            $table->unsignedBigInteger('location_dialogue_id')->nullable();
            $table->string('link_lid', 500)->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('call_up_id')->nullable();
            $table->string('date_time_call_up', 100 )->nullable();
            $table->unsignedBigInteger('audit_id')->nullable();
            $table->unsignedBigInteger('specialist_task_id')->nullable();
            $table->date('transfer_date')->nullable();
            $table->date('date_acceptance')->nullable();
            $table->date('ready_date')->nullable();
            $table->unsignedBigInteger('specialist_user_id')->nullable();
            $table->boolean('write_lid')->nullable();
            $table->unsignedBigInteger('lid_status_id');
            $table->string('state', 500)->nullable();
            $table->string('link_to_site', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->string('business_are', 100)->nullable();
            $table->unsignedBigInteger('create_user_id');
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
        Schema::dropIfExists('lids');
    }
}
