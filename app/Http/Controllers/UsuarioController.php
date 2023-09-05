<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{

     public function login(Request $request)
    {



        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;

            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Credenciales no válidas'], 401);
        }
    }


       public function index()
    {
        try {
            // Obtener todos los usuarios con su rol
            $usuarios = Usuario::with('rol')->get();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $usuarios,
                'message' => 'Usuarios obtenidos correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:100',
                'correo_electronico' => 'required|email|max:100|unique:usuarios',
                'contrasena' => 'required|string|max:100',
                'rol_id' => 'nullable|integer|exists:roles,id',
                'estado' => 'nullable|string|max:20',
                'imagen' => 'nullable|string|max:255',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear un nuevo usuario
            $usuario = Usuario::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $usuario,
                'message' => 'Usuario creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // Obtener un usuario por ID con su rol
            $usuario = Usuario::with('rol')->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $usuario,
                'message' => 'Usuario obtenido correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:100',
                'correo_electronico' => 'required|email|max:100|unique:usuarios,correo_electronico,' . $id,
                'contrasena' => 'required|string|max:100',
                'rol_id' => 'nullable|integer|exists:roles,id',
                'estado' => 'nullable|string|max:20',
                'imagen' => 'nullable|string|max:255',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar un usuario por ID
            $usuario = Usuario::findOrFail($id);
            $usuario->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $usuario,
                'message' => 'Usuario actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Eliminar un usuario por ID
            $usuario = Usuario::findOrFail($id);
            $usuario->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Usuario eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
