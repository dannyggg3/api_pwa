<?php

namespace App\Http\Controllers;

use App\Models\InfoAdicional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class InfoAdicionalController extends Controller
{
     public function index()
    {
        try {
            $infoAdicional = InfoAdicional::all();

            $response= new JsonResponse([
                'correctProcess' => true,
                'data' => $infoAdicional,
                'message' => 'Información adicional obtenida correctamente'
            ]);

            $response->setEncodingOptions(JSON_UNESCAPED_UNICODE); 
            return $response; 
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
                'nombre' => 'nullable|string|max:45',
                'descripcion' => 'nullable|string|max:255',
                'ordenes_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $infoAdicional = InfoAdicional::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $infoAdicional,
                'message' => 'Información adicional creada correctamente'
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
            $infoAdicional = InfoAdicional::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $infoAdicional,
                'message' => 'Información adicional obtenida correctamente'
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
                'nombre' => 'nullable|string|max:45',
                'descripcion' => 'nullable|string|max:255',
                'ordenes_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $infoAdicional = InfoAdicional::findOrFail($id);
            $infoAdicional->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $infoAdicional,
                'message' => 'Información adicional actualizada correctamente'
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
            $infoAdicional = InfoAdicional::findOrFail($id);
            $infoAdicional->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Información adicional eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
