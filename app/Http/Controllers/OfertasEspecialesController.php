<?php

namespace App\Http\Controllers;

use App\Models\OfertasEspeciales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class OfertasEspecialesController extends Controller
{
    public function index()
    {
        try {
            $ofertasEspeciales = OfertasEspeciales::with('producto')->get();

            return response()->json([
                'success' => true,
                'data' => $ofertasEspeciales,
                'message' => 'Ofertas especiales obtenidas correctamente'
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
                'producto_id' => 'required|integer',
                'descuento' => 'required|numeric',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ofertaEspecial = OfertasEspeciales::create($request->all());

            return new JsonResponse([
                'success' => true,
                'data' => $ofertaEspecial,
                'message' => 'Oferta especial creada correctamente'
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
            $ofertaEspecial = OfertasEspeciales::with('producto')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $ofertaEspecial,
                'message' => 'Oferta especial obtenida correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'producto_id' => 'integer',
                'descuento' => 'numeric',
                'fecha_inicio' => 'date',
                'fecha_fin' => 'date',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ofertaEspecial = OfertasEspeciales::findOrFail($id);
            $ofertaEspecial->update($request->all());

            return new JsonResponse([
                'success' => true,
                'data' => $ofertaEspecial,
                'message' => 'Oferta especial actualizada correctamente'
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
            $ofertaEspecial = OfertasEspeciales::findOrFail($id);
            $ofertaEspecial->delete();

            return new JsonResponse([
                'success' => true,
                'message' => 'Oferta especial eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
