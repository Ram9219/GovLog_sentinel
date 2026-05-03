<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanOldLogs extends Command
{
    protected $signature = 'logs:clean-old';

    protected $description = 'Remove old logs from storage.';

    public function handle(): int
    {
        $this->info('Old log cleanup is not yet implemented.');

        return self::SUCCESS;
    }
}
