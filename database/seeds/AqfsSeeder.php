<?php

use Illuminate\Database\Seeder;

class AqfsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('aqfs')->insert([
            'question_id' => 3,
            'form_id' => 1
        ]);
    }
}
