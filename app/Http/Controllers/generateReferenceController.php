<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class generateReferenceController extends Controller
{
    /* Module de génération de référence des transactions */
    public function reference( $descritption) {
        $characters = '0123456789';
        $length = 8;
        $charactersLength = strlen($characters);
        $randomString = '';
        if ($descritption == "transfert") {
            $randomString = 'INT';
        }
        elseif ($descritption == "transfert-externe") {
            $randomString = 'EXT';
        }
        elseif ($descritption == "virement") {
            $randomString = 'VI';
        }
        elseif ($descritption == "depot") {
            $randomString = 'ED';
        }
        elseif ($descritption == "retrait") {
            $randomString = 'ER';
        }
        elseif ($descritption == "emprunt") {
            $randomString = 'EE';
        }
        elseif ($descritption == "transaction") {
            $randomString = 'ETX';
        }
        elseif ($descritption == "recharge-compte") {
            $randomString = 'RC';
        }
        elseif ($descritption == "remise") {
            $randomString = 'REM';
        }

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    /* Module de génération code de retraits */
    public function coderetrait($length = 8, $descritption = null) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /* Module de génération numero de compte */
    public function accountnumber($length = 10, $descritption = null) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
