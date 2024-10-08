<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAffiliationToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add affiliation_id and define its foreign key
            $table->unsignedBigInteger('affiliation_id')->nullable()->after('password');
            $table->foreign('affiliation_id')->references('id')->on('affiliations')->onDelete('cascade');
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
            // Rollback changes
            $table->dropForeign(['affiliation_id']);
            $table->dropColumn('affiliation_id');
        });
    }
}
