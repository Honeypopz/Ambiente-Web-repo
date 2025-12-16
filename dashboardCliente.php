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
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lang['brand'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Css/estiloDashboardCliente.css">
</head>

<body>
    <nav class="navbar">
        <div class="nav-brand">
            <h1><?= $lang['brand'] ?></h1>
            <span class="subtitle"><?= $lang['subtitle_client'] ?></span>
        </div>
        <div class="user-info">
            <span class="user-name" id="userName"><?= $_SESSION['nombre_usuario']; ?></span>
            <span class="user-role client"><?= $lang['role_client'] ?></span>
            <!-- Selector de idioma -->
            <div class="language-selector" style="display: inline-block; margin-right: 15px;">
                <?php if ($_SESSION['lang'] == 'es'): ?>
                    <a href="CambioIdioma.php?lang=en" style="color: white; text-decoration: none;">
                        <i class="bi bi-translate"></i> English
                    </a>
                <?php else: ?>
                    <a href="CambioIdioma.php?lang=es" style="color: white; text-decoration: none;">
                        <i class="bi bi-translate"></i> Español
                    </a>
                <?php endif; ?>
            </div>
            <a href="logout.php" class="btn-logout"><?= $lang['btn_logout'] ?></a>
        </div>
    </nav>

    <div class="container">
        <!--Mensaje de bienvenida-->
        <div class="welcome-card">
            <h2><?= $lang['hello'] ?>, <span id="displayName"><?= $_SESSION['nombre_usuario']; ?></span></h2>
            <p><?= $lang['welcome'] ?></p>
        </div>

        <!--Acciones para cliente-->
        <div class="client-actions">
            <h3><?= $lang['my_options'] ?></h3>
            <div class="actions-grid">
                <!-- Realizar Reserva -->
                <div class="client-action-card">
                    <h4><?= $lang['action_reserva'] ?></h4>
                    <p><?= $lang['desc_reserva'] ?></p>
                    <a href="ReservaEstadia.php">
                        <button class="client-btn"><?= $lang['btn_reserva'] ?></button>
                    </a>
                </div>
                
                <!-- Gestionar Reserva -->
                <div class="client-action-card">
                    <h4><?= $lang['action_gestionar'] ?></h4>
                    <p><?= $lang['desc_gestionar'] ?></p>
                    <a href="GestionarReserva.php">
                        <button class="client-btn"><?= $lang['btn_gestionar'] ?></button>
                    </a>
                </div>
                
                <!-- Solicitar Viaje -->
                <div class="client-action-card">
                    <h4><?= $lang['action_viaje'] ?></h4>
                    <p><?= $lang['desc_viaje'] ?></p>
                    <a href="RegistroViaje.php">
                        <button class="client-btn"><?= $lang['btn_viaje'] ?></button>
                    </a>
                </div>
                
                <!-- Lugares de Interés -->
                <div class="client-action-card">
                    <h4><?= $lang['action_lugares'] ?></h4>
                    <p><?= $lang['desc_lugares'] ?></p>
                    <a href="LugaresInteres/LugaresInteres.php">
                        <button class="client-btn"><?= $lang['btn_lugares'] ?></button>
                    </a>
                </div>

                <!-- Multimedia -->
                <div class="client-action-card">
                    <h4><?= $lang['action_multimedia'] ?></h4>
                    <p><?= $lang['desc_multimedia'] ?></p>
                    <a href="multimedia.html">
                        <button class="client-btn"><?= $lang['btn_multimedia'] ?></button>
                    </a>
                </div>
                
                <!-- Mi Perfil -->
                <div class="client-action-card">
                    <h4><?= $lang['action_perfil'] ?></h4>
                    <p><?= $lang['desc_perfil'] ?></p>
                    <a href="MiPerfil.php">
                        <button class="client-btn"><?= $lang['btn_perfil'] ?></button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!--footer con la informacion de contacto-->
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 <?= $lang['brand'] ?>. <?= $lang['rights_reserved'] ?></p>
            <div class="footer-links">
                <a href="https://www.facebook.com/p/Villas-Brenes-61571505092179/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <i class="bi bi-facebook" aria-hidden="true"></i>
                </a>
                <a href="https://wa.link/qmnavx" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i>
                </a>
                <a href="https://www.instagram.com/villasbrenes_/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <i class="bi bi-instagram" aria-hidden="true"></i>
                </a>
                <a href="mailto:test@gmail.com" aria-label="Email">
                    <i class="bi bi-envelope-at" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </footer>
</body>
</html>
