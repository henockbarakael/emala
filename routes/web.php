<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware'=>'auth'],function()
{
    Route::get('home',function()
    {
        return view('home');
    });
});

Auth::routes();

// -----------------------------settings----------------------------------------//
Route::get('company/settings/page', [App\Http\Controllers\SettingController::class, 'companySettings'])->middleware('auth')->name('company/settings/page');
Route::get('roles/permissions/page', [App\Http\Controllers\SettingController::class, 'rolesPermissions'])->middleware('auth')->name('roles/permissions/page');
Route::post('roles/permissions/save', [App\Http\Controllers\SettingController::class, 'addRecord'])->middleware('auth')->name('roles/permissions/save');
Route::post('roles/permissions/update', [App\Http\Controllers\SettingController::class, 'editRolesPermissions'])->middleware('auth')->name('roles/permissions/update');
Route::post('roles/permissions/delete', [App\Http\Controllers\SettingController::class, 'deleteRolesPermissions'])->middleware('auth')->name('roles/permissions/delete');

// -----------------------------login----------------------------------------//
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::get('session_validate', [App\Http\Controllers\Auth\LoginController::class, 'session'])->name('session.validate');
Route::post('start_new_session', [App\Http\Controllers\Auth\LoginController::class, 'start_new_session'])->name('start.new.session');
Route::get('session_stay_in', [App\Http\Controllers\Auth\LoginController::class, 'session_stay_in'])->name('session.stay.in');

Route::post('authenticate', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// ------------------------------ register ---------------------------------//
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'storeUser'])->name('register');

