<?php

namespace EightAndDouble\UserSettings\Commands;

use Illuminate\Console\Command;

class Initialize extends Command
{
    public $signature = 'usersettings:init';

    public $description = 'Initialize User Settings for the first time';

    public function handle(): int
    {
		$users = (new (config('usersettings.users')))->get();

		foreach($users as $user)
		{
			if(!$user->settings && !(empty($user->init_settings))) {
				$user->settings = $user->init_settings;
				$user->save();
			}
		}
		
        $this->comment('Settings have been initialised!');

        return self::SUCCESS;
    }
}
