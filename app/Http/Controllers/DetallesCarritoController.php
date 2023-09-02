<?php

namespace App\Http\Controllers;


use App\Models\DetallesCarrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class DetallesCarritoController extends Controller
{
    public function index()
    {
        try {
            $detallesCarrito = DetallesCarrito::with('carrito', 'variante')->get();
            $response = response()->json([
                'correctProcess' => true,
                'data' => $detallesCarrito,
                'message' => 'Detalles del carrito obtenidos correctamente'
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
                'carrito_id' => 'required|integer',
                'variante_id' => 'required|integer',
                'cantidad' => 'nullable|integer'
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $detalleCarrito = DetallesCarrito::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $detalleCarrito,
                'message' => 'Detalle del carrito creado correctamente'
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
            $detalleCarrito = DetallesCarrito::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $detalleCarrito,
                'message' => 'Detalle del carrito obtenido correctamente'
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
                'carrito_id' => 'required|integer',
                'variante_id' => 'required|integer',
                'cantidad' => 'nullable|integer'
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $detalleCarrito = DetallesCarrito::findOrFail($id);
            $detalleCarrito->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $detalleCarrito,
                'message' => 'Detalle del carrito actualizado correctamente'
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
            $detalleCarrito = DetallesCarrito::findOrFail($id);
            $detalleCarrito->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Detalle del carrito eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
