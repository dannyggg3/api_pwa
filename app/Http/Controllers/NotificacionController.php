<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class NotificacionController extends Controller
{
     public function index()
    {
        try {
            $notificaciones = Notificacion::all();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $notificaciones,
                'message' => 'Notificaciones obtenidas correctamente'
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
                'usuario_id' => 'required|integer',
                'mensaje' => 'required|string',
                'leida' => 'nullable|boolean',
                'fecha_envio' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $notificacion = Notificacion::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $notificacion,
                'message' => 'Notificación creada correctamente'
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
            $notificacion = Notificacion::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $notificacion,
                'message' => 'Notificación obtenida correctamente'
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
                'usuario_id' => 'required|integer',
                'mensaje' => 'required|string',
                'leida' => 'nullable|boolean',
                'fecha_envio' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $notificacion = Notificacion::findOrFail($id);
            $notificacion->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $notificacion,
                'message' => 'Notificación actualizada correctamente'
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
            $notificacion = Notificacion::findOrFail($id);
            $notificacion->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Notificación eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
