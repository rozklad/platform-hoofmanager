<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApilogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('apilogs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('call')->nullable();
			$table->text('data')->nullable();
			$table->text('source')->nullable();
			$table->text('method')->nullable();
			$table->text('result')->nullable();
			$table->text('status')->nullable();
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
		Schema::drop('apilogs');
	}

}
