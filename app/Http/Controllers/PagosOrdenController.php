<?php

namespace App\Http\Controllers;

use App\Models\PagosOrden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class PagosOrdenController extends Controller
{
     public function index()
    {
        try {
            // Obtener todos los pagos de órdenes
            $pagosOrden = PagosOrden::all();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $pagosOrden,
                'message' => 'Pagos de órdenes obtenidos correctamente'
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
                'total' => 'required|numeric',
                'plazo' => 'nullable|string|max:45',
                'tiempo' => 'nullable|string|max:45',
                'formas_pago_id' => 'required|integer',
                'ordenes_id' => 'required|integer',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear un nuevo pago de orden
            $pagoOrden = PagosOrden::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $pagoOrden,
                'message' => 'Pago de orden creado correctamente'
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
            // Obtener un pago de orden por ID
            $pagoOrden = PagosOrden::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $pagoOrden,
                'message' => 'Pago de orden obtenido correctamente'
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
                'total' => 'required|numeric',
                'plazo' => 'nullable|string|max:45',
                'tiempo' => 'nullable|string|max:45',
                'formas_pago_id' => 'required|integer',
                'ordenes_id' => 'required|integer',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar un pago de orden por ID
            $pagoOrden = PagosOrden::findOrFail($id);
            $pagoOrden->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $pagoOrden,
                'message' => 'Pago de orden actualizado correctamente'
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
            // Eliminar un pago de orden por ID
            $pagoOrden = PagosOrden::findOrFail($id);
            $pagoOrden->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Pago de orden eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
