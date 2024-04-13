<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Use;
use App\Models\CustomerCredential;
use Illuminate\Support\Facades\Password;

class UserDetailController extends Controller
{
    public function index(Request $request)
    {
        $responseData = array();
        $user = User::where('id', $request->user_id)->latest()->get();
        $user_arr = array();
        $featureContent = array();
        if ($user) {
            foreach ($user  as $key => $val) {
                $user_arr[$key]['id'] =  $val->id;
                $user_arr[$key]['name'] =  $val->name;
                $user_arr[$key]['email'] =  $val->email;
                $user_arr[$key]['mobile_number'] =  $val->phone;
                $user_arr[$key]['address'] =  $val->address;
                $user_arr[$key]['profile_img'] = asset('images/' . $val->profile_img);
            }
            array_push($responseData, array('response' => array('data' =>  $user_arr)));
            return response()->json($responseData, 200);
        } else {
            array_push($responseData, array('response' => array('data' =>  $user_arr)));
            return response()->json($responseData, 200);
        }
    }

    public function update(Request $request)
    {
        $responseData = array();
        $objUser =  User::where('id', $request->user_id)->first();
        $objUser->name = $request->name;
        $objUser->email =  $request->email;
        $objUser->phone =  $request->mobile_number;
        $objUser->address =  $request->address;
        if ($request->file('profile_img') == null) {
            $input['profile_img'] = $objUser->profile_img;
        } else {
            $destinationPath = '/images';
            $imgfile = $request->file('profile_img');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $objUser->profile_img = $image;
        }
        if ($objUser->save()) {
            array_push($responseData, array('response' => array('status' => 'success', 'msg' => 'Profile Updated Successfully.')));
            return response()->json($responseData, 200);
        } else {
            array_push($responseData, array('response' => array('status' => 'error')));
            return response()->json($responseData, 200);
        }
    }

    public function updateCredentials(Request $request)
    {
        $responseData = array();
        $data = $request->input();
        
        $data['user_id'] = auth()->user()->id;
        $data['service_details'] = json_encode($request->service_details);
        
        $customerCred = CustomerCredential::updateOrCreate(['user_id' => $data['user_id'], 'category'=> $data['category']], $data);
        if ($objUser->save()) {
            array_push($responseData, array('response' => array('status' => 'success', 'msg' => 'Customer Credential Updated Successfully.')));
            return response()->json($responseData, 200);
        } else {
            array_push($responseData, array('response' => array('status' => 'error')));
            return response()->json($responseData, 200);
        }
    }
    public function getCredential(){
        
    }

    public function changepassword(Request $request)
    {
        $responseData = array();
        //$objUser =  User::where('id', $request->user_id)->first();
        $newpassword = $request->new_password;
        $confirmpassword = $request->confirm_password;
        if ($newpassword == $confirmpassword) {
            User::where('id', $request->user_id)->update(['password' => Hash::make($newpassword)]);
            array_push($responseData, array('response' => array('status' => 'success', 'msg' => 'Password Changed Successfully.')));
            return response()->json($responseData, 200);
        } else {
            array_push($responseData, array('response' => array('status' => 'New password and confirm password are not same')));
            return response()->json($responseData, 401);
        }
    }

    public function resetpassword(Request $request)
    {
        $responseData = array();

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            array_push($responseData, array('response' => array('status' => 'success', 'msg' => 'We have emailed your password reset link!')));
            return response()->json($responseData, 200);
        } else {
            array_push($responseData, array('response' => array('status' => 'error')));
            return response()->json($responseData, 200);
        }
    }

    public function emailUpdate(Request $request)
    {
        $responseData = array();
        $objUser =  User::where('id', $request->user_id)->first();
        $objUser->email =  $request->email;

        if ($objUser->save()) {
            array_push($responseData, array('response' => array('status' => 'success', 'msg' => 'Email Updated Successfully.')));
            return response()->json($responseData, 200);
        } else {
            array_push($responseData, array('response' => array('status' => 'error')));
            return response()->json($responseData, 200);
        }
    }
}
