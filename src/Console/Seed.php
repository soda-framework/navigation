<?php

namespace Soda\Navigation\Console;

use Illuminate\Console\Command;

class Seed extends Command
{
    protected $signature = 'soda:navigation:seed';
    protected $description = 'Seed the Soda Navigation Database';

    /**
     * Runs seeds for Soda Reports.
     */
    public function handle()
    {
        $this->call('db:seed', [
            '--class' => 'Soda\\Navigation\\Support\\Seeder',
        ]);
    }
}
