<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use \App\Mail\VerifyEmail;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registerUserData = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'phone' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5',
        ]);

        $user = User::create([
            'name' => $registerUserData['name'],
            'phone' => $registerUserData['phone'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user));

        return redirect()->route('login')->with('success', 'User added successfully!');
    }


    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8',
        ]);

        $user = User::where('email', $loginUserData['email'])->first();

        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return redirect()->route('login')->with('error', 'Invalid Credentials');
        }

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        return redirect()->route('login')->with([
            'success' => 'Welcome, ' . $user->name . '!',
            'access_token' => $token,
        ]);
    }


    public function logout(){
        auth()->user()->currentAccessToken()->delete();
        return response()->json([
          "message"=>"logged out"
        ]);
    }
}