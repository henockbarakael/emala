<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

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

    public function amortizations()
    {
        return $this->hasMany(Amortization::class);
    }


    public function generateAmortizationSchedule()
    {
        $schedule = [];
        $loanAmount = $this->amount;
        $interest = InterestRate::where('id', $this->interest_rate_id)->first();
        $interest_rate = $interest->id;
        $interestRate =  $interest_rate;
        $duration = $this->duration;
        $paymentFrequency = $this->payment_frequency;
        $remainingBalance = $loanAmount;
        $paymentDate = Carbon::now();
        $Frequency = 12;

        if ($paymentFrequency === 'daily') {
            $duration = $duration * 30; // Conversion en jours
            $Frequency = 365;
        } elseif ($paymentFrequency === 'weekly') {
            $duration = $duration * 4; // Conversion en semaines
            $Frequency = 52;

        }

        for ($i = 1; $i <= $duration; $i++) {
            $interest = ($remainingBalance * $interestRate) / ($Frequency * 100);
            $principal = ($loanAmount / $duration) - $interest;
            $remainingBalance -= $principal;

            $schedule[] = [
                'payment_number' => $i,
                'payment_date' => $paymentDate->format('Y-m-d'),
                'interest' => $interest,
                'principal' => $principal,
                'remaining_balance' => $remainingBalance,
                'currency' => $this->currency
            ];


            if ($paymentFrequency === 'daily') {
                $paymentDate->addDay();
            } elseif ($paymentFrequency === 'weekly') {
                $paymentDate->addWeek();
            } elseif ($paymentFrequency === 'monthly') {
                $paymentDate->addMonth();
            }
        }

        return $schedule;
    }

    public function updateRemainingBalance($paymentAmount)
    {
        // $this->remaining_balance = $this->remaining_balance - $paymentAmount;
        $this->remaining_balance = $this->remaining_balance - $paymentAmount;
        $this->save();
    }

    // public function calculateAmortization()
    // {
    //     $loanAmount = $this->amount;
    //     $interestRate = $this->interest_rate;
    //     $term = $this->duration;
    //     $monthlyInterestRate = $interestRate / 100 / 12;
    //     $numberOfPayments = $term;

    //     $monthlyPayment = ($loanAmount * $monthlyInterestRate) / (1 - pow(1 + $monthlyInterestRate, -$numberOfPayments));

    //     $amortizationSchedule = [];

    //     $balance = $loanAmount;
    //     for ($month = 1; $month <= $numberOfPayments; $month++) {
    //         $interest = $balance * $monthlyInterestRate;
    //         $principal = $monthlyPayment - $interest;
    //         $balance -= $principal;

    //         $amortizationSchedule[] = [
    //             'loan_id' => $this->id,
    //             'month' => $month,
    //             'payment' => $monthlyPayment,
    //             'interest' => $interest,
    //             'principal' => $principal,
    //             'balance' => $balance,
    //         ];
    //     }

    //     return $amortizationSchedule;
    // }

    public function calculateAmortization()
    {
        $loanAmount = $this->amount;
        $interestRate = $this->interest_rate;
        $term = $this->duration;
        $frequency = $this->payment_frequency; // 'daily', 'weekly', 'monthly'
        $firstPaymentDate = $this->first_payment_date;
        
        $numberOfPayments = $term;
    
        $monthlyInterestRate = $interestRate / 100 / 12;
        $paymentMultiplier = 1;
        
        switch ($frequency) {
            case 'daily':
                $paymentMultiplier = 30 / 365;
                $numberOfPayments *= 30;
                break;
            case 'weekly':
                $paymentMultiplier = 7 / 365;
                $numberOfPayments *= 4;
                break;
            case 'monthly':
                $paymentMultiplier = 1;
                break;
            default:
                // Gérer l'erreur pour une fréquence de paiement invalide selon vos besoins
                break;
        }
    
        $monthlyPayment = ($loanAmount * $monthlyInterestRate * $paymentMultiplier) / (1 - pow(1 + $monthlyInterestRate * $paymentMultiplier, -$numberOfPayments));
    
        $amortizationSchedule = [];
        $balance = $loanAmount;
        $paymentDate = $firstPaymentDate;
        $totalAmountDue = 0;
        $totalInterest = 0;
    
        for ($paymentNumber = 1; $paymentNumber <= $numberOfPayments; $paymentNumber++) {
            $interest = $balance * $monthlyInterestRate * $paymentMultiplier;
            $principal = $monthlyPayment - $interest;
            $balance -= $principal;

            // Vérifier si la date de paiement est un dimanche, et si c'est le cas, la mettre à jour au jour suivant
            while (date('N', strtotime($paymentDate)) == 7) {
                $paymentDate = date('Y-m-d', strtotime($paymentDate . ' +1 day'));
            }   
    
            $amortizationSchedule[] = [
                'loan_id' => $this->id,
                'payment_number' => $paymentNumber,
                'payment' => $monthlyPayment,
                'interest' => $interest,
                'principal' => $principal,
                'balance' => $balance,
                'payment_date' => $paymentDate,
            ];
    
            // Incrémenter la date de paiement en fonction de la fréquence
            switch ($frequency) {
                case 'daily':
                    $paymentDate = date('Y-m-d', strtotime($paymentDate . ' +1 day'));
                    break;
                case 'weekly':
                    $paymentDate = date('Y-m-d', strtotime($paymentDate . ' +1 week'));
                    break;
                case 'monthly':
                    $paymentDate = date('Y-m-d', strtotime($paymentDate . ' +1 month'));
                    break;
            }

            // Calculer le montant total dû en ajoutant le montant principal du paiement en cours
            // $totalAmountDue += $monthlyPayment;
            // Ajouter les intérêts au montant total des intérêts
            $totalInterest += $interest;
        }
        $totalAmountDue = $loanAmount + $totalInterest;
        // Mettre à jour le montant total dû dans la ligne de prêt correspondante dans la table "loans"
        $this->total_amount_due = $totalAmountDue;
        $this->balance = $totalAmountDue;
        $this->save();
    
        return $amortizationSchedule;
    }

    
    public function updateAmortization()
    {
        $amortizationSchedule = $this->calculateAmortization();

        foreach ($amortizationSchedule as $payment) {
            $month = $payment['month'];
            $paymentModel = Payment::where('loan_id', $this->id)->where('month', $month)->first();

            if ($paymentModel) {
                $paymentModel->update([
                    'payment' => $payment['payment'],
                    'interest' => $payment['interest'],
                    'principal' => $payment['principal'],
                    'balance' => $payment['balance'],
                ]);
            } else {
                Payment::create([
                    'loan_id' => $this->id,
                    'month' => $month,
                    'payment' => $payment['payment'],
                    'interest' => $payment['interest'],
                    'principal' => $payment['principal'],
                    'balance' => $payment['balance'],
                ]);
            }
        }
    }
}
