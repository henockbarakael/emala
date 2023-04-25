<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tirroir_account extends Model
{
    use HasFactory;
    protected $guarded = [];
    // protected static function boot() {
    //     parent::boot();
    
    //     static::saving(function($model){
    //         $model->state_cdf = (100/$model->inifound_cdf) * $model->balance_cdf;
    //         $model->state_usd = (100/$model->inifound_usd) * $model->balance_usd;
    //     }); 
    // }
}
