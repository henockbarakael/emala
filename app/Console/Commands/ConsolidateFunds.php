<?php

namespace App\Console\Commands;

use App\Models\Agency;
use Illuminate\Console\Command;

class ConsolidateFunds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $agencies = Agency::all();

        foreach ($agencies as $agency) {
            $agency->consoliderFonds();
        }

        $this->info('Funds consolidation completed.');
    }
}
