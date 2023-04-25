$request->validate([
    'fund_usd_on_o'   => 'required|numeric',
    'fund_cdf_on_o'   => 'required|numeric',
]);

$userid = Auth::User()->id;
$initialize = new __init__;
$response = $initialize->cashier_account_wallet($userid);
$bank_user_id = $response->bank_user_id;
$bank_account = DB::table('bank_accounts')->where('bank_user_id',$bank_user_id)->first();

$bank_account_id = $bank_account->id;
$todayDate = Carbon::now()->format('Y-m-d H:i:s');

$generate = new GenerateIdController;
$acnumber = $generate->bank_acount();

$branche = DB::table('branches')->where('btype','Parent')->first();
$branche_id = $branche->id;

$_tirroir_account_count = tirroir_account::whereDate('created_at', Carbon::today()->toDateString())->where('bank_user_id',$bank_user_id)->count();

if ($_tirroir_account_count == 0) {

    $_tirroir_yesterday_count = tirroir_account::whereDate('created_at', Carbon::yesterday()->toDateString())->where('bank_user_id',$bank_user_id)->count();

    if ($_tirroir_yesterday_count == 0) {
        cash_register::create([
            'bank_account_id'=>$bank_account_id,
            'fund_cdf_on_o'=> $request->fund_cdf_on_o,
            'fund_usd_on_o'=>$request->fund_usd_on_o,
            'closed'=>"no",
            'opened_on'=>$todayDate
        ]);

        tirroir_account::create([
            'acnumber'   => $acnumber,
            'bank_user_id'   => $bank_user_id,
            'branche_id'   => $branche_id,
            'status'   => 1,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
            'balance_cdf'   => $request->fund_cdf_on_o,
            'balance_usd'   => $request->fund_usd_on_o,
        ]);

        $data = [
            'cash_register' => 0,
        ];

        $account_update = DB::table('bank_accounts')->where('bank_user_id',$bank_user_id)->update($data);

        if ($account_update) {
            Toastr::success('Session de caisse ouvert avec succès!','Success');
            return redirect()->route('admin.dashboard');
        }
    }

    else {
        $cash_register = DB::table('cash_registers')->whereDate('opened_on', Carbon::yesterday()->toDateString())->where('closed','yes')->where('bank_account_id',$bank_account_id)->first();
        $fund_cdf_on_c = $cash_register->fund_cdf_on_c;
        $fund_usd_on_c = $cash_register->fund_usd_on_c;

        if ($request->fund_cdf_on_o == $fund_cdf_on_c && $request->fund_usd_on_o == $fund_usd_on_c) {
            cash_register::create([
                'bank_account_id'=>$bank_account_id,
                'fund_cdf_on_o'=> $request->fund_cdf_on_o,
                'fund_usd_on_o'=>$request->fund_usd_on_o,
                'closed'=>"no",
                'opened_on'=>$todayDate
            ]);

            $data = [
                'cash_register' => 0,
            ];
    
            $account_update = DB::table('bank_accounts')->where('bank_user_id',$bank_user_id)->update($data);
    
            if ($account_update) {
                Toastr::success('Session de caisse ouvert avec succès!','Success');
                return redirect()->route('admin.dashboard');
            }
        }

        elseif ($request->fund_cdf_on_o != $fund_cdf_on_c && $request->fund_usd_on_o != $fund_usd_on_c) {
            Toastr::error('Veuillez revérifier le fond de caisse!','Erreur!');
            return redirect()->back(); 
        }
    }

    
}

elseif ($_tirroir_account_count >= 1) {

    cash_register::create([
        'bank_account_id'=>$bank_account_id,
        'fund_cdf_on_o'=> $request->fund_cdf_on_o,
        'fund_usd_on_o'=>$request->fund_usd_on_o,
        'closed'=>"no",
        'opened_on'=>$todayDate
    ]);

    $data = [
        'cash_register' => 0,
    ];

    $account_update = DB::table('bank_accounts')->where('bank_user_id',$bank_user_id)->update($data);

    if ($account_update) {
        Toastr::success('Session de caisse ouvert avec succès!','Success');
        return redirect()->route('admin.dashboard');
    }
}