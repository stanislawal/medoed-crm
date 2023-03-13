<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateArticlesTableAddCollumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->decimal('price_client', 10, 2)->nullable();
            $table->decimal('price_author', 10, 2)->nullable();
        });
    }


    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('price_client');
            $table->dropColumn('price_author');
        });
    }
}
