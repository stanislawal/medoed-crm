<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects');
            $table->foreignId('service_type_id')->constrained('service_types');
            $table->string('project_theme', 255);
            $table->date('reporting_data');
            $table->string('terms_payment', 255);
            $table->string('region', 255);
            $table->decimal('all_price', 8, 2);
            $table->decimal('accrual_this_month', 8, 2);
            $table->string('task', 255);
            $table->string('link_to_work_plan', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services_project');
    }
}
