<?php

use App\Http\Controllers\Backend\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Backend\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Backend\Cashier\LoanController;
use App\Http\Controllers\Backend\Cashier\PaymentController;
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


Route::view('/home', 'home')->name('home');
// -----------------------------settings----------------------------------------//
Route::get('company/settings/page', [App\Http\Controllers\SettingController::class, 'companySettings'])->middleware('auth')->name('company/settings/page');
Route::get('roles/permissions/page', [App\Http\Controllers\SettingController::class, 'rolesPermissions'])->middleware('auth')->name('roles/permissions/page');
Route::post('roles/permissions/save', [App\Http\Controllers\SettingController::class, 'addRecord'])->middleware('auth')->name('roles/permissions/save');
Route::post('roles/permissions/update', [App\Http\Controllers\SettingController::class, 'editRolesPermissions'])->middleware('auth')->name('roles/permissions/update');
Route::post('roles/permissions/delete', [App\Http\Controllers\SettingController::class, 'deleteRolesPermissions'])->middleware('auth')->name('roles/permissions/delete');

// -----------------------------login----------------------------------------//
Route::get('session_validate', [App\Http\Controllers\Auth\LoginController::class, 'session'])->name('session.validate');
Route::post('start_new_session', [App\Http\Controllers\Auth\LoginController::class, 'start_new_session'])->name('start.new.session');
Route::get('session_stay_in', [App\Http\Controllers\Auth\LoginController::class, 'session_stay_in'])->name('session.stay.in');

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

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

    Route::get('ouverture-caisse', [App\Http\Controllers\Admin\CashRegisterController::class, 'ouverture'])->name('admin.ouverture_caisse');
    Route::get('cloture-caisse', [App\Http\Controllers\Admin\CashRegisterController::class, 'cloture'])->name('admin.cloture_caisse');
    Route::post('cloture-caisse', [App\Http\Controllers\Admin\CashRegisterController::class, 'postcloture'])->name('admin.postcloture_caisse');
    Route::post('fond_de_caisse_ouverture', [App\Http\Controllers\Admin\CashRegisterController::class, 'fond_ouverture'])->name('admin.fondcaisse.ouverture');
    Route::post('fond_de_caisse_cloture', [App\Http\Controllers\Admin\CashRegisterController::class, 'fond_cloture'])->name('admin.fondcaisse.cloture');

    Route::get('profil-utilisateur', [App\Http\Controllers\Admin\AccountController::class, 'profilUser'])->name('admin.account.profil.user');

    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'admin'])->name('admin.dashboard');
    Route::get('transaction', [App\Http\Controllers\Backend\Admin\TransactionController::class, 'all'])->name('admin.transaction.all');
    Route::get('transaction-deposit', [App\Http\Controllers\Backend\Admin\TransactionController::class, 'deposit'])->name('admin.transaction.deposit');
    Route::get('transaction-transfer', [App\Http\Controllers\Backend\Admin\TransactionController::class, 'transfer'])->name('admin.transaction.transfer');
    Route::get('transaction-withdrawal', [App\Http\Controllers\Backend\Admin\TransactionController::class, 'withdrawal'])->name('admin.transaction.withdrawal');
    Route::post('transaction', [App\Http\Controllers\Backend\Admin\TransactionController::class, 'search'])->name('admin.transaction.search');

    Route::get('customer', [App\Http\Controllers\Backend\Admin\CustomerController::class, 'index'])->name('admin.customer.all');
    Route::post('customer', [App\Http\Controllers\Backend\Admin\CustomerController::class, 'add'])->name('admin.customer.add');
    Route::post('customer-edit', [App\Http\Controllers\Backend\Admin\CustomerController::class, 'edit'])->name('admin.customer.edit');
    Route::post('customer-delete', [App\Http\Controllers\Backend\Admin\CustomerController::class, 'delete'])->name('admin.customer.delete');

    Route::get('user-account/{id}', [App\Http\Controllers\Backend\Admin\UserController::class, 'user_account'])->name('admin.user.account');
    Route::get('user', [App\Http\Controllers\Backend\Admin\UserController::class, 'index'])->name('admin.user.all');
    Route::post('user', [App\Http\Controllers\Backend\Admin\UserController::class, 'add'])->name('admin.user.add');
    Route::post('user-edit', [App\Http\Controllers\Backend\Admin\UserController::class, 'edit'])->name('admin.user.edit');
    Route::post('user-delete', [App\Http\Controllers\Backend\Admin\UserController::class, 'delete'])->name('admin.user.delete');

    Route::get('branch', [App\Http\Controllers\Backend\Admin\BranchController::class, 'index'])->name('admin.branch.all');
    Route::post('branch', [App\Http\Controllers\Backend\Admin\BranchController::class, 'add'])->name('admin.branch.add');
    Route::post('branch-edit', [App\Http\Controllers\Backend\Admin\BranchController::class, 'edit'])->name('admin.branch.edit');
    Route::post('branch-delete', [App\Http\Controllers\Backend\Admin\BranchController::class, 'delete'])->name('admin.branch.delete');
    Route::post('branch-topup', [App\Http\Controllers\Backend\Admin\BranchController::class, 'topup'])->name('admin.branch.topup');

    Route::get('wallet-agence', [App\Http\Controllers\Backend\Admin\WalletController::class, 'agence'])->name('admin.wallet.agence');
    Route::post('wallet-agence', [App\Http\Controllers\Backend\Admin\WalletController::class, 'walletAgenceAdd'])->name('admin.wallet-agence.add');
    Route::post('wallet-agence-edit', [App\Http\Controllers\Backend\Admin\WalletController::class, 'walletAgenceEdit'])->name('admin.wallet-agence.edit');
    Route::post('wallet-agence-delete', [App\Http\Controllers\Backend\Admin\WalletController::class, 'walletAgenceDelete'])->name('admin.wallet-agence.delete');
    Route::post('wallet-agence-topup', [App\Http\Controllers\Backend\Admin\WalletController::class, 'creditAgenceFiliale'])->name('admin.wallet-agence.topup');
    Route::post('wallet-agence-deduct', [App\Http\Controllers\Backend\Admin\WalletController::class, 'deduct_agency_balance'])->name('admin.wallet-agence.deduct');


    Route::get('wallet-emala', [App\Http\Controllers\Backend\Admin\WalletController::class, 'emala'])->name('admin.wallet.emala');
    Route::post('wallet-emala', [App\Http\Controllers\Backend\Admin\WalletController::class, 'walletEmalaEdit'])->name('admin.wallet-emala.edit');
    Route::post('wallet-emala-topup', [App\Http\Controllers\Backend\Admin\WalletController::class, 'creditAgencePrincipale'])->name('admin.wallet-emala.topup');
    Route::post('wallet-emala-deduct', [App\Http\Controllers\Backend\Admin\WalletController::class, 'deduct_emala_balance'])->name('admin.wallet-emala.deduct');
    Route::post('wallet-emala-delete', [App\Http\Controllers\Backend\Admin\WalletController::class, 'walletEmalaDelete'])->name('admin.wallet-emala.delete');

    Route::get('demande-de-recharge', [App\Http\Controllers\Backend\Admin\RechargeRequestController::class, 'index'])->name('admin.recharge.request');
    Route::post('demande-de-recharge', [App\Http\Controllers\Backend\Admin\RechargeRequestController::class, 'respondRequest'])->name('admin.recharge.process');

    Route::get('compte-client/{id}', [App\Http\Controllers\Backend\Admin\AccountController::class, 'client'])->name('admin.customer.account');

    Route::get('compte-client-phone/{id}', [App\Http\Controllers\Backend\Admin\AccountController::class, 'clientPhone'])->name('admin.customer.account.phone');

    Route::get('depot/cash/{id}', [App\Http\Controllers\Backend\Admin\AccountController::class, 'client_depot'])->name('admin.customer.depot');
    Route::post('depot/cash', [App\Http\Controllers\Backend\Admin\AccountController::class, 'client_depot_save'])->name('admin.customer.depot.save');

    Route::get('retrait/cash/{id}', [App\Http\Controllers\Backend\Admin\AccountController::class, 'client_retrait'])->name('admin.customer.retrait');
    Route::post('retrait/cash', [App\Http\Controllers\Backend\Admin\AccountController::class, 'client_retrait_save'])->name('admin.customer.retrait.save');

    Route::get('transfert/interne/{id}', [App\Http\Controllers\Backend\Admin\AccountController::class, 'client_transfert'])->name('admin.customer.transfert');
    Route::post('transfert/interne', [App\Http\Controllers\Backend\Admin\AccountController::class, 'client_transfert_save'])->name('admin.customer.transfert.save');


    // Route::get('pret-bancaire/interne/{id}', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'pret'])->name('cashier.customer.pret');
    Route::post('pret-bancaire/interne', [App\Http\Controllers\Backend\Admin\AccountController::class, 'demandePost'])->name('admin.pret.demande.post');
    Route::get('historique-des-prets', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'index'])->name('admin.pret.index');
    Route::get('demande-de-pret', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'create'])->name('admin.pret.create');
    Route::get('loanRequest/{id}', [App\Http\Controllers\Backend\Admin\LoanController::class, 'createId'])->name('admin.loans.createId');
    Route::post('valider-demande-pret/{id}', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'validerDemandePret'])->name('admin.valider-demande-pret');
    Route::post('annuler-demande-pret/{id}', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'annulerDemandePret'])->name('admin.annuler-demande-pret');
    Route::get('prets/{id}', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'show'])->name('prets.show');
    Route::get('pret-amortissement/{id}', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'amortissement'])->name('admin.amortissement');
    Route::get('pret-amortissement', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'amortissementIndex'])->name('admin.pret.amortissement');
    Route::post('paiement-amortissement', [App\Http\Controllers\Backend\Admin\AmortizationController::class, 'generateAmortizationSchedule'])->name('admin.am.paiement');

    Route::get('pret-bancaire/interne/{id}', [App\Http\Controllers\Backend\Admin\AccountController::class, 'pret'])->name('admin.customer.pret');

    Route::get('/loans', [AdminLoanController::class, 'index'])->name('admin.loans.index');
    Route::get('/loans/create', [AdminLoanController::class, 'create'])->name('admin.loans.create');
    Route::post('/loans', [AdminLoanController::class, 'store'])->name('admin.loans.store');
    Route::get('/loans/{id}', [AdminLoanController::class, 'show'])->name('admin.loans.show');
    Route::get('/loans/history/{id}', [AdminLoanController::class, 'history'])->name('admin.loans.history');
    Route::get('/loans/{id}/edit', [AdminLoanController::class, 'edit'])->name('admin.loans.edit');
    Route::put('/loans/{id}', [AdminLoanController::class, 'update'])->name('admin.loans.update');
    Route::delete('/loans/{id}', [AdminLoanController::class, 'destroy'])->name('admin.loans.destroy');

    Route::get('/loans/{loanId}/payments/create', [AdminPaymentController::class, 'create'])->name('admin.payments.create');
    Route::post('/loans/{loanId}/payments', [AdminPaymentController::class, 'store'])->name('admin.payments.store');
    Route::post('/loans/{id}/make-payment', [AdminPaymentController::class, 'makePayment'])->name('admin.loans.make-payment');
    Route::get('/loans/{loanId}/payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
    // Route::get('pret-demande', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'demande'])->name('admin.pret.demande');
    // Route::post('pret-demande', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'demandePost'])->name('admin.pret.demande.post');
    // Route::post('Approuvé-demande', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'Approuvé'])->name('admin.demande.success');
    // Route::post('desApprouvé-demande', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'desApprouvé'])->name('admin.demande.failed');

    // Route::get('pret-amortissement', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'amortissement'])->name('admin.pret.amortissement');

    Route::get('pret-type', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'type'])->name('admin.pret.type');
    Route::post('pret-type', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'typePost'])->name('admin.pret.type.post');
    Route::post('pret-type-edit', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'typeEdit'])->name('admin.pret.type.edit');
    Route::post('pret-type-delete', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'typeDelete'])->name('admin.pret.type.delete');

    Route::get('pret-plan', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'plan'])->name('admin.pret.plan');
    Route::post('pret-plan', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'planPost'])->name('admin.pret.plan.post');
    Route::post('pret-plan-edit', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'planEdit'])->name('admin.pret.plan.edit');
    Route::post('pret-plan-delete', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'planDelete'])->name('admin.pret.plan.delete');

    Route::get('remboursement-pret/{id}', [App\Http\Controllers\Backend\Admin\AccountController::class, 'remboursement'])->name('admin.customer.pret');
    Route::post('remboursement-pret', [App\Http\Controllers\Backend\Admin\PretBancaireController::class, 'remboursementPost'])->name('admin.customer.remboursement.post');

    Route::get('/rechercher-client', [App\Http\Controllers\Backend\Admin\AccountController::class, 'rechercherClient'])->name('admin.client.rechercher');
    Route::get('/client/details', [App\Http\Controllers\Backend\Admin\AccountController::class, 'getDetails'])->name('admin.client.details');
});


