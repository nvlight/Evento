<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignEventosIdForTagValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tag_values', function (Blueprint $table) {
            $table->foreign('eventos_id')->references('id')->on('eventos')->onDelete('CASCADE');
            //$table->foreign('tag_id')->references('id')->on('tags')->onDelete('CASCADE');
            //$table->foreign('tag_value_type_id')->references('id')->on('tag_value_types')->onDelete('CASCADE');
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
            $table->dropForeign('tag_values_eventos_id_foreign');
            //$table->dropConstrainedForeignId('eventos_id');

            //$table->dropForeign('tag_id');
            //$table->dropForeign('tag_value_type_id');

            // tag_values_tag_value_type_id_foreign
        });
    }
}
