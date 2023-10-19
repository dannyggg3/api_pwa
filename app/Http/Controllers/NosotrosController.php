<?php

namespace App\Http\Controllers;

use App\Models\Nosotros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class NosotrosController extends Controller
{
   //CRUD
   public function index()
   {
       try {
           // Obtener todas las variantes con su producto relacionado
           $variantes = Nosotros::where('id', 1)->first();

           return new JsonResponse([
               'correctProcess' => true,
               'data' => $variantes,
               'message' => 'nosotros obtenidas correctamente'
           ]);
       } catch (\Exception $e) {
           return new JsonResponse([
               'correctProcess' => false,
               'message' => $e->getMessage()
           ], 200);
       }
   }

   //funcion para actualizar
   public function update(Request $request, $id)
   {
       try {
           $validator = Validator::make($request->all(), [
               'mision' => 'required',
               'vision' => 'required',
               'historia' => 'required',
           ]);

           if ($validator->fails()) {
               return new JsonResponse([
                   'correctProcess' => false,
                   'message' => 'Error de validación',
                   'errors' => $validator->errors()
               ], 200);
           }

           $nosotros = Nosotros::where('id', $id)->first();

           if (!$nosotros) {
               return new JsonResponse([
                   'correctProcess' => false,
                   'message' => 'No se encontró la nosotros'
               ], 200);
           }

           $nosotros->mision = $request->mision;
           $nosotros->vision = $request->vision;
           $nosotros->historia = $request->historia;

           $nosotros->save();

           return new JsonResponse([
               'correctProcess' => true,
               'message' => 'nosotros actualizada correctamente',
                'data' => $nosotros
           ],200);

       } catch (\Exception $e) {
           return new JsonResponse([
               'correctProcess' => false,
               'message' => $e->getMessage()
           ], 200);
       }
   }

}
