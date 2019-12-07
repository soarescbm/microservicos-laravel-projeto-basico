<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model\Genre;
use Faker\Generator as Faker;

$factory->define(Genre::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
    ];
});
