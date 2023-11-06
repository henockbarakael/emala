<?php

namespace App\Console\Commands;

use App\Models\Wallet;
use Illuminate\Console\Command;

class CalculateMonthlyInterest extends Command
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
        $wallets = Wallet::where('wallet_type', 'saving')->get();

        foreach ($wallets as $wallet) {
            $interest = $wallet->wallet_balance * (3 / 100);
            $wallet->wallet_balance += $interest;
            $wallet->save();
        }

        $this->info('Monthly interest calculated successfully.');
    }
}
