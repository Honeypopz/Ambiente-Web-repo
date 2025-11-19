<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitar viaje</title>
    <link href="Css/estiloDashboardCliente.css">
</head>

<body>
    <h1>Pedir vehículo</h1>
    <div class="formTran" id="buscar">
        <form name="TransporteForm" method="post" action="RegistroViaje.php">
            <input type="number" name="cantidad" placeholder="Cantidad de personas" required>
            <input type="hidden" name="destino" placeholder="Destino">
            <select class="form-select" aria-label="Tipo de vehículo">
                <option selected>Eliga el vehículo</option>
                <option value="1">Automovil</option>
                <option value="2">Buseta</option>
            </select>
            <button type="submit">Solicitar viaje</button>
        </form>
    </div>

<script src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY&callback=initMap"></script>
</body>

</html>