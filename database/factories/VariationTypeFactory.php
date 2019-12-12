<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\VariationType;
use Faker\Generator as Faker;

$factory->define(VariationType::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
    ];
});
