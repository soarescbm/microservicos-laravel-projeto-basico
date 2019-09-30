<?php

use App\Model\Video;
use Illuminate\Database\Seeder;

class VideoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = \App\Model\Genre::all();
        factory(Video::class,100)->create()
        ->each(function ($video) use ($genres){
            $subGenres = $genres->random(5)->load('categories');
            $genresId = $subGenres->pluck('id')->toArray();
            $cagegoriesId = [];
            foreach ($subGenres as $genre){
                array_push($cagegoriesId, ...$genre->categories()->pluck('id')->toArray());
            }
            $cagegoriesId = array_unique($cagegoriesId);
            $video->categories()->attach($cagegoriesId);
            $video->genres()->attach($genresId);
        });
    }
}
