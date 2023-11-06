<?php

namespace App\Models;

use App\Exceptions\InsufficientBalanceException;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cashier extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDailyTransactions()
    {
        // Obtenez la date actuelle
        $currentDate = Carbon::now()->format('Y-m-d');

        // Récupérez les transactions de la journée en cours
        $dailyTransactions = $this->transactions()->whereDate('created_at', $currentDate)->get();

        return $dailyTransactions;
    }

    public function getDailyDeposit()
    {
        // Obtenez la date actuelle
        $currentDate = Carbon::now()->format('Y-m-d');

        // Récupérez les transactions de la journée en cours
        $dailyTransactions = $this->transactions()->whereDate('created_at', $currentDate)->where('category', 'Dépôt')->get();

        return $dailyTransactions;
    }

    public function getDailyWithdraw()
    {
        // Obtenez la date actuelle
        $currentDate = Carbon::now()->format('Y-m-d');

        // Récupérez les transactions de la journée en cours
        $dailyTransactions = $this->transactions()->whereDate('created_at', $currentDate)->where('category', 'Retrait')->get();

        return $dailyTransactions;
    }

    public function getDailyTransfer()
    {
        // Obtenez la date actuelle
        $currentDate = Carbon::now()->format('Y-m-d');

        // Récupérez les transactions de la journée en cours
        $dailyTransactions = $this->transactions()->whereDate('created_at', $currentDate)->where('category', 'Transfert')->get();

        return $dailyTransactions;
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function deposit($amount, $currency)
    {
        $this->credit($amount, $currency);
    }

    public function withdraw($amount,$currency)
    {
        $this->debit($amount, $currency);
    }

    public function getCdfBalance($userID)
    {
        return $this->where('user_id', $userID)
            ->where('currency', 'CDF')
            ->value('balance');
    }

    public function getUsdBalance($userID)
    {
        return $this->where('user_id', $userID)
            ->where('currency', 'USD')
            ->value('balance');
    }

    public function transferToAgency($amount,$currency,$report)
    {
        $currencies = ['USD', 'CDF'];

        if (!in_array($currency, $currencies)) {
            throw new \InvalidArgumentException('Invalid currency');
        }

        // Récupérer le compte wallet  correspondant à la devise et à l'agence
        $wallet = Wallet::where('agency_id', $this->agency_id)
                        ->where('currency', $currency)
                        ->first();

        if (!$wallet) {
            throw new \RuntimeException('Wallet account not found');
        }
        
        $this->credit($amount, $currency);
        $this->debit($report, $currency);
        
        // Ajouter le montant au solde
        $wallet->balance += $amount;
        
        // Mettre à jour le solde et la devise
        $wallet->save();
    }

    public function debit($amount, $currency)
    {
        // Récupérer le compte cashier correspondant à la devise et à l'utilisateur
        $cashier = Cashier::where('user_id', $this->user_id)
                        ->where('currency', $currency)
                        ->first();
        // Augmenter le solde du compte de revenus de l'agence.
        $cashier->balance += $amount;

        // Mettre à jour le solde et la devise
        $cashier->save();
        
    }

    public function credit($amount, $currency)
    {
        // Récupérer le compte cashier correspondant à la devise et à l'utilisateur
        $cashier = Cashier::where('user_id', $this->user_id)
                        ->where('currency', $currency)
                        ->first();

        // Vérifier si le solde est suffisant pour effectuer le débit
        if ($cashier->balance >= $amount) {
            // Diminuer le solde du compte d'exploitation de l'agence
            $cashier->balance -= $amount;

            // Mettre à jour le solde et la devise
            $cashier->save();
        } else {
            // Gérer le cas où le solde est insuffisant (par exemple, lancer une exception ou afficher un message d'erreur)
            throw new InsufficientBalanceException("Le solde de votre caisse est insuffisant pour effectuer cette opération.");
        }

        
    }
}
