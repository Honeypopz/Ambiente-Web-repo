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
            <h2>Panel de Control Administrativo</h2>
            <p>Gestiona todos los detalles desde el sistema</p>
        </div>

        <!--Resumen de datos-->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" id="totalUsers">0</div>
                <div class="stat-label">Usuarios Totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="totalRevenue">₡0</div>
                <div class="stat-label">Ingresos Totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="activeBookings">0</div>
                <div class="stat-label">Reservas Activas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="satisfaction">98%</div>
                <div class="stat-label">Satisfacción</div>
            </div>
        </div>

        <!--Acciones de Administración-->
        <div class="admin-actions-grid">
            <div class="action-card">
                <h3>Gestión de Usuarios</h3>
                <p>Administra usuarios</p>
                <button class="action-btn">Acceder</button>
            </div>
            
            <div class="action-card">
                <h3>Gestión de Propiedades</h3>
                <p>Administra villas, habitaciones y servicios</p>
                <button class="action-btn">Acceder</button>
            </div>
            
            <div class="action-card">
                <h3>Gestión de reservas</h3>
                <p>Administra reservas de clientes</p>
                <button class="action-btn">Acceder</button>
            </div>
            
            <div class="action-card">
                <h3>Reportes</h3>
                <p>Genera reportes y estadísticas del negocio</p>
                <button class="action-btn">Acceder</button>
            </div>
            
            <div class="action-card">
                <h3>Cambiar idioma</h3>
                <p>Selecciona tu idioma preferido</p>
                <button class="action-btn">Acceder</button>
            </div>
        </div>

        <!-- Información Reciente -->
        <div class="recent-activity">
            <h3>Actividad Reciente</h3>
            <div class="activity-list">
                <div class="activity-item">
                    <span class="activity-time">Hace 5 min</span>
                    <span class="activity-text">Nuevo usuario registrado: Usuario de ejemplo</span>
                </div>
                <div class="activity-item">
                    <span class="activity-time">Hace 15 min</span>
                    <span class="activity-text">Reserva confirmada: Villa del cangrejo</span>
                </div>
                <div class="activity-item">
                    <span class="activity-time">Hace 1 hora</span>
                    <span class="activity-text">Pago procesado: ₡85,000</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('totalUsers').textContent = '15';
        });
        
    </script>
</body>
</html>