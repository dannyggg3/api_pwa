<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ProductoController extends Controller
{
     public function index()
    {
        try {
            // Obtener todos los productos con sus categorías y marcas relacionadas
            $productos = Producto::with('categoria', 'marca')->get();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $productos,
                'message' => 'Productos obtenidos correctamente'
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
                'categoria_id' => 'nullable|integer',
                'marca_id' => 'nullable|integer',
                'nombre' => 'required|string|max:100',
                'descripcion' => 'nullable|string',
                'precio' => 'nullable|numeric',
                'estado' => 'nullable|string|max:20',
                'imagen' => 'nullable|string|max:255',
                'caracteristicas' => 'nullable|string|max:255',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear un nuevo producto
            $producto = Producto::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $producto,
                'message' => 'Producto creado correctamente'
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
            // Obtener un producto por ID con su categoría y marca relacionadas
            $producto = Producto::with('categoria', 'marca')->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $producto,
                'message' => 'Producto obtenido correctamente'
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
                'categoria_id' => 'nullable|integer',
                'marca_id' => 'nullable|integer',
                'nombre' => 'required|string|max:100',
                'descripcion' => 'nullable|string',
                'precio' => 'nullable|numeric',
                'estado' => 'nullable|string|max:20',
                'imagen' => 'nullable|string|max:255',
                'caracteristicas' => 'nullable|string|max:255',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar un producto por ID
            $producto = Producto::findOrFail($id);
            $producto->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $producto,
                'message' => 'Producto actualizado correctamente'
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
            // Eliminar un producto por ID
            $producto = Producto::findOrFail($id);
            $producto->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Producto eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
