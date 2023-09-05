<?php

namespace App\Http\Controllers;


use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class MarcaController extends Controller
{
     public function index()
    {
        try {
            $marcas = Marca::all();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $marcas,
                'message' => 'Marcas obtenidas correctamente'
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
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:100',
                'estado' => 'nullable|string|max:20',
                'imagen' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $marca = Marca::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $marca,
                'message' => 'Marca creada correctamente'
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
            $marca = Marca::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $marca,
                'message' => 'Marca obtenida correctamente'
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
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:100',
                'estado' => 'nullable|string|max:20',
                'imagen' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $marca = Marca::findOrFail($id);
            $marca->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $marca,
                'message' => 'Marca actualizada correctamente'
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
            $marca = Marca::findOrFail($id);
            $marca->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Marca eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
