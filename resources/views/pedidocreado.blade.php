<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
        }

        header,
        footer {
            text-align: center;
            padding: 1em 0;
        }

        h2,
        h3 {
            color: #444444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #dddddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <header>
        <h1>CONFECCIONES ROCIO</h1>
        <p>{{ $data->empresa->direccion }}</p>
        <p>Correo Electrónico y Teléfono</p>
    </header>
    <section>
        <h2>Detalle del Pedido</h2>
        <p>Hola {{ $data->cliente->nombre }},</p>
        <p>Gracias por tu pedido. Aquí están los detalles:</p>
        <table>
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data->detallesOrden as $producto)
                    <tr>
                        <td><img src="{{ url($producto->variante->imagen) }}" alt="{{ $producto->variante->color }}"
                                width="100"></td>
                        <td>{{ $producto->variante->producto->nombre }} - {{ $producto->variante->color }}</td>
                        <td>{{ $producto->cantidad }}</td>
                        <td>${{ $producto->variante->precio }}</td>
                        <td>{{ $producto->subtotal + $producto->iva }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h3>Dirección de Envío</h3>
        <p> {{ $data->direccionEntrega->parroquia->ciudad->ciudad }} -
            {{ $data->direccionEntrega->parroquia->parroquia }} - {{ $data->direccionEntrega->direccion }}</p>
        <br>


        @if ($data->envio > 0)
            <h3>Envio</h3>
            <p>${{ $data->envio }}</p>
            <br>
        @else
            <h3>Retira en local</h3>
            <p>$0,00</p>
            <br>
        @endif



        <h3>Total del Pedido</h3>
        <p>${{ $data->total }}</p>
    </section>
    <footer>
        <p>Ambato, Picaigua - Av. Galo Vela </p>
    </footer>
</body>

</html>
