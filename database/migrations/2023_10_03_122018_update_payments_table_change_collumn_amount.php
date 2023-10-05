<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentsTableChangeCollumnAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment', function(Blueprint $table){
            $table->decimal('sber_a', 20, 2)->default(0)->change();
            $table->decimal('sber_d', 20, 2)->default(0)->change();
            $table->decimal('sber_k', 20, 2)->default(0)->change();
            $table->decimal('tinkoff_a', 20, 2)->default(0)->change();
            $table->decimal('tinkoff_k', 20, 2)->default(0)->change();
            $table->decimal('privat', 20, 2)->default(0)->change();
            $table->decimal('um', 20, 2)->default(0)->change();
            $table->decimal('wmz', 20, 2)->default(0)->change();
            $table->decimal('birja', 20, 2)->default(0)->change();
        });
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
