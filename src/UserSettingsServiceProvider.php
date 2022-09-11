<?php

namespace EightAndDouble\UserSettings;

use EightAndDouble\UserSettings\Commands\Initialize;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasMigration('create_usersettings_table')
            ->hasCommand(Initialize::class);
    }
}
