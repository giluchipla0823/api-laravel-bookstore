<?php

use App\User;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Genre;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

// Factory Authors
$factory->define(Author::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'active' => 1
    ];
});

// Factory Publishers
$factory->define(Publisher::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'active' => 1
    ];
});

// Factory Genres
$factory->define(Genre::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->realText(20),
        'active' => 1
    ];
});

// Factory Books
$factory->define(Book::class, function (Faker\Generator $faker) {
    return [
        'author_id' => Author::all()->random()->id,
        'publisher_id' => Publisher::all()->random()->id,
        'title' => $faker->sentence(4),
        'summary' => $faker->paragraph(1),
        'description' => $faker->text(500),
        'quantity' => $faker->numberBetween(1, 50),
        'price' => $faker->randomNumber(2),
        'image' => $faker->randomElement([
            '1.jpg',
            '2.jpg',
            '3.jpg',
            '4.jpg',
            '5.jpg',
            '6.jpg',
            '7.jpg',
            '7.jpg',
            '8.jpg',
            '9.jpg',
            '10.jpg',
            '11.jpg',
            '12.jpg',
            '13.jpg',
            '14.jpg',
            '15.jpg',
            '16.jpg',
            '17.jpg',
            '18.jpg',
        ]),
        'active' => 1,
    ];
});