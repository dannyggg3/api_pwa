<?php

namespace App\Http\Controllers;

use App\Models\Publicidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class PublicidadController extends Controller
{
     public function index()
    {
        try {
            // Obtener todas las publicidades
            $publicidades = Publicidad::all();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $publicidades,
                'message' => 'Publicidades obtenidas correctamente'
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
                'titulo' => 'nullable|string|max:100',
                'imagen' => 'nullable|string|max:255',
                'enlace' => 'nullable|string|max:255',
                'estado' => 'nullable|string|max:20',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear una nueva publicidad
            $publicidad = Publicidad::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $publicidad,
                'message' => 'Publicidad creada correctamente'
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
            // Obtener una publicidad por ID
            $publicidad = Publicidad::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $publicidad,
                'message' => 'Publicidad obtenida correctamente'
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
                'titulo' => 'nullable|string|max:100',
                'imagen' => 'nullable|string|max:255',
                'enlace' => 'nullable|string|max:255',
                'estado' => 'nullable|string|max:20',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar una publicidad por ID
            $publicidad = Publicidad::findOrFail($id);
            $publicidad->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $publicidad,
                'message' => 'Publicidad actualizada correctamente'
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
            // Eliminar una publicidad por ID
            $publicidad = Publicidad::findOrFail($id);
            $publicidad->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Publicidad eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
