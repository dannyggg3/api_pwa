<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class SliderController extends Controller
{
     public function index()
    {
        try {
            // Obtener todos los sliders
            $sliders = Slider::all();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $sliders,
                'message' => 'Sliders obtenidos correctamente'
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
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'titulo' => 'nullable|string|max:100',
                'descripcion' => 'nullable|string',
                'imagen' => 'nullable|string|max:255',
                'estado' => 'nullable|string|max:20',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear un nuevo slider
            $slider = Slider::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $slider,
                'message' => 'Slider creado correctamente'
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
            // Obtener un slider por ID
            $slider = Slider::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $slider,
                'message' => 'Slider obtenido correctamente'
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
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'titulo' => 'nullable|string|max:100',
                'descripcion' => 'nullable|string',
                'imagen' => 'nullable|string|max:255',
                'estado' => 'nullable|string|max:20',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar un slider por ID
            $slider = Slider::findOrFail($id);
            $slider->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $slider,
                'message' => 'Slider actualizado correctamente'
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
            // Eliminar un slider por ID
            $slider = Slider::findOrFail($id);
            $slider->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Slider eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
