<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignTagValueTypeIdForTagValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tag_values', function (Blueprint $table) {
            $table->foreign('tag_value_type_id')->references('id')->on('tag_value_types')->onDelete('CASCADE');
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
            $table->dropForeign('tag_values_tag_value_type_id_foreign');
            //$table->dropConstrainedForeignId('tag_value_type_id');
        });
    }
}
