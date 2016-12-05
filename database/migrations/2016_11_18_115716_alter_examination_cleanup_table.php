<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExaminationCleanupTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try
        {
            Schema::table('findings', function (Blueprint $table)
            {
                $table->integer('item_id')->after('id');
                $table->integer('user_id')->after('id');
                $table->dropColumn('examination_id');
            });
        } catch (\RuntimeException $e) {
            // already changed
        }

        // Get data
        try
        {
            $examinations = app('sanatorium.hoofmanager.examination')->get();

            foreach ( $examinations as $examination )
            {

                foreach ( $examination->findings()->get() as $finding )
                {

                    $finding->item_id = $examination->item_id;

                    $finding->user_id = $examination->user_id;

                    $finding->save();

                }

            }

        } catch (\ReflectionException $e) {
            // sanatorium.hoofmanager.examination do not exist anymore
        }

        Schema::drop('examinations');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        $examinations = app('sanatorium.hoofmanager.examination')->get();

        $findings = app('sanatorium.hoofmanager.finding')->get();

        Schema::table('findings', function(Blueprint $table)
        {
            $table->dropColumn('item_id');
            $table->dropColumn('user_id');
        });

        Schema::create('examinations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('item_id');
            $table->timestamps();
        });
    }

}
