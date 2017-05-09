<?php

namespace Soda\Navigation\Console;

use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = 'soda:navigation:install';
    protected $description = 'Install the Soda Navigation module';

    /**
     * Runs all database migrations for Soda Reports.
     */
    public function handle()
    {
        $this->call('migrate', [
            '--path' => '/vendor/soda-framework/navigation/migrations',
        ]);

        $this->call('db:seed', [
            '--class' => 'Soda\\Navigation\\Support\\InstallPermissions',
        ]);
    }
}
