<?php

use Illuminate\Database\Seeder;

class FormsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('forms')->insert([
            'name' => 'título formulário teste',
            'description' => 'descrição formulário teste',
            'user_id' => 1
        ]);
    }
}
