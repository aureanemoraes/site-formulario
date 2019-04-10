<?php

use Illuminate\Database\Seeder;

class OptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('options')->insert([
            'name' => 'Vermelho',
            'slug' => 'vermelho'
        ]);

        DB::table('options')->insert([
            'name' => 'Azul',
            'slug' => 'azul'
        ]);

        DB::table('options')->insert([
            'name' => 'Amarelo',
            'slug' => 'amarelo'
        ]);

        DB::table('options')->insert([
            'name' => 'NotePad++',
            'slug' => 'notepad++'
        ]);

        DB::table('options')->insert([
            'name' => 'Visual Studio Code',
            'slug' => 'visual-studio-code'
        ]);

        DB::table('options')->insert([
            'name' => 'sublime text',
            'slug' => 'sublime-text'
        ]);
    }
}
