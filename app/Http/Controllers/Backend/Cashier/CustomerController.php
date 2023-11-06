<?php

namespace App\Http\Controllers\Backend\Cashier;

use App\Http\Controllers\Backend\DateController;
use App\Http\Controllers\Backend\GenerateIdController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\VerifyNumberController;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    public function index(){
        $customers = Customer::all();
        return view('backend.cashier.customer.index', compact('customers'));
    }

    public function create(){
        $customers = Customer::all();
        return view('backend.cashier.customer.create', compact('customers'));
    }

    public function getCustomerData($id)
    {
        $userId = Crypt::decrypt($id);

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

    public function getCustomerDataByPhone($phoneNumber)
    {
        // $phoneNumber = Crypt::decrypt($phoneNumber);

        $customer = DB::connection('mysql2')
            ->table('users')
            ->join('wallets', 'users.id', '=', 'wallets.customer_id')
            ->select(
                'users.id',
                'users.firstname',
                DB::raw('users.name AS lastname'),
                DB::raw('users.created_at AS join_date'),
                'users.phone',
                'users.email',
                'users.country',
                'users.address',
                'users.avatar',
                'users.role_name',
                'users.city',
                'wallets.wallet_type',
                DB::raw('GROUP_CONCAT(DISTINCT wallets.wallet_code) AS wallet_codes'),
                DB::raw('SUM(CASE WHEN wallets.wallet_currency = "CDF" AND wallets.wallet_type = "Current" THEN wallets.wallet_balance END) AS cdf_current_balance'),
                DB::raw('SUM(CASE WHEN wallets.wallet_currency = "USD" AND wallets.wallet_type = "Current" THEN wallets.wallet_balance END) AS usd_current_balance'),
                DB::raw('MAX(CASE WHEN wallets.wallet_type = "Current" THEN wallets.wallet_code END) AS current_wallet_code')
            )
            ->where('users.phone', $phoneNumber)
            ->where('wallets.wallet_type', 'Current')
            ->groupBy('users.id', 'wallets.wallet_type')
            ->first();
           
        $transactions = [];
        $id_user = null;
        $avatar = null;
        $role_name = null;
        $city = null;
        $email = null;
        $country = null;
        $address = null;
        $phone_number = $phoneNumber;
        $join_date = null;
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
            $email = $customer->email;
            $country = $customer->country;
            $join_date = $customer->join_date;
            $lastname = $customer->lastname;
            $role_name = $customer->role_name;
            $avatar = $customer->avatar;
            $firstname = $customer->firstname;
            $c_bcdf = $customer->cdf_current_balance;
            $c_busd = $customer->usd_current_balance;
            $id_user = $customer->id;

            $saving = DB::connection('mysql2')
                ->table('users')
                ->join('wallets', 'users.id', '=', 'wallets.customer_id')
                ->select(
                    'wallets.wallet_type',
                    'users.phone',
                    DB::raw('GROUP_CONCAT(DISTINCT wallets.wallet_code) AS wallet_codes'),
                    DB::raw('SUM(CASE WHEN wallets.wallet_currency = "CDF" AND wallets.wallet_type = "Saving" THEN wallets.wallet_balance END) AS cdf_saving_balance'),
                    DB::raw('SUM(CASE WHEN wallets.wallet_currency = "USD" AND wallets.wallet_type = "Saving" THEN wallets.wallet_balance END) AS usd_saving_balance'),
                    DB::raw('MAX(CASE WHEN wallets.wallet_type = "Saving" THEN wallets.wallet_code END) AS saving_wallet_code')
                )
                ->where('users.phone', $phoneNumber)
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
            'city',
            'country',
            'email',
            'address',
            'phone_number',
            'join_date',
            'lastname',
            'firstname',
            'c_bcdf',
            'c_busd',
            's_bcdf',
            's_busd',
            'cnumber',
            'snumber'
        );
    }

    public function add(Request $request){
        $request->validate([
            'firstname'   => 'required|string|max:255',
            'lastname'   => 'required|string|max:255',
            'phone_number'   => 'required|string|max:255',
            'ville'   => 'required|string|max:255',
            'pays'   => 'required|string|max:255',
            'adresse'   => 'required|string|max:255',
        ]);

        $date = new DateController;
        $today = $date->todayDate();

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $phone= new VerifyNumberController;
        $phone_number = $phone->verify_number($request->phone_number);
        $created_by = Auth::user()->id;
        $role_name = "Customer";
        $pin = new GenerateIdController;
        $password = $pin->password();

        $account = new AccountController;
    

        # Vérification de l'existence du numéro de téléphone dans la table users
        $verify = $this->verifyUser($phone_number);
     
        if ($verify["status"]==404) {
            $customer = [
                'firstname' => $firstname,
                'name' => $lastname,
                'phone' => $phone_number,
                'role_name' => $role_name,
                'city' => $request->ville,
                'country' => $request->pays,
                'address' => $request->adresse,
                'created_by' => $created_by,
                'created_at' => $today,
                'updated_at' => $today,
                'password' => Hash::make($password),
                'password_salt' => $password,
            ];
            $newCustomer = Customer::insert($customer);
            if ($newCustomer) {
                $result = Customer::where('phone',$phone_number)->first();
                $customer_id = $result->id;
                $account->compte($customer_id);
            }

            Alert::success('Succès', 'Client créé avec succès!');
            return redirect()->route('cashier.customer.all');
        }
        else {
            Alert::success('Succès', $verify["message"]);
            return redirect()->back();
        }
    }


    public function edit(Request $request){
        $request->validate([
            'lastname'      => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'phone_number' => 'required|string|min:9|max:12',
            'adresse' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $phone_number = $request->phone_number;
        $lastname = $request->lastname;
        $firstname = $request->firstname;
        $adresse = $request->adresse;
        $ville = $request->city;
        $pays = $request->country;

        $date = new DateController;
        $today = $date->todayDate();
        DB::beginTransaction();
        try {
            Customer::where('phone',$phone_number)->update([
                'firstname' => $firstname,
                'name' => $lastname,
                'phone' => $phone_number,
                'city' => $ville,
                'country' => $pays,
                'address' => $adresse,
                'updated_at'   => $today,
            ]);
        
            DB::commit();
            Alert::success('Succès', 'Infomation mise à jour avec succès !');
            return redirect()->route('cashier.customer.all');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::success('Succès', 'Une erreur est survenue lors de la modification du client!');
            return redirect()->route('cashier.customer.all');
        }


    }

    public function delete(Request $request){
        $customer_id = $request->id;
        $customer = Customer::where('id',$customer_id)->first();
        DB::beginTransaction();
        try {
            $customer->delete();
            DB::commit();
            Alert::success('Succès', 'Client supprimé avec succès !');
            return redirect()->route('cashier.customer.all');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::success('Succès', 'Une erreur est survenue lors de la suppression du client!');
            return redirect()->route('cashier.customer.all');
        }
        
    }

    public function verifyUser($phone_number){
        $user = Customer::where('phone',$phone_number)->count();
        if ($user == 0) {
            return ['status' => 404,'message' => 'Client non trouvé'];
        }
        else {
            return ['status' => 200,'message' => 'Ce numéro est déjà attribué à un client!'];
        }
    }
}
