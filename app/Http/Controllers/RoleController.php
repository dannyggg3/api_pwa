<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
     public function index()
    {
        try {
            // Obtener todos los roles
            $roles = Role::all();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $roles,
                'message' => 'Roles obtenidos correctamente'
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
                'nombre' => 'required|string|max:50',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear un nuevo rol
            $rol = Role::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $rol,
                'message' => 'Rol creado correctamente'
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
            // Obtener un rol por ID
            $rol = Role::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $rol,
                'message' => 'Rol obtenido correctamente'
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
                'nombre' => 'required|string|max:50',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar un rol por ID
            $rol = Role::findOrFail($id);
            $rol->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $rol,
                'message' => 'Rol actualizado correctamente'
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
            // Eliminar un rol por ID
            $rol = Role::findOrFail($id);
            $rol->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Rol eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
