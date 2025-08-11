<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectsTbaleAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('hours',8,2)->nullable();
            $table->decimal('total_amount_agreement', 8,2)->nullable();
            $table->foreignId('leading_specialist_id')->nullable()->constrained('specialist_services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('hours');
            $table->dropColumn('total_amount_agreement');
            $table->dropColumn('leading_specialist_id');
        });
    }
}
