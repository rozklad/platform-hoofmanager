<?php namespace Sanatorium\Hoofmanager\Database\Seeds;

use DB;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ExaminationsCleanupTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// $faker = Faker::create();

		DB::table('examinations_cleanup')->truncate();

		foreach(range(1, 1) as $index)
		{
			// DB::table('examinations_cleanup')->insert([
			// 	
			// ]);
		}
	}

}
