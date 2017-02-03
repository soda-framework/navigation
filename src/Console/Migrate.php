<?php

namespace Soda\Navigation\Console;

use Illuminate\Console\Command;

class Migrate extends Command
{
    protected $signature = 'soda:navigation:migrate';
    protected $description = 'Migrate the Soda Navigation Database';

    /**
     * Runs all database migrations for Soda Reports.
     */
    public function handle()
    {
        $this->call('migrate', [
            '--path' => '/vendor/soda-framework/navigation/migrations',
        ]);
    }
}
