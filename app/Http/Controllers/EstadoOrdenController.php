<?php

namespace App\Http\Controllers;

use App\Models\EstadoOrden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class EstadoOrdenController extends Controller
{
    public function index()
    {
        try {
            $estadosOrden = EstadoOrden::all();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $estadosOrden,
                'message' => 'Estados de orden obtenidos correctamente'
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
                'estado' => 'nullable|string|max:45',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $estadoOrden = EstadoOrden::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $estadoOrden,
                'message' => 'Estado de orden creado correctamente'
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
            $estadoOrden = EstadoOrden::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $estadoOrden,
                'message' => 'Estado de orden obtenido correctamente'
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
                'estado' => 'nullable|string|max:45',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $estadoOrden = EstadoOrden::findOrFail($id);
            $estadoOrden->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $estadoOrden,
                'message' => 'Estado de orden actualizado correctamente'
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
            $estadoOrden = EstadoOrden::findOrFail($id);
            $estadoOrden->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Estado de orden eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
