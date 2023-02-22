<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class loginController extends Controller
{
    //

    public function login(){
       

        $credentials = request(['username', 'password']);


        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Wrong username or password']);
        }

        
        $user = auth()->user();
        return response()->json(['details' => array('token' => $token, 'user' =>array('level_id'=>$user['level_id'],'ngo_id'=>$user['ngo_id'],'user_id'=>$user['id']) )]);
    }
}
