<?php

namespace App\Models;

use App\Exceptions\InsufficientBalanceException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerWallet extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'wallets';
  
    protected $fillable = [
        'customer_id',
        'wallet_code',
        'wallet_balance',
        'wallet_type',
        'wallet_currency',
        'created_at',
        'updated_at',
    ];

  


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function savingAccount($currency = null)
    {
        $query = $this->hasOne(CustomerWallet::class)
            ->where('wallet_type', 'saving');

        if ($currency) {
            $query->where('wallet_currency', $currency);
        }

        return $query;
    }

    public function principalAccount($currency = null)
    {
        $query = $this->hasOne(CustomerWallet::class)
            ->where('wallet_type', 'current');

        if ($currency) {
            $query->where('wallet_currency', $currency);
        }

        return $query;
    }

    public function debitBalance($amount)
    {
        $walletBalance = $this->wallet_balance ?: '0.00';

        if ($walletBalance >= $amount) {
            $walletBalance -= $amount;
            $this->wallet_balance = $walletBalance;
            $this->save();

            return 'Compte débité avec succès !';
        } else {
          
            throw new InsufficientBalanceException("Le solde du client est insuffisant!");

        }
    }

    public function creditBalance($amount)
    {
        $walletBalance = $this->wallet_balance ?: '0.00';

        $walletBalance += $amount;
    
        $this->wallet_balance = $walletBalance; 
        $this->save();

        return 'Compte crédité avec succès !';
    }
}
