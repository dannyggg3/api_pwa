<?php

namespace App\Http\Controllers;

use App\Models\Suscriptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Mail\NuevoSuscriptor;
use Illuminate\Support\Facades\Mail;

class SuscriptorController extends Controller
{
    public function index()
    {
        try {
            $suscriptores = Suscriptor::all();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $suscriptores,
                'message' => 'Suscriptores obtenidos correctamente'
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
                'email' => 'required|email|max:100',
            ]);



            //enviar email



            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }

            // si ya existe el email en la tabla suscriptores, no se crea
            $suscriptor = Suscriptor::where('email', $request->email)->first();

            if ($suscriptor) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Ya existe un suscriptor con ese email'
                ], 200);
            }





            Mail::to($request->email)->send(new NuevoSuscriptor($request->email));

            $suscriptor = Suscriptor::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $suscriptor,
                'message' => 'Suscriptor creado correctamente'
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
            $suscriptor = Suscriptor::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $suscriptor,
                'message' => 'Suscriptor obtenido correctamente'
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
                'email' => 'email|max:100',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }

            $suscriptor = Suscriptor::findOrFail($id);
            $suscriptor->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $suscriptor,
                'message' => 'Suscriptor actualizado correctamente'
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
            $suscriptor = Suscriptor::findOrFail($id);
            $suscriptor->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Suscriptor eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
