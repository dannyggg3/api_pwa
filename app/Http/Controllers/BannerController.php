<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;


class BannerController extends Controller
{


    public function index()
    {
        try {
            $banners = Banner::all();
            $response = response()->json([
                'correctProcess' => true,
                'data' => $banners,
                'message' => 'Banners obtenidos correctamente'
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
                'titulo' => 'required|string|max:100',
                'estado' => 'required|string|max:20',
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
                $imagenPath = $request->file('imagen')->store('banners', 'public'); // 'public' es el disco configurado en filesystems.php

                // Genera la URL completa de la imagen.
                $imagenUrl = Storage::url($imagenPath);
            } else {
                $imagenUrl = null;
            }

            // Crea la categoría con la URL de la imagen.
            $banner = Banner::create([
                'titulo' => $request->input('titulo'),
                'estado' => $request->input('estado'),
                'imagen' => $imagenUrl, // Almacena la URL de la imagen en la base de datos.
            ]);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $banner,
                'message' => 'Banner creado correctamente'
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
            $banner = Banner::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $banner,
                'message' => 'Banner obtenido correctamente'
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

            $banner = Banner::findOrFail($id); // Encuentra la categoría por su ID.

            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:100',
                'estado' => 'nullable|string|max:20',
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
                if ($banner->imagen) {
                    Storage::disk('public')->delete($banner->imagen);
                }

                // Sube la nueva imagen al servidor y obtén la URL.
                $imagenPath = $request->file('imagen')->store('banners', 'public'); // 'public' es el disco configurado en filesystems.php

                // Genera la URL completa de la nueva imagen.
                $imagenUrl = Storage::url($imagenPath);
            } else {
                // Si no se cargó una nueva imagen, conserva la imagen existente.
                $imagenUrl = $banner->imagen;
            }

            // Actualiza la categoría con la nueva información, incluida la URL de la imagen.
            $banner->update([
                'titulo' => $request->input('titulo'),
                'estado' => $request->input('estado'),
                'imagen' => $imagenUrl, // Actualiza la URL de la imagen en la base de datos.
            ]);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $banner,
                'message' => 'Banner actualizado correctamente'
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
            $banner = Banner::findOrFail($id);
            $banner->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Banner eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
