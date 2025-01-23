<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
   public function GetUsers()
   {
        $users = User::all();
        if($users->isEmpty()){
            return response()->json([
            'message' => 'Usuarios no encontrados'
            ], 404);
        }

        return response()->json([
            'message' => 'Usuarios encontrados',
            'data' => $users
        ], 200);
   }

   public function AddUser(Request $request)
   {
       $validator = Validator::make($request->all(), [
           'name' => 'required|string|max:255|min:3',
           'lastname01' => 'required|string|max:255|min:3',
           'lastname02' => 'nullable|string|max:255|min:3',
           'phone' => 'required|numeric|digits_between:10,15',
           'email' => 'required|email|unique:users,email',
           'password' => 'required|string|min:5',
       ], [
           'name.required' => 'El nombre es obligatorio.',
           'name.min' => 'El nombre debe tener al menos 3 caracteres.',
           'lastname01.required' => 'El primer apellido es obligatorio.',
           'email.required' => 'El correo electrónico es obligatorio.',
           'email.email' => 'El correo electrónico debe tener un formato válido.',
           'email.unique' => 'El correo electrónico ya está registrado.',
           'password.required' => 'La contraseña es obligatoria.',
           'password.min' => 'La contraseña debe tener al menos 5 caracteres.',
       ]);
   
       if ($validator->fails()) {
           return response()->json([
               'message' => 'Error en la validación',
               'errors' => $validator->errors()
           ], 422);
       }
   
       try {
           $validarData = $validator->validated();
           $validarData['password'] = bcrypt($validarData['password']);
           $users = User::create($validarData);
            
            if($users){
                return response()->json([
                    'message' => 'Usuario creado correctamente',
                    'data' => $users
                ], 201);
            }
            
            else{
                return response()->json([
                    'message' => 'Error al crear el usuario'
                ], 400);
            }
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al procesar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
   }

   public function GetUser($id)
   {
        $user = User::find($id);
        if($user){
            return response()->json([
                'message' => 'Usuario encontrado',
                'data' => $user
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }
   }

   public function UpdateUser(Request $request, $id)
   {
        $validarData = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'lastname01' => 'required|string|max:255|min:3',
            'lastname02' => 'nullable|string|max:255|min:3',
            'phone' => 'nullable|string|max:10'|'min:10',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'required|string|min:5',
        
        ],
        [
            'name.required' => 'El nombre es obligatorio.',
            'lastname01.required' => 'El primer apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);
        
        try{

            $validarData['password'] = bcrypt($validarData['password']);
            $user = User::find($id);
            if($user){
                $user->update($validarData);
                return response()->json([
                    'message' => 'Usuario actualizado correctamente',
                    'data' => $user
                ], 200);
            }
            else{
                return response()->json([
                    'message' => 'Usuario no encontrado'
                ], 404);
            }
        }
        catch(\Exception){
            return response()->json([
                'message' => 'Error al actualizar el usuario'
            ], 400);
        }
   }
}