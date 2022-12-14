<?php

namespace EightAndDouble\UserSettings\Tests\TestCommand;

use EightAndDouble\UserSettings\Tests\Models\User;
use EightAndDouble\UserSettings\UserSettingsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class CommandTestCase extends Orchestra
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => '\\EightAndDouble\\UserSettings\\Tests\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        User::factory()->count(15)->create();
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
}
