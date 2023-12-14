<?php

namespace App\Http\Controllers;


use App\Models\DetallesCarrito;
use App\Models\Variante;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class DetallesCarritoController extends Controller
{
    public function index()
    {
        try {
            $detallesCarrito = DetallesCarrito::with('carrito', 'variante')->get();
            $response = response()->json([
                'correctProcess' => true,
                'data' => $detallesCarrito,
                'message' => 'Detalles del carrito obtenidos correctamente'
            ]);

            $response->setEncodingOptions($response->getEncodingOptions() | JSON_UNESCAPED_UNICODE);

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cliente_id' => 'required|integer',
                'variante_id' => 'required|integer',
                'cantidad' => 'nullable|integer'
            ]);


            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }



            //si ya existe el usuario y la variante en el carrito, se actualiza la cantidad
            $detalleCarrito = DetallesCarrito::where('cliente_id', $request->cliente_id)
                ->where('variante_id', $request->variante_id)->first();



            if ($detalleCarrito) {

                //valida el stock de la variante
                $variante = Variante::findOrFail($request->variante_id);


                if ($variante->stock < ($request->cantidad + $detalleCarrito->cantidad)) {
                    return new JsonResponse([
                        'correctProcess' => false,
                        'message' => 'No hay stock suficiente para este producto'
                    ], 200);
                }

                $detalleCarrito->cantidad = $detalleCarrito->cantidad + $request->cantidad;
                $detalleCarrito->save();

                return new JsonResponse([
                    'correctProcess' => true,
                    'data' => $detalleCarrito,
                    'message' => 'Detalle del carrito actualizado correctamente'
                ], 200);
            }


            $detalleCarrito = DetallesCarrito::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $detalleCarrito,
                'message' => 'Detalle del carrito creado correctamente'
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
            $detalleCarrito = DetallesCarrito::where('cliente_id', $id)->with('variante')->get();

            //agregar el producto a la variante
            foreach ($detalleCarrito as $detalle) {


                $producto = Producto::where('id', $detalle->variante->producto_id)->with('marca', 'categoria')->first();
                $detalle->variante->producto = $producto;
            }

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $detalleCarrito,
                'message' => 'Detalle del carrito obtenido correctamente'
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
            $validator = Validator::make($request->all(), [
                'cliente_id' => 'required|integer',
                'variante_id' => 'required|integer',
                'cantidad' => 'nullable|integer'
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }

            $detalleCarrito = DetallesCarrito::findOrFail($id);
            $detalleCarrito->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $detalleCarrito,
                'message' => 'Detalle del carrito actualizado correctamente'
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
            $detalleCarrito = DetallesCarrito::findOrFail($id);
            $detalleCarrito->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Detalle del carrito eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
