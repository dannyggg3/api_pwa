<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
  public function index()
    {
        try {
            $empresas = Empresa::where('id',1)->first();

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $empresas,
                'message' => 'Datos obtenidos correctamente'
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
                'ruc' => 'nullable|string|max:45',
                'razon_social' => 'nullable|string|max:255',
                'direccion' => 'nullable|string|max:255',
                'obligado_contabilidad' => 'nullable|boolean',
                'regimen' => 'nullable|string|max:45',
                'logo' => 'nullable|string|max:45',
                'ambiente' => 'nullable|string|max:45',
                'establecimiento' => 'nullable|string|max:45',
                'punto_emision' => 'nullable|string|max:45',
                'secuencial' => 'nullable|string|max:45',
                'archivop12' => 'nullable|string|max:50',
                'usuario' => 'nullable|string|max:50',
                'clave' => 'nullable|string|max:50',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $empresa = Empresa::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $empresa,
                'message' => 'Empresa creada correctamente'
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
            $empresa = Empresa::findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $empresa,
                'message' => 'Empresa obtenida correctamente'
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
            $validator = Validator::make($request->all(), [
                'ruc' => 'nullable|string|max:45',
                'razon_social' => 'nullable|string|max:255',
                'direccion' => 'nullable|string|max:255',
                'obligado_contabilidad' => 'nullable|boolean',
                'regimen' => 'nullable|string|max:45',
                'logo' => 'nullable|string|max:45',
                'ambiente' => 'nullable|string|max:45',
                'establecimiento' => 'nullable|string|max:45',
                'punto_emision' => 'nullable|string|max:45',
                'secuencial' => 'nullable|string|max:45',
                'archivop12' => 'nullable|string|max:200',
                'usuario' => 'nullable|string|max:50',
                'clave' => 'nullable|string|max:50',
            ]);

             $empresa = Empresa::where('id',1)->first();



             //guarda un archivo .p12 en el stoeage y obtiene la url


                if ($request->hasFile('filep12')) {
                    // Obtiene el archivo subido
                    $archivo = $request->file('filep12');

                    // Nombre de archivo deseado con extensión .p12
                    $nombreArchivo = $empresa->ruc.'.p12';

                    // Sube el archivo al servidor con el nombre deseado
                    $archivoPath = $archivo->storeAs('firma', $nombreArchivo, 'public');

                    // Genera la URL completa del archivo
                    $fileUrl = Storage::url($archivoPath);
                } else {
                    // Si no se cargó una nueva imagen, conserva la imagen existente.
                    $fileUrl = $empresa->archivop12;
                }

            $request->merge([
                'archivop12' => $fileUrl
            ]);




            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $empresa = Empresa::findOrFail($id);
            $empresa->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $empresa,
                'message' => 'Empresa actualizada correctamente'
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
            $empresa = Empresa::findOrFail($id);
            $empresa->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Empresa eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
