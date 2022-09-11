<?php

namespace EightAndDouble\UserSettings\Tests\TestClass;

use EightAndDouble\UserSettings\UserSettingsServiceProvider;
use EightAndDouble\UserSettings\Tests\Models\User;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factories\Factory;

use Orchestra\Testbench\TestCase as Orchestra;

class ClassTestCase extends Orchestra
{
	use DatabaseMigrations;

	protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

		Factory::guessFactoryNamesUsing(
            fn (string $modelName) => '\\EightAndDouble\\UserSettings\\Tests\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

		// User::factory()->count(15)->create();

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