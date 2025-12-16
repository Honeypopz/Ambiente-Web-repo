<?php
session_start();
// Español por defecto
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
<html lang="<?= $_SESSION['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['title_admin_dashboard'] ?> - <?= $lang['brand'] ?></title>
    <link rel="stylesheet" href="Css/estiloDashboardAdmin.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <h1><?= $lang['brand'] ?></h1>
            <span class="subtitle"><?= $lang['subtitle_admin'] ?></span>
        </div>
        <div class="user-info">
            <span class="user-name" id="userName"><?= $_SESSION['nombre_usuario']; ?></span>
            <span class="user-role admin"><?= $lang['role_admin'] ?></span>
            <a href="logout.php" class="btn-logout"><?= $lang['btn_logout'] ?></a>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-header">
            <h2><?= $lang['header_admin_dashboard'] ?></h2>
            <p><?= $lang['desc_admin_dashboard'] ?></p>
        </div>

        <!--Acciones de Administración-->
        <div class="admin-actions-grid">
            <div class="action-card">
                <h3><?= $lang['card_usuarios_title'] ?></h3>
                <p><?= $lang['card_usuarios_desc'] ?></p>
                <a href="VisualizarUsuarios.php" class="action-btn"><?= $lang['card_acceder'] ?></a>
            </div>
            
            <div class="action-card">
                <h3><?= $lang['card_reservas_title'] ?></h3>
                <p><?= $lang['card_reservas_desc'] ?></p>
                <a href="VisualizarReservas.php" class="action-btn"><?= $lang['card_acceder'] ?></a>
            </div>

            <div class="action-card">
                <h3><?= $lang['card_viajes_title'] ?></h3>
                <p><?= $lang['card_viajes_desc'] ?></p>
                <a href="VisualizarViajes.php" class="action-btn"><?= $lang['card_acceder'] ?></a>
            </div>
        </div>
    </div>
</body>
</html>
