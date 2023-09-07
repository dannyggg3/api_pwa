<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
     public function index()
    {
        try {
            // Obtener todos los productos con sus categorías y marcas relacionadas
            $productos = Producto::with('categoria', 'marca','variantes')->get();
            $categorias= Categoria::all();
            $marcas= Marca::all();

            return new JsonResponse([
                'correctProcess' => true,
                'data' =>   ['productos'=>$productos,
                            'categorias'=>$categorias,
                            'marcas'=>$marcas
                            ] ,
                'message' => 'Productos obtenidos correctamente'

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
                'categoria_id' => 'nullable|integer',
                'marca_id' => 'nullable|integer',
                'nombre' => 'required|string|max:100',
                'descripcion' => 'nullable|string',
                'precio' => 'nullable|numeric',
                'estado' => 'nullable|string|max:20',
                'imagen' => 'required|image|max:2048',
                'caracteristicas' => 'nullable|string|max:255',
            ]);

            // Si la validación falla, retornar un error de validación
            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }


            // Sube la imagen al servidor y obtén la URL.
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('productos', 'public'); // 'public' es el disco configurado en filesystems.php

                // Genera la URL completa de la imagen.
                $imagenUrl = Storage::url($imagenPath);
            } else {
                $imagenUrl = null;
            }

            // Crear un nuevo producto
            $producto = Producto::create([
                'categoria_id' => $request->input('categoria_id'),
                'marca_id' => $request->input('marca_id'),
                'nombre' => $request->input('nombre'),
                'descripcion' => $request->input('descripcion'),
                'precio' => $request->input('precio'),
                'estado' => $request->input('estado'),
                'imagen' => $imagenUrl,
                'caracteristicas' => $request->input('caracteristicas'),

            ]);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $producto,
                'message' => 'Producto creado correctamente'
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
            // Obtener un producto por ID con su categoría y marca relacionadas
            $producto = Producto::with('categoria', 'marca')->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $producto,
                'message' => 'Producto obtenido correctamente'
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

            // Actualizar un producto por ID
            $producto = Producto::findOrFail($id);

            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'categoria_id' => 'nullable|integer',
                'marca_id' => 'nullable|integer',
                'nombre' => 'required|string|max:100',
                'descripcion' => 'nullable|string',
                'precio' => 'nullable|numeric',
                'estado' => 'nullable|string|max:20',
                'imagen' => 'nullable|image|max:2048',
                'caracteristicas' => 'nullable|string|max:255',
            ]);

            // Si la validación falla, retornar un error de validación
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
                if ($producto->imagen) {
                    Storage::disk('public')->delete($producto->imagen);
                }

                // Sube la nueva imagen al servidor y obtén la URL.
                $imagenPath = $request->file('imagen')->store('productos', 'public'); // 'public' es el disco configurado en filesystems.php

                // Genera la URL completa de la nueva imagen.
                $imagenUrl = Storage::url($imagenPath);
            } else {
                // Si no se cargó una nueva imagen, conserva la imagen existente.
                $imagenUrl = $producto->imagen;
            }

            $producto->update([
                'categoria_id' => $request->input('categoria_id'),
                'marca_id' => $request->input('marca_id'),
                'nombre' => $request->input('nombre'),
                'descripcion' => $request->input('descripcion'),
                'precio' => $request->input('precio'),
                'estado' => $request->input('estado'),
                'imagen' => $imagenUrl,
                'caracteristicas' => $request->input('caracteristicas'),
            ]);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $producto,
                'message' => 'Producto actualizado correctamente'
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
            // Eliminar un producto por ID
            $producto = Producto::findOrFail($id);
            $producto->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Producto eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
