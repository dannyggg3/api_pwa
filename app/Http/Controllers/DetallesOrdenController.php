<?php

namespace App\Http\Controllers;

use App\Models\DetallesOrden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class DetallesOrdenController extends Controller
{
    public function index()
    {
        try {
            $detallesOrden = DetallesOrden::all();
            return new JsonResponse([
                'correctProcess' => true,
                'data' => $detallesOrden,
                'message' => 'Detalles de orden obtenidos correctamente'
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
                'orden_id' => 'required|integer',
                'variante_id' => 'required|integer',
                'cantidad' => 'required|integer',
                'subtotal' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $detallesOrden = DetallesOrden::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $detallesOrden,
                'message' => 'Detalle de orden creado correctamente'
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
            $detallesOrden = DetallesOrden::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $detallesOrden,
                'message' => 'Detalle de orden obtenido correctamente'
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
                'orden_id' => 'required|integer',
                'variante_id' => 'required|integer',
                'cantidad' => 'required|integer',
                'subtotal' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $detallesOrden = DetallesOrden::findOrFail($id);
            $detallesOrden->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $detallesOrden,
                'message' => 'Detalle de orden actualizado correctamente'
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
            $detallesOrden = DetallesOrden::findOrFail($id);
            $detallesOrden->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Detalle de orden eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
