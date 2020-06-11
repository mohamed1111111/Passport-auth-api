<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request){
      $validatedData =  $request->validate([
                        'name'=>'required|max:55',
                        'email'=>'required|email|unique:users',
                        'password'=>'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|min:8'

        ]);
        $validatedData['password']=bcrypt($request->password);
        $user = User::create($validatedData);
        $accessToken = $user->createToken('authToken')->accessToken;
        return response()->json(['user'=>$user,'access_token'=>$accessToken]);


    }


        public function login(Request $request){
          $loginData =  $request->validate([
                            'email'=>'required|email',
                            'password'=>'required'

            ]);

            if(!auth()->attempt($loginData)){
              return response()->json(['message'=>'Invalid']);

            }
            $accessToken = auth()->user()->createToken('authToken')->accessToken;
            return response()->json(['user'=>auth()->user(),'access_token'=>$accessToken]);


        }

}
