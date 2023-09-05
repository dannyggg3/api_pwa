<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class TipoDocumentoController extends Controller
{
     public function index()
    {
        try {
            // Obtener todos los tipos de documento
            $tiposDocumento = TipoDocumento::all();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $tiposDocumento,
                'message' => 'Tipos de documento obtenidos correctamente'
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
                'nombre' => 'required|string|max:45',
                'valor' => 'required|string|max:45',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear un nuevo tipo de documento
            $tipoDocumento = TipoDocumento::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $tipoDocumento,
                'message' => 'Tipo de documento creado correctamente'
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
            // Obtener un tipo de documento por ID
            $tipoDocumento = TipoDocumento::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $tipoDocumento,
                'message' => 'Tipo de documento obtenido correctamente'
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
                'nombre' => 'required|string|max:45',
                'valor' => 'required|string|max:45',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar un tipo de documento por ID
            $tipoDocumento = TipoDocumento::findOrFail($id);
            $tipoDocumento->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $tipoDocumento,
                'message' => 'Tipo de documento actualizado correctamente'
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
            // Eliminar un tipo de documento por ID
            $tipoDocumento = TipoDocumento::findOrFail($id);
            $tipoDocumento->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Tipo de documento eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
