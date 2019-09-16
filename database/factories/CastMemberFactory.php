<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model\CastMember;
use Faker\Generator as Faker;

$factory->define(CastMember::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
        'type' => rand(1,2)
    ];
});
