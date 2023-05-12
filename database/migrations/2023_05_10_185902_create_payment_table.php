<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->boolean('mark')->default(false);
            $table->unsignedBigInteger('status_payment_id')->nullable();
            $table->date('date');
            $table->decimal('sber_d', 8,1)->nullable();
            $table->decimal('sber_k', 8,1)->nullable();
            $table->decimal('privat', 8,1)->nullable();
            $table->decimal('um', 8,1)->nullable();
            $table->decimal('wmz', 8,1)->nullable();
            $table->decimal('birja', 8,1)->nullable();
            $table->string('number', 256)->nullable();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('create_user_id');
            $table->string('comment', 256)->nullable();
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
        Schema::dropIfExists('payment');
    }
}
