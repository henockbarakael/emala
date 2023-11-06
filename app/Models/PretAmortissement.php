<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PretAmortissement extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pretBancaire()
    {
        return $this->belongsTo(PretBancaire::class, 'pret_bancaire_id');
    }
}
