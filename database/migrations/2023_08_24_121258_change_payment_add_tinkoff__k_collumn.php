<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePaymentAddTinkoffKCollumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment', function(Blueprint $table){
            $table->decimal('tinkoff_л', 8,1)->default(0)->nullable()->after('tinkoff_a');
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
            $table->dropColumn(['tinkoff_k']);

        });
    }
}
