<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\API\Initialize;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branche;
use App\Models\RechargeRequest;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TicketController extends Controller
{
    public function branche_id($user_id){
        $branche = Branche::where('user_id', $user_id)->first();
        if ($branche == null) {
            $branche_id = null;
            return $branche_id;
        }
        else {
            $branche_id = $branche->id;
            return $branche_id;
        }
        
    }
    public function cashierBrancheId($user_id){
        $branche = Account::where('user_id',$user_id)->first();
            
        if ($branche == null) {
            $branche_id = null;
            return $branche_id;
        }
        else {
            $branche_id = $branche->branche_id;
            return $branche_id;
        }
        
    }
    public function create(){
        $branche_id = $this->branche_id(Auth::user()->id);
    
        
        $admins = DB::table('users')->where('role_name', 'Admin')->get();
        if (Auth::user()->role_name == "Admin") {
            $tickets = Ticket::join('users','tickets.receiver_id','users.id')
            ->join('branches','tickets.receiver_id','branches.user_id')
            ->select('tickets.*','users.firstname','users.lastname','branches.id')
            ->where('branches.id',$branche_id)
            ->where('receiver_id', Auth::user()->id)->get();
            return view('backend.ticket.create',compact('tickets','admins'));
        }
        elseif (Auth::user()->role_name == "Manager") {
            $tickets = Ticket::join('users','tickets.sender_id','users.id')
            ->join('branches','tickets.sender_id','branches.user_id')
            ->select('tickets.*','users.firstname','users.lastname','branches.id')
            ->where('branches.id',$branche_id)
            ->where('sender_id', Auth::user()->id)->get();
            return view('manager.ticket.create',compact('tickets','admins'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            
            $agence = Account::where('user_id',Auth::user()->id)->first();
            $brancheId = $agence->branche_id;

            $account = Account::where('branche_id',$brancheId)->where('account_level',2)->first();
            $userId = $account->user_id;

            $admins = DB::table('users')->where('id', $userId)->first();

            $tickets = Ticket::join('users','tickets.sender_id','users.id')
            // ->join('branches','tickets.sender_id','branches.user_id')
            ->select('tickets.*','users.firstname','users.lastname')
            ->where('tickets.branche_id',$brancheId)
            ->where('sender_id', Auth::user()->id)->get();

            // dd($brancheId);

            return view('cashier.ticket.create',compact('tickets','admins'));
        }
    } 
    public function index($length = 3, $descritption = null) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }



    public function store(Request $request){
        $request->validate([
            'subject'   => 'required|string|max:255',
            'message'   => 'required|string|max:255',
            'assigned_id'   => 'required|string|max:255',
        ]);
        $subject = $request->subject;
        $date = Carbon::now();
        $user_id = Auth::user()->id;
        $today = $date->format('dmY');
        $assigned_id = $request->assigned_id;
        $message = $request->message;
        $file = $request->file;
        if (!empty($file)) {
            $file_name = $this->branche_id($user_id).$today.$this->index() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/assets/images/tickets/'), $file_name);
        }
        else {
            $file_name = null;
        }
        
        $initialize = new Initialize;
        $response = $initialize->ticket_request($subject,$message,$file_name,$assigned_id);
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }
    public function create_recharge(){
        if (Auth::user()->role_name == "Admin") {
            $tickets = RechargeRequest::join('users','recharge_requests.requester_id','users.id')
            ->join('branches','recharge_requests.branche_id','branches.id')
            ->select('recharge_requests.*','users.firstname','users.lastname','branches.bcode','branches.bname')
            ->get();
            return view('admin.recharge.create',compact('tickets'));
        }
        elseif (Auth::user()->role_name == "Manager") {
            $tickets = RechargeRequest::join('users','recharge_requests.requester_id','users.id')
            ->select('recharge_requests.*','users.firstname','users.lastname')
            // ->where('requester_id', Auth::user()->id)
            ->where('assigned_id', Auth::user()->id)->get();
            return view('manager.recharge.create',compact('tickets'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            $tickets = RechargeRequest::join('users','recharge_requests.requester_id','users.id')
            ->select('recharge_requests.*','users.firstname','users.lastname')
            ->where('requester_id', Auth::user()->id)->get();
            return view('cashier.recharge.create',compact('tickets'));
        }
        

        
    } 

    public function downloadFile($file_name)
    {
        $myFile = public_path('assets/images/tickets/'.$file_name);
    	$headers = ['Content-Type: application/force-download'];
        return response()->download($myFile, $file_name, $headers);
        /*
        $myFile = public_path('assets/images/tickets/'.$file_name);
        $headers = ['Content-Type: application/pdf'];
        $newName = 'emala-pdf-file-'.time().'.pdf';
    	return response()->download($myFile, $newName, $headers);
        */
    }

    public function store_recharge(Request $request){
        $request->validate([
            'subject'   => 'required|string|max:255',
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'assigned_id'   => 'required|string|max:255',
        ]);
        $subject = $request->subject;
        // dd($subject);
        $amount = $request->amount;
        $currency = $request->currency;
        $assigned_id = $request->assigned_id;

        $initialize = new Initialize;
        $response = $initialize->recharge_request($subject,$amount,$currency,$assigned_id);
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 

    }
    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }
    public function success_recharge(Request $request){
        $requestId = $request->id;
        $data = RechargeRequest::where('id',$requestId)->first();
        $branche_id = $data->branche_id;
        $requester_id = $data->requester_id;
        $amount = $data->amount;
        $currency = $data->currency;

        $todayDate = $this->todayDate();

        $user = User::where('id', $requester_id)->first();
        $role_name = $user->role_name;
        if ($role_name == "Manager") {
            $user_level = 2;
            $account_level = 1;
        }
        elseif ($role_name == "Cashier") {
            $user_level = 3;
            $account_level = 2;
        }
        // dd($requester_id);
        $account = DB::table('accounts')->where('user_id', $requester_id)->where('account_level', $user_level)->where('currency', $currency)->where('branche_id', $branche_id)->first();
        // dd($account);
        $account_id = $account->id;

        

        $details = new Initialize;
        $response = $details->topup_account($amount, $account_id,$currency,$account_level);
       
        if ($response['success'] == true) {
            $datas = ['status'=> "Approuvé",'updated_at'=> $todayDate];
            $update = RechargeRequest::where('id',$requestId)->update($datas);
            if ($update) {
                Alert::success('Succès', $response['message']);
                return redirect()->back();
            }
            else {
                Alert::error('Echec', $response['message']);
                return redirect()->back();
            }
            
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        }  
    }
}
