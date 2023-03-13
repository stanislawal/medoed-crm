<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('price_client');
            $table->dropColumn('price_author');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_user_id');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('theme_id')->nullable();
            $table->string('project_name', 255);
            $table->unsignedBigInteger('mood_id')->nullable();
            $table->string('pay_info', 255)->nullable();
            $table->decimal('price_client', 10, 2)->nullable();
            $table->decimal('price_author', 10, 2)->nullable();
            $table->string('pay_method', 255)->nullable();
            $table->date('start_date_project')->nullable();
            $table->date('end_date_project')->nullable();
            $table->integer('total_symbols')->nullable();
            $table->integer('progress_symbols')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('style_id')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->timestamps();
        });
    }
}
