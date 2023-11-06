<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
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

    public function agenceFiliaire()
    {
        return $this->belongsTo(Agency::class, 'agence_filiaire_id');
    }

    public function getCdfBalance()
    {
        return $this->agenceFiliaire->cashiers()
            ->where('currency', 'CDF')
            ->value('balance');
    }

    public function getUsdBalance()
    {
        return $this->agenceFiliaire->cashiers()
            ->where('currency', 'USD')
            ->value('balance');
    }
}
