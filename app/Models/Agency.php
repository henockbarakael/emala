<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cashiers()
    {
        return $this->hasMany(Cashier::class);
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }


    public function getCdfBalancePrincipale()
    {
        if ($this->agence_principale_id === null) {
            $cdfWallet = $this->wallet->where('currency', 'CDF')->first();
    
            if ($cdfWallet) {
                return [
                    'balance' => $cdfWallet->balance,
                    'wallet_id' => $cdfWallet->id,
                ];
            }
        }
    
        return null;
    }
    
    public function getUsdBalancePrincipale()
    {
        if ($this->agence_principale_id === null) {
            $usdWallet = $this->wallet->where('currency', 'USD')->first();
    
            if ($usdWallet) {
                return [
                    'balance' => $usdWallet->balance,
                    'wallet_id' => $usdWallet->id,
                ];
            }
        }
    
        return null;
    }

    public function getCdfBalanceFiliale()
    {
        $cdfWallet = Wallet::whereHas('agency', function ($query) {
            $query->where('agency_id', $this->id);
        })->where('currency', 'CDF')->first();
    
        if ($cdfWallet) {
            return [
                'balance' => $cdfWallet->balance,
                'wallet_id' => $cdfWallet->id,
            ];
        }
    
        return null;
    }

    public function getUsdBalanceFiliale()
    {
        $usdWallet = Wallet::whereHas('agency', function ($query) {
            $query->where('agency_id', $this->id);
        })->where('currency', 'USD')->first();

        if ($usdWallet) {
            return [
                'balance' => $usdWallet->balance,
                'wallet_id' => $usdWallet->id,
            ];
        }

        return null;
    }

    

    public function agencePrincipale()
    {
        return $this->belongsTo(Agency::class, 'agence_principale_id');
    }

    public function agencesFiliales()
    {
        return $this->hasMany(Agency::class, 'agence_principale_id');
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    public function consoliderFonds($reportFC,$reportUSD)
    {
        // Récupérer tous les caissiers de l'agence
        $caissiers = $this->cashiers;

        // Parcourir tous les caissiers et consolider les fonds collectés
        foreach ($caissiers as $caissier) {
            $fondsPerçus = $caissier->balance;
            $devise = $caissier->currency;
            if ($devise === 'CDF') {
                $report = $reportFC;
            }
            elseif ($devise === 'USD') {
                $report = $reportUSD;
            }
            // Transférer les fonds du caissier vers le compte de l'agence
            $caissier->transferToAgency($fondsPerçus,$devise,$report);
            // Enregistrer les fonds collectés dans le registre de l'agence
            $this->enregistrerDansRegistre($caissier, $fondsPerçus, $devise);
        }
    }

    private function enregistrerDansRegistre($caissier, $montant, $devise)
    {
        // Créer une entrée de registre pour enregistrer les fonds collectés
        $registre = new Registre();
        $registre->caissier_id = $caissier->id;
        $registre->montant = $montant;
        $registre->devise = $devise;
        $registre->date = now();
        $registre->save();
    }
}
