<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendPendingNotifications extends Command
{
    protected $signature = 'notifications:send-pending';

    protected $description = 'Send pending notifications.';

    public function handle(): int
    {
        $this->info('Pending notifications dispatch is not yet implemented.');

        return self::SUCCESS;
    }
}
