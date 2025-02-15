<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableAddColumnForInfoDocument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fio_for_doc', 255)->nullable()->after('remember_token');
            $table->string('inn_for_doc', 255)->nullable()->after('fio_for_doc');
            $table->string('contract_number_for_doc', 255)->nullable()->after('inn_for_doc');
            $table->date('date_contract_for_doc')->nullable()->after('contract_number_for_doc');
            $table->string('email_for_doc', 255)->nullable()->after('date_contract_for_doc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fio_for_doc');
            $table->dropColumn('inn_for_doc');
            $table->dropColumn('contract_number_for_doc');
            $table->dropColumn('date_contract_for_doc');
            $table->dropColumn('email_for_doc');
        });
    }
}
