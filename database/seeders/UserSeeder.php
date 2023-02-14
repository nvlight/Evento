<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //User::factory(10)->create();

        User::factory()
            ->count(10)

            ->create();

//        User::factory(10)->create()->each( function( $user){
//
//        });

//        factory(Category::class, 10)->create()->each(function (Category $category) {
//            $counts = [0, random_int(3, 7)];
//            $category->children()->saveMany(factory(Category::class, $counts[array_rand($counts)])->create()->each(function (Category $category) {
//                $counts = [0, random_int(3, 7)];
//                $category->children()->saveMany(factory(Category::class, $counts[array_rand($counts)])->create());
//            }));
//        });
    }
}
