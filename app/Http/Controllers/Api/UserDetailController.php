<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserCredential;
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
                $user_arr[$key]['gender'] =  $val->gender;
                $user_arr[$key]['mobile'] =  $val->mobile;
                $user_arr[$key]['address'] =  $val->address;
                $user_arr[$key]['photo'] = asset('images/customers/' . $val->photo);
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
        $objUser->age =  $request->age;
        $objUser->gender =  $request->gender;
        $objUser->mobile =  $request->mobile;
        $objUser->address =  $request->address;
        $objUser->acct_no =  $request->acct_no;
        $objUser->landline_no =  $request->landline_no;
        if ($request->file('photo') == null) {
            $input['photo'] = $objUser->photo;
        } else {
            $destinationPath = '/images/customers/';
            $imgfile = $request->file('photo');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $objUser->photo = $image;
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

        $data['user_id'] = $request->user_id;
        $data['service_details'] = json_encode($request->service_details);

        $customerCred = UserCredential::updateOrCreate(['user_id' => $data['user_id'], 'category' => $data['category']], $data);
        if ($customerCred) {
            array_push($responseData, array('response' => array('status' => 'success', 'msg' => 'Customer Credential Updated Successfully.')));
            return response()->json($responseData, 200);
        } else {
            array_push($responseData, array('response' => array('status' => 'error')));
            return response()->json($responseData, 200);
        }
    }
    public function getCredentials(Request $request)
    {
        // Validate the request data
        // $request->validate([
        //     'user_id' => 'required',
        //     'category' => 'required'
        // ]);

        // Retrieve the user credentials
        $responseData = UserCredential::where('user_id', $request->user_id)
            ->where('category', $request->category)
            ->first();

        // Check if data is found
        if ($responseData) {
            return response()->json([
                'success' => true,
                'data' => $responseData,
                'message' => 'User credentials retrieved successfully.'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No user credentials found for the given parameters.'
            ], 400);
        }
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
            return response()->json($responseData, 400);
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
