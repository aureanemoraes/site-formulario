<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersSeeder::class,
            FormsSeeder::class,
            QuestionsSeeder::class,
            OptionsSeeder::class,
            OqfsSeeder::class,
        ]);

    }
}
