<?php

namespace App\Http\Controllers;

use App\Models\FacturaElectronica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class FacturaElectronicaController extends Controller
{
   public function index()
    {
        try {
            $facturasElectronicas = FacturaElectronica::with('orden')->get();

            $response= new JsonResponse([
                'correctProcess' => true,
                'data' => $facturasElectronicas,
                'message' => 'Facturas electrónicas obtenidas correctamente'
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
                'factura' => 'nullable|string|max:45',
                'estado' => 'nullable|string|max:45',
                'numero_autorizacion' => 'nullable|string|max:100',
                'clave_acceso' => 'nullable|string|max:100',
                'fecha' => 'nullable|string|max:45',
                'descargada' => 'nullable|string|max:45',
                'ordenes_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $facturaElectronica = FacturaElectronica::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $facturaElectronica,
                'message' => 'Factura electrónica creada correctamente'
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
            $facturaElectronica = FacturaElectronica::with('orden')->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $facturaElectronica,
                'message' => 'Factura electrónica obtenida correctamente'
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
                'factura' => 'nullable|string|max:45',
                'estado' => 'nullable|string|max:45',
                'numero_autorizacion' => 'nullable|string|max:100',
                'clave_acceso' => 'nullable|string|max:100',
                'fecha' => 'nullable|string|max:45',
                'descargada' => 'nullable|string|max:45',
                'ordenes_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $facturaElectronica = FacturaElectronica::findOrFail($id);
            $facturaElectronica->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $facturaElectronica,
                'message' => 'Factura electrónica actualizada correctamente'
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
            $facturaElectronica = FacturaElectronica::findOrFail($id);
            $facturaElectronica->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Factura electrónica eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
