<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId = 1;
        Tag::factory()->createMany([
            ['user_id' => $userId, 'name' => 'доход', 'description' => '', 'text_color' => '', 'bg_color' => '#5CB85C',],
            ['user_id' => $userId, 'name' => 'расход', 'description' => '', 'text_color' => '', 'bg_color' => '#D9534F',],
            ['user_id' => $userId, 'name' => 'машина', 'description' => '', 'text_color' => '', 'bg_color' => '#cccccc',],
            ['user_id' => $userId, 'name' => 'работа', 'description' => '', 'text_color' => '', 'bg_color' => '#79B1E1',],
            ['user_id' => $userId, 'name' => 'дом', 'description' => '', 'text_color' => '', 'bg_color' => '#aa5599',],
            ['user_id' => $userId, 'name' => 'семья', 'description' => '', 'text_color' => '', 'bg_color' => '#aa5599',],
            ['user_id' => $userId, 'name' => 'Праздник', 'description' => '', 'text_color' => '', 'bg_color' => '#A20000',],
            ['user_id' => $userId, 'name' => 'выходной', 'description' => '', 'text_color' => '', 'bg_color' => '#D2770A',],
        ]);
    }
}
