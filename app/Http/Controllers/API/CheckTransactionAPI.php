<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ExternalTransfer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CheckTransactionAPI extends Controller
{
    public function checkTransaction($reference){
        $transaction = ExternalTransfer::where('reference',$reference)->where('status','En attente')->first();
        $count = ExternalTransfer::where('reference',$reference)->where('status','En attente')->count();
        if ($count >= 1) {
            $response = [
                'success' => true,
                'resultat' => 1,
                'message' => "Transaction Found",
                'status' => "Successful",
                'sender_number' => $transaction->sender_phone,
                'senderFirstname' => $transaction->sender_first,
                'senderLastname' => $transaction->sender_last,
                'receiver_number' => $transaction->receiver_phone,
                'receiverFirstname' => $transaction->receiver_first,
                'receiverLastname' => $transaction->receiver_last,
                'reference' => $transaction->reference,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'fees' => $transaction->fees,
                'remise' => $transaction->remise,
                'money_received' => $transaction->money_received,
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'resultat' => 0,
                'message' => "Transaction not found or Bad reference!",
                'status' => "Failed",
            ];
            return $response;
        }
    }
}
