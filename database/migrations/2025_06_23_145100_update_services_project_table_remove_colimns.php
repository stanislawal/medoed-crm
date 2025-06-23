<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateServicesProjectTableRemoveColimns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services_project', function (Blueprint $table) {
            $table->dropColumn([
                'project_theme',
                'reporting_data',
                'terms_payment',
                'region',
                'link_to_work_plan'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services_project', function (Blueprint $table) {
            $table->string('project_theme', 255);
            $table->date('reporting_data');
            $table->string('terms_payment', 255);
            $table->string('region', 255);
            $table->string('link_to_work_plan', 255)->nullable();
        });
    }
}
