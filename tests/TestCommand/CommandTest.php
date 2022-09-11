<?php

use EightAndDouble\UserSettings\Tests\Models\User;

it('init_command_initialises_settings', function () {
    $this->artisan('usersettings:init')
        ->expectsOutput('Settings have been initialised!')
        ->assertSuccessful();
});

it('init_command_only_initialises_empty_settings', function () {
    $user1 = User::create([
        'name' => 'tester',
        'email' => mt_rand(1, 9999).'tester@test.com',
        'password' => 'password',
    ]);

    $user1->settings = [
        'theme' => [
            'color' => 'blue',
        ],
    ];

    $user1->save();

    $user2 = User::create([
        'name' => 'tester',
        'email' => mt_rand(1, 9999).'tester@test.com',
        'password' => 'password',
    ]);

    $this->artisan('usersettings:init')
        ->expectsOutput('Settings have been initialised!')
        ->assertSuccessful();

    $user1 = $user1->fresh();
    $user2 = $user2->fresh();

    $user1_settings = json_decode($user1->settings, true);
    $user2_settings = json_decode($user2->settings, true);

    $this->assertEquals('blue', $user1_settings['theme']['color']);
    $this->assertEquals('red', $user2_settings['theme']['color']);
});
