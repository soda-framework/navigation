<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNavigationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug_type');
            $table->string('slug_value');
            $table->integer('parent_id')->unsigned()->nullable()->index('FK_navigation_items_navigation_items');
            $table->integer('position')->unsigned()->nullable()->default('0');
            $table->integer('application_id')->unsigned()->nullable()->index('FK_navigation_items_applications');
            $table->integer('status')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('navigation_items');
    }
}
