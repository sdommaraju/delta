<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_skills', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('candidate_id')->unsigned();
            $table->string('skill');
            $table->float('experience',3,1);
            $table->boolean('recent');
            $table->timestamps();
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            	
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign('candidate_skills_candidate_id_foreign');
        Schema::drop('candidates');
    }
}
