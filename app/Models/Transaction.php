<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customerFrom()
    {
        return $this->belongsTo('App\Models\Customer', 'transaction_from', 'phone');
    }

    public function customerTo()
    {
        return $this->belongsTo('App\Models\Customer', 'transaction_to', 'phone');
    }

    public function cashier()
    {
        return $this->belongsTo(Cashier::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agence_id');
    }
}
