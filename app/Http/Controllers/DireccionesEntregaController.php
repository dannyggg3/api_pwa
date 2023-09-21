<?php

namespace App\Http\Controllers;



use App\Models\DireccionesEntrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Models\Parroquias;
use App\Models\Ciudad;
use App\Models\Provincia;


class DireccionesEntregaController extends Controller
{
   public function index()
    {
        try {
            $direccionesEntrega = DireccionesEntrega::with('cliente')->get();

            foreach ($direccionesEntrega as $direccionEntrega) {

                //parroquia
                    $parroquia=Parroquias::where('id',$direccionEntrega->parroquia_id)->first();
                    $direccionEntrega->parroquia=$parroquia;

                //ciudad
                    $ciudad=Ciudad::where('id',$parroquia->ciudad_id)->first();
                    $direccionEntrega->ciudad=$ciudad;

                //provincia
                    $provincia=Provincia::where('id',$ciudad->provincias_id)->first();
                    $direccionEntrega->provincia=$provincia;
            }

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $direccionesEntrega,
                'message' => 'Direcciones de entrega obtenidas correctamente'
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
            $validator = Validator::make($request->all(), [
                'cliente_id' => 'required|integer',
                'cedula' => 'nullable|string|max:45',
                'direccion' => 'nullable|string|max:255',
                'estado' => 'nullable|string|max:20',
                'parroquia_id' => 'required|string',
                'comentarios' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }

            $direccionEntrega = DireccionesEntrega::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $direccionEntrega,
                'message' => 'Dirección de entrega creada correctamente'
            ], 201);
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
            $direccionEntrega = DireccionesEntrega::with('ciudad')->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $direccionEntrega,
                'message' => 'Dirección de entrega obtenida correctamente'
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
                'cedula' => 'nullable|string|max:45',
                'direccion' => 'nullable|string|max:255',
                'estado' => 'nullable|string|max:20',
                'parroquia_id' => 'required|string',
                'comentarios' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }

            $direccionEntrega = DireccionesEntrega::findOrFail($id);
            $direccionEntrega->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $direccionEntrega,
                'message' => 'Dirección de entrega actualizada correctamente'
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
            $direccionEntrega = DireccionesEntrega::findOrFail($id);
            $direccionEntrega->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Dirección de entrega eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
