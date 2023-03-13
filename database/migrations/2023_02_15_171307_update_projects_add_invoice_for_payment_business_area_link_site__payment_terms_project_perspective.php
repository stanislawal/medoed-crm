<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectsAddInvoiceForPaymentBusinessAreaLinkSitePaymentTermsProjectPerspective extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_perspective')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('link_site')->nullable();
            $table->string('business_area')->nullable();
            $table->string('invoice_for_payment')->nullable();

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
            $table->dropColumn('project_perspective');
            $table->dropColumn('payment_terms');
            $table->dropColumn('link_site');
            $table->dropColumn('business_area');
            $table->dropColumn('invoice_for_payment');
        });
    }
}
