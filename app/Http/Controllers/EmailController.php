<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class EmailController extends Controller
{
    public function welcomeNova($userId) 
    {
        $user = User::find($userId);

        if (!$user) {
            return Redirect::route('home')->with('error', 'User not found.');
        }

        $user->email_verified = true;
        $user->save();

        return Redirect::route('login')->with('success', 'Your email address has been confirmed and validated. You can now log in.');
    }
}
