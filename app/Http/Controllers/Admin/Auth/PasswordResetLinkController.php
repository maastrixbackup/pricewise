<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Brian2694\Toastr\Facades\Toastr;
use Mail;
use Carbon\Carbon;
use App\Mail\AdminForgotPwMail;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email'
        ]);

        $token =\Str::random(64);
        \DB::table('password_resets')->insert([
            'email'=>$request->email,
            'token'=>$token,
            'created_at'=>Carbon::now()
        ]);
        $action_link = route('admin.password.reset',['token'=>$token,'email'=>$request->email]);
        $body = "We are received a request to reset the password for Solfehx account associated with ".$request->email.
        ".You can reset your password by clicking the link below " ;

       

        $body = [
            'body'=>$body,
            'action_link'=>$action_link,
        ];
 
        Mail::to($request->email)->send(new AdminForgotPwMail($body));

        return back()->with('success','We have emailed your password reset link');
       
       
    }
}
