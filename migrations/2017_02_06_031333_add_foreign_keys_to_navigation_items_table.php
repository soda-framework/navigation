<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToNavigationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->foreign('application_id', 'FK_navigation_items_applications')->references('id')->on('applications')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('parent_id', 'FK_navigation_items_navigation_items')->references('id')->on('navigation_items')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->dropForeign('FK_navigation_items_applications');
            $table->dropForeign('FK_navigation_items_navigation_items');
        });
    }
}
