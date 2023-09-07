<?php

namespace App\Http\Controllers;

use App\Models\Variante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class VarianteController extends Controller
{
     public function index()
    {
        try {
            // Obtener todas las variantes con su producto relacionado
            $variantes = Variante::with('producto')->get();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $variantes,
                'message' => 'Variantes obtenidas correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'producto_id' => 'required|integer|exists:productos,id',
                'color' => 'nullable|string|max:50',
                'talla' => 'nullable|string|max:50',
                'stock' => 'nullable|integer',
                'estado' => 'nullable|string|max:20',
                'codigo_color' => 'required|string|max:20',
                'precio' => 'nullable|numeric',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }


            //BUSCA SI YA EXISTE POR TALLA Y COLOR
            $variante = Variante::where('producto_id', $request->producto_id)
                ->where('talla', $request->talla)
                ->where('color', $request->color)
                ->first();

            if($variante){
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Ya existe una variante con la talla y color ingresados'
                ], 200);
            }


            // Crear una nueva variante
            $variante = Variante::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $variante,
                'message' => 'Variante creada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function show($id)
    {
        try {
            // Obtener una variante por ID con su producto relacionado
            $variante = Variante::with('producto')->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $variante,
                'message' => 'Variante obtenida correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

      public function showProduct($id)
    {
        try {
            // Obtener una variante por ID con su producto relacionado ordenar por color


            $variante = Variante::where('producto_id', $id)->orderBy('color', 'asc')->get();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $variante,
                'message' => 'Variantes obtenida correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'producto_id' => 'required|integer|exists:productos,id',
                'color' => 'nullable|string|max:50',
                'talla' => 'nullable|string|max:50',
                'stock' => 'nullable|integer',
                'estado' => 'nullable|string|max:20',
                'codigo_color' => 'required|string|max:20',
                'precio' => 'nullable|numeric',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }

            // Actualizar una variante por ID
            $variante = Variante::findOrFail($id);
            $variante->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $variante,
                'message' => 'Variante actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function destroy($id)
    {
        try {
            // Eliminar una variante por ID
            $variante = Variante::findOrFail($id);
            $variante->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Variante eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
