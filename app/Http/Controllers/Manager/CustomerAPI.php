<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerAPI extends Controller
{
    public function request(Request $request){
        $phone_number = $request->receiver_phone;
        $user = DB::connection('mysql2')->table('users')->select('firstname','name')->where('phone',$phone_number)->first();
       // Store it in a array
       $firstname = $user->firstname;
       $lastname = $user->name;
       $result = array("$firstname", "$lastname");
        
        // Send in JSON encoded form
        $myJSON = json_encode($result);
        echo $myJSON;
    }
    public function autocomplete(Request $request)
    {
        return DB::connection('mysql2')->table('users')->select('firstname','name','phone')
                    ->where('phone', 'LIKE', "%{$request->term}%")
                    ->pluck('phone');
    }
    public function search_autocomplete(Request $request)
    {
        $query = $request->get('term','');
        $users= DB::connection('mysql2')->table('users');
        if($request->type=='receiver_phone'){
            $users->where('phone','LIKE','%'.$query.'%');
        }
        if($request->type=='receiver_first'){
            $users->where('firstname','LIKE','%'.$query.'%');
        }
        if($request->type=='receiver_last'){
            $users->where('name','LIKE','%'.$query.'%');
        }
           $users=$users->get();        
        $data=array();
        foreach ($users as $user) {
                $data[]=array('phone'=>$user->phone,'firstname'=>$user->firstname,'lastname'=>$user->name);
        }
        if(count($data))
             return $data;
        else
            return ['phone'=>'','firstname'=>'','lastname'=>''];
    }
}
