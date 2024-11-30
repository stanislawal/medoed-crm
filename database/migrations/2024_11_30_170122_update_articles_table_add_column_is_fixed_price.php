<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateArticlesTableAddColumnIsFixedPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('is_fixed_price_client')->default(false)->after('price_client');
            $table->boolean('is_fixed_price_author')->default(false)->after('price_author');
            $table->boolean('is_fixed_price_redactor')->default(false)->after('price_redactor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('is_fixed_price_client');
            $table->dropColumn('is_fixed_price_author');
            $table->dropColumn('is_fixed_price_redactor');
        });
    }
}
