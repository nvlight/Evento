<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumtsToTagValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tag_values', function (Blueprint $table) {
            $table->unsignedBigInteger('eventos_id');
            $table->unsignedBigInteger('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tag_values', function (Blueprint $table) {
            $table->dropColumn('eventos_id');
            $table->dropColumn('tag_id');
        });
    }
}
