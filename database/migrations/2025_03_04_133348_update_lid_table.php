<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lids', function (Blueprint $table) {
            $table->string('price', 255)->nullable()->change();
            $table->unsignedBigInteger('lid_specialist_status_id')->nullable()->after('state');
            $table->string('state_specialist', 500)->nullable()->after('lid_specialist_status_id');
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
            $table->decimal('price', 8, 2)->nullable()->change();
            $table->dropColumn('lid_specialist_status_id');
            $table->dropColumn('state_specialist');
        });
    }
}
