<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {


        $request->validate(
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|string|email|max:255|unique:users',
                'mobile_number' => 'required',
                'address' => 'required|string',

            ],

        );

        $user = User::create([
            'name' => $request->first_name . ' ' .  $request->last_name,
            'email' => $request->email,
            'phone' => $request->mobile_number,
            'address' => $request->address
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer',]);
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required',

            ],

        );
        $user = User::where('email',$request->email)->first();
        
       
        
        if($user) {
            $name = explode(" ", $user->name);
            ;
            count($name) > 1 ? $lastName = $name[1]:$lastName="";
            $firstName = $name[0];
            $arr['address'] =$user->address;
            $arr['email'] =  $user->email;
            $arr['phone'] =  $user->phone;
            $arr['first_name'] =  $firstName;
            $arr['last_name'] =  $lastName;
            $arr['dob'] =  $user->dob;

       }
        if ($user) {
            if(Hash::check($request->password,$user->password)){
                return response()
            ->json(['message' => 'Hi ' . $user->name . ', welcome to home','user_id'=> $user->id,'user_data'=>$arr]);
            }
            else {
                return response()
                ->json(['message' => 'Wrong Password'], 401);
            }
        }
        else {
            return response()
            ->json(['message' => 'Wrong UserID/Email'], 401);
        }
    }

      // method for user logout and delete token
      public function logout()
      {
          auth()->user()->tokens()->delete();
  
          return [
              'message' => 'You have successfully logged out and the token was successfully deleted'
          ];
      }
  
}
