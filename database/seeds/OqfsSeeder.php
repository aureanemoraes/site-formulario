<?php

use Illuminate\Database\Seeder;

class OqfsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oqfs')->insert([
            'option_id' => 1,
            'question_id' => 1,
            'form_id' => 1
        ]);

        DB::table('oqfs')->insert([
            'option_id' => 2,
            'question_id' => 1,
            'form_id' => 1
        ]);

        DB::table('oqfs')->insert([
            'option_id' => 3,
            'question_id' => 1,
            'form_id' => 1
        ]);

        DB::table('oqfs')->insert([
            'option_id' => 4,
            'question_id' => 2,
            'form_id' => 1
        ]);

        DB::table('oqfs')->insert([
            'option_id' => 5,
            'question_id' => 2,
            'form_id' => 1
        ]);

        DB::table('oqfs')->insert([
            'option_id' => 6,
            'question_id' => 2,
            'form_id' => 1
        ]);
    }
}
