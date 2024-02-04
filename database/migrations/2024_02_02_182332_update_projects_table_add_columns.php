<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectsTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('project_team')->nullable();
            $table->text('product_company')->nullable();
            $table->text('link_to_resources')->nullable();
            $table->text('mass_media_with_publications')->nullable();
            $table->text('task_client')->nullable();
            $table->text('content_public_platform')->nullable();
            $table->text('project_perspective_sees_account')->nullable();
            $table->boolean('edo')->nullable();
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
                'project_team',
                'product_company',
                'link_to_resources',
                'mass_media_with_publications',
                'task_client',
                'content_public_platform',
                'project_perspective_sees_account',
                'edo'
            ]);
        });
    }
}
