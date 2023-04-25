<?php

namespace App\Console\Commands;

use App\Http\Controllers\API\BrancheOperationController;
use Illuminate\Console\Command;

class CreditBrancheEveryMinute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'branche:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will credit branche every minute';

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
        $task = new BrancheOperationController;
        $status = $task->updatebrancheeveryminute();
        if ($status['success'] == true) {
            echo "Branche successfully updated";
        }
        else {
            echo "An error occurred while updating the branch";
        }
    }
}
