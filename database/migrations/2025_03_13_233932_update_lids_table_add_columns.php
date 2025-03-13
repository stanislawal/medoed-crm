<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLidsTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lids', function (Blueprint $table) {
            $table->boolean('interesting')->default(0);
            $table->date('date_write_lid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lids', function (Blueprint $table) {
            $table->dropColumn('interesting');
            $table->dropColumn('date_write_lid');
        });
    }
}
