<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{
     public function index()
    {
        try {
            $clientes = Cliente::with(['usuario', 'tipoDocumento'])->get();
            $response = response()->json([
                'correctProcess' => true,
                'data' => $clientes,
                'message' => 'Clientes obtenidos correctamente'
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
                'usuario_id' => 'nullable|integer',
                'documento' => 'nullable|string|max:45',
                'nombre' => 'required|string|max:100',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'estado' => 'nullable|string|max:20',
                'imagen' => 'nullable|string|max:255',
                'tipo_documento_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cliente = Cliente::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $cliente,
                'message' => 'Cliente creado correctamente'
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
            $cliente = Cliente::with(['usuario', 'tipoDocumento'])->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $cliente,
                'message' => 'Cliente obtenido correctamente'
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
                'usuario_id' => 'nullable|integer',
                'documento' => 'nullable|string|max:45',
                'nombre' => 'nullable|string|max:100',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'estado' => 'nullable|string|max:20',
                'imagen' => 'nullable|string|max:255',
                'tipo_documento_id' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cliente = Cliente::findOrFail($id);
            $cliente->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $cliente,
                'message' => 'Cliente actualizado correctamente'
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
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Cliente eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
