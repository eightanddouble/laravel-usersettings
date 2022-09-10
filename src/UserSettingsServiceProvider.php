<?php

namespace EightAndDouble\UserSettings;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use EightAndDouble\UserSettings\Commands\UserSettingsCommand;

class UserSettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-usersettings')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-usersettings_table')
            ->hasCommand(UserSettingsCommand::class);
    }
}
