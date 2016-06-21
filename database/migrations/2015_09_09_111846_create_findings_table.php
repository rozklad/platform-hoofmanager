<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFindingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('findings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('disease_id')->nullable();
			$table->integer('part_id')->nullable();
			$table->integer('subpart_id')->nullable();
			$table->integer('treatment_id')->nullable();
			$table->integer('examination_id');
			$table->timestamp('check_date')->nullable();
			$table->string('type')->nullable();
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
		Schema::drop('findings');
	}

}
