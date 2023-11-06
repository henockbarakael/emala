<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PaymentController extends Controller
{
    public function create($loanId)
    {
        // Afficher le formulaire de création d'un nouveau paiement pour un prêt donné
        $loan = Loan::findOrFail($loanId);

         // Vérifier si le prêt est déjà remboursé
        if ($loan->status === 'Remboursé') {
            Alert::info('Information', 'Le prêt est déjà entièrement remboursé.');
            return redirect()->back();
        }

        return view('backend.admin.payments.create', compact('loan'));
    }

    public function makePayment(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $paymentAmount = $request->input('amount');
        $paymentCurrency = $request->input('currency');

        // Vérifier si le montant du paiement est valide
        if ($paymentAmount <= 0) {
            Alert::error('Erreur', 'Le montant du paiement doit être supérieur à zéro.');
            return redirect()->back();
        }

        // Créer un nouveau paiement
        $payment = new Payment();
        $payment->loan_id = $loan->id;
        $payment->amount = $paymentAmount;
        $payment->currency = $paymentCurrency;
        $payment->payment_date = now();
        $payment->save();

        // Mettre à jour le solde et le statut du prêt
        $loan->balance -= $paymentAmount;

        if ($loan->balance <= 0) {
            $loan->balance = 0;
            $loan->status = 'Remboursé';
        }

        $loan->save();

        // Mettre à jour le solde dans les amortissements restants
        DB::table('amortizations')
            ->where('loan_id', $loan->id)
            ->where('balance', '>', 0)
            ->decrement('balance', $paymentAmount);

        if ($loan->status === 'Remboursé') {
            Alert::success('Succès', 'Le prêt a été entièrement remboursé.');
        } else {
            Alert::success('Succès', 'Le paiement a été enregistré avec succès.');
        }

        return redirect()->back();
    }

    // public function makePayment(Request $request, $id)
    // {
    //     $loan = Loan::findOrFail($id);
    //     $paymentAmount = $request->input('amount');

    //     // Vérifier si le montant du paiement est valide
    //     if ($paymentAmount <= 0) {
    //         Alert::error('Erreur', 'Le montant du paiement doit être supérieur à zéro.');
    //         return redirect()->back();
    //     }

    //     // Vérifier si le prêt est déjà entièrement remboursé
    //     if ($loan->balance <= 0) {
    //         Alert::info('Information', 'Le prêt est déjà entièrement remboursé.');
    //         return redirect()->back();
    //     }

    //     // Vérifier si le montant du paiement est supérieur au solde restant
    //     if ($paymentAmount >= $loan->balance) {
    //         // Mettre à jour le solde et le statut du prêt
    //         $loan->balance = 0;
    //         $loan->status = 'Remboursé';
    //         $loan->save();

    //         // Mettre à jour le solde dans les amortissements restants
    //         DB::table('amortizations')
    //             ->where('loan_id', $loan->id)
    //             ->where('balance', '>', 0)
    //             ->update(['balance' => 0]);

    //         Alert::success('Succès', 'Le prêt a été entièrement remboursé.');
    //         return redirect()->back();
    //     }

    //     // Mettre à jour le solde et le statut du prêt
    //     $loan->balance -= $paymentAmount;
    //     $loan->save();

    //     // Mettre à jour le solde dans les amortissements restants
    //     DB::table('amortizations')
    //         ->where('loan_id', $loan->id)
    //         ->where('balance', '>', 0)
    //         ->decrement('balance', $paymentAmount);

    //     Alert::success('Succès', 'Le paiement a été enregistré avec succès.');
    //     return redirect()->back();
    // }

    public function store(Request $request, $loanId)
    {
        /// Valider les données du formulaire et créer un nouveau paiement pour le prêt donné

        $loan = Loan::findOrFail($loanId);

        $payment = Payment::create([
            'loan_id' => $loan->id,
            'amount' => $request->amount,
            'payment_date' => Carbon::now(),
        ]);

        // Mettre à jour les informations du prêt
        $loan->payments()->save($payment);

        // Mettre à jour le solde restant du prêt
        $loan->updateRemainingBalance($request->amount);

        // Rediriger vers la page de détails du prêt ou effectuer d'autres actions
        return redirect()->route('admin.loans.show', $loan->id);
    }

    public function index($loanId)
    {
        $loan = Loan::findOrFail($loanId);
        $payments = $loan->payments;

        return view('payments.index', compact('loan', 'payments'));
    }
}
