<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectsTableChangeTypeCollumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function(Blueprint $table){
            $table->text('dop_info')->change();
            $table->decimal('price_client', 10, 2)->change();
            $table->text('dop_info')->change();
        });
    }

    /**
     * Reverse the migrations.
     *f
     * @return void
     */
    public function down()
    {

    }
}
