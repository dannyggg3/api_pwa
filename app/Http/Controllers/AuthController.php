<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Support\Facades\Validator;
use App\Mail\RecuperarClave;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','loginP','recuperar']]);
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

        $cliente = Cliente::with('detallesCarrito')->where('usuario_id', $user->id)->first();
        $user->cliente=$cliente;

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


    public function recuperar(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        //si no es email retorna error de email
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            return response()->json([
                'correctProcess' => false,
                'message' => 'El correo ingresado no es valido',
            ], 200);
        }


        $credentials = $request->only('email');

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'correctProcess' => false,
                'message' => 'El correo ingresado no existe',
            ], 200);
        }

        //si existe desencripta la clave
        $clave=$user->clave;

        $data = new \stdClass();
        $data->email = $user->email;
        $data->clave = $clave;

        //envia el correo con la clave
         try {
        Mail::to($user->email)->send(new RecuperarClave($data));
      } catch(Throwable $e) {
        return response()->json(['message' => $e->getMessage()], 401);
      }

        return response()->json([
                'correctProcess' => true,
                'message' => 'Se ha enviado un correo con la clave',
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
            'rol_id' => 2,
            'clave' => $request->password,
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
            $user->clave = $request->password;
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
