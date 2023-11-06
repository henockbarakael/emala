<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Backend\GenerateIdController;
use App\Http\Controllers\Controller;
use App\Models\Amortization;
use App\Models\Cashier;
use App\Models\Loan;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class LoanController extends Controller
{
    public function index()
    {
        // Afficher une liste des prêts
        $loans = Loan::all();
        
        return view('backend.admin.loan.index',compact('loans'));
    }

    public function history($id)
    {
        // Récupérer la demande de prêt spécifique à partir de l'identifiant
        $loans = Loan::where('id',$id)->get();

        if (!$loans) {
            // Gérer le cas où la demande de prêt n'est pas trouvée
            abort(404, "La demande de prêt n'a pas été trouvée.");
        }


        // Autres opérations ou logique de traitement des données

        return view('backend.admin.loan.history', compact('loans'));
    }

    public function create()
    {
        // Afficher le formulaire de création d'un nouveau prêt
        $loans = Loan::all();
        
        return view('backend.admin.loan.create',compact('loans'));
    }

    public function createId($id)
    {
        $data = $this->getCustomerData($id);

      
        return view('backend.admin.loan.createId', compact('data'));
    }

    public function getCustomerData($userId)
    {
      

        $customer = DB::connection('mysql2')
            ->table('users')
            ->join('wallets', 'users.id', '=', 'wallets.customer_id')
            ->select(
                'users.firstname',
                DB::raw('users.name AS lastname'),
                DB::raw('users.created_at AS join_date'),
                'users.phone',
                'users.address',
                'users.avatar',
                'users.country',
                'users.email',
                'users.role_name',
                'users.city',
                'wallets.wallet_type',
                DB::raw('GROUP_CONCAT(DISTINCT wallets.wallet_code) AS wallet_codes'),
                DB::raw('SUM(CASE WHEN wallets.wallet_currency = "CDF" AND wallets.wallet_type = "Current" THEN wallets.wallet_balance END) AS cdf_current_balance'),
                DB::raw('SUM(CASE WHEN wallets.wallet_currency = "USD" AND wallets.wallet_type = "Current" THEN wallets.wallet_balance END) AS usd_current_balance'),
                DB::raw('MAX(CASE WHEN wallets.wallet_type = "Current" THEN wallets.wallet_code END) AS current_wallet_code')
            )
            ->where('users.id', $userId)
            ->where('wallets.wallet_type', 'Current')
            ->groupBy('users.id', 'wallets.wallet_type')
            ->first();

        $transactions = [];
        $id_user = $userId;
        $avatar = null;
        $role_name = null;
        $city = null;
        $address = null;
        $phone_number = null;
        $join_date = null;
        $email = null;
        $country = null;
        $lastname = null;
        $firstname = null;
        $c_bcdf = null;
        $c_busd = null;
        $s_bcdf = null;
        $s_busd = null;
        $cnumber = null;
        $snumber = null;

        if ($customer) {
            $cnumber = $customer->current_wallet_code;
            $city = $customer->city;
            $address = $customer->address;
            $phone_number = $customer->phone;
            $join_date = $customer->join_date;
            $lastname = $customer->lastname;
            $role_name = $customer->role_name;
            $avatar = $customer->avatar;
            $country = $customer->country;
            $firstname = $customer->firstname;
            $c_bcdf = $customer->cdf_current_balance;
            $c_busd = $customer->usd_current_balance;

            $saving = DB::connection('mysql2')
                ->table('users')
                ->join('wallets', 'users.id', '=', 'wallets.customer_id')
                ->select(
                    'wallets.wallet_type',
                    DB::raw('GROUP_CONCAT(DISTINCT wallets.wallet_code) AS wallet_codes'),
                    DB::raw('SUM(CASE WHEN wallets.wallet_currency = "CDF" AND wallets.wallet_type = "Saving" THEN wallets.wallet_balance END) AS cdf_saving_balance'),
                    DB::raw('SUM(CASE WHEN wallets.wallet_currency = "USD" AND wallets.wallet_type = "Saving" THEN wallets.wallet_balance END) AS usd_saving_balance'),
                    DB::raw('MAX(CASE WHEN wallets.wallet_type = "Saving" THEN wallets.wallet_code END) AS saving_wallet_code')
                )
                ->where('users.id', $userId)
                ->where('wallets.wallet_type', 'Saving')
                ->groupBy('users.id', 'wallets.wallet_type')
                ->first();

            $snumber = $saving ? $saving->saving_wallet_code : null;
            $s_bcdf = $saving ? $saving->cdf_saving_balance : null;
            $s_busd = $saving ? $saving->usd_saving_balance : null;

            $transactions = Transaction::where('sender_phone', $phone_number)->get();
        }

        return compact(
            'transactions',
            'id_user',
            'avatar',
            'role_name',
            'email',
            'city',
            'country',
            'address',
            'phone_number',
            'join_date',
            'lastname',
            'country',
            'firstname',
            'c_bcdf',
            'c_busd',
            's_bcdf',
            's_busd',
            'cnumber',
            'snumber'
        );
    }

    public function store(Request $request)
    {
       
        $request->validate([
            'amount' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'currency' => 'required|string|max:255',
            'payment_frequency' => 'required|in:monthly,weekly,daily',
        ]);

        $phone_number = $request->phone;

        $customer = Customer::where('phone', $phone_number)->first();
        // $interest = InterestRate::where('id', 1)->first();
        // $interest_rate = $interest->id;
            
        if (!$customer) {
            throw new \Exception('Customer not found'); // Gérer le cas où le client n'est pas trouvé
        }

        $customer_id = $customer->id;

        $premier_echeance = Carbon::today()->addDays(7);

        $pin = new GenerateIdController;
        $control_number = $pin->reference();

        try {
            $loan = Loan::create([
                'amount' => $request->amount,
                'interest_rate' => $request->interest_rate,
                'duration' => $request->duration,
                'currency' => $request->currency,
                'payment_frequency' => $request->payment_frequency,
                'control_number' => $control_number,
                'status' => 'En attente',
                'first_payment_date' => $premier_echeance,
                'customer_id' => $customer_id
            ]);

            $cashier = Cashier::where('user_id',Auth::user()->id)->first();
            $agencyId = $cashier->agency->id;
            $loan->agency_id = $agencyId;
            $loan->processed_by = Auth::user()->id;
            $loan->save();

            // Calculer le calendrier d'amortissement
            $amortizationSchedule = $loan->calculateAmortization();

            // Enregistrer les amortissements dans la base de données
            $loan->amortizations()->createMany($amortizationSchedule);

            Alert::success('Succès', 'Historique de prêt envoyée avec succès !');
            return redirect()->route('admin.loans.index');

        } catch (\Exception $e) {
            // Gérer l'erreur et afficher un message approprié
            $errorMessage = $e->getMessage();
            Alert::error('Erreur', 'Une erreur s\'est produite lors de la demande de prêt : ' . $errorMessage);
            return redirect()->back()->withInput();
        }

    }

    

    

    public function show($id)
    {
        $loan = Loan::findOrFail($id);

        // Vérifier si le prêt n'est pas approuvé
        if ($loan->status !== 'Approuvé') {
            Alert::info('Information', 'Le prêt n\'est pas approuvé.');
            return redirect()->back();
        }

        // Vérifier si le prêt a déjà été entièrement remboursé
        if ($loan->status === 'Approuvé' && $loan->balance <= 0) {
            Alert::info('Information', 'Le prêt a déjà été entièrement remboursé.');
            return redirect()->back();
        }

        $amortizationSchedule = Amortization::where('loan_id', $loan->id)->get();
    
        return view('backend.admin.loan.show', compact('loan', 'amortizationSchedule'));
    }

    public function edit($id)
    {
        // Afficher le formulaire d'édition d'un prêt existant
    }

    public function update(Request $request, $id)
    {
        // Valider les données du formulaire et mettre à jour les informations du prêt
    }

    public function destroy($id)
    {
        // Supprimer un prêt existant
    }
}
