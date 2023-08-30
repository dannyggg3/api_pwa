<?php

namespace App\Http\Controllers;

use App\Models\DatosFacturacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class DatosFacturacionController extends Controller
{
      /**
     * Obtener todos los datos de facturación.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $datosFacturacion = DatosFacturacion::with('cliente', 'tipoDocumento')->get();
            $response = response()->json([
                'correctProcess' => true,
                'data' => $datosFacturacion,
                'message' => 'Datos de facturación obtenidos correctamente'
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

    /**
     * Almacenar un nuevo dato de facturación.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
           $validator = Validator::make($request->all(), [
                'cliente_id' => 'required|integer',
                'nombre' => 'nullable|string|max:100',
                'ruc_cedula' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'ciudad' => 'nullable|string|max:100',
                'telefono' => 'nullable|string|max:20',
                'tipo_documento_id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $datoFacturacion = DatosFacturacion::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $datoFacturacion,
                'message' => 'Dato de facturación creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un dato de facturación específico.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $datoFacturacion = DatosFacturacion::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $datoFacturacion,
                'message' => 'Dato de facturación obtenido correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un dato de facturación existente.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
             $validator = Validator::make($request->all(), [
                'cliente_id' => 'required|integer',
                'nombre' => 'nullable|string|max:100',
                'ruc_cedula' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'ciudad' => 'nullable|string|max:100',
                'telefono' => 'nullable|string|max:20',
                'tipo_documento_id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $datoFacturacion = DatosFacturacion::findOrFail($id);
            $datoFacturacion->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $datoFacturacion,
                'message' => 'Dato de facturación actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un dato de facturación.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $datoFacturacion = DatosFacturacion::findOrFail($id);
            $datoFacturacion->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Dato de facturación eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
