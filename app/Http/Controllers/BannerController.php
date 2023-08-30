<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;


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
                'imagen' => 'required|string|max:255',
                'estado' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $banner = Banner::create($request->all());

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
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:100',
                'imagen' => 'required|string|max:255',
                'estado' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $banner = Banner::findOrFail($id);
            $banner->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $banner,
                'message' => 'Banner actualizado correctamente'
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
