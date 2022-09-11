<?php

use EightAndDouble\UserSettings\Facades\UserSettings;
use EightAndDouble\UserSettings\Tests\Models\User;

it('can_get_settings', function () {
    $color = UserSettings::get('theme.color');
    $this->assertEquals('red', $color);
});

it('can_get_settings_default value', function () {
    $color = UserSettings::get('theme.brand', default:'default_value');
    $this->assertEquals('default_value', $color);
});

it('can_set_new_settings_with_value', function () {
    UserSettings::set('theme.size', '48');
    $size = UserSettings::get('theme.size');
    $this->assertEquals('48', $size);
});

it('can_set_new_settings_with_null', function () {
    UserSettings::set('theme.font');
    $font = UserSettings::get('theme.font');
    $this->assertEquals(null, $font);
});

it('can_set_new_array_of_settings', function () {
    UserSettings::set([
		'theme.size' => '48',
		'theme.layout' => 'a4',
		'brand.color' => 'purple'
	]);

    $res = UserSettings::get('theme.size');
    $this->assertEquals('48', $res);

    $res = UserSettings::get('theme.layout');
    $this->assertEquals('a4', $res);

    $res = UserSettings::get('brand.color');
    $this->assertEquals('purple', $res);
});

it('can_set_unset_a_specific_setting', function () {
    UserSettings::set('page.border.left', '12');
    UserSettings::set('page.border.right', '10');
    $size = UserSettings::get('page.border.left');
    $this->assertEquals('12', $size);

    UserSettings::forget('page.border.left');
    $size = UserSettings::get('page.border.left');

    $this->assertEquals(null, $size);
});

it('can_check_if_a_setting_exists', function () {
    UserSettings::set('page.border.left', '12');
    $truthy = UserSettings::has('page.border.left');
    $this->assertTrue($truthy);
    $falsy = UserSettings::has('page.border.right');
    $this->assertFalse($falsy);
});

it('can_return_all_settings', function () {
    $settings = [
        'theme' => [
            'color' => 'blue',
        ],
        'page' => [
            'border' => [
                'left' => 12,
                'right' => 12,
                'top' => 10,
                'bottom' => 10,
            ],
        ],
    ];
    $user = User::create([
        'name' => 'tester',
        'email' => mt_rand(1, 9999).'tester@test.com',
        'password' => 'password',
    ]);

    $user->settings = $settings;
    $user->save();

    $settings_of_user = UserSettings::all($user['id']);

    $this->assertEquals($settings, $settings_of_user);
});

it('can_return_a_single_group_of_settings', function () {
    $settings = [
        'theme' => [
            'color' => 'blue',
        ],
        'page' => [
            'border' => [
                'left' => 12,
                'right' => 12,
                'top' => 10,
                'bottom' => 10,
            ],
        ],
    ];

    $user = User::create([
        'name' => 'tester',
        'email' => mt_rand(1, 9999).'tester@test.com',
        'password' => 'password',
    ]);

    $user->settings = $settings;
    $user->save();

    $settings_of_user = UserSettings::get('page', $user['id']);

    $this->assertEquals($settings['page'], $settings_of_user);
});
