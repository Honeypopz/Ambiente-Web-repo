
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
$usuarios = [];
$totalUsuarios = 0;

if (!$conexion->connect_error) {
    //Obtener usuarios
    $result = $conexion->query("SELECT id, nombre, email, fecha_registro, rol FROM usuarios ORDER BY fecha_registro DESC");
    
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    
    //Contar usuarios
    $result = $conexion->query("SELECT COUNT(*) as total FROM usuarios");
    if ($row = $result->fetch_assoc()) {
        $totalUsuarios = $row['total'];
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Usuarios - Admin Villas Brenes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Css/estiloClientesAdmin.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">
            <h1>Villas Brenes</h1>
            <span class="subtitle">Visualizar Usuarios</span>
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
                <h2>Lista de Usuarios Registrados</h2>
                <p>Consulta todos los usuarios del sistema</p>
            </div>
            
            <div class="stats-summary">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $totalUsuarios; ?></span>
                    <span class="stat-label">Usuarios Totales</span>
                </div>
            </div>
            
            <div class="users-table-container">
                <?php if (empty($usuarios)): ?>
                    <div class="empty-state">
                        <i class="bi bi-people"></i>
                        <h3>No hay usuarios registrados</h3>
                        <p>No se encontraron usuarios en el sistema.</p>
                    </div>
                <?php else: ?>
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Fecha de Registro</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td class="user-id">#<?php echo $usuario['id']; ?></td>
                                <td class="user-name"><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td class="user-email"><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td class="user-date"><?php echo date('d/m/Y H:i', strtotime($usuario['fecha_registro'])); ?></td>
                                <td class="user-role">
                                    <span class="role-badge <?php echo $usuario['rol']; ?>">
                                        <?php echo strtoupper($usuario['rol']); ?>
                                    </span>
                                </td>
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