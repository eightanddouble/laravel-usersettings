<?php

namespace EightAndDouble\UserSettings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \EightAndDouble\UserSettings\UserSettings
 */
class UserSettings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \EightAndDouble\UserSettings\UserSettings::class;
    }
}
