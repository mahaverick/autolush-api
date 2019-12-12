<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Product;
use App\Models\Variation;
use App\Models\VariationType;
use Faker\Generator as Faker;

$factory->define(Variation::class, function (Faker $faker) {
    return [
        'product_id' => factory(Product::class)->create()->id,
        'variation_type_id' => factory(VariationType::class)->create()->id,
        'name' => $faker->unique()->name,
        'price' => $faker->randomElement([$faker->randomFloat(2, 0, 10000), null]),
    ];
});
