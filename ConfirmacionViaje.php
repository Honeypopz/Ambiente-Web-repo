<?php
session_start();
// Español por defecto
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

include __DIR__ . "/lang/" . $_SESSION['lang'] . ".php";

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

require "conexionDB.php";

$destino = $_POST['destino'];
$cantidad  = $_POST['cantidad'];
$vehiculo  = $_POST['vehiculo'];
$fecha     = $_POST['fecha'];
$hora      = $_POST['hora'];
$notas      = $_POST['notas'];

$stmt = $conexion->prepare("SELECT nombre_conductor FROM conductor WHERE vehiculo = ? LIMIT 1");
$stmt->bind_param("s", $vehiculo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $conductor = $row['nombre_conductor'];
} else {
    $conductor = null; 
}

$stmt1 = $conexion->prepare("INSERT INTO viaje (cantidad, destino, vehiculo, nombre_conductor)
VALUES (?, ?, ?, ?)");
$stmt1->bind_param("isss", $cantidad);
$stmt1->execute();

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Viaje Registrado- Villas Brenes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Css/estiloRegistroViaje.css">
    </head>
    <body>
        <div class="container">
    <h1>Solicitud de Viaje Registrada</h1>

    <p><strong>Destino:</strong> <?= htmlspecialchars($destino) ?></p>
    <p><strong>Cantidad de personas:</strong> <?= htmlspecialchars($cantidad) ?></p>
    <p><strong>Vehículo:</strong> <?= htmlspecialchars($vehiculo) ?></p>
    <p><strong>Fecha:</strong> <?= htmlspecialchars($fecha) ?></p>
    <p><strong>Hora:</strong> <?= htmlspecialchars($hora) ?></p>
    <p><strong>Notas:</strong> <?= nl2br(htmlspecialchars($notas)) ?></p>

    <p><strong>Conductor asignado:</strong> 
        <?= $conductor ? htmlspecialchars($conductor) : "No disponible" ?>
    </p>

    <a href="dashboardCliente.php" class="btn-solicitar">Volver al inicio</a>
</div>
    </body>
</html>