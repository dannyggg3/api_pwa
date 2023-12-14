<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Mail\ContactoMail;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function index()
    {
        try {
            $contactos = Contacto::all();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $contactos,
                'message' => 'Contactos obtenidos correctamente'
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
                'nombre' => 'required|string|max:100',
                'apellido' => 'required|string|max:100',
                'email' => 'required|email|max:100',
                'telefono' => 'required|string|max:100',
                'mensaje' => 'required|string',
                // 'fecha' => 'required|datetime', // Opcional, dependiendo de si deseas manejarlo en el backend
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }

            $contacto = Contacto::create($request->all());


            //get user rol 1
            $user = User::where('rol_id', 1)->first();

            //send email
            Mail::to($user->email)->send(new ContactoMail($contacto));




            return new JsonResponse([
                'correctProcess' => true,
                'data' => $contacto,
                'message' => 'Contacto creado correctamente'
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
            $contacto = Contacto::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $contacto,
                'message' => 'Contacto obtenido correctamente'
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
                'nombre' => 'string|max:100',
                'apellido' => 'string|max:100',
                'email' => 'email|max:100',
                'telefono' => 'string|max:100',
                'mensaje' => 'string',
                // 'fecha' => 'datetime', // Opcional, dependiendo de si deseas manejarlo en el backend
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }

            $contacto = Contacto::findOrFail($id);
            $contacto->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $contacto,
                'message' => 'Contacto actualizado correctamente'
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
            $contacto = Contacto::findOrFail($id);
            $contacto->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Contacto eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
