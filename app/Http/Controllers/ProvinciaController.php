<?php

namespace App\Http\Controllers;

use App\Models\Provincia;
use App\Models\Ciudad;
use App\Models\Parroquias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ProvinciaController extends Controller
{
     public function index()
    {
        try {
            // Obtener todas las provincias
            $provincias = Provincia::all();
            $ciudades = Ciudad::all();
            $parroquias = Parroquias::all();

            $retorno = array(
                'provincias' => $provincias,
                'ciudades' => $ciudades,
                'parroquias' => $parroquias
            );

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $retorno,
                'message' => 'Provincias obtenidas correctamente'
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
                'provincia' => 'required|string|max:45',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear una nueva provincia
            $provincia = Provincia::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $provincia,
                'message' => 'Provincia creada correctamente'
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
            // Obtener una provincia por ID
            $provincia = Provincia::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $provincia,
                'message' => 'Provincia obtenida correctamente'
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
                'provincia' => 'required|string|max:45',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar una provincia por ID
            $provincia = Provincia::findOrFail($id);
            $provincia->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $provincia,
                'message' => 'Provincia actualizada correctamente'
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
            // Eliminar una provincia por ID
            $provincia = Provincia::findOrFail($id);
            $provincia->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Provincia eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
