<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use \App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Validator;
use App\Constants\ErrorCodes; 
class AuthController extends Controller
{

    /**
     * registro de un usuario.
     *
     * @param Request $request La solicitud HTTP entrante que contiene los datos del registro del usuario.
     * @return RedirectResponse Redirige de vuelta con los errores o a la página de inicio de sesión con un mensaje de éxito.
     */

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

    /**
     *  inicio de sesión del usuario.
     *
     * @param Request $request La solicitud HTTP entrante que contiene las credenciales de inicio de sesión.
     * @return RedirectResponse Redirige a la página de inicio de sesión con un mensaje de éxito o error.
     */

    public function login(Request $request){
        $loginData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8',
        ]);

        $email = filter_var(trim($loginData['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($loginData['password']);

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            
            return redirect()->route('login')->with([
                'error_code' => ErrorCodes::E1001,  
                'message' => 'The provided credentials are incorrect.' 
            ]);
        }

        $token = $user->createToken(htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') . '-AuthToken')->plainTextToken;

        return redirect()->route('login')->with([
            'success' => 'Welcome, ' . e($user->name) . '!',
            'access_token' => $token,
        ]);
    }

    /**
     * cierre de sesión del usuario.
     *
     * @param Request $request La solicitud HTTP entrante que contiene la acción de cierre de sesión del usuario.
     * @return JSON response Respuesta JSON que confirma el éxito del cierre de sesión.
     */

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => htmlspecialchars("Logged out successfully", ENT_QUOTES, 'UTF-8'),
        ]);
    }

}
