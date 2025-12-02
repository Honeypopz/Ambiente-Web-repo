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
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <!--Mensaje de bienvenida-->
        <div class="welcome-card">
            <h2>Hola, <span id="displayName"><?php echo $_SESSION['nombre_usuario']; ?></span></h2>
            <p>Bienvenido a tu espacio personal en Villas Brenes</p>
        </div>

        <div class="quick-stats">
            <div class="quick-stat">
                <div class="stat-number" id="myBookings">1 ej</div>
                <div class="stat-label">Mis Reservas</div>
            </div>
            <div class="quick-stat">
                <div class="stat-number" id="daysSince">1 ej</div>
                <div class="stat-label">Días pasados en Villas Brenes</div>
            </div>
        </div>

        <!--Acciones para cliente-->
        <div class="client-actions">
            <h3>Mis Opciones</h3>
            <div class="actions-grid">
                <div class="client-action-card">
                    <h4>Buscar Villas</h4>
                    <p>Explora nuestras villas disponibles</p>
                    <button class="client-btn">Explorar</button>
                </div>
                
                <div class="client-action-card">
                    <h4>Mis Reservas</h4>
                    <p>Gestiona tus reservas actuales</p>
                    <button class="client-btn">Ver Reservas</button>
                </div>
                
                <div class="client-action-card">
                    <h4>Mi Perfil</h4>
                    <p>Actualiza tu información personal</p>
                    <button class="client-btn">Editar Perfil</button>
                </div>
                
                <div class="client-action-card">
                    <h4>Soporte</h4>
                    <p>¿Necesitas ayuda? Contáctanos</p>
                    <button class="client-btn">Contactar</button>
                </div>
                
                <div class="client-action-card">
                    <h4>Solicitar transporte</h4>
                    <p>Solicita un vehiculo para movilizarte</p>
                    <button class="client-btn">Escribir Reseña</button>
                </div>
                
                <div class="client-action-card">
                    <h4>Configuraciones</h4>
                    <p>Gestiona tu idioma preferido y notificaciones entrantes</p>
                    <button class="client-btn">Ver Ofertas</button>
                </div>

                <div class="client-action-card">
                    <h4>Ubicaciones de interés</h4>
                    <p>Mira lugares atractivos en la zona para viajar</p>
                    <button class="client-btn">Escribir Reseña</button>
                </div>

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
                    <button class="villa-btn">Reservar</button>
                </div>
            </div>
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