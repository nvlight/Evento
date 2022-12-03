<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRenameTagIdFirstTagIdSecondForTagValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tag_values', function (Blueprint $table) {
            $table->renameColumn('tag_id', 'tag_id_first');
            $table->foreign('tag_id_first')->references('id')->on('tags')->onDelete('CASCADE');

            $table->unsignedBigInteger('tag_id_second');
            $table->foreign('tag_id_second')->references('id')->on('tags')->onDelete('CASCADE');
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
            $table->renameColumn('tag_id_first', 'tag_id');
            $table->dropForeign('tag_values_tag_id_first_foreign');
            $table->dropIndex('tag_values_tag_id_first_foreign');

            $table->dropConstrainedForeignId('tag_id_second');
        });
    }
}
