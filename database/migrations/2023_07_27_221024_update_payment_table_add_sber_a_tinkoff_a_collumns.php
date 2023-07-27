<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentTableAddSberATinkoffACollumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment', function(Blueprint $table){
            $table->decimal('tinkoff_a', 8,1)->default(0)->nullable()->after('sber_k');
            $table->decimal('sber_a', 8,1)->default(0)->nullable()->after('date');
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
            $table->dropColumn(['tinkoff_a']);
            $table->dropColumn(['sber_a']);
        });
    }
}
