<?php

namespace App\Http\Controllers;

use App\Models\FormaPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class FormaPagoController extends Controller
{
    public function index()
    {
        try {
            $formasPago = FormaPago::all();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $formasPago,
                'message' => 'Formas de pago obtenidas correctamente'
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
                'pago' => 'nullable|string|max:45',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $formaPago = FormaPago::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $formaPago,
                'message' => 'Forma de pago creada correctamente'
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
            $formaPago = FormaPago::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $formaPago,
                'message' => 'Forma de pago obtenida correctamente'
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
                'pago' => 'nullable|string|max:45',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $formaPago = FormaPago::findOrFail($id);
            $formaPago->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $formaPago,
                'message' => 'Forma de pago actualizada correctamente'
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
            $formaPago = FormaPago::findOrFail($id);
            $formaPago->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Forma de pago eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
