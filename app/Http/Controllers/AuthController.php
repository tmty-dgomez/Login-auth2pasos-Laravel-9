<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use \App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
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
            'name.required' => 'The name field is required.',
            'name.min' => 'The name must be at least 3 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email is already registered.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 5 characters.',
        ]);

        if ($validator->fails()) {
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

        Mail::to($user->email)->send(new VerifyEmail($user));

        return redirect()->route('login')->with(
            'success', 'User registered successfully!'
        );
    }

    public function login(Request $request){
        $loginData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8',
        ]);

        $email = filter_var(trim($loginData['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($loginData['password']);

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return redirect()->route('login')->with('error', 'Invalid login details.');
        }

        $token = $user->createToken(htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') . '-AuthToken')->plainTextToken;

        return redirect()->route('login')->with([
            'success' => 'Welcome, ' . e($user->name) . '!',
            'access_token' => $token,
        ]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => htmlspecialchars("Logged out successfully", ENT_QUOTES, 'UTF-8'),
        ]);
    }

}
