<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once "lugaresController.php";

$controller = new LugaresController();
$lugares = $controller->obtenerLugares();
?>

session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

include "lugaresController.php";

$controller = new LugaresController();
$lugares = $controller->obtenerLugares();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lugares de Interés</title>
    <link rel="stylesheet" href="lugares.css">
</head>

<body>

<h1>Lugares de Interés</h1>
<p>Explora sitios recomendados cerca de Villas Brenes</p>

<div class="grid">
    <?php foreach ($lugares as $l): ?>
        <div class="card">
            <img src="../<?php echo $l['imagen']; ?>" alt="Imagen">
            <h3><?php echo $l['nombre']; ?></h3>
            <p><?php echo $l['descripcion']; ?></p>

            <a href="../Viaje.php" class="btn">Agendar viaje</a>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
