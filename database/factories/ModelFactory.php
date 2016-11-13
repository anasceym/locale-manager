<?php

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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Project::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->company,
        'user_id' => factory(App\User::class)->create()->id
    ];
});

$factory->define(App\Project_lang::class, function (Faker\Generator $faker) {

    return [
        'lang_code' => collect(collect(Config::get('locale'))->keys())->random(),
        'project_id' => factory(App\Project::class)->create()->id
    ];
});

$factory->define(App\Project_namespace::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->company,
        'project_id' => factory(App\Project::class)->create()->id
    ];
});

$factory->define(App\Translation_key::class, function (Faker\Generator $faker) {

    return [
        'translation_key' => str_replace(strtolower($faker->company), ' ', '_'),
        'project_id' => factory(App\Project::class)->create()->id,
        'project_namespace_id' => factory(App\Project_namespace::class)->create()->id,
        'project_lang_id' => factory(App\Project_lang::class)->create()->id
    ];
});
