<?php
session_start();
//español por defecto
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

include __DIR__ . "/lang/" . $_SESSION['lang'] . ".php";

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_usuario'] !== 'administrador') {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link rel="stylesheet" href="Css/estiloDashboardAdmin.css">
</head>

<body>
    <nav class="navbar">
        <div class="nav-brand">
            <h1>Villas Brenes</h1>
            <span class="subtitle">Panel de Administración</span>
        </div>
        <div class="user-info">
            <span class="user-name" id="userName"><?php echo $_SESSION['nombre_usuario']; ?></span>
            <span class="user-role admin">Administrador</span>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-header">
            <h2>Panel de Visualización Administrativa</h2>
            <p>Visualiza los detalles de los usuarios, reservas, solicitudes de viaje y conductores</p>
        </div>

        <!--Acciones de Administración-->
        <div class="admin-actions-grid">
            <div class="action-card">
                <h3>Usuarios registrados</h3>
                <p>Visualiza todos los usuarios que se encuentran en el sistema</p>
                <a href="VisualizarUsuarios.php" class="action-btn">Acceder</a>
            </div>
            
            <div class="action-card">
                <h3>Reservas realizadas</h3>
                <p>Visualiza las reservas que han realizado los usuarios </p>
                <a href="VisualizarReservas.php" class="action-btn">Acceder</a>
            </div>

            <div class="action-card">
                <h3>Viajes solicitados y conductores</h3>
                <p>Visualiza los conductores de los vehiculos y las solicitudes de viajes</p>
                <a href="VisualizarViajes.php" class="action-btn">Acceder</a>
            </div>

    <script>       
    </script>
</body>
</html>