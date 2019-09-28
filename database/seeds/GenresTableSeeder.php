<?php

use App\Model\Genre;
use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = \App\Model\Category::all();
        factory(Genre::class,100)->create()
        ->each(function ($genre) use ($categories){
            $categoriesId = $categories->random(5)->pluck('id')->toArray();
            $genre->categories()->attach($categoriesId);
        });
    }
}
