<?php
session_start();
//español por defecto
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
    <title>Villas Brenes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Css/estiloDashboardCliente.css">
</head>

<body>
    <nav class="navbar">
        <div class="nav-brand">
            <h1>Villas Brenes</h1>
            <span class="subtitle">Mi Espacio Personal</span>
        </div>
        <div class="user-info">
            <span class="user-name" id="userName"><?php echo $_SESSION['nombre_usuario']; ?></span>
            <span class="user-role client">CLIENTE</span>
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
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <!--Mensaje de bienvenida-->
        <div class="welcome-card">
            <h2>Hola, <span id="displayName"><?php echo $_SESSION['nombre_usuario']; ?></span></h2>
            <p>Bienvenido a tu espacio personal en Villas Brenes</p>
        </div>


        <!--Acciones para cliente-->
        <div class="client-actions">
            <h3>Mis Opciones</h3>
            <div class="actions-grid">
                <!-- Realizar Reserva -->
                <div class="client-action-card">
                    <h4>Realizar Reserva</h4>
                    <p>Reserva tu estadía en nuestras villas</p>
                    <a href="ReservaEstadia.php">
                        <button class="client-btn">Realizar reserva</button>
                    </a>
                </div>
                
                <!-- Editar Reserva -->
                <div class="client-action-card">
                    <h4>Editar Reserva</h4>
                    <p>Modifica o cancela tus reservas existentes</p>
                    <a href="EditarReserva.php">
                        <button class="client-btn">Gestionar reservas</button>
                    </a>
                </div>
                
                <!-- Solicitar Viaje -->
                <div class="client-action-card">
                    <h4>Solicitar Viaje</h4>
                    <p>Solicita transporte para movilizarte</p>
                    <a href="RegistroViaje.php">
                        <button class="client-btn">Solicitar Transporte</button>
                    </a>
                </div>
                
                <!-- Lugares de Interés -->
                <div class="client-action-card">
                    <h4>Lugares de Interés</h4>
                    <p>Descubre lugares atractivos en la zona</p>
                    <a href="LugaresInteres/LugaresInteres.php">
                        <button class="client-btn">Explorar Lugares</button>
                    </a>
                </div>

                <!-- Multimedia -->
                <div class="client-action-card">
                    <h4>Multimedia</h4>
                    <p>Visualiza imágenes de Villas Brenes</p>
                    <a href="multimedia.html">
                        <button class="client-btn">Ver imágenes</button>
                    </a>
                </div>
                
                <!-- Notificaciones -->
                <div class="client-action-card">
                    <h4>Notificaciones</h4>
                    <p>Revisa tus alertas y mensajes importantes</p>
                    <a href="Notificaciones.php">
                        <button class="client-btn">Ver Notificaciones</button>
                    </a>
                </div>
                
                <!-- Mi Perfil -->
                <div class="client-action-card">
                    <h4>Encuesta de satisfacción</h4>
                    <p>Dinos luego de tu estadia que valoración nos das</p>
                    <button class="client-btn">Ver Ofertas</button>
                </div>
            </div>
        </div>

        <!--Recomendaciones para cliente-->
        <div class="recommended-villas">
            <h3>Villas recomendadas para ti</h3>
            <div class="villas-grid">
                <div class="villa-card">
                    <h4>Casa del cangrejo</h4>
                    <p>4 personas - Piscina - WiFi</p>
                    <div class="villa-price">₡85,000/noche</div>
                    <a class="villa-btn" href="ReservaEstadia.php">Reservar</a>
                    <h4>Mi Perfil</h4>
                    <p>Actualiza tu información personal</p>
                    <a href="MiPerfil.php">
                        <button class="client-btn">Editar Perfil</button>
                    </a>
                </div>
                
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

        });
    </script>
    
    <!--footer con la informacion de contacto-->
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
</body>
</html>