<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    public function index()
    {
        try {
            $categorias = Categoria::with('productos')->get();
            $response = response()->json([
                'correctProcess' => true,
                'data' => $categorias,
                'message' => 'Categorías obtenidas correctamente'
            ]);

            $response->setEncodingOptions($response->getEncodingOptions() | JSON_UNESCAPED_UNICODE);

            return $response;
        } catch (\Exception $e) {
            return response()->json([
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

            $categoria = Categoria::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $categoria,
                'message' => 'Categoría creada correctamente'
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
            $categoria = Categoria::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $categoria,
                'message' => 'Categoría obtenida correctamente'
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
                'nombre' => 'nullable|string|max:100',
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

            $categoria = Categoria::findOrFail($id);
            $categoria->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $categoria,
                'message' => 'Categoría actualizada correctamente'
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
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Categoría eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
