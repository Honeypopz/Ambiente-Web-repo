
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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones - Villas Brenes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Css/estiloNotificaciones.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">
            <h1>Villas Brenes</h1>
            <span class="subtitle">Mis Notificaciones</span>
        </div>
        <div class="user-info">
            <span class="user-name"><?php echo $_SESSION['nombre_usuario']; ?></span>
            <span class="user-role client">CLIENTE</span>
            <!-- Selector de idioma -->
            <div class="language-selector">
                <?php if ($_SESSION['lang'] == 'es'): ?>
                    <a href="CambioIdioma.php?lang=en">
                        <i class="bi bi-translate"></i> English
                    </a>
                <?php else: ?>
                    <a href="CambioIdioma.php?lang=es">
                        <i class="bi bi-translate"></i> Español
                    </a>
                <?php endif; ?>
            </div>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="notifications-header">
            <h2>Mis Notificaciones</h2>
            <p>Historial de tus actividades y reservas</p>
        </div>
        
        <div class="notifications-list">
            <!-- Notificación de ejemplo -->
            <div class="notification-card">
                <div class="notification-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="notification-content">
                    <h3>Reserva Confirmada</h3>
                    <p>Tu reserva en "Casa del Cangrejo" ha sido confirmada para el 15-20 de Diciembre, 2024.</p>
                    <span class="notification-time">15 de Diciembre, 2024 - 14:30</span>
                </div>
            </div>
            

        </div>
        

        <div class="notifications-empty" style="<?php echo (false) ? 'display: block;' : 'display: none;' ?>">
            <div class="empty-icon">
                <i class="bi bi-bell-slash"></i>
            </div>
            <h3>No hay notificaciones</h3>
            <p>Cuando tengas nuevas notificaciones, aparecerán aquí.</p>
        </div>
        
        <div class="back-link">
            <a href="dashboardCliente.php" class="btn-back">
                Volver
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 Villas Brenes. Todos los derechos reservados.</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const notificationsList = document.querySelector('.notifications-list');
            const emptyState = document.querySelector('.notifications-empty');
            
            // Verificar si hay notificaciones
            if (notificationsList && notificationsList.children.length === 0) {
                notificationsList.style.display = 'none';
                emptyState.style.display = 'block';
            }
        });
    </script>
</body>
</html>