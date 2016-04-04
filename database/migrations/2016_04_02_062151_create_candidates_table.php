<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('agency_id')->unsigned();
			$table->string('first_name',55);
			$table->string('last_name',55);
			$table->text('address1');
			$table->string('city');
			$table->string('state');
			$table->string('zip',15);
			$table->string('email');
			$table->string('phone_number',55);
			$table->text('description');
			$table->string('source');
			$table->string('salary');
			$table->integer('salary_range');
			$table->text('resume_content');
			$table->float('experience',11,2);
			$table->boolean('starred');
			$table->integer('created_by');
			$table->integer('miles_radius');
			$table->string('resume_file');
			$table->timestamps();
			$table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign('candidates_agency_id_foreign');
        Schema::drop('candidates');
    }
}
