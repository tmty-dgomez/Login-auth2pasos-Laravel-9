<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Validator;
use App\Constants\ErrorCodes;

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
            'password' => 'required|string|min:5',
        ], [
            'name.required' => ErrorCodes::E0R01 . ' The name field is required.',
            'name.min' => ErrorCodes::E0R02 . ' The name must be at least 3 characters.',
            'email.required' => ErrorCodes::E0R03 . ' The email field is required.',
            'email.email' => ErrorCodes::E0R04 . ' The email must be a valid email address.',
            'email.unique' => ErrorCodes::E0R05 . ' The email has already been taken.',
            'password.required' => ErrorCodes::E0R06 . ' The password field is required.',
            'password.min' => ErrorCodes::E0R07 . ' The password must be at least 5 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validatedData = $validator->validated();
        $validatedData['name'] = htmlspecialchars(trim($validatedData['name']), ENT_QUOTES, 'UTF-8');
        $validatedData['email'] = filter_var(trim($validatedData['email']), FILTER_SANITIZE_EMAIL);

        User::create([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->route('login')->with('success', 'User registered successfully!');
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
    
        $verificationCode = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 5));
        $user->verification_code = $verificationCode;
        $user->save();
        
        Mail::to($user->email)->send(new VerifyEmail($user, $verificationCode));
        return redirect()->route('verifyCode')->with('success', 'Please check your email for the verification code.');
    }
    
    

    /**
     * Log out the user.
     *
     * @param Request $request HTTP request containing logout action.
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Verify the user's email using a verification code.
     *
     * @param Request $request HTTP request containing verification code.
     * @return \Illuminate\Http\RedirectResponse
     */
   
     public function verifyLoginCode(Request $request)
     {
         $verifyData = $request->validate([
             'verify' => 'required|string|min:5|max:5',
         ]);
     
         $confirmationCode = trim($verifyData['verify']);
         
         $user = User::where('verification_code', $confirmationCode)->first();
     
         if (!$user) {
             return redirect()->route('verifyCode')->with([
                 'error_code' => ErrorCodes::E404,
                 'message' => 'Invalid verification code.',
             ]);
         }
     
         $user->update([
             'verification_code' => null, 
         ]);
     
         $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
     
         return redirect()->route('dashboard')->with([
             'success' => 'Verification successful! You are now logged in.',
             'access_token' => $token,
         ]);
     }
}    
