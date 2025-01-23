<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function bienvenida($userId)
    {
        $user = User::find($userId);

        return view('bienvenida', ['user' => $user]);
    }
}

