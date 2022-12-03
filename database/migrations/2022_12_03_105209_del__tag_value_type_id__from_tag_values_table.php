<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DelTagValueTypeIdFromTagValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tag_values', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tag_value_type_id');
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
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('CASCADE');
        });
    }
}
