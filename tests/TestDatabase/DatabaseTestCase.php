<?php

namespace EightAndDouble\UserSettings\Tests\TestDatabase;

use EightAndDouble\UserSettings\Tests\Models\User;
use EightAndDouble\UserSettings\UserSettingsServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class DatabaseTestCase extends Orchestra
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->actingAsUser();
    }

    protected function getPackageProviders($app)
    {
        return [
            UserSettingsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('usersettings.users', '\EightAndDouble\UserSettings\Tests\Models\User');
        $app['config']->set('usersettings.settings_column', 'settings');

        Schema::dropAllTables();
    }

    public function actingAsUser()
    {
        $user = User::create([
            'name' => 'tester',
            'email' => mt_rand(1, 9999).'tester@test.com',
            'password' => 'password',
        ]);

        $user->settings = [
            'theme' => [
                'color' => 'red',
            ],
        ];

        $user->save();

        return $this->actingAs($user);
    }
}
