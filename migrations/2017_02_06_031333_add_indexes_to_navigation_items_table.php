<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIndexesToNavigationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('navigation_items', function (Blueprint $table) {
            $table->index(['status', 'application_id', 'parent_id']);
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
            $table->dropIndex(['status', 'application_id', 'parent_id']);
        });
    }
}
