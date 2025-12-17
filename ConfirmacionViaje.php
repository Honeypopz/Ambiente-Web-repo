<?php
session_start();
require_once 'confirmaciones.php';

// Español por defecto
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

include __DIR__ . "/lang/" . $_SESSION['lang'] . ".php";

// Redirigir si no hay usuario logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

// Conexión PDO
require_once __DIR__ . '/configuracion/conexionBD.php';
$db = new BaseDeDatos();
$conexion = $db->obtenerConexion();

if (!$conexion) {
    die("Error de conexión con la base de datos.");
}

// Obtener datos del formulario
$destino  = $_POST['destino'] ?? '';
$cantidad = $_POST['cantidad'] ?? 0;
$vehiculo = $_POST['vehiculo'] ?? '';
$fecha    = $_POST['fecha'] ?? '';
$hora     = $_POST['hora'] ?? '';
$notas    = $_POST['notas'] ?? '';

// Buscar conductor asignado al vehículo
$stmt = $conexion->prepare("SELECT id_conductor, nombre_conductor FROM conductor WHERE vehiculo = ? LIMIT 1");
$stmt->execute([$vehiculo]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$conductor   = $row['nombre_conductor'] ?? null;
$conductorId = $row['id_conductor'] ?? null;

// Insertar el viaje
$stmt1 = $conexion->prepare(
    "INSERT INTO viaje (cantidad, destino, vehiculo, conductor_nombre, conductor_id) VALUES (?, ?, ?, ?, ?)"
);
$stmt1->execute([$cantidad, $destino, $vehiculo, $conductor, $conductorId]);

$viajeId = $conexion->lastInsertId();
$email = $_SESSION['email_usuario'] ?? '';

// Notificar por correo
if ($email) {
    notificarReservaViaje($email, $viajeId, $destino, $fecha, $hora);
}
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