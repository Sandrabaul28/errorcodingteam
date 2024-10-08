<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('farmers', function (Blueprint $table) {
            // Add the foreign key constraint
            $table->foreign('affiliation_id')->references('id')->on('affiliations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('farmers', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['affiliation_id']);
        });
    }

};
