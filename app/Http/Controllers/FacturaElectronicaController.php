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
require_once app_path('Libraries/fpdf/mc_table.php');
use Fpdf\Fpdf;

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
            $empresa = Empresa::where('id', 1)->first();

            $orden = Orden::where('id', $idOrden)->first();

            if(!$orden){
                return new JsonResponse([
                    'correctProcess' => false,
                    'message' => 'Orden no encontrada'
                ], 200);
            }
            //dd($orden);



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

            //valida si existe para crear
            $facturaElectronica = FacturaElectronica::where('ordenes_id', $idOrden)->first();

            if($facturaElectronica){
                $claveacceso=$facturaElectronica->clave_acceso;
                $factura=$facturaElectronica->factura;
            }


            if(!$facturaElectronica){
               FacturaElectronica::create([
                'factura' => $factura,
                'estado' => 'CREADA',
                'numero_autorizacion' => '',
                'clave_acceso' => $claveacceso,
                'fecha' => (string)$new_date,
                'descargada' => '0',
                'ordenes_id' => $orden->id,
            ]);

            $empresa->secuencial=$empresa->secuencial+1;
            $empresa->save();
            }



            $this->generaXML($idOrden,$factura,$claveacceso);

            $fac=FacturaElectronica::where('factura',$factura)->first();


            $pdf=asset('storage/public/xml/'.$empresa->ruc.'/'.$claveacceso.'.pdf');

           // $pdf=Storage::url('public/xml/'.$empresa->ruc.'/'.$claveacceso.'.pdf');
            $fac->pdf=$pdf;


            return new JsonResponse([
                'correctProcess' => true,
                'message' => 'Factura electrónica generada correctamente',
                'data' => $fac
            ], 200);



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
            $xmlFactura .= '<tipoIdentificacionComprador>'.((int)$customers->tipo_documento_id==1?'05':'04').'</tipoIdentificacionComprador>    ';
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
                    $xmlFactura .= '<precioUnitario>'.number_format((float)$precioUnitario,2,'.','').'</precioUnitario>      ';
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

                    $xmlFactura .= '</detalle>  ';

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
                    $xmlFactura .= '</detalle>  ';
                }





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

            $path = Storage::path( 'public/xml/'.$general_settins->ruc.'/');

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

             $res= $this->firmaDocumentos($general_settins->ambiente,$general_settins->firma_electronica,$general_settins->clave, $numdocumento,$claveacceso);

             if($res=='OK'){
                   $resSD= $this->subirDocumento($general_settins->ambiente,$numdocumento,$general_settins->ruc,$claveacceso,'FAC');
                   if($resSD){
                        $this->bajarDocumento($general_settins->ambiente,$claveacceso,$general_settins->ruc,$numdocumento,'FAC');

                         $path = Storage::path( 'public/xml/'.$general_settins->ruc.'/'.$claveacceso.'.xml');

                        $ruta = Storage::path( 'public/xml/'.$general_settins->ruc.'/'.$claveacceso.'.pdf');
                        $xml = \simplexml_load_file($path);
                        $this->generaPDF($xml,$claveacceso,$ruta);
                   }
             }
        }



           public function firmaDocumentos($ambiente=1,$firma,$clave,$docEntrada,$docSalida){

             $general_settins = Empresa::where('id',1)->first();

            //dd('public/xml/'.$general_settins->ruc.'/'.$docEntrada.'_FAC.xml');

            if(Storage::exists('public/xml/'.$general_settins->ruc.'/'.$docEntrada.'_FAC.xml')){

                 $xml = simplexml_load_file(storage_path('app/public/xml/'.$general_settins->ruc.'/'.$docEntrada.'_FAC.xml'));
                 $ns = $xml->getNamespaces(true);
                 $rutaArchivo=storage_path('app/public/xml/'.$general_settins->ruc.'/'.$docEntrada.'_FAC.xml');
                 $rutaSalida=storage_path('app/public/xml/'.$general_settins->ruc.'/');


            if (isset($ns['ds'])) {
                return 'OK';
            } else {
                 if (function_exists("exec"))
                {
                    switch ($ambiente) {
                        case 1:
                            $resultado = exec("java -jar ".public_path()."/sri/sri.jar ".public_path()."/firmas/".$general_settins->ruc.'.p12'." ".$clave." ".$rutaArchivo." ".$rutaSalida." ".$docSalida.".xml");
                             exec('chmod 777 -R '.$rutaSalida);
                            //dd("java -jar ".public_path()."/sri/sri.jar ".public_path()."/firmas/".$firma." ".$clave." ".$rutaArchivo." ".$rutaSalida." ".$docSalida.".xml",$resultado);
                            break;
                    }
                    return 'OK';
                }
                else
                {
                    return 'No Esta instalado el exec';
                }
            }

            }else {
            return 'No existe el XML';
        }
    }

     public function subirDocumento($ambiente,$xml,$bdcliente,$documento,$tipo)
    {

        try {
                 $txt_factura_xml=file_get_contents(Storage::path('public/xml/'.$bdcliente.'/'.$documento.'.xml'));
                if ($ambiente == 1) {
                    $clienteSOAP = new \SoapClient('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl');
                }
                elseif ($ambiente == 2)
                {
                    $clienteSOAP = new \SoapClient('https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl');
                }
                else {
                    die('no soportado para esta empresa');
                }
                $resultado_metodo = $clienteSOAP->validarComprobante(array('xml'=>$txt_factura_xml));
                $resultado_metodo_txt= print_r($resultado_metodo,true);
                $resultado_metodo_txt= addslashes($resultado_metodo_txt);
                ##tomo variables de respuesta
                $estado_control= '';
                $codigo_control= '';
                $informacionAdicional = '';
                $mensajeDevuelto = '';
                // print_r($resultado_metodo);
                if (isset($resultado_metodo->RespuestaRecepcionComprobante->estado))
                    $estado_control=$resultado_metodo->RespuestaRecepcionComprobante->estado;
                if (isset($resultado_metodo->RespuestaRecepcionComprobante->comprobantes->comprobante->mensajes->mensaje->identificador)) {
                    $codigo_control=$resultado_metodo->RespuestaRecepcionComprobante->comprobantes->comprobante->mensajes->mensaje->identificador;
                }
                if (isset($resultado_metodo->RespuestaRecepcionComprobante->comprobantes->comprobante->mensajes->mensaje->informacionAdicional)) {
                    $informacionAdicional=$resultado_metodo->RespuestaRecepcionComprobante->comprobantes->comprobante->mensajes->mensaje->informacionAdicional;
                }
                if (isset($resultado_metodo->RespuestaRecepcionComprobante->comprobantes->comprobante->mensajes->mensaje->mensaje)) {
                    $mensajeDevuelto=$resultado_metodo->RespuestaRecepcionComprobante->comprobantes->comprobante->mensajes->mensaje->mensaje;
                }
                if ($mensajeDevuelto == 'CLAVE ACCESO REGISTRADA') {
                    $estado_control = 'RECIBIDA';
                }
                $doc = explode('_', $xml);

                $documento_update = FacturaElectronica::where('factura', $xml)->first();

                switch ($estado_control) {
                    case 'DEVUELTA':
                        //Guardamos el error
                        $documento_update->estado = 'DEVUELTA';
                        $documento_update->numero_autorizacion = str_replace("'"," ",$informacionAdicional);
                        $documento_update->save();
                        return false;
                        break;
                    case 'RECIBIDA':
                        //Preguntamos
                        $documento_update->estado = 'RECIBIDA';
                        $documento_update->numero_autorizacion='';
                        $documento_update->save();
                        return true;
                        break;
                    default:
                        //Preguntamos
                        $documento_update->estado = 'ERROR';
                        $documento_update->numero_autorizacion=str_replace("'"," ",$informacionAdicional);
                        $documento_update->save();
                        return false;
                        break;
                }
        } catch (\Exception $e) {


            return false;
        }


    }


      public function bajarDocumento($ambiente,$claveAcceso,$bdcliente,$documento,$tipo)
    {
        if ($ambiente == 1) {
            $clienteSOAP = new \SoapClient('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl');
        }
        elseif ($ambiente == 2)
        {
            $clienteSOAP = new \SoapClient('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl');
        } else {
            die('no soportado para esta empresa');
        }
        $resultado_metodo = $clienteSOAP->autorizacionComprobante(array('claveAccesoComprobante'=>$claveAcceso));
        $resultado_metodo_txt= print_r($resultado_metodo,true);
        $resultado_metodo_txt= addslashes($resultado_metodo_txt);
        // print_r($resultado_metodo);
        $estado = '';
        $comprobante = '';
        $xmlOriginal = '';
        $mensajeDevuelto = '';
        $informacionAdicional = '';
        if (isset($resultado_metodo->RespuestaAutorizacionComprobante->numeroComprobantes)) {
            if ($resultado_metodo->RespuestaAutorizacionComprobante->numeroComprobantes == 0) {
                $estado = 'NO EXISTE DOCUMENTO';
            }
        }
        if (isset($resultado_metodo->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado)) {
            $estado = $resultado_metodo->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;
        }
        if (isset($resultado_metodo->RespuestaAutorizacionComprobante->autorizaciones->autorizacion)) {
            $xmlOriginal = $resultado_metodo->RespuestaAutorizacionComprobante->autorizaciones->autorizacion;
        }
        if (isset($resultado_metodo->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante)) {
            $comprobante = $resultado_metodo->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante;
        }
        if (isset($resultado_metodo->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje->informacionAdicional)) {
            $informacionAdicional = $resultado_metodo->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje->informacionAdicional;
        }
        if (isset($resultado_metodo->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje)) {
            $mensajeDevuelto = $resultado_metodo->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes->mensaje->mensaje;
        }

         $documento_update = FacturaElectronica::where('factura', $documento)->first();
         $doc = explode('_', $documento);

        if ($estado == 'NO AUTORIZADO') {

            $documento_update->estado='NO AUTORIZADO';
             $documento_update->numero_autorizacion = str_replace("'"," ",$informacionAdicional);
            $documento_update->save();

        }
        else if (strlen($claveAcceso) == 0) {

            $documento_update->estado='NO AUTORIZADO';
            $documento_update->numero_autorizacion = str_replace("'"," ",$mensajeDevuelto);
            $documento_update->save();

        }
        else if ($estado == 'NO EXISTE DOCUMENTO') {

            $documento_update->estado='NO AUTORIZADO';
            $documento_update->numero_autorizacion = 'No Existe el numero de autorizacion en el SRI';
            $documento_update->save();
        }
        else if ($estado == 'RECHAZADA') {
            $documento_update->estado='RECHAZADA';
            $documento_update->numero_autorizacion = 'DOCUMENTO RECHAZADO';
            $documento_update->save();
        }
        else {

            $documento_update->estado='AUTORIZADO';
            $documento_update->numero_autorizacion = $claveAcceso;
            $documento_update->descargada=1;
            $documento_update->save();

            $general_settins = Empresa::where('id',1)->first();

             $path = Storage::path( 'public/xml/'.$general_settins->ruc.'/');
             $fac=$claveAcceso.".xml";

            if (Storage::exists('public/xml/'.$general_settins->ruc.'/'.$claveAcceso.'.xml'))
            {
                unlink($path.$fac);
            }

            //XML SRI FACDATA
            $file=fopen($path.$fac,"a") or die("Problemas");
            fputs($file,$comprobante);
            fclose($file);

            //XML SRI ORIGINAL
            $xmlOriginalLimpio = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><autorizacion></autorizacion>');
            $xmlOriginal = json_decode(json_encode($xmlOriginal), true);
            $this->array_to_xml($xmlOriginal,$xmlOriginalLimpio);
            $xmlOriginalLimpio->asXML($path.$claveAcceso."_AUT.xml");


            //ya esta autorizado y valido q tenga info el xml descargado

            // $this->getpdfall(array('param1'=>$bdcliente));

        }
    }

     public function array_to_xml($array, &$xml_user_info) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml_user_info->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                }else{
                    $subnode = $xml_user_info->addChild("item$key");
                    $this->array_to_xml($value, $subnode);
                }
            }else {
                if ($key == 'comprobante')
                    $xml_user_info->addChild("$key","<![CDATA[$value]]>");
                else
                    $xml_user_info->addChild("$key","$value");
            }
        }
    }

    public function generaPdf($document, $claveAcceso, $ruta){


        $empresa = Empresa::where('id', 1)->first();
        $pdf = new \PDF_MC_Table();
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);

        if ($document->infoFactura->obligadoContabilidad == 'SI') {

            $contabilidad = "SI";
        } else {
            $contabilidad = "NO";
        }

        $pdf->SetXY(20, 0+10);
        //$pdf->image('uploads/Logo.jpg', null, null, 80, 30);

        //public path
        $path = public_path('logo/logo_header.jpeg');

        $pdf->image($path, null, null, 60, 15);

        $pdf->SetXY(110+2, 10+10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(100, 10, "RUC: " . $document->infoTributaria->ruc, 0, 'J', true);
        $pdf->SetXY(110+2, 15+10);
        $pdf->MultiCell(100, 10, "Factura Nro: " . $document->infoTributaria->estab . $document->infoTributaria->ptoEmi . $document->infoTributaria->secuencial, 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(110+2, 20+10);
        $pdf->MultiCell(100, 10, 'Nro Autorizacion: ', 0);
        $pdf->SetXY(110+2, 25+10);
        $pdf->MultiCell(100, 10, $claveAcceso, 0);
        $pdf->SetXY(110+2, 30+10);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->MultiCell(100, 10, 'Ambiente: ' . $ambiente, 0);
        $pdf->SetXY(110+2, 35+10);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->MultiCell(100, 10, 'Emision: ' . $emision, 0);

        $pdf->Rect(10,20+10,100,17);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 20+10);
        $pdf->MultiCell(100, 5, $document->infoTributaria->razonSocial, 0,0,'C');
        $pdf->SetXY(10, 25+10);
        $pdf->MultiCell(20, 3,"Dir. Matriz:", 0);
        $pdf->SetXY(10, 30+10);
        $pdf->MultiCell(60, 3, "OBLIGADO A LLEVAR CONTABILIDAD: ", 0);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(30, 25+10);
        $pdf->MultiCell(80, 3, $document->infoTributaria->dirMatriz, 0);
        $pdf->SetXY(70, 30+10);
        $pdf->MultiCell(20, 3, $contabilidad, 0);

        //Codigo de barras
        $pdf->SetXY(112, 45+10);
        //$this->generarCodigoBarras($claveAcceso);

        $path = public_path('logo/codigo_mod.png');
        $pdf->image($path, null, null, 90, 20);
        $pdf->SetXY(110, 60+10);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(100, 10, $claveAcceso, 0, 0, "C", true);

        //informacion del cliente
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->Rect(10,38+10,100,36);
        $pdf->SetXY(10, 38+10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(100, 5, "INFORMACION DEL CLIENTE", 0,'C');


        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 45+10);
        $pdf->Cell(30, 5, "RUC/CI:", 0,'L');
        $pdf->SetXY(10, 50+10);
        $pdf->MultiCell(30, 5,"Razon S./Nombre:", 0,'L');
        $pdf->SetXY(10, 60+10);
        $pdf->MultiCell(30, 5, "Direccion:", 0);
        $pdf->SetXY(10, 70+10);
        $pdf->MultiCell(30, 5, "Fecha Emision:", 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(40, 45+10);
        $pdf->MultiCell(70, 5, $document->infoFactura->identificacionComprador, 0, 'L');
        $pdf->SetXY(40, 50+10);
        $pdf->MultiCell(70, 5,$document->infoFactura->razonSocialComprador, 0,'L');
        $pdf->SetXY(40, 60+10);
        $pdf->MultiCell(70, 5, $document->infoFactura->direccionComprador, 0,'L');
        $pdf->SetXY(40, 70+10);
        $pdf->MultiCell(70, 5, $document->infoFactura->fechaEmision, 0,'L');

        $ejeX = 65;
        $Y=$ejeX+10;
        //detalle de la factura
        $pdf->SetXY(10, $Y+10);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(30, 10, "Codigo", 1, 0, "C", true);
        $pdf->Cell(85, 10, "Descripcion", 1, 0, "C", true);
        $pdf->Cell(15, 10, "Cantidad", 1, 0, "C", true);
        $pdf->Cell(20, 10, "Precio", 1, 0, "C", true);
        $pdf->Cell(20, 10, "% Desc", 1, 0, "C", true);
        $pdf->Cell(20, 10, "Total", 1, 0, "C", true);

        $Y=$Y+10;
        $pdf->SetWidths(array(30,85,15,20,20,20));
        $pdf->SetXY(10, $Y+10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        foreach ($document->detalles->detalle as $a => $b) {
            $pdf->Row(array($b->codigoPrincipal,$b->descripcion,$b->cantidad, number_format(floatval($b->precioUnitario), 2),$b->descuento,$b->precioTotalSinImpuesto));
        }

        //Total de la factura
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $iva = 0;
        $ice = 0;
        $IRBPNR = 0;
        $subtotal12 = 0;
        $subtotal0 = 0;
        $subtotal_no_impuesto = 0;
        $subtotal_no_iva = 0;

        foreach ($document->infoFactura->totalConImpuestos->totalImpuesto as $a => $b) {
            if ($b->codigo == 2) {
                $iva = $b->valor;
                if ($b->codigoPorcentaje == 0) {
                    $subtotal0 = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 2) {
                    $subtotal12 = $b->baseImponible;
                    //    $iva = $b->valor;
                }
                if ($b->codigoPorcentaje == 6) {
                    $subtotal_no_impuesto = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 7) {
                    $subtotal_no_iva = $b->baseImponible;
                }
            }
            if ($b->codigo == 3) {
                $ice = $b->valor;
            }
            if ($b->codigo == 5) {
                $IRBPNR = $b->valor;
            }
        }

        $Y2=$pdf->GetY()+5;
        $Y=$pdf->GetY()+5;


$pdf->SetDrawColor(0, 0, 0); // RGB: Negro
        $pdf->SetXY(150, $Y);
        $pdf->Cell(25, 5, "Subtotal 12%: ", 1, 0, "L", true);
        $pdf->SetXY(175, $Y);
        $pdf->Cell(25, 5,$subtotal12, 0, 0, "R", true);
        $Y=$Y+5;
        $pdf->SetXY(150, $Y);
        $pdf->Cell(25, 5, "SubTotal 0%: ", 0, 0, "L", true);
        $pdf->SetXY(175, $Y);
        $pdf->Cell(25, 5, $subtotal0, 0, 0, "R", true);
        $Y=$Y+5;
        $pdf->SetXY(150, $Y);
        $pdf->Cell(25, 5, "SubTotal no sujeto de IVA: ", 0, 0, "L", true);
        $pdf->SetXY(175, $Y);
        $pdf->Cell(25, 5, $subtotal_no_impuesto, 0, 0, "R", true);
        $Y=$Y+5;
        $pdf->SetXY(150, $Y);
        $pdf->Cell(25, 5, "SubTotal exento de IVA: ", 0, 0, "L", true);
        $pdf->SetXY(175, $Y);
        $pdf->Cell(25, 5, $subtotal_no_iva, 0, 0, "R", true);
        $Y=$Y+5;
        $pdf->SetXY(150, $Y);
        $pdf->Cell(25, 5, "SubTotal sin Impuestos: ", 0, 0, "L", true);
        $pdf->SetXY(175, $Y);
        $pdf->Cell(25, 5, $document->infoFactura->totalDescuento, 0, 0, "R", true);
        $Y=$Y+5;
        $pdf->SetXY(150, $Y);
        $pdf->Cell(25, 5, "Descuento: ", 0, 0, "L", true);
        $pdf->SetXY(175, $Y);
        $pdf->Cell(25, 5, $document->infoFactura->totalDescuento, 0, 0, "R", true);
        $Y=$Y+5;
        $pdf->SetXY(150, $Y);
        $pdf->Cell(25, 5, "IVA 12%: ", 0, 0, "L");
        $pdf->SetXY(175, $Y);
        $pdf->Cell(25, 5, $iva, 0, 0, "R");
        $Y=$Y+5;
        $pdf->SetXY(150, $Y);
        $pdf->Cell(25, 5, "ICE: ", 0, 0, "L");
        $pdf->SetXY(175, $Y);
        $pdf->Cell(25, 5, $ice, 0, 0, "R");
        $Y=$Y+5;
        $pdf->SetXY(150, $Y);
        $pdf->Cell(25,5, "IRBPNR: ", 0, 0, "L");
        $pdf->SetXY(175, $Y);
        $pdf->Cell(25, 5, $IRBPNR, 0, 0, "R");
        $Y=$Y+5;
        $pdf->SetXY(150, $Y);
        $pdf->Cell(25, 5, "Valor Total: ", 0, 0, "L");
        $pdf->SetXY(175, $Y);
        $pdf->Cell(25, 5, $document->infoFactura->importeTotal, 0, 0, "R");





        $infoAdicional = "";
        $correo = "";

        foreach ($document->infoAdicional->campoAdicional as $a) {
            foreach ($a->attributes() as $b) {
                if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                    $correo = $a;
                    $infoAdicional .= $b . ': ' . $a . "\n";
                } else {
                    $infoAdicional .= $b . ': ' . $a . "\n";
                }
            }
        }

        $pdf->SetXY(10, $Y2+10);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->MultiCell(105, 5, "Informacion Adicional", 0);
        $Y2=$Y2+5;
        $pdf->SetXY(10, $Y2+10);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(105, 5, "" . $infoAdicional . "", 1);

        $pdf->SetFont('Arial', 'B', 8);
        $Y2=$pdf->GetY()+5;
        $pdf->SetXY(10, $Y2+10);
        $pdf->SetFillColor(215, 215, 215);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(60,4,"Forma de Pago",1,0,'C',true);
        $pdf->Cell(20,4,"Valor",1,0,'C',1);
        $pdf->Cell(10,4,"Plazo",1,0,'C',1);
        $pdf->Cell(15,4,"Tiempo",1,0,'C',1);
        $Y2=$Y2+4;
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetFillColor(255, 255, 255);


        foreach ($document->infoFactura->pagos->pago as $e => $f) {
            if ($f->formaPago == '01') {
                $pdf->SetXY(10, $Y2+10);
                $pdf->Cell(60, 4, 'Sin utilizacion del sistema financiero', 1, 0, "C", true);
                $pdf->Cell(20, 4, $f->total, 1, 0, "C", true);
                $pdf->Cell(10, 4, $f->plazo, 1, 0, "C", true);
                $pdf->Cell(15, 4, $f->unidadTiempo, 1, 0, "C", true);
                $Y2=$Y2+4;
            }
            if ($f->formaPago == '15') {
                $pdf->SetXY(10, $Y2+10);
                $pdf->Cell(60, 54, 'Compensacion de deudas', 1, 0, "L", true);
                $pdf->Cell(20, 4, $f->total, 1, 0, "C", true);
                $pdf->Cell(10, 4, $f->plazo, 1, 0, "C", true);
                $pdf->Cell(15, 4, $f->unidadTiempo, 1, 0, "C", true);
                $Y2=$Y2+4;
            }
            if ($f->formaPago == '16') {
                $pdf->SetXY(10, $Y2+10);
                $pdf->Cell(60, 4, 'Tarjeta debito', 1, 0, "L", true);
                $pdf->Cell(20, 4, $f->total, 1, 0, "C", true);
                $pdf->Cell(10, 4, $f->plazo, 1, 0, "C", true);
                $pdf->Cell(15, 4, $f->unidadTiempo, 1, 0, "C", true);
                $Y2=$Y2+4;
            }
            if ($f->formaPago == '17') {
                $pdf->SetXY(10, $Y2+10);
                $pdf->Cell(60, 4, 'Dinero Electronico', 1, 0, "L", true);
                $pdf->Cell(20, 4, $f->total, 1, 0, "C", true);
                $pdf->Cell(10, 4, $f->plazo, 1, 0, "C", true);
                $pdf->Cell(15, 4, $f->unidadTiempo, 1, 0, "C", true);
                $Y2=$Y2+4;
            }
            if ($f->formaPago == '18') {
                $pdf->SetXY(10, $Y2+10);
                $pdf->Cell(60, 4, 'Tarjeta Prepago', 1, 0, "L", true);
                $pdf->Cell(20, 4, $f->total, 1, 0, "C", true);
                $pdf->Cell(10, 4, $f->plazo, 1, 0, "C", true);
                $pdf->Cell(15, 4,  $f->unidadTiempo, 1, 0, "C", true);
                $Y2=$Y2+4;
            }
            if ($f->formaPago == '19') {
                $pdf->SetXY(10, $Y2+10);
                $pdf->Cell(60, 4, 'Tarjeta de credito', 1, 0, "L", true);
                $pdf->Cell(20, 4, 'Total: ' . $f->total, 1, 0, "C", true);
                $pdf->Cell(10, 4, 'Plazo: ' . $f->plazo, 1, 0, "C", true);
                $pdf->Cell(15, 4, 'Unidad de tiempo: ' . $f->unidadTiempo, 1, 0, "C", true);
                $Y2=$Y2+4;
            }
            if ($f->formaPago == '20') {
                $pdf->SetXY(10, $Y2+10);
                $pdf->Cell(60, 4, 'Otros con utilizacion del sistema financiero', 1, 0, "L", true);
                $pdf->Cell(20, 4, $f->total, 1, 0, "C", true);
                $pdf->Cell(10, 4, $f->plazo, 1, 0, "C", true);
                $pdf->Cell(15, 4, $f->unidadTiempo, 1, 0, "C", true);
                $Y2=$Y2+4;
            }
            if ($f->formaPago == '21') {
                $pdf->SetXY(10, $Y2+10);
                $pdf->Cell(60, 4, 'Endoso de titulos', 1, 0, "L", true);
                $pdf->Cell(20, 4, $f->total, 1, 0, "C", true);
                $pdf->Cell(10, 4, $f->plazo, 1, 0, "C", true);
                $pdf->Cell(15, 4, $f->unidadTiempo, 1, 0, "C", true);
                $Y2=$Y2+4;
            }
        }

        $pdf->Output($ruta, 'F');
		$pos=strrpos($ruta,"/");
		$ruta=substr($ruta,0,$pos+1);
		// $pdf->Output($ruta.$claveAcceso.'.pdf', 'D');
        // echo 'ingresa mail';
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
