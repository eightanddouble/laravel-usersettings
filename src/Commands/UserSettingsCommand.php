<?php

namespace EightAndDouble\UserSettings\Commands;

use Illuminate\Console\Command;

class UserSettingsCommand extends Command
{
    public $signature = 'usersettings';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
