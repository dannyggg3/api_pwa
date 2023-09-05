<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;


class OrdenController extends Controller
{
   public function index()
    {
        try {
            $ordenes = Orden::with('cliente', 'estadoOrden', 'datosFacturacion', 'direccionEntrega', 'detallesOrden')->get();

            return response()->json([
                'success' => true,
                'data' => $ordenes,
                'message' => 'Órdenes obtenidas correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cliente_id' => 'required|integer',
                'fecha' => 'required|date',
                'estado' => 'required|string|max:20',
                'total' => 'required|numeric',
                'estado_orden_id' => 'required|integer',
                'datosfacturacion_id' => 'required|integer',
                'direccionesentrega_id' => 'required|integer',
                'subtotal12' => 'required|numeric',
                'subtotaliva0' => 'required|numeric',
                'subtotal_sin_impuestos' => 'required|numeric',
                'descuento' => 'required|numeric',
                'iva' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $orden = Orden::create($request->all());

            return new JsonResponse([
                'success' => true,
                'data' => $orden,
                'message' => 'Orden creada correctamente'
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $orden = Orden::with('cliente', 'estadoOrden', 'datosFacturacion', 'direccionEntrega', 'detallesOrden')->find($id);

            if (!$orden) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Orden no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $orden,
                'message' => 'Orden obtenida correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $orden = Orden::find($id);

            if (!$orden) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Orden no encontrada'
                ], 404);
            }

           $validator = Validator::make($request->all(), [
                'cliente_id' => 'required|integer',
                'fecha' => 'required|date',
                'estado' => 'required|string|max:20',
                'total' => 'required|numeric',
                'estado_orden_id' => 'required|integer',
                'datosfacturacion_id' => 'required|integer',
                'direccionesentrega_id' => 'required|integer',
                'subtotal12' => 'required|numeric',
                'subtotaliva0' => 'required|numeric',
                'subtotal_sin_impuestos' => 'required|numeric',
                'descuento' => 'required|numeric',
                'iva' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $orden->update($request->all());

            return new JsonResponse([
                'success' => true,
                'data' => $orden,
                'message' => 'Orden actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $orden = Orden::find($id);

            if (!$orden) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Orden no encontrada'
                ], 404);
            }

            $orden->delete();

            return new JsonResponse([
                'success' => true,
                'message' => 'Orden eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
