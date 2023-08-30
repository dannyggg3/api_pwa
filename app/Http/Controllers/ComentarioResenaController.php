<?php

namespace App\Http\Controllers;

use App\Models\ComentarioResena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ComentarioResenaController extends Controller
{
    // Obtener todos los comentarios y reseñas
    public function index()
    {
        try {
            $comentariosResenas = ComentarioResena::with(['cliente', 'producto'])->get();
            
            $response = response()->json([
                'correctProcess' => true,
                'data' => $comentariosResenas,
                'message' => 'Comentarios y reseñas obtenidos correctamente'
            ]);
             $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);  
            return $response;
          
        } catch (\Exception $e) {
            return response()->json([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Crear un nuevo comentario o reseña
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cliente_id' => 'nullable|integer',
                'producto_id' => 'nullable|integer',
                'comentario' => 'nullable|string',
                'puntuacion' => 'nullable|integer|min:1|max:5',
                'fecha_comentario' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $comentarioResena = ComentarioResena::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $comentarioResena,
                'message' => 'Comentario o reseña creada correctamente'
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Obtener un comentario o reseña por su ID
    public function show($id)
    {
        try {
            $comentarioResena = ComentarioResena::with(['cliente', 'producto'])->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $comentarioResena,
                'message' => 'Comentario o reseña obtenida correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Actualizar un comentario o reseña por su ID
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cliente_id' => 'nullable|integer',
                'producto_id' => 'nullable|integer',
                'comentario' => 'nullable|string',
                'puntuacion' => 'nullable|integer|min:1|max:5',
                'fecha_comentario' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $comentarioResena = ComentarioResena::findOrFail($id);
            $comentarioResena->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $comentarioResena,
                'message' => 'Comentario o reseña actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Eliminar un comentario o reseña por su ID
    public function destroy($id)
    {
        try {
            $comentarioResena = ComentarioResena::findOrFail($id);
            $comentarioResena->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Comentario o reseña eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
