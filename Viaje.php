<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitar viaje</title>
    <link href="Css/estiloDashboardCliente.css">
</head>

<body>
    <h1>Pedir vehículo</h1>
    <div id="buscar">
        <form name="TransporteForm" method="post" action="">
            <input type="number" name="cantidad" placeholder="Cantidad de personas" required>
            <select class="form-select" aria-label="Ubicación">
                <option selected>Eliga su destino</option>
                <option value="1">Playa ubita</option>
                <option value="2">Liberia</option>
            </select>
            <select class="form-select" aria-label="Tipo de vehículo">
                <option selected>Eliga el vehículo</option>
                <option value="1">Automovil</option>
                <option value="2">Buseta</option>
            </select>
            <button type="submit">Solicitar viaje</button>
        </form>
    </div>

    <script src="script.js"></script>
</body>


</html>