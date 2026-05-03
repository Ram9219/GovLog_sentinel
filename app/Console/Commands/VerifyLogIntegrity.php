<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyLogIntegrity extends Command
{
    protected $signature = 'logs:verify-integrity';

    protected $description = 'Verify the integrity of stored logs.';

    public function handle(): int
    {
        $this->info('Log integrity verification is not yet implemented.');

        return self::SUCCESS;
    }
}
