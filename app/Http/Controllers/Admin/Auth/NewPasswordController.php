<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use App\Models\OldPassword;
use App\Models\Admin;
use Brian2694\Toastr\Facades\Toastr;


class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('admin.auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
       
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $check_token =  \DB::table('password_resets')->where([
            'email'=>$request->email,
            'token'=>$request->token
        ])->first();

        
            
        if(!($check_token)) {
            return back()->withInput()->with('error','Invalid Token');
        } else {
            Admin::where('email',$request->email)->update([
                'password' =>Hash::make($request->password)
            ]);
        }

        \DB::table('password_resets')->where([
            'email'=>$request->email
        ])->delete();
        
        return redirect()->route('admin.login')->with('success','Your password has been changed! You can login with new password.');
        
        
    }
}
