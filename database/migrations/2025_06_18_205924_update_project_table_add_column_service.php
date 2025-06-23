<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectTableAddColumnService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('plan_gross_income')->nullable();

            $table->string('project_theme_service', 255)->nullable();
            $table->date('reporting_data')->nullable();
            $table->string('terms_payment', 255)->nullable();
            $table->string('region', 255)->nullable();
            $table->string('passport_to_work_plan', 255)->nullable();
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
            $table->dropColumn([
                'plan_gross_income',
                'project_theme',
                'reporting_data',
                'terms_payment',
                'region',
                'passport_to_work_plan'
            ]);
        });
    }
}
