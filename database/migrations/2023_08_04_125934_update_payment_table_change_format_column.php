<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentTableChangeFormatColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment', function(Blueprint $table){
            $table->decimal('sber_a', 20,2)->nullable()->change();
            $table->decimal('tinkoff_a', 20,2)->nullable()->change();
            $table->decimal('sber_d', 20,2)->nullable()->change();
            $table->decimal('sber_k', 20,2)->nullable()->change();
            $table->decimal('privat', 20,2)->nullable()->change();
            $table->decimal('um', 20,2)->nullable()->change();
            $table->decimal('wmz', 20,2)->nullable()->change();
            $table->decimal('birja', 20,2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment', function(Blueprint $table){
            $table->decimal('sber_a', 8,2)->nullable()->change();
            $table->decimal('tinkoff_a', 8,2)->nullable()->change();
            $table->decimal('sber_d', 8,2)->nullable()->change();
            $table->decimal('sber_k', 8,2)->nullable()->change();
            $table->decimal('privat', 8,2)->nullable()->change();
            $table->decimal('um', 8,2)->nullable()->change();
            $table->decimal('wmz', 8,2)->nullable()->change();
            $table->decimal('birja', 8,2)->nullable()->change();
        });
    }
}
