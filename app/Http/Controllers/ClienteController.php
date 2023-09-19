<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AuthController;

class ClienteController extends Controller
{
     public function index()
    {
        try {
            $clientes = Cliente::with(['usuario', 'tipoDocumento'])->get();
            $response = response()->json([
                'correctProcess' => true,
                'data' => $clientes,
                'message' => 'Clientes obtenidos correctamente'
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
                'usuario_id' => 'nullable|integer',
                'documento' => 'nullable|string|max:45',
                'nombre' => 'required|string|max:100',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'estado' => 'nullable',
                'imagen' => 'nullable|string|max:255',
                'tipo_documento_id' => 'required|integer',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }


            //valida si ya existe el email y la cedula

            $email = User::where('email', $request->email)->first();
            $cedula = Cliente::where('documento', $request->documento)->first();

            if($email || $cedula){
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'El email o la cedula ya existe'
                ], 200);
            }


            $primerControlador = new AuthController();

            $request->merge([
                'name'=>$request->nombre,
            ]);

            $primerControlador->register($request);

           //obtener el id del usuario creado
            $usuario = User::where('email', $request->email)->first();

            if($usuario){
                $request->merge([
                    'usuario_id' => $usuario->id
                ]);
            }




            $cliente = Cliente::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $cliente,
                'message' => 'Cliente creado correctamente'
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
            $cliente = Cliente::with(['usuario', 'tipoDocumento'])->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $cliente,
                'message' => 'Cliente obtenido correctamente'
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
                'usuario_id' => 'nullable|integer',
                'documento' => 'nullable|string|max:45',
                'nombre' => 'nullable|string|max:100',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'estado' => 'nullable|string|max:20',
                'imagen' => 'nullable|string|max:255',
                'tipo_documento_id' => 'nullable|integer',
                'email' => 'nullable',
                'password' => 'nullable',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }

           //valida si ya existe el email y la cedual en otro usuario
            $email = User::where('email', $request->email)->where('id', '!=',  $request->usuario_id)->first();
            $cedula = Cliente::where('documento', $request->documento)->where('id', '!=', $id)->first();

            if($email || $cedula){
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'El email o la cedula ya existe'
                ], 200);
            }

            //si viene datos en el password se actualiza
            $primerControlador = new AuthController();

            $request->merge([
                'name'=>$request->nombre,
            ]);

            if($request->password != '' ){
                $primerControlador->updatePassword($request,  $request->usuario_id);
            }



            $cliente = Cliente::findOrFail($id);
            $cliente->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $cliente,
                'message' => 'Cliente actualizado correctamente'
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
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Cliente eliminado correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
