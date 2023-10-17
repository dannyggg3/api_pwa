<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','loginP']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'correctProcess' => false,
                'message' => 'Credenciales incorrectas',
            ], 200);
        }

        $user = Auth::user();
        return response()->json([
                'correctProcess' => true,
                'data' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

     public function loginP(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);



        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'correctProcess' => false,
                'message' => 'Credenciales incorrectas',
            ], 200);
        }

        $user = Auth::user();

        if($user->rol_id != 2){
            return response()->json([
                'correctProcess' => false,
                'message' => 'No tiene permisos para acceder a esta ruta',
            ], 200);
        }

        return response()->json([
                'correctProcess' => true,
                'data' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function register(Request $request){

        try {
             $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => 2
        ]);

        $token = Auth::login($user);
        return response()->json([
            'correctProcess'=> true,
            'message' => 'Usuario creado exitosamente',
            'data' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
        } catch (\Throwable $th) {
            //return error
            return response()->json([
                'correctProcess'=> false,
                'message' => 'Error al crear el usuario',
                'data' => $th->getMessage(),
            ]);
        }
    }




    //actualizar clave de un usuario por el id
    public function updatePassword(Request $request, $id){
        try {

            $validator = Validator::make($request->all(), [

                'password' => 'required|string|min:6',
            ]);

            $user = User::find($id);
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'correctProcess' => true,
                'message' => 'Contraseña actualizada correctamente',
                'data' => $user,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'correctProcess' => false,
                'message' => 'Error al actualizar la contraseña:' . $th->getMessage(),
            ]);
        }
    }




    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }


    //obtener todos los usuarios con rol_id 2
    public function clientes()
    {
        try {
              //retorna los usuarios con rol_id 2
            $users = User::where('rol_id', 2)->get();
            return response()->json([
                'correctProcess' => true,
                'data' => $users,
        ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'correctProcess' => false,
                'message' => 'Error al obtener los clientes:' . $th->getMessage(),
            ]);
        }
    }
}
