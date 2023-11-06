<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'users';
    protected $guarded = [];

    public function customer_wallet()
    {
        return $this->hasMany(CustomerWallet::class);
    }

    public function saving_wallets()
    {
        return $this->hasMany(CustomerWallet::class)->where('wallet_type', 'saving');
    }

    public function current_wallets()
    {
        return $this->hasMany(CustomerWallet::class)->where('wallet_type', 'current');
    }

    public function agencies()
    {
        return $this->belongsToMany(Agency::class)->withPivot('cashier_id');
    }

}
