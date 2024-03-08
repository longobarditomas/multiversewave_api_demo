<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('local'))
            Tag::truncate();

        $groups = ['string' => 1, 'voice' => 2, 'production' => 3, 'ensemble' => 4, 'genres' => 5, 'percusion' => 6 , 'keyboard' => 7, 'brass' => 8, 'wind' => 9];

        $all_tags = [
            'string'      => ['Guitar', 'Violin', 'Cello', 'Bass Guitar', 'Viola', 'Harp', 'Ukulele', 'Double Bass', 'Banjo', 'Mandolin', 'Sitar'], 
            'voice'       => ['Singer', 'Tenor'], 
            'production'  => ['Producer'],
            'ensemble'    => ['Band'],
            'genres'      => ['Rock', 'Trap'],
            'percusion'   => ['Drums', 'Marimba', 'Xylophone'],
            'keyboard'    => ['Piano', 'Accordion', 'Electric Organ', 'Synthesizer'],
            'brass'       => ['Saxophone', 'Trumpet', 'Trombone', 'Tuba'],
            'wind'        => ['Flute', 'Clarinet', 'Oboe', 'Bassoon', 'Piccolo', 'Bagpipes', 'Harmonica'],
        ];

        foreach ($all_tags as $group_name => $tags) {
            foreach ($tags as $tag_name) {
                Tag::create([
                    'name' => $tag_name,
                    'group_id' => $groups[$group_name],
                ]);
            }
        }
    }
}
