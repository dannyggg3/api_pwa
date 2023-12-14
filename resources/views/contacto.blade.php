<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>NUEVO MENSAJE DE CONTACTO</title>
</head>

<body>
    <h2>NUEVO MENSAJE DE CONTACTO - CONFECCIONES ROCIO</h2><br>

    <br>
    <h3>Nombre: {{ $data->nombre }}</h3> <br>
    <h3>Apellido: {{ $data->apellido }}</h3> <br>
    <h3>Email: {{ $data->email }}</h3> <br>
    <h3>Telefono: {{ $data->telefono }}</h3> <br>
    <h3>Mensaje: {{ $data->mensaje }}</h3> <br>

    <br>
    <h4>Gracias por usar nuestro sistema</h4>
</body>

</html>
