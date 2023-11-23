<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Facturacion Electronica</title>
</head>

<body>
    <div>
        <table width="100%" style="align-content: center;">
            <tr>

            </tr>
            <tr>
                <td>
                    <b>Estimado(a) {{ $data->cliente }}</b><br>
                    <p><b>Documento:</b>{{ $data->documento }}</p>
                    <p><b>Numero:</b>{{ $data->factura }}</p>
                    <p><b>Clave de Acceso:</b>{{ $data->clave_acceso }}</p>
                    <p><b>Fecha:</b>{{ date('Y-m-d') }}</p><br>

                </td>
            </tr>
            <tr>
                <p><b>FACTURACIÓN ELECTRONICA</b></p>
            </tr>
        </table>
    </div>
</body>

</html>
