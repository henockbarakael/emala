<?php

namespace Database\Seeders;

use App\Models\Cashier;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class DepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amount = 10000; // Montant du dépôt
        $currency = 'USD'; // Devise du dépôt
        $customerId = 1; // ID du client
        $cashierId = 1; // ID du caissier
        $walletType = 'current'; // Type de portefeuille

        // Exécutez le script de dépôt
        $response = $this->deposit($amount, $currency, $customerId, $cashierId, $walletType);

        if ($response['success']) {
            $this->command->info('Deposit successful');
        } else {
            $this->command->error($response['message']);
        }
    }

    public function deposit($amount, $currency, $customerId, $cashierId, $walletType)
    {
        $customer = Customer::findOrFail($customerId);
        $customerWallet = $customer->customer_wallet;

        if ($walletType === 'current') {
            $principalWallet = $customerWallet->where('wallet_type', 'current')->where('wallet_currency', $currency)->first();

            if (!$principalWallet) {
                return ['success' => false, 'message' => 'Principal wallet not found'];
            }

            $principalWallet->debitBalance($amount);
        } elseif ($walletType === 'saving') {
            $savingWallet = $customerWallet->where('wallet_type', 'saving')->where('wallet_currency', $currency)->first();

            if (!$savingWallet) {
                return ['success' => false, 'message' => 'Saving wallet not found'];
            }

            $savingWallet->debitBalance($amount);
        } else {
            return ['success' => false, 'message' => 'Invalid wallet type'];
        }

        // Mettre à jour le solde du caissier associé à l'agence spécifiée
        $cashier = Cashier::findOrFail($cashierId);
        $cashier->deposit($amount, $currency);

        // Consolider les fonds
        // $cashier->agency->consoliderFonds();

        return ['success' => true, 'message' => 'Deposit successful'];
    }
}
