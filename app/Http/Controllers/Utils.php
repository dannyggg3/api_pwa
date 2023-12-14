<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class Utils
{


    /**
     * Retorna el Método de Pago
     */
    public static function getPaymentType($payment)
    {
        $paying_method = 'Efectivo';
        switch ($payment) {
            case "01":
                $paying_method = "SIN UTILIZACIÓN DEL SISTEMA FINANCIERO";
                break;
            case "15":
                $paying_method = "COMPENSACIÓN DE DEUDAS";
                break;
            case "16":
                $paying_method = "TARJETA DE DÉBITO";
                break;
            case "17":
                $paying_method = "DINERO ELECTRÓNICO";
                break;
            case "18":
                $paying_method = "TARJETA PREPAGO";
                break;
            case "19":
                $paying_method = "TARJETA DE CRÉDITO";
                break;
            case "20":
                $paying_method = "OTROS CON UTILIZACIÓN DEL SISTEMA FINANCIERO";
                break;
            case "21":
                $paying_method = "ENDOSO DE TÍTULOS";
                break;
        }
        return $paying_method;
    }

    /**
     * Retorna el código para la generación del XML
     */
    public static function getCodeTax($listTax, $rateTax)
    {
        foreach ($listTax as $key => $itemTax) {
            if ((float)$itemTax->rate == (float)$rateTax) {
                return $itemTax->xml_code;
            }
        }
        return 0;
    }

    /**
     * Genera la Secuencia para el Documento XML
     */
    public static function claveAcceso($param = array())
    {
        $fecha = $param['fechaEmision'];
        $a1 = substr($fecha, 0, 1);
        $a2 = substr($fecha, 1, 1);
        $a3 = substr($fecha, 3, 1);
        $a4 = substr($fecha, 4, 1);
        $a5 = substr($fecha, 6, 1);
        $a6 = substr($fecha, 7, 1);
        $a7 = substr($fecha, 8, 1);
        $a8 = substr($fecha, 9, 1);
        $tipoComprobante = $param['codDoc'];
        $a9 = substr($tipoComprobante, 0, 1);
        $a10 = substr($tipoComprobante, 1, 1);
        $ruc = $param['ruc'];
        $a11 = substr($ruc, 0, 1);
        $a12 = substr($ruc, 1, 1);
        $a13 = substr($ruc, 2, 1);
        $a14 = substr($ruc, 3, 1);
        $a15 = substr($ruc, 4, 1);
        $a16 = substr($ruc, 5, 1);
        $a17 = substr($ruc, 6, 1);
        $a18 = substr($ruc, 7, 1);
        $a19 = substr($ruc, 8, 1);
        $a20 = substr($ruc, 9, 1);
        $a21 = substr($ruc, 10, 1);
        $a22 = substr($ruc, 11, 1);
        $a23 = substr($ruc, 12, 1);
        $a24 = $param['ambiente'];
        $establecimiento = $param['estab'];
        $a25 = substr($establecimiento, 0, 1);
        $a26 = substr($establecimiento, 1, 1);
        $a27 = substr($establecimiento, 2, 1);
        $ptoEmi = $param['ptoEmi'];
        $a28 = substr($ptoEmi, 0, 1);
        $a29 = substr($ptoEmi, 1, 1);
        $a30 = substr($ptoEmi, 2, 1);
        $secuencial = $param['secuencial'];
        $a31 = substr($secuencial, 0, 1);
        $a32 = substr($secuencial, 1, 1);
        $a33 = substr($secuencial, 2, 1);
        $a34 = substr($secuencial, 3, 1);
        $a35 = substr($secuencial, 4, 1);
        $a36 = substr($secuencial, 5, 1);
        $a37 = substr($secuencial, 6, 1);
        $a38 = substr($secuencial, 7, 1);
        $a39 = substr($secuencial, 8, 1);
        //secuencial
        $a40 = substr($secuencial, 1, 1);
        $a41 = substr($secuencial, 2, 1);
        $a42 = substr($secuencial, 3, 1);
        $a43 = substr($secuencial, 4, 1);
        $a44 = substr($secuencial, 5, 1);
        $a45 = substr($secuencial, 6, 1);
        $a46 = substr($secuencial, 7, 1);
        $a47 = substr($secuencial, 8, 1);
        $a48 = 1;
        //Calcular
        $sumatotal = 0;
        $sumatotal += $a1 * 7;
        $sumatotal += $a2 * 6;
        $sumatotal += $a3 * 5;
        $sumatotal += $a4 * 4;
        $sumatotal += $a5 * 3;
        $sumatotal += $a6 * 2;
        $sumatotal += $a7 * 7;
        $sumatotal += $a8 * 6;
        $sumatotal += $a9 * 5;
        $sumatotal += $a10 * 4;
        $sumatotal += $a11 * 3;
        $sumatotal += $a12 * 2;
        $sumatotal += $a13 * 7;
        $sumatotal += $a14 * 6;
        $sumatotal += $a15 * 5;
        $sumatotal += $a16 * 4;
        $sumatotal += $a17 * 3;
        $sumatotal += $a18 * 2;
        $sumatotal += $a19 * 7;
        $sumatotal += $a20 * 6;
        $sumatotal += $a21 * 5;
        $sumatotal += $a22 * 4;
        $sumatotal += $a23 * 3;
        $sumatotal += $a24 * 2;
        $sumatotal += $a25 * 7;
        $sumatotal += $a26 * 6;
        $sumatotal += $a27 * 5;
        $sumatotal += $a28 * 4;
        $sumatotal += $a29 * 3;
        $sumatotal += $a30 * 2;
        $sumatotal += $a31 * 7;
        $sumatotal += $a32 * 6;
        $sumatotal += $a33 * 5;
        $sumatotal += $a34 * 4;
        $sumatotal += $a35 * 3;
        $sumatotal += $a36 * 2;
        $sumatotal += $a37 * 7;
        $sumatotal += $a38 * 6;
        $sumatotal += $a39 * 5;
        $sumatotal += $a40 * 4;
        $sumatotal += $a41 * 3;
        $sumatotal += $a42 * 2;
        $sumatotal += $a43 * 7;
        $sumatotal += $a44 * 6;
        $sumatotal += $a45 * 5;
        $sumatotal += $a46 * 4;
        $sumatotal += $a47 * 3;
        $sumatotal += $a48 * 2;
        $sumatotal = $sumatotal % 11;
        $valorBuscado = 11 - $sumatotal;
        switch ($valorBuscado) {
            case 11:
                $valorBuscado = 0;
                break;
            case 10:
                $valorBuscado = 1;
                break;
        }
        return substr($fecha, 0, 2) . substr($fecha, 3, 2) . substr($fecha, 6, 4) . $tipoComprobante . $ruc . $param['ambiente'] . $establecimiento . $ptoEmi . $secuencial . substr($secuencial, 1, 8) . $a48 . $valorBuscado;
    }

    public static function validaCedula($documento)
    {


        if (strlen($documento) != 10) {
            return false;
        }

        //valida cedula del ecuador
        $nro_region = substr($documento, 0, 2);
        if ($nro_region >= 1 && $nro_region <= 24) {
            $ult_digito = substr($documento, -1, 1);
            $valor2 = substr($documento, 1, 1);
            $valor4 = substr($documento, 3, 1);
            $valor6 = substr($documento, 5, 1);
            $valor8 = substr($documento, 7, 1);
            $suma_pares = ($valor2 + $valor4 + $valor6 + $valor8);
            $valor1 = substr($documento, 0, 1);
            $valor1 = ($valor1 * 2);
            if ($valor1 > 9) {
                $valor1 = ($valor1 - 9);
            }
            $valor3 = substr($documento, 2, 1);
            $valor3 = ($valor3 * 2);
            if ($valor3 > 9) {
                $valor3 = ($valor3 - 9);
            }
            $valor5 = substr($documento, 4, 1);
            $valor5 = ($valor5 * 2);
            if ($valor5 > 9) {
                $valor5 = ($valor5 - 9);
            }
            $valor7 = substr($documento, 6, 1);
            $valor7 = ($valor7 * 2);
            if ($valor7 > 9) {
                $valor7 = ($valor7 - 9);
            }
            $valor9 = substr($documento, 8, 1);
            $valor9 = ($valor9 * 2);
            if ($valor9 > 9) {
                $valor9 = ($valor9 - 9);
            }
            $suma_impares = ($valor1 + $valor3 + $valor5 + $valor7 + $valor9);
            $suma = ($suma_pares + $suma_impares);
            $dis = substr($suma, 0, 1);
            $dis = (($dis + 1) * 10);
            $digito = ($dis - $suma);
            if ($digito == 10) {
                $digito = '0';
            }
            if ($digito == $ult_digito) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function validaRuc($ruc)
    {

        if (strlen($ruc) != 13) {
            return false;
        }

        //valida ruc del ecuador
        $nro_region = substr($ruc, 0, 2);
        if ($nro_region >= 1 && $nro_region <= 24) {
            $ult_digito = substr($ruc, -1, 1);
            $valor2 = substr($ruc, 1, 1);
            $valor4 = substr($ruc, 3, 1);
            $valor6 = substr($ruc, 5, 1);
            $valor8 = substr($ruc, 7, 1);
            $valor10 = substr($ruc, 9, 1);
            $valor12 = substr($ruc, 11, 1);
            $suma_pares = ($valor2 + $valor4 + $valor6 + $valor8 + $valor10 + $valor12);
            $valor1 = substr($ruc, 0, 1);
            $valor1 = ($valor1 * 2);
            if ($valor1 > 9) {
                $valor1 = ($valor1 - 9);
            }
            $valor3 = substr($ruc, 2, 1);
            $valor3 = ($valor3 * 2);
            if ($valor3 > 9) {
                $valor3 = ($valor3 - 9);
            }
            $valor5 = substr($ruc, 4, 1);
            $valor5 = ($valor5 * 2);
            if ($valor5 > 9) {
                $valor5 = ($valor5 - 9);
            }
            $valor7 = substr($ruc, 6, 1);
            $valor7 = ($valor7 * 2);
            if ($valor7 > 9) {
                $valor7 = ($valor7 - 9);
            }
            $valor9 = substr($ruc, 8, 1);
            $valor9 = ($valor9 * 2);
            if ($valor9 > 9) {
                $valor9 = ($valor9 - 9);
            }
            $valor11 = substr($ruc, 10, 1);
            $valor11 = ($valor11 * 2);
            if ($valor11 > 9) {
                $valor11 = ($valor11 - 9);
            }
            $suma_impares = ($valor1 + $valor3 + $valor5 + $valor7 + $valor9 + $valor11);
            $suma = ($suma_pares + $suma_impares);
            $dis = substr($suma, 0, 1);
            $dis = (($dis + 1) * 10);
            $digito = ($dis - $suma);
            if ($digito == 10) {
                $digito = '0';
            }
            if ($digito == $ult_digito) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