Route::group(['prefix'=>'manager', 'middleware'=>['manager','auth','PreventBackHistory']], function(){

    Route::get('ouverture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'ouverture'])->name('manager.ouverture_caisse');
    Route::get('cloture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'cloture'])->name('manager.cloture_caisse');
    Route::post('cloture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'postcloture'])->name('manager.postcloture_caisse');
    Route::post('fond_de_caisse_ouverture', [App\Http\Controllers\Manager\CashRegisterController::class, 'fond_ouverture'])->name('manager.fondcaisse.ouverture');
    Route::post('fond_de_caisse_cloture', [App\Http\Controllers\Manager\CashRegisterController::class, 'fond_cloture'])->name('manager.fondcaisse.cloture');

    Route::get('profil-utilisateur', [App\Http\Controllers\Manager\AccountController::class, 'profilUser'])->name('manager.account.profil.user');


    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'manager'])->name('manager.dashboard');
    Route::get('transaction', [App\Http\Controllers\Backend\Manager\TransactionController::class, 'all'])->name('manager.transaction.all');
    Route::get('transaction-deposit', [App\Http\Controllers\Backend\Manager\TransactionController::class, 'deposit'])->name('manager.transaction.deposit');
    Route::get('transaction-transfer', [App\Http\Controllers\Backend\Manager\TransactionController::class, 'transfer'])->name('manager.transaction.transfer');
    Route::get('transaction-withdrawal', [App\Http\Controllers\Backend\Manager\TransactionController::class, 'withdrawal'])->name('manager.transaction.withdrawal');
    Route::post('transaction', [App\Http\Controllers\Backend\Manager\TransactionController::class, 'search'])->name('manager.transaction.search');
    
    Route::get('customer', [App\Http\Controllers\Backend\Manager\CustomerController::class, 'index'])->name('manager.customer.all');
    Route::post('customer', [App\Http\Controllers\Backend\Manager\CustomerController::class, 'add'])->name('manager.customer.add');
    Route::post('customer-edit', [App\Http\Controllers\Backend\Manager\CustomerController::class, 'edit'])->name('manager.customer.edit');
    Route::post('customer-delete', [App\Http\Controllers\Backend\Manager\CustomerController::class, 'delete'])->name('manager.customer.delete');
    
    Route::get('user', [App\Http\Controllers\Backend\Manager\UserController::class, 'index'])->name('manager.user.all');
    Route::post('user', [App\Http\Controllers\Backend\Manager\UserController::class, 'add'])->name('manager.user.add');
    Route::post('user-edit', [App\Http\Controllers\Backend\Manager\UserController::class, 'edit'])->name('manager.user.edit');
    Route::post('user-delete', [App\Http\Controllers\Backend\Manager\UserController::class, 'delete'])->name('manager.user.delete');
    
    Route::get('wallet-agence', [App\Http\Controllers\Backend\Manager\WalletController::class, 'agence'])->name('manager.wallet.agence');
    Route::get('wallet-recharge-historique', [App\Http\Controllers\Backend\Manager\WalletController::class, 'historique'])->name('manager.wallet.recharge.historique');
    Route::post('wallet-recharge', [App\Http\Controllers\Backend\Manager\WalletController::class, 'recharge'])->name('manager.wallet.recharge');

    Route::get('compte-client/{id}', [App\Http\Controllers\Backend\Manager\AccountController::class, 'client'])->name('manager.customer.account');
    Route::get('compte-client-phone/{id}', [App\Http\Controllers\Backend\Manager\AccountController::class, 'clientPhone'])->name('manager.customer.account.phone');

    Route::get('depot/cash/{id}', [App\Http\Controllers\Backend\Manager\AccountController::class, 'client_depot'])->name('manager.customer.depot');
    Route::post('depot/cash', [App\Http\Controllers\Backend\Manager\AccountController::class, 'client_depot_save'])->name('manager.customer.depot.save');

    Route::get('retrait/cash/{id}', [App\Http\Controllers\Backend\Manager\AccountController::class, 'client_retrait'])->name('manager.customer.retrait');
    Route::post('retrait/cash', [App\Http\Controllers\Backend\Manager\AccountController::class, 'client_retrait_save'])->name('manager.customer.retrait.save');

    Route::get('transfert/interne/{id}', [App\Http\Controllers\Backend\Manager\AccountController::class, 'client_transfert'])->name('manager.customer.transfert');
    Route::post('transfert/interne', [App\Http\Controllers\Backend\Manager\AccountController::class, 'client_transfert_save'])->name('manager.customer.transfert.save');

    Route::get('pret-bancaire/interne/{id}', [App\Http\Controllers\Backend\Manager\AccountController::class, 'pret'])->name('manager.customer.pret');
    Route::post('pret-bancaire/interne', [App\Http\Controllers\Backend\Manager\AccountController::class, 'demandePost'])->name('manager.pret.demande.post');
    Route::get('pret-demande', [App\Http\Controllers\Backend\Manager\PretBancaireController::class, 'demande'])->name('manager.pret.demande');
    // Route::post('pret-demande', [App\Http\Controllers\Backend\Manager\PretBancaireController::class, 'demandePost'])->name('manager.pret.demande.post');
    Route::get('pret-amortissement', [App\Http\Controllers\Backend\Manager\PretBancaireController::class, 'amortissement'])->name('manager.pret.amortissement');

    Route::get('remboursement-pret/{id}', [App\Http\Controllers\Backend\Manager\AccountController::class, 'remboursement'])->name('manager.customer.pret');
    Route::post('remboursement-pret', [App\Http\Controllers\Backend\Manager\PretBancaireController::class, 'remboursementPost'])->name('manager.customer.remboursement.post');


});

Route::group(['prefix'=>'cashier', 'middleware'=>['cashier','auth','PreventBackHistory']], function(){

    Route::get('ouverture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'ouverture'])->name('cashier.ouverture_caisse');
    Route::get('cloture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'cloture'])->name('cashier.cloture_caisse');
    Route::post('cloture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'postcloture'])->name('cashier.postcloture_caisse');
    Route::post('ouverture-caisse', [App\Http\Controllers\Manager\CashRegisterController::class, 'fond_ouverture'])->name('cashier.fondcaisse.ouverture');
    Route::post('fond_de_caisse_cloture', [App\Http\Controllers\Manager\CashRegisterController::class, 'fond_cloture'])->name('cashier.fondcaisse.cloture');

    Route::get('profil-utilisateur', [App\Http\Controllers\Cashier\AccountController::class, 'profilUser'])->name('cashier.account.profil.user');


    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'cashier'])->name('cashier.dashboard');
    Route::get('transaction', [App\Http\Controllers\Backend\Cashier\TransactionController::class, 'all'])->name('cashier.transaction.all');
    Route::get('transaction-deposit', [App\Http\Controllers\Backend\Cashier\TransactionController::class, 'deposit'])->name('cashier.transaction.deposit');
    Route::get('transaction-transfer', [App\Http\Controllers\Backend\Cashier\TransactionController::class, 'transfer'])->name('cashier.transaction.transfer');
    Route::get('transaction-withdrawal', [App\Http\Controllers\Backend\Cashier\TransactionController::class, 'withdrawal'])->name('cashier.transaction.withdrawal');
    Route::post('transaction', [App\Http\Controllers\Backend\Cashier\TransactionController::class, 'search'])->name('cashier.transaction.search');
    
    Route::get('customer', [App\Http\Controllers\Backend\Cashier\CustomerController::class, 'index'])->name('cashier.customer.all');
    Route::post('customer', [App\Http\Controllers\Backend\Cashier\CustomerController::class, 'add'])->name('cashier.customer.add');
    Route::get('customer/create', [App\Http\Controllers\Backend\Cashier\CustomerController::class, 'create'])->name('cashier.customer.create');
    Route::post('customer-edit', [App\Http\Controllers\Backend\Cashier\CustomerController::class, 'edit'])->name('cashier.customer.edit');
    Route::post('customer-delete', [App\Http\Controllers\Backend\Cashier\CustomerController::class, 'delete'])->name('cashier.customer.delete');
    
    Route::get('user', [App\Http\Controllers\Backend\Cashier\UserController::class, 'index'])->name('cashier.user.all');
    Route::post('user', [App\Http\Controllers\Backend\Cashier\UserController::class, 'add'])->name('cashier.user.add');
    Route::post('user-edit', [App\Http\Controllers\Backend\Cashier\UserController::class, 'edit'])->name('cashier.user.edit');
    Route::post('user-delete', [App\Http\Controllers\Backend\Cashier\UserController::class, 'delete'])->name('cashier.user.delete');
    
    Route::get('wallet-agence', [App\Http\Controllers\Backend\Cashier\WalletController::class, 'agence'])->name('cashier.wallet.agence');
    Route::get('wallet-recharge-historique', [App\Http\Controllers\Backend\Cashier\WalletController::class, 'historique'])->name('cashier.wallet.recharge.historique');
    Route::post('wallet-recharge', [App\Http\Controllers\Backend\Cashier\WalletController::class, 'recharge'])->name('cashier.wallet.recharge');

    Route::get('compte-client/{id}', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'client'])->name('cashier.customer.account');
    Route::get('compte-client-phone/{id}', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'clientPhone'])->name('cashier.customer.account.phone');

    Route::get('depot/cash/{id}', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'client_depot'])->name('cashier.customer.depot');
    Route::post('depot/cash', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'client_depot_save'])->name('cashier.customer.depot.save');

    Route::get('deposit', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'deposit'])->name('cashier.customer.deposit');
    Route::post('deposit', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'processDeposit'])->name('cashier.customer.processDeposit');

    Route::get('withdraw', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'withdraw'])->name('cashier.customer.withdraw');
    Route::post('withdraw', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'processWithdraw'])->name('cashier.customer.processWithdraw');

    Route::get('transfer', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'transfer'])->name('cashier.customer.transfer');
    Route::post('transfer', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'processTransfer'])->name('cashier.customer.processTransfer');

    Route::get('/rechercher-client', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'rechercherClient'])->name('client.rechercher');
    Route::get('/client/details', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'getDetails'])->name('client.details');

    Route::get('retrait/cash/{id}', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'client_retrait'])->name('cashier.customer.retrait');
    Route::post('retrait/cash', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'client_retrait_save'])->name('cashier.customer.retrait.save');

    Route::get('transfert/interne/{id}', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'client_transfert'])->name('cashier.customer.transfert');
    Route::post('transfert/interne', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'client_transfert_save'])->name('cashier.customer.transfert.save');

    Route::get('pret-bancaire/interne/{id}', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'pret'])->name('cashier.customer.pret');
    Route::post('pret-bancaire/interne', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'demandePost'])->name('cashier.pret.demande.post');
    Route::get('historique-des-prets', [App\Http\Controllers\Backend\Cashier\PretBancaireController::class, 'index'])->name('cashier.pret.index');
    Route::get('demande-de-pret', [App\Http\Controllers\Backend\Cashier\PretBancaireController::class, 'create'])->name('cashier.pret.create');
    Route::get('loanRequest/{id}', [App\Http\Controllers\Backend\Cashier\LoanController::class, 'createId'])->name('cashier.loans.createId');
    Route::post('valider-demande-pret/{id}', [App\Http\Controllers\Backend\Cashier\PretBancaireController::class, 'validerDemandePret'])->name('cashier.valider-demande-pret');
    Route::post('annuler-demande-pret/{id}', [App\Http\Controllers\Backend\Cashier\PretBancaireController::class, 'annulerDemandePret'])->name('cashier.annuler-demande-pret');
    Route::get('prets/{id}', [App\Http\Controllers\Backend\Cashier\PretBancaireController::class, 'show'])->name('prets.show');
    Route::get('pret-amortissement/{id}', [App\Http\Controllers\Backend\Cashier\PretBancaireController::class, 'amortissement'])->name('cashier.amortissement');
    Route::get('pret-amortissement', [App\Http\Controllers\Backend\Cashier\PretBancaireController::class, 'amortissementIndex'])->name('cashier.pret.amortissement');
    Route::post('paiement-amortissement', [App\Http\Controllers\Backend\Cashier\AmortizationController::class, 'generateAmortizationSchedule'])->name('cashier.am.paiement');


    Route::get('/loans', [LoanController::class, 'index'])->name('cashier.loans.index');
    Route::get('/loans/create', [LoanController::class, 'create'])->name('cashier.loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('cashier.loans.store');
    Route::get('/loans/{id}', [LoanController::class, 'show'])->name('cashier.loans.show');
    Route::get('/loans/history/{id}', [LoanController::class, 'history'])->name('cashier.loans.history');
    Route::get('/loans/{id}/edit', [LoanController::class, 'edit'])->name('cashier.loans.edit');
    Route::put('/loans/{id}', [LoanController::class, 'update'])->name('cashier.loans.update');
    Route::delete('/loans/{id}', [LoanController::class, 'destroy'])->name('cashier.loans.destroy');

    Route::get('/loans/{loanId}/payments/create', [PaymentController::class, 'create'])->name('cashier.payments.create');
    Route::post('/loans/{loanId}/payments', [PaymentController::class, 'store'])->name('cashier.payments.store');
    Route::post('/loans/{id}/make-payment', [PaymentController::class, 'makePayment'])->name('cashier.loans.make-payment');
    Route::get('/loans/{loanId}/payments', [PaymentController::class, 'index'])->name('cashier.payments.index');

    Route::get('remboursement-pret/{id}', [App\Http\Controllers\Backend\Cashier\AccountController::class, 'remboursement'])->name('cashier.customer.pret');
    Route::post('remboursement-pret', [App\Http\Controllers\Backend\Cashier\PretBancaireController::class, 'remboursementPost'])->name('cashier.customer.remboursement.post');


});
