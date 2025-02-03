<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class EmailController extends Controller
{
    public function verifyEmail(Request $request, $userId)
    {
        $user = User::find($userId);
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'El usuario no existe.');
        }
    
        if ($user->email_verified) {
            return redirect()->route('login')->with('info', 'El correo ya ha sido verificado.');
        }
    
        // Marcar el email como verificado
        $user->email_verified = true;
        $user->save();
    
        // Pasar la URL firmada a la vista del login
        return redirect()->route('login')->with('signed_url', $request->fullUrl());
    }
    
}
