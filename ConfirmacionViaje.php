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
    die($lang['error_bd']);
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
<html lang="<?= $_SESSION['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['title_viaje_registrado'] ?> - <?= $lang['brand'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Css/estiloRegistroViaje.css">
</head>
<body>
    <div class="container">
        <h1><?= $lang['title_viaje_registrado'] ?></h1>

        <p><strong><?= $lang['label_destino'] ?>:</strong> <?= htmlspecialchars($destino) ?></p>
        <p><strong><?= $lang['label_cantidad'] ?>:</strong> <?= htmlspecialchars($cantidad) ?></p>
        <p><strong><?= $lang['label_vehiculo'] ?>:</strong> <?= htmlspecialchars($vehiculo) ?></p>
        <p><strong><?= $lang['label_fecha'] ?>:</strong> <?= htmlspecialchars($fecha) ?></p>
        <p><strong><?= $lang['label_hora'] ?>:</strong> <?= htmlspecialchars($hora) ?></p>
        <p><strong><?= $lang['label_notas'] ?>:</strong> <?= nl2br(htmlspecialchars($notas)) ?></p>

        <p><strong><?= $lang['label_conductor'] ?>:</strong> 
            <?= $conductor ? htmlspecialchars($conductor) : $lang['no_disponible'] ?>
        </p>

        <a href="dashboardCliente.php" class="btn-solicitar"><?= $lang['btn_volver_inicio'] ?></a>
    </div>
</body>
</html>
