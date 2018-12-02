<?php

use Faker\Generator as Faker;

$factory = app(\Illuminate\Database\Eloquent\Factory::class);

$factory->define(\Goopil\RestFilter\Tests\Utils\TestModel::class, function (Faker $faker) {
    return [
        'bool' => $faker->boolean(),

        'char'   => substr($faker->text(5), 0, 1),
        'string' => $faker->text(190),
        'text'   => $faker->paragraph(10),

        'int'     => rand(1, 1000),
        'double'  => $faker->randomFloat(2, 1, 1000 ),
        'decimal' => $faker->randomFloat(5, 1, 1000 ),

        'datetime' => $faker->dateTime(),
        'date'     => $faker->date(),
        'time'     => $faker->time(),
    ];
});
