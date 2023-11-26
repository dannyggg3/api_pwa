<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Producto;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\DetallesOrden;
use App\Models\DetallesCarrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Models\FacturaElectronica;
use App\Mail\PedidoGenerado;
use App\Mail\CambioEstadoPedido;
use App\Models\DireccionesEntrega;
use App\Models\Parroquias;
use App\Models\User;
use Illuminate\Support\Facades\Mail;




class OrdenController extends Controller
{
    public function index()
    {
        try {
            $ordenes = Orden::with('cliente', 'estadoOrden', 'datosFacturacion', 'direccionEntrega', 'detallesOrden')
                ->orderBy('id', 'desc')
                ->get();

            //recorre ordenes para ver FacturaElectronica
            foreach ($ordenes as $item) {
                $factura = FacturaElectronica::where('ordenes_id', $item->id)->first();

                if ($factura) {
                    $item->factura = $factura;
                } else {
                    $item->factura = null;
                }
            }


            return response()->json([
                'correctProcess' => true,
                'data' => $ordenes,
                'message' => 'Órdenes obtenidas correctamente'
            ]);
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
                'fecha' => 'required|date',
                'estado' => 'required|string|max:20',
                'total' => 'required|numeric',
                'estado_orden_id' => 'required|integer',
                'datosfacturacion_id' => 'required|integer',
                'direccionesentrega_id' => 'required|integer',
                'subtotal12' => 'required|numeric',
                'subtotaliva0' => 'required|numeric',
                'subtotal_sin_impuestos' => 'required|numeric',
                'descuento' => 'required|numeric',
                'iva' => 'required|numeric',
                'envio' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }

            //fecha now
            $fecha = date('Y-m-d H:i:s');
            //reemplaza a la fecha que viene del front por merge

            $request->merge([
                'fecha' => $fecha
            ]);


            $orden = Orden::create($request->all());


            $carrito = DetallesCarrito::with('variante')->where('cliente_id', $request->cliente_id)->get();

            foreach ($carrito as $item) {

                $prodSinIva = ($item->variante->precio) - ($item->variante->precio * 0.12);
                $iva = $item->variante->precio * 0.12;

                $detalles = DetallesOrden::create([
                    'orden_id' => $orden->id,
                    'variante_id' => $item->variante_id,
                    'cantidad' => $item->cantidad,
                    'subtotal' => $item->cantidad * $prodSinIva,
                    'iva'   => $item->cantidad * $iva,
                ]);
            }

            $carrito = DetallesCarrito::where('cliente_id', $request->cliente_id)->delete();

            //enviar correo
            $empresa = Empresa::where('id', 1)->first();
            $usuario = Cliente::with('usuario')->where('id', $request->cliente_id)->first();
            $orden = Orden::with('cliente', 'estadoOrden', 'datosFacturacion', 'direccionEntrega', 'detallesOrden')->where('id', $orden->id)->first();

            $parroquia = Parroquias::with('ciudad')->where('id', $orden->direccionEntrega->parroquia_id)->first();

            $detalle = DetallesOrden::with('variante')->where('orden_id', $orden->id)->get();

            foreach ($detalle as $item) {
                $producto = Producto::where('id', $item->variante->producto_id)->first();
                $item->variante->producto = $producto;
            }

            $orden->detallesOrden = $detalle;
            $orden->direccionEntrega->parroquia = $parroquia;

            //usuario admin
            $usuarioAdmin = User::where('rol_id', 1)->first();


            $orden->empresa = $empresa;

            //envia el correo con la clave
            try {
                //adjunta el pdf y pasarle la data  a EnviarFactura
                $correo =  Mail::to([$usuario->usuario->email, $usuarioAdmin->email])->send(new PedidoGenerado($orden));
            } catch (\Exception $e) {

                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => $e->getMessage()
                ], 200);
            }



            return new JsonResponse([
                'correctProcess' => true,
                'data' => $orden,
                'message' => 'Orden creada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function cambiarEstado(Request $request, $id)
    {
        try {
            $orden = Orden::where('id', $id)->first();

            if (!$orden) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Orden no encontrada'
                ], 200);
            }

            $validator = Validator::make($request->all(), [
                'estado_orden_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }

            //cambiar estado de la orden
            $orden->estado_orden_id = $request->estado_orden_id;
            $orden->save();

            //enviar correo
            $empresa = Empresa::where('id', 1)->first();
            $usuario = Cliente::with('usuario')->where('id', $orden->cliente_id)->first();
            $ordenResult = Orden::with('cliente', 'estadoOrden', 'datosFacturacion', 'direccionEntrega', 'detallesOrden')->where('id', $orden->id)->first();

            $parroquia = Parroquias::with('ciudad')->where('id', $ordenResult->direccionEntrega->parroquia_id)->first();

            $detalle = DetallesOrden::with('variante')->where('orden_id', $ordenResult->id)->get();

            foreach ($detalle as $item) {
                $producto = Producto::where('id', $item->variante->producto_id)->first();
                $item->variante->producto = $producto;
            }
            $ordenResult->detallesOrden = $detalle;
            $ordenResult->direccionEntrega->parroquia = $parroquia;
            //usuario admin
            $usuarioAdmin = User::where('id', 1)->first();
            $ordenResult->empresa = $empresa;

            $asunto = 'Cambio de estado de pedido';

            if ((int)$orden->estado_orden_id == 5) {
                $asunto = 'Pedido finalizado';
            }

            if ((int)$orden->estado_orden_id == 6) {
                $asunto = 'Pedido cancelado';
            }

            if ((int)$orden->estado_orden_id == 4) {
                $asunto = 'Su orden esta en camino';
            }




            $correo =  Mail::to([$usuario->usuario->email, $usuarioAdmin->email])->send(new CambioEstadoPedido($ordenResult, $asunto));

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $ordenResult,
                'message' => 'Estado de la orden actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }


    public function reportes()
    {

        try {

            //total ventas del dia
            $totalVentasDia = Orden::where('estado_orden_id', 5)->whereDate('fecha', date('Y-m-d'))->sum('total');

            //total de ventas canceladas del dia
            $totalVentasCanceladas = Orden::where('estado_orden_id', 6)->whereDate('fecha', date('Y-m-d'))->sum('total');

            //total de pedidos del dia
            $totalPedidosDia = Orden::whereDate('fecha', date('Y-m-d'))->count();

            //total ventas de cada mes del año
            $totalVentasEnero = Orden::where('estado_orden_id', 5)->whereMonth('fecha', 1)->sum('total');
            $totalVentasFebrero = Orden::where('estado_orden_id', 5)->whereMonth('fecha', 2)->sum('total');
            $totalVentasMarzo = Orden::where('estado_orden_id', 5)->whereMonth('fecha', 3)->sum('total');
            $totalVentasAbril = Orden::where('estado_orden_id', 5)->whereMonth('fecha', 4)->sum('total');
            $totalVentasMayo = Orden::where('estado_orden_id', 5)->whereMonth('fecha', 5)->sum('total');
            $totalVentasJunio = Orden::where('estado_orden_id', 5)->whereMonth('fecha', 6)->sum('total');
            $totalVentasJulio = Orden::where('estado_orden_id', 5)->whereMonth('fecha', 7)->sum('total');
            $totalVentasAgosto = Orden::where('estado_orden_id', 5)->whereMonth('fecha', 8)->sum('total');
            $totalVentasSeptiembre = Orden::where('estado_orden_id', 5)->whereMonth('fecha', 9)->sum('total');
            $totalVentasOctubre = Orden::where('estado_orden_id', 5)->whereMonth('fecha', 10)->sum('total');
            $totalVentasNoviembre = Orden::where('estado_orden_id', 5)->whereMonth('fecha', 11)->sum('total');
            $totalVentasDiciembre = Orden::where('estado_orden_id', 5)->whereMonth('fecha', 12)->sum('total');

            $data = [
                'totalVentasDia' => $totalVentasDia,
                'totalVentasCanceladas' => $totalVentasCanceladas,
                'totalPedidosDia' => $totalPedidosDia,
                'totalVentasEnero' => $totalVentasEnero,
                'totalVentasFebrero' => $totalVentasFebrero,
                'totalVentasMarzo' => $totalVentasMarzo,
                'totalVentasAbril' => $totalVentasAbril,
                'totalVentasMayo' => $totalVentasMayo,
                'totalVentasJunio' => $totalVentasJunio,
                'totalVentasJulio' => $totalVentasJulio,
                'totalVentasAgosto' => $totalVentasAgosto,
                'totalVentasSeptiembre' => $totalVentasSeptiembre,
                'totalVentasOctubre' => $totalVentasOctubre,
                'totalVentasNoviembre' => $totalVentasNoviembre,
                'totalVentasDiciembre' => $totalVentasDiciembre,
            ];

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $data,
                'message' => 'Reportes obtenidos correctamente'
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
            $orden = Orden::with('cliente', 'estadoOrden', 'datosFacturacion', 'direccionEntrega', 'detallesOrden')->find($id);

            if (!$orden) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Orden no encontrada'
                ], 200);
            }

            return response()->json([
                'correctProcess' => true,
                'data' => $orden,
                'message' => 'Orden obtenida correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function showCliente($id)
    {

        try {
            $orden = Orden::with('cliente', 'estadoOrden', 'datosFacturacion', 'direccionEntrega', 'detallesOrden')
                ->where('cliente_id', $id)
                ->orderBy('id', 'desc')
                ->get();


            //foreach de ordenes para agregar variantes
            foreach ($orden as $item) {
                $detalles = DetallesOrden::with('variante')->where('orden_id', $item->id)->get();

                foreach ($detalles as $item2) {

                    $producto = Producto::where('id', $item2->variante->producto_id)->first();

                    $item2->variante->producto = $producto;
                }


                $item->detallesOrden = $detalles;
            }

            return response()->json([
                'correctProcess' => true,
                'data' => $orden,
                'message' => 'Ordenes obtenida correctamente'
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
            $orden = Orden::find($id);

            if (!$orden) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Orden no encontrada'
                ], 200);
            }

            $validator = Validator::make($request->all(), [
                'cliente_id' => 'required|integer',
                'fecha' => 'required|date',
                'estado' => 'required|string|max:20',
                'total' => 'required|numeric',
                'estado_orden_id' => 'required|integer',
                'datosfacturacion_id' => 'required|integer',
                'direccionesentrega_id' => 'required|integer',
                'subtotal12' => 'required|numeric',
                'subtotaliva0' => 'required|numeric',
                'subtotal_sin_impuestos' => 'required|numeric',
                'descuento' => 'required|numeric',
                'iva' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 200);
            }

            $orden->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $orden,
                'message' => 'Orden actualizada correctamente'
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
            $orden = Orden::find($id);

            if (!$orden) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Orden no encontrada'
                ], 200);
            }

            $orden->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Orden eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
