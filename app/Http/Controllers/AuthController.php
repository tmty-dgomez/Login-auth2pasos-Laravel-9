<?php

namespace App\Http\Controllers;

use App\Constants\Errors\V1\ErrorCodes;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Session;
use Illuminate\Support\Carbon;
Use Illuminate\Support\Facades\URL;
use App\Mail\VerifyEmailAddres;
use App\Mail\VerifyEmailAddress;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param Request $request HTTP request containing user registration data.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'phone' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'g-recaptcha-response' => 'required|captcha'
        ], [
            'name.required' => ErrorCodes::E0R01 . ' The name field is required.',
            'name.min' => ErrorCodes::E0R02 . ' The name must be at least 3 characters.',
            'email.required' => ErrorCodes::E0R03 . ' The email field is required.',
            'email.email' => ErrorCodes::E0R04 . ' The email must be a valid email address.',
            'email.unique' => ErrorCodes::E0R05 . ' The email has already been taken.',
            'password.required' => ErrorCodes::E0R06 . ' The password field is required.',
            'password.min' => ErrorCodes::E0R07 . ' The password must be at least 8 characters.',
            'password.regex' => ErrorCodes::E0R08 . ' The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'g-recaptcha-response.required' => ErrorCodes::E2001 . ' Please verify that you are not a robot.',
            'g-recaptcha-response.captcha' => ErrorCodes::E2002 . ' Captcha error! Try again later or contact site admin.',
        ]);
    
        if ($validator->fails()) {
            Log::error(ErrorCodes::E1000 . ' Validation failed during registration', ['errors' => $validator->errors()]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        $validatedData = $validator->validated();
        $validatedData['name'] = htmlspecialchars(trim($validatedData['name']), ENT_QUOTES, 'UTF-8');
        $validatedData['email'] = filter_var(trim($validatedData['email']), FILTER_SANITIZE_EMAIL);
    
        $user = User::create([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
    
        $url = URL::temporarySignedRoute(
            'verifyEmail', 
            now()->addMinutes(10), 
            ['userId' => $user->id]
        );
        
        Mail::to($user->email)->send(new VerifyEmailAddress($url,$user));
    
        Log::info(ErrorCodes::S2002 . ' User registered successfully!');
        return redirect()->route('login')->with('success', ErrorCodes::S2001 . ' User registered successfully!, Please check your email');
    }


    /**
     * Log in the user.
     *
     * @param Request $request HTTP request containing user credentials.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8', 
            'g-recaptcha-response' => 'required|captcha',
        ],[
            'g-recaptcha-response.required' => ErrorCodes::E2001 . ' Please verify that you are not a robot.',
            'g-recaptcha-response.captcha' => ErrorCodes::E2002 . ' Captcha error! Try again later or contact site admin.',
        ]);

        $email = filter_var(trim($loginData['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($loginData['password']); 

        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return redirect()->route('login')->with([
                'error_code' => ErrorCodes::E1001,
                'message' => 'The provided credentials are incorrect.',
            ]);
        }

        if (!$user->email_verified) {
            return redirect()->route('login')->with([
                'error_code' => ErrorCodes::E2003,
                'message' => 'Your email address has not been verified. Please check your email for the verification link.',
            ]);
        }

        $verificationCode = mt_rand(10000, 99999);
        $user->verification_code = $verificationCode;
        $user->save();
        $signedRoute = URL::temporarySignedRoute(
            'verifyCode', 
            now()->addMinutes(10), 
            ['userId' => $user->id] 
        );
        Mail::to($user->email)->send(new VerifyEmail($verificationCode,$signedRoute,$user));
        Log::info(ErrorCodes::S2002 . ' Please check your email for the verification code.');
        return redirect()->route('verifyCode')->with('success', ErrorCodes::S2002 . ' Please check your email for the verification code.');
    }


    /**
     * Log out the user.
     *
     * @param Request $request HTTP request containing logout action.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Log::info(ErrorCodes::S2003 . ' User logged out successfully.');
        return redirect()->route('login')->with('success', ErrorCodes::S2003 . ' You have been logged out successfully.');
    }
   
    /**
     * Verify the user's email using a verification code.
     *
     * @param Request $request HTTP request containing verification code.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyLoginCode(Request $request, int $id)
    {
        $verifyData = $request->validate([
            'verify' => 'required|string|min:5|max:5',
            'g-recaptcha-response' => 'required|captcha'
        ], [
            'verify.required' => 'The verification code field is required.',
            'verify.min' => 'The verification code must be 5 characters.',
            'verify.max' => 'The verification code must be 5 characters.',
            'g-recaptcha-response.required' => ErrorCodes::E2001 . ' Please verify that you are not a robot.',
            'g-recaptcha-response.captcha' => ErrorCodes::E2002 . ' Captcha error! Try again later or contact site admin.',
        ]);
    
        $confirmationCode = trim($verifyData['verify']);
        $user = User::find($id);
    
        if (!$user || $user->verification_code !== $confirmationCode) {
            return redirect()->route('verifyCode')->withErrors([
                'message' => 'Invalid verification code.',
            ]);
        }
    
        $user->update(['verification_code' => null]);
        Auth::guard('web')->login($user);
        Log::info(ErrorCodes::S2002 . ' Verification successful! User logged in.');
        return redirect()->route('dashboard')->with('success', ErrorCodes::S2002 . ' Verification successful! You are now logged in.');
    }
    
}
