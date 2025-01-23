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
    public function register(Request $request){
        $registerUserData = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'lastname01' => 'required|string|max:255|min:3',
            'lastname02' => 'nullable|string|max:255|min:3',
            'phone' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5',
        ]);
        $user = User::create([
            'name' => $registerUserData['name'],
            'lastname01' => $registerUserData['lastname01'],
            'lastname02' => $registerUserData['lastname02'],
            'phone' => $registerUserData['phone'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user));

        return response()->json(['msg' => "Registro correcto", 'data' => $user]);

        return response()->json([
            'message' => 'User Created ',
            'data' => $user
        ],201);
    }

    public function login(Request $request){
        $loginUserData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);
        $user = User::where('email',$loginUserData['email'])->first();
        if(!$user || !Hash::check($loginUserData['password'],$user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        return response()->json([
            'message' => 'Login Successful',
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