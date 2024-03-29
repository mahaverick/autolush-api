<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Product;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique()->name,
        'slug' => Str::slug($name),
        'price' => $faker->randomFloat(2, 0, 10000),
        'description' => $faker->paragraph,
    ];
});
