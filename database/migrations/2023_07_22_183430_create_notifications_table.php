<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time');
            $table->string('type', 255);
            $table->unsignedBigInteger('recipient_id');
            $table->string('message', 500)->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('article_id')->nullable();
            $table->boolean('is_viewed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
