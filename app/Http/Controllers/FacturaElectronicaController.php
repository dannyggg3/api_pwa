<?php

namespace App\Http\Controllers;

use App\Models\FacturaElectronica;
use App\Models\Orden;
use App\Models\User;
use App\Models\Variante;
use App\Models\DetallesOrden;
use App\Models\DatosFacturacion;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FacturaElectronicaController extends Controller
{
   public function index()
    {
        try {
            $facturasElectronicas = FacturaElectronica::with('orden')->get();

            $response= new JsonResponse([
                'correctProcess' => true,
                'data' => $facturasElectronicas,
                'message' => 'Facturas electrónicas obtenidas correctamente'
            ]);

             $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            return $response;

        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function generadoc($idOrden){

        try {

            $orden = Orden::where('id', $idOrden)->first();

            if(!$orden){
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Orden no encontrada'
                ], 200);
            }
            //dd($orden);

            $empresa = Empresa::where('id', 1)->first();

            $fechaEmision=$orden->fecha;


            $timestamp = strtotime($fechaEmision);

                // Creating new date format from that timestamp
            $new_date = date("d-m-Y", $timestamp);

            // ambiente
            // establecimiento
            // punto_emision
            // secuencial
            // archivop12
            // usuario
            // clave
            $newsecuencial = str_pad($empresa->secuencial, 9, "0", STR_PAD_LEFT);
            $factura=$empresa->establecimiento.'-'.$empresa->punto_emision.'-'. $newsecuencial;

            $claveacceso = Utils::claveAcceso(array(
                        'fechaEmision'=>(string)$new_date,
                        'codDoc'=>'01',
                        'ruc'=>$empresa->ruc,
                        'ambiente'=>(int)$empresa->ambiente,
                        'estab'=>$empresa->establecimiento,
                        'ptoEmi'=>$empresa->punto_emision,
                        'secuencial'=>$newsecuencial
                    ));

            $this->generaXML($idOrden,$factura,$claveacceso);



           FacturaElectronica::create([
                'factura' => $factura,
                'estado' => 'CREADA',
                'numero_autorizacion' => '',
                'clave_acceso' => $claveacceso,
                'fecha' => (string)$new_date,
                'descargada' => '0',
                'ordenes_id' => $orden->id,
            ]);


        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 200);
        }

    }


         public function generaXML($idOrden,$numdocumento,$claveacceso){


        $sales=Orden::where('id',$idOrden)->first();
        //$select_secuencial = Warehouse::where('id',$sales->warehouse_id)->first();
        $general_settins = Empresa::where('id',1)->first();
        $product_sales=DetallesOrden::where('orden_id',$idOrden)->get();
        $customers=DatosFacturacion::where('id', $sales->datosfacturacion_id)->first();
        $cliente=Cliente::where('id', $sales->cliente_id)->first();
        $usuario=User::where('id',  $cliente->usuario_id)->first();
        //$payments=Payment::where('sale_id',$sales->id)->first();



            $xmlFactura = '<?xml version="1.0" encoding="UTF-8"?><factura id="comprobante" version="1.1.0">';
            $xmlFactura .= '<infoTributaria>    ';
            //$xmlFactura .= '<ambiente>1</ambiente>    ';
            $xmlFactura .= '<ambiente>'.$general_settins->ambiente.'</ambiente>    ';
            //$xmlFactura .= '<ambiente>1</ambiente>    ';
            $xmlFactura .= '<tipoEmision>1</tipoEmision>    ';
            $xmlFactura .= '<razonSocial>'.$general_settins->razon_social.'</razonSocial>    ';
            $xmlFactura .= '<nombreComercial>'.$general_settins->razon_social.'</nombreComercial>    ';
            $xmlFactura .= '<ruc>'.$general_settins->ruc.'</ruc>    ';
            $xmlFactura .= '<claveAcceso>'.$claveacceso.'</claveAcceso>    ';
            $xmlFactura .= '<codDoc>01</codDoc>    ';
            $xmlFactura .= '<estab>'.substr($numdocumento, 0,3).'</estab>    ';
            $xmlFactura .= '<ptoEmi>'.substr($numdocumento, 4,3).'</ptoEmi>    ';
            $xmlFactura .= '<secuencial>'.substr($numdocumento, 8,9).'</secuencial>    ';
            $xmlFactura .= '<dirMatriz>'.$general_settins->direccion.'</dirMatriz>  ';

            // if($general_settins->agente_retencion>0){
            //     $xmlFactura .= '<agenteRetencion>'.$general_settins->agente_retencion.'</agenteRetencion>';
            // }
            if($general_settins->regimen=='Rimpe'){
                $xmlFactura .= '<contribuyenteRimpe>CONTRIBUYENTE RÉGIMEN RIMPE</contribuyenteRimpe>';
            }





            // $xmlFactura .= $microempresa;
            // $xmlFactura .= $agenteretencion;
            $xmlFactura .= '</infoTributaria>  ';
            $xmlFactura .= '<infoFactura>    ';
            $date=date_create($sales->fecha);
            $xmlFactura .= '<fechaEmision>'.date_format($date,"d/m/Y").'</fechaEmision>    ';
            $xmlFactura .= '<dirEstablecimiento>'.$general_settins->direccion.'</dirEstablecimiento>    ';
            $obligaContabilidad = (int)$general_settins->obligado_contabilidad===1?'SI':'NO';
            $xmlFactura .= '<obligadoContabilidad>'.$obligaContabilidad.'</obligadoContabilidad>    ';
            $xmlFactura .= '<tipoIdentificacionComprador>'.(int)$customers->tipo_documento_id==1?'04':'05'.'</tipoIdentificacionComprador>    ';
            $xmlFactura .= '<razonSocialComprador>'.$customers->nombre.'</razonSocialComprador>    ';
            $xmlFactura .= '<identificacionComprador>'.$customers->ruc_cedula.'</identificacionComprador>    ';
            $xmlFactura .= '<direccionComprador>'.$customers->direccion.'</direccionComprador>    ';
            $xmlFactura .= '<totalSinImpuestos>'.number_format((float)$sales->total/1.12,2,'.','').'</totalSinImpuestos>    ';
            $xmlFactura .= '<totalDescuento>'.$sales->descuento.'</totalDescuento>    ';
            $xmlFactura .= '<totalConImpuestos>      ';





            //redondea los datos a 2 decimales

            $baseImponible= number_format((float)$sales->total / 1.12, 2, '.', '');
            $iva = number_format((float)$baseImponible * 0.12, 2, '.', '');



                 $xmlFactura .= '<totalImpuesto>        ';
                 $xmlFactura .= '<codigo>2</codigo>        ';
                 $xmlFactura .= '<codigoPorcentaje>2</codigoPorcentaje>        ';
                 $xmlFactura .= '<baseImponible>'.number_format((float)$baseImponible,2,'.','').'</baseImponible>        ';
                 $xmlFactura .= '<tarifa>12</tarifa>        ';
                 $xmlFactura .= '<valor>'.number_format((float)$iva, 2, '.', '').'</valor>      ';
                 $xmlFactura .= '</totalImpuesto>    ';


            $xmlFactura .= '</totalConImpuestos>    ';
            $xmlFactura .= '<importeTotal>'.number_format((float)$sales->total, 2, '.', '').'</importeTotal>    ';
            $xmlFactura .= '<moneda>DOLAR</moneda>    ';
            $xmlFactura .= '<pagos>      ';

            $xmlFactura .= '<pago>        ';
            $xmlFactura .= '<formaPago>01</formaPago>        ';
            $xmlFactura .= '<total>'.number_format((float)$sales->total, 2, '.', '').'</total>        ';
            $xmlFactura .= '<plazo>1</plazo>        ';
            $xmlFactura .= '<unidadTiempo>días</unidadTiempo>      ';
            $xmlFactura .= '</pago>    ';

            $xmlFactura .= '</pagos>  ';
            $xmlFactura .= '</infoFactura>  ';
            $xmlFactura .= '<detalles>    ';


            foreach ($product_sales as $key=>$product_sale){
                    $porcentajeMulti = 0;
                    $tarifa = '0.00';

                    $product_sale->tax_rate=2;

                    switch ((int)$product_sale->tax_rate) {
                        case '0':
                            $porcentajeMulti = 0.00;
                            $tarifa = '0.00';
                            break;
                        case '2':
                            $porcentajeMulti = 0.12;
                            $tarifa = '12.00';
                            break;
                        case '6':
                            $porcentajeMulti = 0.00;
                            $tarifa = '0.00';
                            break;
                        case '7':
                            $porcentajeMulti = 0.00;
                            $tarifa = '0.00';
                            break;
                        // nuevo facdata

                        case 0:
                            $porcentajeMulti = 0.00;
                            $tarifa = '0.00';
                            break;

                        case 12:
                            $porcentajeMulti = 0.12;
                            $tarifa = '12.00';
                            break;
                    }

                    $products=Variante::with('producto')->where('id',$product_sale->variante_id)->first();

                    $precioUnitario=(float)$products->precio /  1.12;
                    // $precioUniRedon=round($precioUni,2);
                    //$precioUnitario=number_format($precioUniRedon,2,'.','');
                    $base=(float)$precioUnitario * $product_sale->cantidad;



                    $xmlFactura .= '<detalle>      ';
                    $xmlFactura .= '<codigoPrincipal>'.$products->id.'</codigoPrincipal>      ';
                    $xmlFactura .= '<descripcion>'.preg_replace("[\n|\r|\n\r]", "", $products->producto->nombre) .'</descripcion>      ';
                    $xmlFactura .= '<cantidad>'.$product_sale->cantidad.'</cantidad>      ';
                    $xmlFactura .= '<precioUnitario>'.((float)$precioUnitario).'</precioUnitario>      ';
                    $xmlFactura .= '<descuento>0.00</descuento>      ';
                    $xmlFactura .= '<precioTotalSinImpuesto>'.number_format((float)($base), 2, '.', '').'</precioTotalSinImpuesto>      ';
                    $xmlFactura .= '<impuestos>        ';



                        $xmlFactura .= '<impuesto>          ';
                        $xmlFactura .= '<codigo>2</codigo>          ';
                        $xmlFactura .= '<codigoPorcentaje>2</codigoPorcentaje>          ';
                        $xmlFactura .= '<tarifa>12</tarifa>          ';
                        $xmlFactura .= '<baseImponible>'.number_format((float)($base), 2, '.', '').'</baseImponible>          ';
                        $xmlFactura .= '<valor>'.number_format((float)round((float)$base*0.12,2), 2, '.', '').'</valor>        ';
                        $xmlFactura .= '</impuesto>      ';

                    $xmlFactura .= '</impuestos>    ';

                }

                if($sales->envio>0){

                   $precioUni=(float)$sales->envio /  1.12;
                    $precioUniRedon=round($precioUni,2);

                    $precioUnitario=number_format($precioUniRedon,2,'.','');

                    $baseProd=(float)$precioUnitario * 1;
                    $baseProdRoud=round($baseProd,2);
                    $base=number_format($baseProdRoud,2,'.','');

                    $xmlFactura .= '<detalle>      ';
                    $xmlFactura .= '<codigoPrincipal>000215</codigoPrincipal>      ';
                    $xmlFactura .= '<descripcion>'.preg_replace("[\n|\r|\n\r]", "",'ENVIO SERVIENTREGA') .'</descripcion>      ';
                    $xmlFactura .= '<cantidad>1</cantidad>      ';
                    $xmlFactura .= '<precioUnitario>'.((float)$precioUnitario).'</precioUnitario>      ';
                    $xmlFactura .= '<descuento>0.00</descuento>      ';
                    $xmlFactura .= '<precioTotalSinImpuesto>'.number_format((float)($base), 2, '.', '').'</precioTotalSinImpuesto>      ';
                    $xmlFactura .= '<impuestos>        ';



                        $xmlFactura .= '<impuesto>          ';
                        $xmlFactura .= '<codigo>2</codigo>          ';
                        $xmlFactura .= '<codigoPorcentaje>2</codigoPorcentaje>          ';
                        $xmlFactura .= '<tarifa>12</tarifa>          ';
                        $xmlFactura .= '<baseImponible>'.number_format((float)($base), 2, '.', '').'</baseImponible>          ';
                        $xmlFactura .= '<valor>'.number_format((float)round((float)$base*0.12,2), 2, '.', '').'</valor>        ';
                        $xmlFactura .= '</impuesto>      ';

                    $xmlFactura .= '</impuestos>    ';
                }


                 $xmlFactura .= '</detalle>  ';


            $xmlFactura .= '</detalles>  ';
            $xmlFactura .= '<infoAdicional>    ';

            //if($customers->email!=''){
                $xmlFactura .= '<campoAdicional nombre="emailCliente">'.$usuario->email.'</campoAdicional>    ';
            // }else{
            //     $xmlFactura .= '<campoAdicional nombre="emailCliente">sincorreo@gmail.com</campoAdicional>    ';
            // }



            // if($payments->payment_note!=''){
            //     $xmlFactura .= '<campoAdicional nombre="NotaPago">'.$payments->payment_note.'</campoAdicional>    ';
            // }

            // if($payments->payment_type_2!='' && $payments->payment_type_2!=null){
            //     if($payments->payment_note_2!=''){
            //         $xmlFactura .= '<campoAdicional nombre="SegundaNotaPago">'.$payments->payment_note_2.'</campoAdicional>    ';
            //     }
            // }

            // if($sales->sale_note!=''){
            //     $xmlFactura .= '<campoAdicional nombre="NotaVenta">'.$sales->sale_note.'</campoAdicional>    ';
            // }

            /*
            //Esta nota es solo para el cliente
            if($sales->staff_note!=''){
                $xmlFactura .= '<campoAdicional nombre="Nota">'.$sales->staff_note.'</campoAdicional>    ';
            }*/



            $xmlFactura .= '</infoAdicional></factura>';

            $factura = $numdocumento."_FAC.xml";

            $path = Storage::path( 'xml/'.$general_settins->ruc.'/');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            //Eliminar si Existe
            if (file_exists($path.$factura))
            {
                unlink($path.$factura);
            }

            $file=fopen($path.$factura,"a") or die("Problemas");
            fputs($file,$xmlFactura);
            fclose($file);


        }



    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'factura' => 'nullable|string|max:45',
                'estado' => 'nullable|string|max:45',
                'numero_autorizacion' => 'nullable|string|max:100',
                'clave_acceso' => 'nullable|string|max:100',
                'fecha' => 'nullable|string|max:45',
                'descargada' => 'nullable|string|max:45',
                'ordenes_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $facturaElectronica = FacturaElectronica::create($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $facturaElectronica,
                'message' => 'Factura electrónica creada correctamente'
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
            $facturaElectronica = FacturaElectronica::with('orden')->findOrFail($id);

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $facturaElectronica,
                'message' => 'Factura electrónica obtenida correctamente'
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
                'factura' => 'nullable|string|max:45',
                'estado' => 'nullable|string|max:45',
                'numero_autorizacion' => 'nullable|string|max:100',
                'clave_acceso' => 'nullable|string|max:100',
                'fecha' => 'nullable|string|max:45',
                'descargada' => 'nullable|string|max:45',
                'ordenes_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $facturaElectronica = FacturaElectronica::findOrFail($id);
            $facturaElectronica->update($request->all());

            return new JsonResponse([
                'correctProcess' => true,
                'data' => $facturaElectronica,
                'message' => 'Factura electrónica actualizada correctamente'
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
            $facturaElectronica = FacturaElectronica::findOrFail($id);
            $facturaElectronica->delete();

            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Factura electrónica eliminada correctamente'
            ], 204);
        } catch (\Exception $e) {
            return new JsonResponse([
                'correctProcess' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
