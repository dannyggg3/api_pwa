<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CiudadController extends Controller
{
    public function index()
    {
        try {
            $ciudades = Ciudad::with('provincia')->get();
            $response = response()->json([
                'correctProcess' => true,
                'data' => $ciudades,
                'message' => 'Ciudades obtenidas correctamente'
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
                'ciudad' => 'required|string|max:100',
                'provincias_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ciudad = Ciudad::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $ciudad,
                'message' => 'Ciudad creada correctamente'
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
            $ciudad = Ciudad::with('provincia')->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $ciudad,
                'message' => 'Ciudad obtenida correctamente'
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
                'ciudad' => 'nullable|string|max:100',
                'provincias_id' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ciudad = Ciudad::findOrFail($id);
            $ciudad->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $ciudad,
                'message' => 'Ciudad actualizada correctamente'
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
            $ciudad = Ciudad::findOrFail($id);
            $ciudad->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Ciudad eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
