
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

//Conectar a bd
$conexion = new mysqli("localhost", "root", "", "villasBrenes");
$viajes = [];
$conductores = [];
$totalViajes = 0;
$totalConductores = 0;

if (!$conexion->connect_error) {
    //Obtener viajes
    $result = $conexion->query("SELECT id_viaje, cantidad, destino, vehiculo, conductor_nombre, conductor_id FROM viaje ORDER BY id_viaje DESC");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $viajes[] = $row;
        }
    }
    
    //Contar viajes
    $result = $conexion->query("SELECT COUNT(*) as total FROM viaje");
    if ($result && $row = $result->fetch_assoc()) {
        $totalViajes = $row['total'];
    }
    
    //Obtener conductores
    $result = $conexion->query("SELECT id_conductor, nombre_conductor, vehiculo, contacto FROM conductor ORDER BY id_conductor ASC");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $conductores[] = $row;
        }
    }
    
    //Contar conductores
    $result = $conexion->query("SELECT COUNT(*) as total FROM conductor");
    if ($result && $row = $result->fetch_assoc()) {
        $totalConductores = $row['total'];
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Viajes - Admin Villas Brenes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Css/estiloViajesAdmin.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">
            <h1>Villas Brenes</h1>
            <span class="subtitle">Visualizar Viajes</span>
        </div>
        <div class="user-info">
            <span class="user-name"><?php echo $_SESSION['nombre_usuario']; ?></span>
            <span class="user-role admin">ADMINISTRADOR</span>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="content-container">
            <div class="content-header">
                <h2>Lista de Viajes Registrados</h2>
                <p>Consulta todos los viajes del sistema</p>
            </div>
            
            <div class="stats-summary">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $totalViajes; ?></span>
                    <span class="stat-label">Viajes Totales</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $totalConductores; ?></span>
                    <span class="stat-label">Conductores</span>
                </div>
            </div>
            
            <!-- Seccion Conductores -->
            <div class="content-header mini-header">
                <h3>Conductores de Vehículos</h3>
                <p>Lista de conductores registrados en el sistema</p>
            </div>
            
            <div class="conductores-table-container">
                <?php if (empty($conductores)): ?>
                    <div class="empty-state">
                        <i class="bi bi-person-badge"></i>
                        <h3>No hay conductores registrados</h3>
                        <p>No se encontraron conductores en el sistema.</p>
                    </div>
                <?php else: ?>
                    <table class="conductores-table">
                        <thead>
                            <tr>
                                <th>ID Conductor</th>
                                <th>Nombre</th>
                                <th>Vehículo</th>
                                <th>Contacto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($conductores as $conductor): ?>
                            <tr>
                                <td class="conductor-id">#<?php echo $conductor['id_conductor']; ?></td>
                                <td class="conductor-name"><?php echo htmlspecialchars($conductor['nombre_conductor']); ?></td>
                                <td class="conductor-vehiculo"><?php echo htmlspecialchars($conductor['vehiculo']); ?></td>
                                <td class="conductor-contacto"><?php echo htmlspecialchars($conductor['contacto']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            
            <!-- Sección de Viajes -->
            <div class="content-header mini-header">
                <h3>Viajes Solicitados</h3>
                <p>Lista de viajes registrados</p>
            </div>
            
            <div class="viajes-table-container">
                <?php if (empty($viajes)): ?>
                    <div class="empty-state">
                        <i class="bi bi-geo-alt"></i>
                        <h3>No hay viajes registrados</h3>
                        <p>No se encontraron viajes en el sistema.</p>
                    </div>
                <?php else: ?>
                    <table class="viajes-table">
                        <thead>
                            <tr>
                                <th>ID Viaje</th>
                                <th>Cantidad</th>
                                <th>Destino</th>
                                <th>Vehículo</th>
                                <th>Conductor</th>
                                <th>ID Conductor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($viajes as $viaje): ?>
                            <tr>
                                <td class="viaje-id">#<?php echo $viaje['id_viaje']; ?></td>
                                <td class="viaje-cantidad"><?php echo $viaje['cantidad']; ?></td>
                                <td class="viaje-destino"><?php echo htmlspecialchars($viaje['destino']); ?></td>
                                <td class="viaje-vehiculo"><?php echo htmlspecialchars($viaje['vehiculo']); ?></td>
                                <td class="viaje-conductor"><?php echo htmlspecialchars($viaje['conductor_nombre']); ?></td>
                                <td class="conductor-id">#<?php echo $viaje['conductor_id']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            
            <div class="content-actions">
                <a href="dashboardAdmin.php" class="btn-back">
                    Volver
                </a>
            </div>
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
    </script>
</body>
</html>