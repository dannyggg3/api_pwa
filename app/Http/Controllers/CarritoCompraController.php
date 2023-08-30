<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarritoCompra;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CarritoCompraController extends Controller
{
     public function index()
    {
        try {
            $carritos = CarritoCompra::with('cliente')->get();
            $response = response()->json([
                'correctProcess' => true,
                'data' => $carritos,
                'message' => 'Carritos de compra obtenidos correctamente'
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
                'cliente_id' => 'required|exists:clientes,id',
                'estado' => 'nullable|string|max:20',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $carrito = CarritoCompra::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $carrito,
                'message' => 'Carrito de compra creado correctamente'
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
            $carrito = CarritoCompra::with('cliente')->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $carrito,
                'message' => 'Carrito de compra obtenido correctamente'
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
                'cliente_id' => 'exists:clientes,id',
                'estado' => 'nullable|string|max:20',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $carrito = CarritoCompra::findOrFail($id);
            $carrito->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $carrito,
                'message' => 'Carrito de compra actualizado correctamente'
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
            $carrito = CarritoCompra::findOrFail($id);
            $carrito->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Carrito de compra eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
