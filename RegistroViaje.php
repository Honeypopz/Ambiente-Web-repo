<?php
session_start();
require_once 'confirmaciones.php';
// Español por defecto
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

include __DIR__ . "/lang/" . $_SESSION['lang'] . ".php";

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

//Proceso de formulario
$success = false;
$errores = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //conexion bd
    $conexion = new mysqli("localhost", "root", "", "villasBrenes");
    
    if (!$conexion->connect_error) {
        //Obtener datos formulario
        $nombreReservante = trim($_POST['nombre']);
        $fechaEntrada = $_POST['fecha_entrada'];
        $fechaSalida = $_POST['fecha_salida'];
        $adultos = intval($_POST['adultos']);
        $ninos = intval($_POST['ninos']); 
        $habitacion = intval($_POST['habitacion']);
        $email = trim($_POST['email']);
        $id_usuario = $_SESSION['id_usuario'];
        
        //Validaciones
        if (empty($nombreReservante)) {
            $errores[] = "El nombre del reservante es obligatorio";
        }
        
        if (empty($fechaEntrada) || empty($fechaSalida)) {
            $errores[] = "Las fechas de entrada y salida son obligatorias";
        } elseif ($fechaEntrada >= $fechaSalida) {
            $errores[] = "La fecha de salida debe ser posterior a la fecha de entrada";
        }
        
        if ($adultos < 1) {
            $errores[] = "Debe haber al menos 1 adulto";
        }
        
        if ($ninos < 0) {
            $errores[] = "El número de niños no puede ser negativo";
        }
        
        if ($habitacion < 1) {
            $errores[] = "Debe reservar al menos 1 habitación";
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El correo electrónico no es válido";
        }
        
        //Insertar en bd
        if (empty($errores)) {
            $stmt = $conexion->prepare("INSERT INTO reservas (id_usuario, nombre_Reservante, fechaEntrada, fechaSalida, adultos, ninos, habitacion, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssiiis", $id_usuario, $nombreReservante, $fechaEntrada, $fechaSalida, $adultos, $ninos, $habitacion, $email);
            
            if ($stmt->execute()) {
                $success = true;
                // Obtener el ID de la reserva recién creada
                $reservaId = $conexion->insert_id;
                // Enviar correo de confirmación
                notificarReservaEstadia($email, $reservaId);
                $_POST = array();
            } else {
                $errores[] = "Error al guardar la reserva en la base de datos: " . $conexion->error;
            }
            
            $stmt->close();
        }
        
        $conexion->close();
    } else {
        $errores[] = "Error de conexión a la base de datos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Viaje - Villas Brenes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Css/estiloRegistroViaje.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">
            <h1>Villas Brenes</h1>
            <span class="subtitle">Solicitar Transporte</span>
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
        <div class="viaje-container">
            <div class="viaje-header">
                <h2>Solicitar Transporte</h2>
                <p>Completa los datos para solicitar tu viaje</p>
            </div>
            
            <form id="viajeForm" method="post" action="ConfirmacionViaje.php">
                <div class="form-group">
                    <label for="destino">Destino</label>
                    <input type="text" id="destino" name="destino" 
                           placeholder="Ej: Playa Jacó, Aeropuerto, Monteverde" required>
                </div>
                
                <div class="form-group">
                    <label for="cantidad">Cantidad de personas</label>
                    <input type="number" id="cantidad" name="cantidad" 
                           value="1" min="1" max="20" required>
                </div>
                
                <div class="form-group">
                    <label for="vehiculo">Tipo de vehículo</label>
                    <select id="vehiculo" name="vehiculo" required>
                        <option value="">Seleccione un vehículo</option>
                        <option value="automovil">Automóvil (1-4 personas)</option>
                        <option value="van">Van (5-8 personas)</option>
                        <option value="buseta">Buseta (9-15 personas)</option>
                        <option value="autobus">Autobús (16-20 personas)</option>
                    </select>
                </div>
                
                <div class="form-group fecha-hora-group">
                    <div>
                        <label for="fecha">Fecha del viaje</label>
                        <input type="date" id="fecha" name="fecha" required>
                    </div>
                    <div>
                        <label for="hora">Hora de recogida</label>
                        <input type="time" id="hora" name="hora" 
                               value="08:00" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="notas">Notas adicionales (opcional)</label>
                    <textarea id="notas" name="notas" rows="3" 
                              placeholder="Especificaciones especiales, puntos de referencia, etc."></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-solicitar">Solicitar Viaje</button>

                    <a href="dashboardCliente.php" class="btn-cancelar">
                        Cancelar
                    </a>
                </div>
            </form>
            
            <div class="info-conductores">
                <h3>Información de vehículos disponibles</h3>
                <div class="conductores-grid">
                    <div class="conductor-card">
                        <h4>Automóvil</h4>
                        <p>Capacidad: 1-4 personas</p>
                        <p>Precio: ₡15,000 - ₡25,000</p>
                    </div>
                    <div class="conductor-card">
                        <h4>Van</h4>
                        <p>Capacidad: 5-8 personas</p>
                        <p>Precio: ₡30,000 - ₡45,000</p>
                    </div>
                    <div class="conductor-card">
                        <h4>Buseta</h4>
                        <p>Capacidad: 9-15 personas</p>
                        <p>Precio: ₡50,000 - ₡70,000</p>
                    </div>
                    <div class="conductor-card">
                        <h4>Autobús</h4>
                        <p>Capacidad: 16-20 personas</p>
                        <p>Precio: ₡80,000 - ₡120,000</p>
                    </div>
                </div>
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

    
</body>
</html>