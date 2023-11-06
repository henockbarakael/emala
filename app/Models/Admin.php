<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Cashier::class, 'agency_id', 'cashier_id','id');
    }

    public function getDailyTransactions()
    {
        $today = Carbon::today();
        return Transaction::whereDate('created_at', $today)->get();
    }

    public function getWeeklyTransactions()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        return Transaction::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
    }

    public function getMonthlyTransactions()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        return Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();
    }

    public function getCategoryTransactions($category)
    {
        $today = Carbon::today();
        return Transaction::where('category', $category)->whereDate('created_at', $today)->get();
    }

    public function agencePrincipale()
    {
        return $this->belongsTo(Agency::class, 'agence_principale_id');
    }


    public function getCdfBranchesBalances()
    {
        $mainAgency = $this->agencePrincipale;

        if (!$mainAgency) {
            return 0;
        }

        $branchAgencies = $mainAgency->agencesFiliales()->pluck('id');

        return Cashier::whereIn('agency_id', $branchAgencies)
            ->where('currency', 'CDF')
            ->sum('balance');
    }

    public function getUsdBranchesBalances()
    {
        $mainAgency = $this->agencePrincipale;

        if (!$mainAgency) {
            return 0;
        }

        $branchAgencies = $mainAgency->agencesFiliales()->pluck('id');

        return Cashier::whereIn('agency_id', $branchAgencies)
            ->where('currency', 'USD')
            ->sum('balance');
    }

    public function agencesFiliales()
    {
        return $this->hasMany(Agency::class, 'agence_principale_id');
    }
}
