<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookmarks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->text('title');
            $table->integer('page_number');

            $table->unsignedBigInteger('metadata_id');
            $table->foreign('metadata_id')
                ->references('id')
                ->on('metadata')
                ->onUpdate('cascade')
                ->onDelete('cascade');

                $table->unsignedBigInteger('parent_id')->nullable();
                $table->foreign('parent_id')
                    ->references('id')
                    ->on('bookmarks')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bookmarks');
    }
}
