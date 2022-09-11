<?php

use Illuminate\Support\Facades\Schema;

it('can_see_the_user_table', function () {
    $users_table = (new (config('usersettings.users')))->getTable();
    $users_table_exists = Schema::hasTable($users_table);
    expect($users_table_exists)->toBeTrue();
});

it('confirms_settings_column_exists', function () {
    $users_table = (new (config('usersettings.users')))->getTable();
    $settings_column_exists = Schema::hasColumn($users_table, config('usersettings.settings_column'));
    expect($settings_column_exists)->toBeTrue();
});

// it('confirms_settings_column_is_fillable', function() {
// 	$users = new (config('usersettings.users'));
// 	$truthy = $users->isFillable(config('usersettings.settings_column'));
//     expect($truthy)->toBeTrue();
// });

it('can_read_settings_array', function () {
    $user = \Auth::user();
    expect($user->settings)->toBeArray();
});
