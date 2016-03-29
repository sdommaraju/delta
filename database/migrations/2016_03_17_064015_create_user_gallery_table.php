<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_gallery', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('user_id')->unsigned();
			$table->string('filename');
			$table->string('mime');
			$table->string('original_filename');
			$table->timestamps();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       $table->dropForeign('user_gallery_user_id_foreign');
       Schema::drop('user_gallery');
    }
}
