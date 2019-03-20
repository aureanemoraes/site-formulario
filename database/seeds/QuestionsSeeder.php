<?php

use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([
            'name' => 'Qual sua cor favorita?',
            'description' => 'Selecione a cor que mais lhe agrada.',
            'type' => 1
        ]);

        DB::table('questions')->insert([
           'name' => 'Quais os editores de código de sua preferência?',
           'type' => 2
        ]);

        DB::table('questions')->insert([
            'name' => 'Qual sua opnião sobre o Laravel?',
            'description' => 'Descreva sua experiência.',
            'type' => 3
        ]);
    }
}
