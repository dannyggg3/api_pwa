<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:100',
                'estado' => 'required|string|max:20',
                'descripcion' => 'nullable|string',
                'imagen' => 'required|image|max:2048', // Asegúrate de que 'imagen' sea un archivo de imagen válido y no supere 2MB.
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Sube la imagen al servidor y obtén la URL.
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('sliders', 'public'); // 'public' es el disco configurado en filesystems.php

                // Genera la URL completa de la imagen.
                $imagenUrl = Storage::url($imagenPath);
            } else {
                $imagenUrl = null;
            }

            // Crea la categoría con la URL de la imagen.
            $slider = Slider::create([
                'titulo' => $request->input('titulo'),
                'descripcion' => $request->input('descripcion'),
                'estado' => $request->input('estado'),
                'imagen' => $imagenUrl, // Almacena la URL de la imagen en la base de datos.
            ]);

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

            $slider = Slider::findOrFail($id); // Encuentra la categoría por su ID.

            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:100',
                'estado' => 'nullable|string|max:20',
                'descripcion' => 'nullable|string|max:200',
                'imagen' => 'nullable|image|max:2048', // Asegúrate de que 'imagen' sea un archivo de imagen válido y no supere 2MB.
            ]);



            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verifica si se ha cargado una nueva imagen.
            if ($request->hasFile('imagen')) {
                // Elimina la imagen anterior si existe.
                if ($slider->imagen) {
                    Storage::disk('public')->delete($slider->imagen);
                }

                // Sube la nueva imagen al servidor y obtén la URL.
                $imagenPath = $request->file('imagen')->store('sliders', 'public'); // 'public' es el disco configurado en filesystems.php

                // Genera la URL completa de la nueva imagen.
                $imagenUrl = Storage::url($imagenPath);
            } else {
                // Si no se cargó una nueva imagen, conserva la imagen existente.
                $imagenUrl = $slider->imagen;
            }

            // Actualiza la categoría con la nueva información, incluida la URL de la imagen.
            $slider->update([
                'titulo' => $request->input('titulo'),
                'descripcion' => $request->input('descripcion'),
                'estado' => $request->input('estado'),
                'imagen' => $imagenUrl, // Actualiza la URL de la imagen en la base de datos.
            ]);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $slider,
                'message' => 'Slider actualizado correctamente'
            ], 200);
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
