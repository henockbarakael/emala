<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifyNumberController extends Controller
{
    public function verify_number($number = ''){
        $inputPhone = $number;
        $len_number = strlen($number);

            if ($len_number == 9) {
                return '243'.$inputPhone;
            }

            if ($len_number == 10) {
                if (substr($inputPhone, 0, 1) == '0') {
                    return $inputPhone = '243'.substr($inputPhone, 1, $len_number);
                } elseif (substr($inputPhone, 0, 1) != '0') {
                    return false;
                }
            }

            if ($len_number < 9 || $len_number > 12) {
                return false;
            }

            if ($len_number == 12) {
                if (substr($inputPhone, 0, 3) != '243') {
                    return false;
                }
                return $inputPhone;
            }
    }
    public function operator($number = ''){
        $customer_number = $number;
        $len_number = strlen($number);

        if ($len_number == 9) {
            if (substr($customer_number, 0, 2) == '81' || substr($customer_number, 0, 2) == '82' || substr($customer_number, 1, 2) == '83') {
                return 'mpesa';
            }

            if (substr($customer_number, 0, 2) == '99' || substr($customer_number, 0, 2) == '97') {
                return 'airtel';
            }

            if (substr($customer_number, 0, 2) == '85' || substr($customer_number, 0, 2) == '84' || substr($customer_number, 0, 2) == '89' || substr($customer_number, 0, 2) == '80') {
                return 'orange';
            }
        }

        if ($len_number == 10) {
            if (substr($customer_number, 0, 1) == '0') {
                if (substr($customer_number, 1, 2) == '81' || substr($customer_number, 1, 2) == '82' || substr($customer_number, 1, 2) == '83') {
                    return 'mpesa';
                }
            }

            if (substr($customer_number, 0, 1) == '0') {
                if (substr($customer_number, 1, 2) == '99' || substr($customer_number, 1, 2) == '97') {
                    return 'airtel';
                }
            }

            if (substr($customer_number, 0, 1) == '0') {
                if (substr($customer_number, 1, 2) == '85' || substr($customer_number, 1, 2) == '84' || substr($customer_number, 1, 2) == '89' || substr($customer_number, 1, 2) == '80') {
                    return 'orange';
                }
            }

        }

        if ($len_number == 12) {
            if (substr($customer_number, 0, 3) == '243') {
                if (substr($customer_number, 3, 2) == '81' || substr($customer_number, 3, 2) == '82' || substr($customer_number, 3, 2) == '83') {
                    return 'mpesa';
                }

                if (substr($customer_number, 3, 2) == '99' || substr($customer_number, 3, 2) == '97') {
                    return 'airtel';
                }

                if (substr($customer_number, 3, 2) == '85' || substr($customer_number, 3, 2) == '84' || substr($customer_number, 3, 2) == '89' || substr($customer_number, 3, 2) == '80') {
                    return 'orange';
                }
            }

        }

        if ($len_number < 9 || $len_number > 12) {
            return false;
        }

    }
}
