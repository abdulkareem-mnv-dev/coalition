<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Products;
use Faker\Generator as Faker;

$factory->define(Products::class, function (Faker $faker) {
    return [
    	'name'		=> 	$faker->unique()->words(1,true),
    	'quantity'	=>	rand(1,30),
    	'price'		=>	rand(50,1000)
    ];
});