// ----------------------------- forget password ----------------------------//
Route::get('forget-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'getEmail'])->name('forget-password');
Route::post('forget-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'postEmail'])->name('forget-password');

// ----------------------------- reset password -----------------------------//
Route::get('reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'getPassword']);
Route::post('reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'updatePassword']);


Route::group(['prefix'=>'admin', 'middleware'=>['admin','auth','PreventBackHistory']], function(){
    // ----------------------------- dashboard -----------------------------//
    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'admin'])->name('admin.dashboard');

    Route::get('ouverture-caisse', [App\Http\Controllers\API\CashRegisterController::class, 'ouverture'])->name('admin.ouverture_caisse');
    Route::get('cloture-caisse', [App\Http\Controllers\API\CashRegisterController::class, 'cloture'])->name('admin.cloture_caisse');
    Route::post('cloture-caisse', [App\Http\Controllers\API\CashRegisterController::class, 'postcloture'])->name('admin.postcloture_caisse');
    Route::post('fond_de_caisse_ouverture', [App\Http\Controllers\API\CashRegisterController::class, 'fond_ouverture'])->name('admin.fondcaisse.ouverture');
    Route::post('fond_de_caisse_cloture', [App\Http\Controllers\API\CashRegisterController::class, 'fond_cloture'])->name('admin.fondcaisse.cloture');

    Route::get('autocomplete', [App\Http\Controllers\API\CustomerAPI::class, 'autocomplete'])->name('admin.autocomplete');
    Route::get('search_autocomplete', [App\Http\Controllers\API\CustomerAPI::class, 'search_autocomplete'])->name('admin.search_autocomplete');
    Route::get('donwload-file/{file_name}', [App\Http\Controllers\Ticket\TicketController::class, 'downloadFile'])->name('admin.download.file');
    Route::get('liste-client', [App\Http\Controllers\UtilisateurController::class, 'liste_client'])->name('admin.liste_client');
    Route::post('creation-client', [App\Http\Controllers\UtilisateurController::class, 'creation_client'])->name('admin.creation_client');
    Route::post('modification-client', [App\Http\Controllers\UtilisateurController::class, 'modification_client'])->name('admin.modification_client');
    Route::post('supprimer-client', [App\Http\Controllers\UtilisateurController::class, 'supprimer_client'])->name('admin.supprimer_client');
    Route::get('liste-caissier', [App\Http\Controllers\UtilisateurController::class, 'liste_caissier'])->name('admin.liste_caissier');
    Route::post('creation-caissier', [App\Http\Controllers\UtilisateurController::class, 'creation_caissier'])->name('admin.creation_caissier');
    Route::get('liste-gerant', [App\Http\Controllers\UtilisateurController::class, 'liste_gerant'])->name('admin.liste_gerant');
    Route::get('liste-admin', [App\Http\Controllers\UtilisateurController::class, 'liste_admin'])->name('admin.liste_admin');
    Route::post('creation-admin', [App\Http\Controllers\UtilisateurController::class, 'creation_admin'])->name('admin.creation_admin');
    Route::post('modification-admin', [App\Http\Controllers\UtilisateurController::class, 'modification_admin'])->name('admin.modification_admin');
    Route::post('supprimer-admin', [App\Http\Controllers\UtilisateurController::class, 'supprimer_admin'])->name('admin.supprimer_admin');
    Route::post('creation-gerant', [App\Http\Controllers\UtilisateurController::class, 'creation_gerant'])->name('admin.creation_gerant');
    Route::post('modification-gerant', [App\Http\Controllers\UtilisateurController::class, 'modification_gerant'])->name('admin.modification_gerant');
    Route::post('supprimer-gerant', [App\Http\Controllers\UtilisateurController::class, 'supprimer_gerant'])->name('admin.supprimer_gerant');
    Route::get('liste-marchand', [App\Http\Controllers\UtilisateurController::class, 'liste_marchand'])->name('admin.liste_marchand');
    Route::post('creation-marchand', [App\Http\Controllers\UtilisateurController::class, 'creation_marchand'])->name('admin.creation_marchand');

    Route::get('compte-client/{id}', [App\Http\Controllers\API\AccountController::class, 'client'])->name('admin.account.create.client');
    Route::get('depot/cash/{id}', [App\Http\Controllers\API\AccountController::class, 'client_depot'])->name('admin.account.create.client.depot.create');
    Route::get('retrait/cash/{id}', [App\Http\Controllers\API\AccountController::class, 'client_retrait'])->name('admin.account.create.client.retrait.create');
    Route::get('transfert/interne/{id}', [App\Http\Controllers\API\AccountController::class, 'client_transfert'])->name('admin.account.create.client.transfert.create');
    Route::get('mobile-money/interne/{id}', [App\Http\Controllers\API\AccountController::class, 'client_mobile'])->name('admin.account.create.client.mobile.create');
    Route::get('compte-gerant/{id}', [App\Http\Controllers\API\AccountController::class, 'gerant'])->name('admin.account.create.gerant');
    Route::get('compte-caissier/{id}', [App\Http\Controllers\API\AccountController::class, 'caissier'])->name('admin.account.create.caissier');
    Route::get('transfert/compte-compte/{id}', [App\Http\Controllers\API\AccountController::class, 'transfert_compte_compte'])->name('admin.account.transfert_compte_compte');
    Route::post('transfert/compte-compte', [App\Http\Controllers\API\TransferController::class, 'Current_Saving'])->name('admin.account.transfert_current_saving');
    Route::get('retrait/compte-epargne/{id}', [App\Http\Controllers\API\AccountController::class, 'retrait_compte_epargne'])->name('admin.account.retrait_compte_epargne');
    Route::post('retrait-compte-epargne', [App\Http\Controllers\Manager\WithdrawalController::class, 'retrait_compte_epargne'])->name('admin.withdrawal.epargne.store');

    Route::get('activity-log', [App\Http\Controllers\API\LogController::class, 'activity_log'])->name('admin.activity_log');
    Route::get('user-activity-log', [App\Http\Controllers\API\LogController::class, 'user_activity_log'])->name('admin.user_activity_log');

    Route::post('create-wallet', [App\Http\Controllers\API\WalletController::class, 'create'])->name('admin.wallet.create');
    Route::post('delete-wallet', [App\Http\Controllers\API\WalletController::class, 'delete'])->name('admin.wallet.delete');
    Route::post('topup-wallet', [App\Http\Controllers\API\WalletController::class, 'topup'])->name('admin.wallet.topup');
    Route::get('liste-wallet', [App\Http\Controllers\API\WalletController::class, 'all'])->name('admin.wallet.all');

    Route::get('liste-gateway', [App\Http\Controllers\API\PaymentGatewayController::class, 'all'])->name('admin.gateway.all');

    Route::get('liste-branche', [App\Http\Controllers\API\BrancheController::class, 'all'])->name('admin.branche.all');
    Route::get('branche-treller', [App\Http\Controllers\API\BrancheController::class, 'allTreller'])->name('admin.branche.all.treller');
    Route::get('branche-account', [App\Http\Controllers\API\BrancheController::class, 'allAccount'])->name('admin.branche.all.account');
    Route::get('branche-account-manager', [App\Http\Controllers\API\BrancheController::class, 'Manager'])->name('admin.branche.account.manager');
    Route::post('topup-account', [App\Http\Controllers\API\BrancheController::class, 'topup'])->name('admin.branche.account.topup');
    Route::get('topup-branche', [App\Http\Controllers\API\BrancheController::class, 'topup_agence'])->name('admin.branche.manager.all');
    Route::post('topup-branche', [App\Http\Controllers\API\BrancheController::class, 'recharge_agence'])->name('admin.branche.account.recharge');
    Route::get('profil-utilisateur', [App\Http\Controllers\API\AccountController::class, 'profilUser'])->name('admin.account.profil.user');

    Route::get('create-ticket', [App\Http\Controllers\Ticket\TicketController::class, 'create'])->name('admin.ticket.create');
    Route::post('create-ticket', [App\Http\Controllers\Ticket\TicketController::class, 'store'])->name('admin.ticket.store');
    Route::get('create-recharge-request', [App\Http\Controllers\Ticket\TicketController::class, 'create_recharge'])->name('admin.reharge.request.create');
    Route::post('create-recharge-request', [App\Http\Controllers\Ticket\TicketController::class, 'store_recharge'])->name('admin.reharge.request.store');
    Route::post('create-recharge-success', [App\Http\Controllers\Ticket\TicketController::class, 'success_recharge'])->name('admin.reharge.request.success');
    Route::post('create-recharge-failed', [App\Http\Controllers\Ticket\TicketController::class, 'failed_recharge'])->name('admin.reharge.request.failed');

    Route::post('branche-treller', [App\Http\Controllers\API\BrancheController::class, 'StoreTreller'])->name('admin.branche.store.treller');
    Route::post('create-branche', [App\Http\Controllers\API\BrancheController::class, 'create'])->name('admin.branche.create');
    Route::post('assign-branche', [App\Http\Controllers\API\BrancheController::class, 'assign'])->name('admin.branche.assign');
    Route::post('update-branche', [App\Http\Controllers\API\BrancheController::class, 'UpdateBranche'])->name('admin.branche.update');

    Route::get('comingsoon', [App\Http\Controllers\Comingsoon::class, 'comingsoon'])->name('admin.comingsoon');

    Route::get('create-saving', [App\Http\Controllers\Admin\SavingController::class, 'create'])->name('admin.saving.create');
    Route::get('create-current', [App\Http\Controllers\Admin\CurrentController::class, 'create'])->name('admin.current.create');
    Route::post('create-saving', [App\Http\Controllers\Admin\SavingController::class, 'store'])->name('admin.saving.store');
    Route::post('create-current', [App\Http\Controllers\Admin\CurrentController::class, 'store'])->name('admin.current.store');
    Route::get('verification-du-compte', [App\Http\Controllers\Admin\SavingController::class, 'verify'])->name('admin.saving.verify');
    Route::post('verification-du-compte', [App\Http\Controllers\Admin\SavingController::class, 'verify_post'])->name('admin.saving.verify-post');
    
    Route::get('internal-deposit', [App\Http\Controllers\API\DepositController::class, 'internal'])->name('admin.internal.deposit');
    Route::get('external-deposit', [App\Http\Controllers\API\DepositController::class, 'external'])->name('admin.external.deposit');
    Route::post('internal-deposit', [App\Http\Controllers\API\DepositController::class, 'internal_store'])->name('admin.internal.deposit.store');
    Route::post('external-deposit', [App\Http\Controllers\API\DepositController::class, 'external_store'])->name('admin.external.deposit.store');

    Route::get('create-transfer-externe', [App\Http\Controllers\API\TransferController::class, 'create_emala'])->name('admin.transfer.emala_externe');
    Route::get('create-transfer-interne', [App\Http\Controllers\API\TransferController::class, 'create_emala_interne'])->name('admin.transfer.emala_interne');
    Route::get('create-transfer-virement', [App\Http\Controllers\API\TransferController::class, 'create_emala_virement'])->name('admin.transfer.emala_virement');
    Route::post('create-transfer-externe', [App\Http\Controllers\API\TransferController::class, 'EmalaStore'])->name('admin.transfer.emala.store');
    Route::post('create-transfer-interne', [App\Http\Controllers\API\TransferController::class, 'EmalaStoreInterne'])->name('admin.transfer.emala_interne.store');
    Route::post('create-transfer-virement', [App\Http\Controllers\API\TransferController::class, 'EmalaStoreVirement'])->name('admin.transfer.emala_virement.store');
    Route::get('create-transfer-emala-mobile-money', [App\Http\Controllers\API\TransferController::class, 'create_mobile'])->name('admin.transfer.mobile');

    Route::get('withdrawal-transfert-cash', [App\Http\Controllers\API\WithdrawalController::class, 'transfert_cash'])->name('admin.withdrawal.transfert');
    Route::post('withdrawal-transfert-cash', [App\Http\Controllers\API\WithdrawalController::class, 'transfert_cash_store'])->name('admin.withdrawal.transfert.store');
    Route::get('withdrawal-compte-cash', [App\Http\Controllers\API\WithdrawalController::class, 'compte_cash'])->name('admin.withdrawal.compte');
    Route::post('withdrawal-compte-cash', [App\Http\Controllers\API\WithdrawalController::class, 'compte_cash_store'])->name('admin.withdrawal.compte.store');

    Route::get('create-withdrawal-emala-mobile-money', [App\Http\Controllers\API\WithdrawalController::class, 'create_mobile'])->name('admin.withdrawal.mobile');
    Route::post('create-withdrawal-emala-mobile-money', [App\Http\Controllers\API\MobileMoneyController::class, 'credit'])->name('admin.withdrawal.mobile.credit');


    Route::get('payment-request', [App\Http\Controllers\API\PaymentRequestController::class, 'PaymentRequest'])->name('admin.payment.index');

    Route::get('transaction-all', [App\Http\Controllers\API\TransactionController::class, 'all'])->name('admin.transaction.all');
    Route::post('transaction-all', [App\Http\Controllers\API\TransactionController::class, 'recherche'])->name('admin.transaction.recherche');
    Route::get('transaction-deposit', [App\Http\Controllers\API\TransactionController::class, 'deposit'])->name('admin.transaction.deposit');
    Route::get('transaction-withdrawal', [App\Http\Controllers\API\TransactionController::class, 'withdrawal'])->name('admin.transaction.withdrawal');
    Route::get('transaction-transfer', [App\Http\Controllers\API\TransactionController::class, 'transfer'])->name('admin.transaction.transfer');
    Route::get('transaction-limit', [App\Http\Controllers\API\TransactionController::class, 'limit'])->name('admin.transaction.limit');
    Route::post('transaction-limit', [App\Http\Controllers\API\TransactionController::class, 'limitPOST'])->name('admin.transaction.limit.post');

});


Route::group(['prefix'=>'manager', 'middleware'=>['manager','auth','PreventBackHistory']], function(){
    // ----------------------------- dashboard -----------------------------//
    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'manager'])->name('manager.dashboard');

    Route::get('transaction-all', [App\Http\Controllers\Manager\TransactionController::class, 'all'])->name('manager.transaction.all');
    Route::post('transaction-all', [App\Http\Controllers\Manager\TransactionController::class, 'recherche'])->name('manager.transaction.recherche');
    Route::get('transaction-deposit', [App\Http\Controllers\Manager\TransactionController::class, 'deposit'])->name('manager.transaction.deposit');
    Route::get('transaction-withdrawal', [App\Http\Controllers\Manager\TransactionController::class, 'withdrawal'])->name('manager.transaction.withdrawal');
    Route::get('transaction-transfer', [App\Http\Controllers\Manager\TransactionController::class, 'transfer'])->name('manager.transaction.transfer');
    Route::get('transaction-limit', [App\Http\Controllers\Manager\TransactionController::class, 'limit'])->name('manager.transaction.limit');
    Route::post('transaction-limit', [App\Http\Controllers\Manager\TransactionController::class, 'limitPOST'])->name('manager.transaction.limit.post');

    Route::get('ouverture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'ouverture'])->name('manager.ouverture_caisse');
    Route::get('cloture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'cloture'])->name('manager.cloture_caisse');
    Route::post('cloture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'postcloture'])->name('manager.postcloture_caisse');
    Route::post('fond_de_caisse_ouverture', [App\Http\Controllers\Manager\CashRegisterController::class, 'fond_ouverture'])->name('manager.fondcaisse.ouverture');
    Route::post('fond_de_caisse_cloture', [App\Http\Controllers\Manager\CashRegisterController::class, 'fond_cloture'])->name('manager.fondcaisse.cloture');

    Route::get('autocomplete', [App\Http\Controllers\Manager\CustomerAPI::class, 'autocomplete'])->name('manager.autocomplete');
    Route::get('search_autocomplete', [App\Http\Controllers\Manager\CustomerAPI::class, 'search_autocomplete'])->name('manager.search_autocomplete');


    Route::get('donwload-file/{file_name}', [App\Http\Controllers\Ticket\TicketController::class, 'downloadFile'])->name('manager.download.file');


    Route::get('create-ticket', [App\Http\Controllers\Ticket\TicketController::class, 'create'])->name('manager.ticket.create');
    Route::post('create-ticket', [App\Http\Controllers\Ticket\TicketController::class, 'store'])->name('manager.ticket.store');
    Route::get('create-recharge-request', [App\Http\Controllers\Ticket\TicketController::class, 'create_recharge'])->name('manager.reharge.request.create');
    Route::post('create-recharge-request', [App\Http\Controllers\Ticket\TicketController::class, 'store_recharge'])->name('manager.reharge.request.store');
    Route::post('create-recharge-success', [App\Http\Controllers\Ticket\TicketController::class, 'success_recharge'])->name('manager.reharge.request.success');
    Route::post('create-recharge-failed', [App\Http\Controllers\Ticket\TicketController::class, 'failed_recharge'])->name('manager.reharge.request.failed');


    Route::get('liste-client', [App\Http\Controllers\Manager\UtilisateurController::class, 'liste_client'])->name('manager.liste_client');
    Route::post('creation-client', [App\Http\Controllers\Manager\UtilisateurController::class, 'creation_client'])->name('manager.creation_client');
    Route::post('modification-client', [App\Http\Controllers\Manager\UtilisateurController::class, 'modification_client'])->name('manager.modification_client');
    Route::post('supprimer-client', [App\Http\Controllers\Manager\UtilisateurController::class, 'supprimer_client'])->name('manager.supprimer_client');
    Route::get('liste-caissier', [App\Http\Controllers\Manager\UtilisateurController::class, 'liste_caissier'])->name('manager.liste_caissier');
    Route::post('creation-caissier', [App\Http\Controllers\Manager\UtilisateurController::class, 'creation_caissier'])->name('manager.creation_caissier');
    Route::post('supprimer-caissier', [App\Http\Controllers\Manager\UtilisateurController::class, 'supprimer_caissier'])->name('manager.supprimer_caissier');
    Route::get('liste-gerant', [App\Http\Controllers\Manager\UtilisateurController::class, 'liste_gerant'])->name('manager.liste_gerant');
    Route::post('creation-gerant', [App\Http\Controllers\Manager\UtilisateurController::class, 'creation_gerant'])->name('manager.creation_gerant');
    Route::post('modification-gerant', [App\Http\Controllers\Manager\UtilisateurController::class, 'modification_gerant'])->name('manager.modification_gerant');
    Route::post('supprimer-gerant', [App\Http\Controllers\Manager\UtilisateurController::class, 'supprimer_gerant'])->name('manager.supprimer_gerant');
    Route::get('liste-marchand', [App\Http\Controllers\Manager\UtilisateurController::class, 'liste_marchand'])->name('manager.liste_marchand');
    Route::post('creation-marchand', [App\Http\Controllers\Manager\UtilisateurController::class, 'creation_marchand'])->name('manager.creation_marchand');

    Route::get('profil-utilisateur', [App\Http\Controllers\Manager\AccountController::class, 'profilUser'])->name('manager.account.profil.user');
    Route::get('compte-client/{id}', [App\Http\Controllers\Manager\AccountController::class, 'client'])->name('manager.account.create.client');
    Route::get('compte-client-phone/{phone}', [App\Http\Controllers\Manager\AccountController::class, 'customerByPhone'])->name('manager.account.create.clientByPhone');
    Route::get('depot/cash/{id}', [App\Http\Controllers\Manager\AccountController::class, 'client_depot'])->name('manager.account.create.client.depot.create');
    Route::get('retrait/cash/{id}', [App\Http\Controllers\Manager\AccountController::class, 'client_retrait'])->name('manager.account.create.client.retrait.create');
    Route::get('transfert/interne/{id}', [App\Http\Controllers\Manager\AccountController::class, 'client_transfert'])->name('manager.account.create.client.transfert.create');
    Route::get('mobile-money/interne/{id}', [App\Http\Controllers\Manager\AccountController::class, 'client_mobile'])->name('manager.account.create.client.mobile.create');
    Route::get('compte-gerant/{id}', [App\Http\Controllers\Manager\AccountController::class, 'gerant'])->name('manager.account.create.gerant');
    Route::get('compte-caissier/{id}', [App\Http\Controllers\Manager\AccountController::class, 'caissier'])->name('manager.account.create.caissier');
    Route::get('transfert/compte-compte/{id}', [App\Http\Controllers\Manager\AccountController::class, 'transfert_compte_compte'])->name('manager.account.transfert_compte_compte');
    Route::post('transfert/compte-compte', [App\Http\Controllers\Manager\TransferController::class, 'Current_Saving'])->name('manager.account.transfert_current_saving');
    Route::get('retrait/compte-epargne/{id}', [App\Http\Controllers\Manager\AccountController::class, 'retrait_compte_epargne'])->name('manager.account.retrait_compte_epargne');
    Route::post('retrait-compte-epargne', [App\Http\Controllers\Manager\WithdrawalController::class, 'retrait_compte_epargne'])->name('manager.withdrawal.epargne.store');


    Route::get('activity-log', [App\Http\Controllers\Manager\LogController::class, 'activity_log'])->name('manager.activity_log');
    Route::get('user-activity-log', [App\Http\Controllers\Manager\LogController::class, 'user_activity_log'])->name('manager.user_activity_log');

    Route::get('liste-gateway', [App\Http\Controllers\Manager\PaymentGatewayController::class, 'all'])->name('manager.gateway.all');

    Route::get('liste-branche', [App\Http\Controllers\Manager\BrancheController::class, 'all'])->name('manager.branche.all');
    Route::get('branche-treller', [App\Http\Controllers\Manager\BrancheController::class, 'allTreller'])->name('manager.branche.all.treller');
    Route::post('branche-treller', [App\Http\Controllers\API\BrancheController::class, 'StoreTreller'])->name('manager.branche.store.treller');
    Route::get('branche-account-cashier', [App\Http\Controllers\Manager\BrancheController::class, 'allAccount'])->name('manager.branche.all.account');
    Route::get('branche-account-manager', [App\Http\Controllers\Manager\BrancheController::class, 'Manager'])->name('manager.branche.account.manager');
    Route::post('topup-account', [App\Http\Controllers\Manager\BrancheController::class, 'topup'])->name('manager.branche.account.topup');

    Route::get('comingsoon', [App\Http\Controllers\Manager\Comingsoon::class, 'comingsoon'])->name('manager.comingsoon');

    Route::get('create-saving', [App\Http\Controllers\Manager\SavingController::class, 'create'])->name('manager.saving.create');
    Route::get('create-current', [App\Http\Controllers\Manager\CurrentController::class, 'create'])->name('manager.current.create');
    Route::post('create-saving', [App\Http\Controllers\Manager\SavingController::class, 'store'])->name('manager.saving.store');
    Route::post('create-current', [App\Http\Controllers\Manager\CurrentController::class, 'store'])->name('manager.current.store');
    Route::get('verification-du-compte', [App\Http\Controllers\Manager\SavingController::class, 'verify'])->name('manager.saving.verify');
    Route::post('verification-du-compte', [App\Http\Controllers\Manager\SavingController::class, 'verify_post'])->name('manager.saving.verify-post');
    
    Route::get('internal-deposit', [App\Http\Controllers\Manager\DepositController::class, 'internal'])->name('manager.internal.deposit');
    Route::get('external-deposit', [App\Http\Controllers\Manager\DepositController::class, 'external'])->name('manager.external.deposit');
    Route::post('internal-deposit', [App\Http\Controllers\Manager\DepositController::class, 'internal_store'])->name('manager.internal.deposit.store');
    Route::post('external-deposit', [App\Http\Controllers\Manager\DepositController::class, 'external_store'])->name('manager.external.deposit.store');

    Route::get('create-transfer-externe', [App\Http\Controllers\Manager\TransferController::class, 'create_emala'])->name('manager.transfer.emala_externe');
    Route::get('create-transfer-interne', [App\Http\Controllers\Manager\TransferController::class, 'create_emala_interne'])->name('manager.transfer.emala_interne');
    Route::get('create-transfer-virement', [App\Http\Controllers\Manager\TransferController::class, 'create_emala_virement'])->name('manager.transfer.emala_virement');
    Route::post('create-transfer-externe', [App\Http\Controllers\Manager\TransferController::class, 'EmalaStore'])->name('manager.transfer.emala.store');
    Route::post('create-transfer-interne', [App\Http\Controllers\Manager\TransferController::class, 'EmalaStoreInterne'])->name('manager.transfer.emala_interne.store');
    Route::post('create-transfer-virement', [App\Http\Controllers\Manager\TransferController::class, 'EmalaStoreVirement'])->name('manager.transfer.emala_virement.store');
    Route::get('create-transfer-emala-mobile-money', [App\Http\Controllers\Manager\TransferController::class, 'create_mobile'])->name('manager.transfer.mobile');

    Route::get('withdrawal-transfert-cash', [App\Http\Controllers\Manager\WithdrawalController::class, 'transfert_cash'])->name('manager.withdrawal.transfert');
    Route::post('withdrawal-transfert-cash', [App\Http\Controllers\Manager\WithdrawalController::class, 'transfert_cash_store'])->name('manager.withdrawal.transfert.store');
    Route::get('withdrawal-compte-cash', [App\Http\Controllers\Manager\WithdrawalController::class, 'compte_cash'])->name('manager.withdrawal.compte');
    Route::post('withdrawal-compte-cash', [App\Http\Controllers\Manager\WithdrawalController::class, 'compte_cash_store'])->name('manager.withdrawal.compte.store');

    Route::get('create-withdrawal-emala-mobile-money', [App\Http\Controllers\Manager\WithdrawalController::class, 'create_mobile'])->name('manager.withdrawal.mobile');
    Route::post('create-withdrawal-emala-mobile-money', [App\Http\Controllers\Manager\MobileMoneyController::class, 'credit'])->name('manager.withdrawal.mobile.credit');


    Route::get('payment-request', [App\Http\Controllers\Manager\PaymentRequestController::class, 'PaymentRequest'])->name('manager.payment.index');
});


Route::group(['prefix'=>'cashier', 'middleware'=>['cashier','auth','PreventBackHistory']], function(){
    // ----------------------------- dashboard -----------------------------//
    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'cashier'])->name('cashier.dashboard');

    Route::get('transaction-all', [App\Http\Controllers\Manager\TransactionController::class, 'all'])->name('cashier.transaction.all');
    Route::post('transaction-all', [App\Http\Controllers\Manager\TransactionController::class, 'recherche'])->name('cashier.transaction.recherche');
    Route::get('transaction-deposit', [App\Http\Controllers\Manager\TransactionController::class, 'deposit'])->name('cashier.transaction.deposit');
    Route::get('transaction-withdrawal', [App\Http\Controllers\Manager\TransactionController::class, 'withdrawal'])->name('cashier.transaction.withdrawal');
    Route::get('transaction-transfer', [App\Http\Controllers\Manager\TransactionController::class, 'transfer'])->name('cashier.transaction.transfer');
    Route::get('transaction-limit', [App\Http\Controllers\Manager\TransactionController::class, 'limit'])->name('cashier.transaction.limit');
    Route::post('transaction-limit', [App\Http\Controllers\Manager\TransactionController::class, 'limitPOST'])->name('cashier.transaction.limit.post');

    Route::get('ouverture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'ouverture'])->name('cashier.ouverture_caisse');
    Route::get('cloture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'cloture'])->name('cashier.cloture_caisse');
    Route::post('cloture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'postcloture'])->name('cashier.postcloture_caisse');
    Route::post('fond_de_caisse_ouverture', [App\Http\Controllers\Manager\CashRegisterController::class, 'fond_ouverture'])->name('cashier.fondcaisse.ouverture');
    Route::post('fond_de_caisse_cloture', [App\Http\Controllers\Manager\CashRegisterController::class, 'fond_cloture'])->name('cashier.fondcaisse.cloture');

    Route::get('autocomplete', [App\Http\Controllers\Manager\CustomerAPI::class, 'autocomplete'])->name('cashier.autocomplete');
    Route::get('search_autocomplete', [App\Http\Controllers\Manager\CustomerAPI::class, 'search_autocomplete'])->name('cashier.search_autocomplete');
    Route::get('donwload-file/{file_name}', [App\Http\Controllers\Ticket\TicketController::class, 'downloadFile'])->name('cashier.download.file');

    Route::get('create-ticket', [App\Http\Controllers\Ticket\TicketController::class, 'create'])->name('cashier.ticket.create');
    Route::post('create-ticket', [App\Http\Controllers\Ticket\TicketController::class, 'store'])->name('cashier.ticket.store');
    Route::get('create-recharge-request', [App\Http\Controllers\Ticket\TicketController::class, 'create_recharge'])->name('cashier.reharge.request.create');
    Route::post('create-recharge-request', [App\Http\Controllers\Ticket\TicketController::class, 'store_recharge'])->name('cashier.reharge.request.store');

    Route::get('liste-client', [App\Http\Controllers\Manager\UtilisateurController::class, 'liste_client'])->name('cashier.liste_client');
    Route::post('creation-client', [App\Http\Controllers\Manager\UtilisateurController::class, 'creation_client'])->name('cashier.creation_client');
    Route::post('modification-client', [App\Http\Controllers\Manager\UtilisateurController::class, 'modification_client'])->name('cashier.modification_client');
    Route::post('supprimer-client', [App\Http\Controllers\Manager\UtilisateurController::class, 'supprimer_client'])->name('cashier.supprimer_client');
    Route::get('liste-caissier', [App\Http\Controllers\Manager\UtilisateurController::class, 'liste_caissier'])->name('cashier.liste_caissier');
    Route::post('creation-caissier', [App\Http\Controllers\Manager\UtilisateurController::class, 'creation_caissier'])->name('cashier.creation_caissier');
    Route::post('supprimer-caissier', [App\Http\Controllers\Manager\UtilisateurController::class, 'supprimer_caissier'])->name('cashier.supprimer_caissier');
    Route::get('liste-gerant', [App\Http\Controllers\Manager\UtilisateurController::class, 'liste_gerant'])->name('cashier.liste_gerant');
    Route::post('creation-gerant', [App\Http\Controllers\Manager\UtilisateurController::class, 'creation_gerant'])->name('cashier.creation_gerant');
    Route::post('modification-gerant', [App\Http\Controllers\Manager\UtilisateurController::class, 'modification_gerant'])->name('cashier.modification_gerant');
    Route::post('supprimer-gerant', [App\Http\Controllers\Manager\UtilisateurController::class, 'supprimer_gerant'])->name('cashier.supprimer_gerant');
    Route::get('liste-marchand', [App\Http\Controllers\Manager\UtilisateurController::class, 'liste_marchand'])->name('cashier.liste_marchand');
    Route::post('creation-marchand', [App\Http\Controllers\Manager\UtilisateurController::class, 'creation_marchand'])->name('cashier.creation_marchand');

    Route::get('compte-client/{id}', [App\Http\Controllers\Manager\AccountController::class, 'client'])->name('cashier.account.create.client');
    Route::get('profil-utilisateur', [App\Http\Controllers\Manager\AccountController::class, 'profilUser'])->name('cashier.account.profil.user');
    Route::get('compte-client-phone/{phone}', [App\Http\Controllers\Manager\AccountController::class, 'customerByPhone'])->name('cashier.account.create.clientByPhone');
    Route::get('depot/cash/{id}', [App\Http\Controllers\Manager\AccountController::class, 'client_depot'])->name('cashier.account.create.client.depot.create');
    Route::get('retrait/cash/{id}', [App\Http\Controllers\Manager\AccountController::class, 'client_retrait'])->name('cashier.account.create.client.retrait.create');
    Route::get('transfert/interne/{id}', [App\Http\Controllers\Manager\AccountController::class, 'client_transfert'])->name('cashier.account.create.client.transfert.create');
    Route::get('transfert/compte-compte/{id}', [App\Http\Controllers\Manager\AccountController::class, 'transfert_compte_compte'])->name('cashier.account.transfert_compte_compte');
    Route::post('transfert/compte-compte', [App\Http\Controllers\Manager\TransferController::class, 'Current_Saving'])->name('cashier.account.transfert_current_saving');
    Route::get('retrait/compte-epargne/{id}', [App\Http\Controllers\Manager\AccountController::class, 'retrait_compte_epargne'])->name('cashier.account.retrait_compte_epargne');
    Route::get('mobile-money/interne/{id}', [App\Http\Controllers\Manager\AccountController::class, 'client_mobile'])->name('cashier.account.create.client.mobile.create');
    Route::get('compte-gerant/{id}', [App\Http\Controllers\Manager\AccountController::class, 'gerant'])->name('cashier.account.create.gerant');
    Route::get('compte-caissier/{id}', [App\Http\Controllers\Manager\AccountController::class, 'caissier'])->name('cashier.account.create.caissier');
    Route::post('retrait-compte-epargne', [App\Http\Controllers\Manager\WithdrawalController::class, 'retrait_compte_epargne'])->name('cashier.withdrawal.epargne.store');

    Route::get('activity-log', [App\Http\Controllers\Manager\LogController::class, 'activity_log'])->name('cashier.activity_log');
    Route::get('user-activity-log', [App\Http\Controllers\Manager\LogController::class, 'user_activity_log'])->name('cashier.user_activity_log');

    Route::get('liste-gateway', [App\Http\Controllers\Manager\PaymentGatewayController::class, 'all'])->name('cashier.gateway.all');

    Route::get('liste-branche', [App\Http\Controllers\Manager\BrancheController::class, 'all'])->name('cashier.branche.all');
    Route::get('branche-treller', [App\Http\Controllers\Manager\BrancheController::class, 'allTreller'])->name('cashier.branche.all.treller');
    Route::post('branche-treller', [App\Http\Controllers\API\BrancheController::class, 'StoreTreller'])->name('cashier.branche.store.treller');
    Route::get('branche-account-all', [App\Http\Controllers\Manager\BrancheController::class, 'allAccount'])->name('cashier.branche.all.account');
    Route::get('branche-account-cashier', [App\Http\Controllers\Manager\BrancheController::class, 'Manager'])->name('cashier.branche.account.cashier');
    Route::post('topup-account', [App\Http\Controllers\Manager\BrancheController::class, 'topup'])->name('cashier.branche.account.topup');

    Route::get('comingsoon', [App\Http\Controllers\Manager\Comingsoon::class, 'comingsoon'])->name('cashier.comingsoon');

    Route::get('create-saving', [App\Http\Controllers\Manager\SavingController::class, 'create'])->name('cashier.saving.create');
    Route::get('create-current', [App\Http\Controllers\Manager\CurrentController::class, 'create'])->name('cashier.current.create');
    Route::post('create-saving', [App\Http\Controllers\Manager\SavingController::class, 'store'])->name('cashier.saving.store');
    Route::post('create-current', [App\Http\Controllers\Manager\CurrentController::class, 'store'])->name('cashier.current.store');
    Route::get('verification-du-compte', [App\Http\Controllers\Manager\SavingController::class, 'verify'])->name('cashier.saving.verify');
    Route::post('verification-du-compte', [App\Http\Controllers\Manager\SavingController::class, 'verify_post'])->name('cashier.saving.verify-post');
    
    Route::get('internal-deposit', [App\Http\Controllers\Manager\DepositController::class, 'internal'])->name('cashier.internal.deposit');
    Route::get('external-deposit', [App\Http\Controllers\Manager\DepositController::class, 'external'])->name('cashier.external.deposit');
    Route::post('internal-deposit', [App\Http\Controllers\Manager\DepositController::class, 'internal_store'])->name('cashier.internal.deposit.store');
    Route::post('external-deposit', [App\Http\Controllers\Manager\DepositController::class, 'external_store'])->name('cashier.external.deposit.store');

    Route::get('create-transfer-externe', [App\Http\Controllers\Manager\TransferController::class, 'create_emala'])->name('cashier.transfer.emala_externe');
    Route::get('create-transfer-interne', [App\Http\Controllers\Manager\TransferController::class, 'create_emala_interne'])->name('cashier.transfer.emala_interne');
    Route::get('create-transfer-virement', [App\Http\Controllers\Manager\TransferController::class, 'create_emala_virement'])->name('cashier.transfer.emala_virement');
    Route::post('create-transfer-externe', [App\Http\Controllers\Manager\TransferController::class, 'EmalaStore'])->name('cashier.transfer.emala.store');
    Route::post('create-transfer-interne', [App\Http\Controllers\Manager\TransferController::class, 'EmalaStoreInterne'])->name('cashier.transfer.emala_interne.store');
    Route::post('create-transfer-virement', [App\Http\Controllers\Manager\TransferController::class, 'EmalaStoreVirement'])->name('cashier.transfer.emala_virement.store');
    Route::get('create-transfer-emala-mobile-money', [App\Http\Controllers\Manager\TransferController::class, 'create_mobile'])->name('cashier.transfer.mobile');

    Route::get('withdrawal-transfert-cash', [App\Http\Controllers\Manager\WithdrawalController::class, 'transfert_cash'])->name('cashier.withdrawal.transfert');
    Route::post('withdrawal-transfert-cash', [App\Http\Controllers\Manager\WithdrawalController::class, 'transfert_cash_store'])->name('cashier.withdrawal.transfert.store');
    Route::get('withdrawal-compte-cash', [App\Http\Controllers\Manager\WithdrawalController::class, 'compte_cash'])->name('cashier.withdrawal.compte');
    Route::post('withdrawal-compte-cash', [App\Http\Controllers\Manager\WithdrawalController::class, 'compte_cash_store'])->name('cashier.withdrawal.compte.store');

    Route::get('create-withdrawal-emala-mobile-money', [App\Http\Controllers\Manager\WithdrawalController::class, 'create_mobile'])->name('cashier.withdrawal.mobile');
    Route::post('create-withdrawal-emala-mobile-money', [App\Http\Controllers\Manager\MobileMoneyController::class, 'credit'])->name('cashier.withdrawal.mobile.credit');
    Route::get('payment-request', [App\Http\Controllers\Manager\PaymentRequestController::class, 'PaymentRequest'])->name('cashier.payment.index');
});
