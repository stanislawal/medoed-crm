<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectsTableAddNewColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('company_name', 255)->nullable();
            $table->string('deadline_accepting_work', 255)->nullable();
            $table->string('contract_number', 255)->nullable();
            $table->string('legal_name_company', 255)->nullable();
            $table->string('period_work_performed', 255)->nullable();
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
                'company_name',
                'deadline_accepting_work',
                'contract_number',
                'legal_name_company',
                'period_work_performed'
            ]);
        });
    }
}
