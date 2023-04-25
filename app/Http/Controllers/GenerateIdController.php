<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateIdController extends Controller
{
    public function AccountNumber($length = 8){
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function SavingAcnumber($length = 9){
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function requestID($length = 5){
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = 'R';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function bank_acount($length = 5){
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = 'B';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function defaultPIN($length = 5) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function code_agence($length = 6) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = 'A';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function wallet_id($length = 6) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = 'W';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function tirroir_id($length = 6) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = 'T';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
