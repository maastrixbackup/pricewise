<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\URL;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('images/customers'), $photoName);
            $input['photo'] = $photoName;
        }
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        if (!$user) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $request->name;
        $body['name'] = $request->name;
        $email_template = EmailTemplate::where('id', 10)->first();
        $body['body'] = $email_template->mail_body;
        $body['signature'] = $email_template->signature;
        // Generate verification link
        $verificationLink = route('verification.verify', [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]);

        $body['action_link'] = $verificationLink;
        try {
            Mail::to($user->email)->send(new WelcomeEmail($body));
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return $this->sendResponse($success, 'Customer registered successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        //\Log::info('Attempting login with:', $request->input());
        $email = trim($request->email);

        if (Auth::attempt(['email' => $email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('_token')->plainTextToken;
            $success['user'] =  $user;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return response()->json(['error' => 'Incorrect Email or Password'], 401);
        }
    }

    // public function login(Request $request)
    // {dd('hi');
    //     $input = $request->all();
    //     if (!$token = JWTAuth::attempt($input)) {
    //         return response()->json(['result' => 'wrong email or password.']);
    //     }
    //         return response()->json(['result' => $token]);
    // }


    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return response()->json(['message' => 'User logged out successfully']);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            // Generate the URL for the reset password link
            //$resetPasswordUrl = URL::signedRoute('reset-password', ['email' => $request->email]);

            // Send the email with the reset password link
            // (You need to implement the email sending logic here)

            return response()->json(['message' => 'Password reset link sent to your email', 'reset_password_url' => '']);
        } else {
            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully']);
        } else {
            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
        }
    }
}
