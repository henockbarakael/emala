<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\LockableTrait;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use LockableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $connection = 'mysql';
    protected $table = 'users';
    protected $guarded = [];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cashier()
    {
        return $this->hasOne(Cashier::class);
    }

    public function getCashierId()
    {
        $user = User::find(Auth::user()->id);
        $cashier = $user->cashier;
        return $cashier->id;
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function getAdminId()
    {
        $user = User::find(Auth::user()->id);
        $admin = $user->admin;
        return $admin->id;
    }

    public function manager()
    {
        return $this->hasOne(Manager::class);
    }

    public function getManagerId()
    {
        $user = User::find(Auth::user()->id);
        $admin = $user->manager;
        return $admin->id;
    }

    public function getUserInformation($userId)
    {
        if ($this->isCashier()) {
            // Utilisateur de type cashier
            $agency = $this->agency;
            $cashier = $this->cashier;
            $balanceCDF = $cashier->getCdfBalance($userId);
            $balanceUSD = $cashier->getUsdBalance($userId);

            return [
                'type' => 'cashier',
                'agency' => $agency,
                'balance_cdf' => $balanceCDF,
                'balance_usd' => $balanceUSD,
            ];
        } elseif ($this->isManager()) {
            // Utilisateur de type manager
            $managedAgency = $this->managedAgency;
            $cashiersCount = $managedAgency->cashiers()->count();
            $balanceCDF = $this->manager->getCdfBalance();
            $balanceUSD = $this->manager->getUsdBalance();

            return [
                'type' => 'manager',
                'managed_agency' => $managedAgency,
                'cashiers_count' => $cashiersCount,
                'balance_cdf' => $balanceCDF,
                'balance_usd' => $balanceUSD,
            ];
        }

        return null;
    }

    public function isCashier()
    {
        return $this->role_name === 'Cashier';
    }

    public function isManager()
    {
        return $this->role_name === 'Manager';
    }
}
