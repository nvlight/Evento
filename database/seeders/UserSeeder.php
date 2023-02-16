<?php

namespace Database\Seeders;

use App\Models\Evento;
use App\Models\Tag;
use App\Models\TagValue;
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
        // User::factory()->count(10)->create();
        // User::factory()->has(Tag::factory()->count(3), 'tags')->create();
        // User::factory()->has(Tag::factory()->count(3))->create();
        // User::factory()->hasTags(3)->create();

//        User::factory()
//            ->hasTags(5)
//            ->has(Evento::factory()->count(4)->hasTagvalue([
//                    'description' => 'fixed descr',
//                    //'tag_id'  => '',
//                ]))
//            ->create();

        //        Evento::factory(5)
//            ->state(['user_id' => $userId])
//                ->hasTagvalue([
//                    'tag_id_first'  => $userTagIds->random(),
//                    'tag_id_second'  => $userTagIds->random(),
//                ])
//            ->create();

        // $userWithTags = User::factory()->hasTags(1)->create();
        $userWithTags = User::factory()
            ->hasTags(random_int(11,15))
            ->create();

        $staticTags = Tag::factory()->createMany([
            ['user_id' => $userWithTags->id, 'name' => 'доход',  'text_color' => '#ffffff', 'bg_color' => '#5CB85C',],
            ['user_id' => $userWithTags->id, 'name' => 'расход', 'text_color' => '#ffffff', 'bg_color' => '#D9534F',],
        ]);
        //$userWithTags->load('tags');

        //$tagIds = $staticTags->pluck('id')->toArray();
        //$tag1 = $staticTags->where('id', $tagIds[array_key_first($tagIds)]);
        //$tag2 = $staticTags->where('id', $tagIds[array_key_last($tagIds)]);
        //$userWithTags->tags->push($tag1);
        //$userWithTags->tags->push($tag2);

        $eventos = Evento::factory(random_int(55,105))
            ->state(['user_id' => $userWithTags->id])
            ->create();

        foreach( $eventos as $evento){
            $eventoType = random_int(1,4);

            $userTagIds = $userWithTags->tags->pluck('id');
            $tagValue = ['tag_id_first'  => $userTagIds->random()];
            switch ($eventoType){
                case 1: break;

                case 2: $tagValue += ['value' => random_int(0, 50000) ];
                        break;

                case 3: $tagValue += ['tag_id_second'  => $userTagIds->random() ];
                        break;

                case 4: $tagValue += ['tag_id_second'  => $userTagIds->random() ];
                        $tagValue += ['value' => random_int(0, 50000) ];
                        break;
            };

            TagValue::factory()
                ->state([
                    'evento_id' => $evento->id,
                ])
                ->create($tagValue);
        }
    }
}
