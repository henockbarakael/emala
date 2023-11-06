<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\PretBancaire;
use DateInterval;
use Illuminate\Http\Request;

class AmortizationController extends Controller
{
    public function generateAmortizationSchedule(Request $request)
    {
        // Valider les données de la requête
        $request->validate([
            'loan_id' => 'required|exists:pret_bancaires,id',
            'loan_amount' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'loan_duration' => 'required|integer',
        ]);

        // Récupérer les données de la requête
        $loanId = $request->input('loan_id');
        $loanAmount = $request->input('loan_amount');
        $interestRate = $request->input('interest_rate');
        $loanDuration = $request->input('loan_duration');

        // Récupérer la fréquence de paiement à partir de la table pret_bancaires
        $loan = PretBancaire::findOrFail($loanId);
        $paymentFrequency = $loan->payment_frequency;

        // Calculer les détails de l'amortissement
        $monthlyInterestRate = ($interestRate/100) / 12; // Taux d'intérêt mensuel

        // Calculer le montant du paiement en fonction de la fréquence de paiement
        $monthlyPayment = $this->calculateMonthlyPayment($loanAmount, $monthlyInterestRate, $loanDuration);
        
        $paymentAmount = $this->calculatePaymentAmount($monthlyPayment, $paymentFrequency);

        $amortizationSchedule = $this->generateAmortizationTable($loanAmount, $monthlyInterestRate, $loanDuration, $paymentAmount, $paymentFrequency);

        // dd($interestRate,$monthlyInterestRate,$monthlyPayment,$paymentAmount,$amortizationSchedule );

        // Enregistrer les données d'amortissement dans la base de données
        $remainingBalance = $loanAmount;

        foreach ($amortizationSchedule as $payment) {
            $remainingBalance -= $payment['principal_amount'];
            // $payment['balance'] = $remainingBalance;

            $loan->amortissements()->create($payment);
        }

        // Mettre à jour le solde restant du prêt
        $loan->update(['balance' => $remainingBalance]);

        // Retourner une réponse réussie
        return response()->json(['message' => 'Amortization schedule generated successfully'], 200);
    }

    private function calculateMonthlyPayment($loanAmount, $monthlyInterestRate, $loanDuration)
    {
        // Calculer le montant du paiement mensuel en utilisant une formule d'amortissement
        $monthlyPayment = ($loanAmount * $monthlyInterestRate) / (1 - pow(1 + $monthlyInterestRate, -$loanDuration));
        return $monthlyPayment;
    }

    private function calculatePaymentAmount($monthlyPayment, $paymentFrequency)
    {
        // Calculer le montant du paiement en fonction de la fréquence de paiement
        switch ($paymentFrequency) {
            case 'monthly':
                $paymentAmount = $monthlyPayment;
                break;
            case 'weekly':
                $paymentAmount = $monthlyPayment / 4.33; // Approximation de 52 semaines divisées par 12 mois
                break;
            case 'daily':
                $paymentAmount = $monthlyPayment / 30.44; // Approximation de 365 jours divisés par 12 mois
                break;
            default:
                $paymentAmount = $monthlyPayment;
        }

        return $paymentAmount;
    }

    private function generateAmortizationTable($loanAmount, $monthlyInterestRate, $loanDuration, $paymentAmount, $paymentFrequency)
    {
        $amortizationSchedule = [];

        $balance = $loanAmount;
        $paymentDate = now();

        $paymentInterval = $this->getPaymentInterval($paymentFrequency);

        $numberOfPayments = $loanDuration;

            // Ajuster le nombre de paiements en fonction de la fréquence de paiement
            switch ($paymentFrequency) {
                case 'weekly':
                    $numberOfPayments *= 4.33; // Approximation de 52 semaines divisées par 12 mois
                    break;
                case 'daily':
                    $numberOfPayments *= 30.44; // Approximation de 365 jours divisés par 12 mois
                    break;
                default:
                    // Pour la fréquence de paiement mensuelle, aucun ajustement n'est nécessaire
                    break;
            }
                

        for ($i = 1; $i <= $numberOfPayments; $i++) {
            $interestAmount = $balance * $monthlyInterestRate;
            $principalAmount = $paymentAmount - $interestAmount;

            $amortizationSchedule[] = [
                'payment_amount' => $paymentAmount,
                'payment_date' => $paymentDate,
                'principal_amount' => $principalAmount,
                'interest_amount' => $interestAmount,
            ];

            $balance -= $principalAmount;
            $paymentDate = $paymentDate->add($paymentInterval);
        }

        return $amortizationSchedule;
    }

    private function getPaymentInterval($paymentFrequency)
    {
       switch ($paymentFrequency) {
            case 'monthly':
                $paymentInterval = new DateInterval('P1M'); // Interval d'un mois
                break;
            case 'weekly':
                $paymentInterval = new DateInterval('P7D'); // Interval d'une semaine
                break;
            case 'daily':
                $paymentInterval = new DateInterval('P1D'); // Interval d'un jour
                break;
            default:
                $paymentInterval = new DateInterval('P1M'); // Par défaut, utiliser un interval d'un mois
        }

        return $paymentInterval;
    }
}
