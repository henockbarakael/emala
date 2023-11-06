<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PretBancaire extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'pret_bancaires';

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function amortissements()
    {
        return $this->hasMany(Amortization::class, 'loan_id');
    }
}
