<?php

namespace App\Http\Controllers;

use App\Models\ProductoDeseado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ProductoDeseadoController extends Controller
{
      public function index()
    {
        try {
            // Obtener todos los productos deseados con información del cliente y producto relacionados
            $productosDeseados = ProductoDeseado::with('cliente', 'producto')->get();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $productosDeseados,
                'message' => 'Productos deseados obtenidos correctamente'
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
                'cliente_id' => 'nullable|integer',
                'producto_id' => 'nullable|integer',
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

            // Crear un nuevo producto deseado
            $productoDeseado = ProductoDeseado::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $productoDeseado,
                'message' => 'Producto deseado creado correctamente'
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
            // Obtener un producto deseado por ID con información del cliente y producto relacionados
            $productoDeseado = ProductoDeseado::with('cliente', 'producto')->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $productoDeseado,
                'message' => 'Producto deseado obtenido correctamente'
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
                'cliente_id' => 'nullable|integer',
                'producto_id' => 'nullable|integer',
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

            // Actualizar un producto deseado por ID
            $productoDeseado = ProductoDeseado::findOrFail($id);
            $productoDeseado->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $productoDeseado,
                'message' => 'Producto deseado actualizado correctamente'
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
            // Eliminar un producto deseado por ID
            $productoDeseado = ProductoDeseado::findOrFail($id);
            $productoDeseado->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Producto deseado eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
