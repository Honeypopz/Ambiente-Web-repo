

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
$reservas = [];
$totalReservas = 0;

if (!$conexion->connect_error) {
    //Obtener reservas
    $sql = "SELECT id_reserva, id_usuario, nombre_Reservante, fechaEntrada, fechaSalida, adultos, ninos, habitacion, email 
            FROM reservas ORDER BY fechaEntrada DESC";

    
    $result = $conexion->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
 
            $reserva_limpia = [
                'id_reserva' => $row['id_reserva'] ?? '-',
                'id_usuario' => $row['id_usuario'] ?? '-',
                'nombre_Reservante' => $row['nombre_Reservante'] ?? '-',
                'fechaEntrada' => $row['fechaEntrada'] ?? '-',
                'fechaSalida' => $row['fechaSalida'] ?? '-',
                'adultos' => $row['adultos'] ?? '0',
                'ninos' => $row['ninos'] ?? '0',
                'habitacion' => $row['habitacion'] ?? '0',
                'email' => $row['email'] ?? '-'
            ];
            
            $reservas[] = $reserva_limpia;
        }
    } else {
        echo "No se encontraron reservas o error en la consulta<br>";
    }
    
    //Total de reservas
    $result = $conexion->query("SELECT COUNT(*) as total FROM reservas");
    if ($result && $row = $result->fetch_assoc()) {
        $totalReservas = $row['total'];
    }
} else {
    echo "Error de conexión: " . $conexion->connect_error;
}

$conexion->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Reservas - Admin Villas Brenes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Css/estiloReservasAdmin.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">
            <h1>Villas Brenes</h1>
            <span class="subtitle">Visualizar Reservas</span>
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
                <h2>Lista de Reservas Realizadas</h2>
                <p>Consulta todas las reservas del sistema</p>
            </div>
            
            <div class="stats-summary">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $totalReservas; ?></span>
                    <span class="stat-label">Reservas Totales</span>
                </div>
            </div>
            
            <div class="reservas-table-container">
                <?php if (empty($reservas)): ?>
                    <div class="empty-state">
                        <i class="bi bi-calendar-check"></i>
                        <h3>No hay reservas registradas</h3>
                        <p>No se encontraron reservas en el sistema.</p>
                    </div>
                <?php else: ?>
                    <table class="reservas-table">
                        <thead>
                            <tr>
                                <th>ID Reserva</th>
                                <th>ID Usuario</th>
                                <th>Nombre Reservante</th>
                                <th>Fecha Entrada</th>
                                <th>Fecha Salida</th>
                                <th>Adultos</th>
                                <th>Niños</th>
                                <th>Habitaciones</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $index => $reserva): ?>
                            <tr>
                                <td class="reserva-id">#<?php echo htmlspecialchars($reserva['id_reserva']); ?></td>
                                <td class="user-id">#<?php echo htmlspecialchars($reserva['id_usuario']); ?></td>
                                <td class="reserva-name"><?php echo htmlspecialchars($reserva['nombre_Reservante']); ?></td>
                                <td class="reserva-fecha"><?php 
                                    if ($reserva['fechaEntrada'] != '-' && $reserva['fechaEntrada'] != '') {
                                        echo date('d/m/Y', strtotime($reserva['fechaEntrada']));
                                    } else {
                                        echo '-';
                                    }
                                ?></td>
                                <td class="reserva-fecha"><?php 
                                    if ($reserva['fechaSalida'] != '-' && $reserva['fechaSalida'] != '') {
                                        echo date('d/m/Y', strtotime($reserva['fechaSalida']));
                                    } else {
                                        echo '-';
                                    }
                                ?></td>
                                <td class="reserva-cantidad-adultos"><?php echo htmlspecialchars($reserva['adultos']); ?></td>
                                <td class="reserva-cantidad-ninos"><?php echo htmlspecialchars($reserva['ninos']); ?></td>
                                <td class="reserva-cantidad-habitacion"><?php echo htmlspecialchars($reserva['habitacion']); ?></td>
                                <td class="reserva-email"><?php echo htmlspecialchars($reserva['email']); ?></td>
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