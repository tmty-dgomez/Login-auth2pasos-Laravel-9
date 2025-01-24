<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'No users found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Users retrieved successfully.',
            'data' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8'),
                    'email' => htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'),
                    'phone' => htmlspecialchars($user->phone, ENT_QUOTES, 'UTF-8'),
                ];
            }),
        ], 200);
    }

    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'phone' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:5',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ], [
            'name.required' => 'The name field is required.',
            'name.regex' => 'The name can only contain letters and spaces.',
            'phone.required' => 'The phone field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email is already registered.',
            'password.required' => 'The password field is required.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one digit.',
        ]);

        $validatedData = $validator->validated();
        $validatedData['name'] = htmlspecialchars($validatedData['name'], ENT_QUOTES, 'UTF-8');
        $validatedData['email'] = filter_var($validatedData['email'], FILTER_SANITIZE_EMAIL);
        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);

        return response()->json([
            'message' => 'User created successfully.',
            'data' => [
                'id' => $user->id,
                'name' => htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8'),
                'email' => htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'),
                'phone' => htmlspecialchars($user->phone, ENT_QUOTES, 'UTF-8'),
            ],
        ], 201);
    }

    public function getUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        return response()->json([
            'message' => 'User retrieved successfully.',
            'data' => [
                'id' => $user->id,
                'name' => htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8'),
                'email' => htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'),
                'phone' => htmlspecialchars($user->phone, ENT_QUOTES, 'UTF-8'),
            ],
        ], 200);
    }

    public function updateUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'phone' => 'nullable|numeric|digits_between:10,15',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => [
                'required',
                'string',
                'min:5',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ], [
            'name.required' => 'The name field is required.',
            'name.regex' => 'The name can only contain letters and spaces.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email is already registered.',
            'password.required' => 'The password field is required.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one digit.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        $validatedData['name'] = htmlspecialchars($validatedData['name'], ENT_QUOTES, 'UTF-8');
        $validatedData['email'] = filter_var($validatedData['email'], FILTER_SANITIZE_EMAIL);
        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        $user->update($validatedData);

        return response()->json([
            'message' => 'User updated successfully.',
            'data' => [
                'id' => $user->id,
                'name' => htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8'),
                'email' => htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'),
                'phone' => htmlspecialchars($user->phone, ENT_QUOTES, 'UTF-8'),
            ],
        ], 200);
    }
}
