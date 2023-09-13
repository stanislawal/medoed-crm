<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateArticlesTableAddRedactorsCollumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function(Blueprint $table){
            $table->decimal('redactor_payment_amount', 8, 2)->default(0)->nullable()->after('price_author');
            $table->date('redactor_payment_date')->nullable()->after('payment_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function(Blueprint $table){
            $table->dropColumn(['redactor_payment_amount', 'redactor_payment_date']);
        });
    }
}
