

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
    <title>Reservar Estadía - Villas Brenes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Css/estiloReservaEstadia.css">
</head>

<body>

    <nav class="navbar">
        <div class="nav-brand">
            <h1>Villas Brenes</h1>
            <span class="subtitle">Reservar Estadía</span>
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
        <div class="reserva-container">
            <div class="reserva-header">
                <h2>Realizar Reserva</h2>
                <p>Completa los datos para reservar tu estadía</p>
            </div>
            
            <?php if ($success): ?>
            <div class="message success" id="successMessage">
                ¡Reserva realizada correctamente! Se ha guardado en el sistema.
            </div>
            <?php endif; ?>
            
            <?php if (!empty($errores)): ?>
            <div class="message error" id="errorMessage">
                <?php echo implode('<br>', $errores); ?>
            </div>
            <?php endif; ?>
            
            <form id="reservaForm" method="POST" action="ReservaEstadia.php">
                <div class="form-group">
                    <label for="nombre">Nombre del reservante</label>
                    <input type="text" id="nombre" name="nombre" 
                           value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : htmlspecialchars($_SESSION['nombre_usuario']); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($_SESSION['email_usuario'] ?? ''); ?>" 
                           required>
                </div>
                
                <div class="form-group fecha-group">
                    <div>
                        <label for="fecha_entrada">Fecha de entrada</label>
                        <input type="date" id="fecha_entrada" name="fecha_entrada" 
                               value="<?php echo isset($_POST['fecha_entrada']) ? htmlspecialchars($_POST['fecha_entrada']) : date('Y-m-d'); ?>" 
                               min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div>
                        <label for="fecha_salida">Fecha de salida</label>
                        <input type="date" id="fecha_salida" name="fecha_salida" 
                               value="<?php echo isset($_POST['fecha_salida']) ? htmlspecialchars($_POST['fecha_salida']) : date('Y-m-d', strtotime('+1 day')); ?>" 
                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>
                </div>
                
                <div class="form-group personas-group">
                    <div>
                        <label for="adultos">Número de adultos</label>
                        <input type="number" id="adultos" name="adultos" 
                               value="<?php echo isset($_POST['adultos']) ? htmlspecialchars($_POST['adultos']) : '1'; ?>" 
                               min="1" max="10" required>
                    </div>
                    <div>
                        <label for="ninos">Número de niños</label>
                        <input type="number" id="ninos" name="ninos" 
                               value="<?php echo isset($_POST['ninos']) ? htmlspecialchars($_POST['ninos']) : '0'; ?>" 
                               min="0" max="10" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="habitacion">Número de habitaciones</label>
                    <input type="number" id="habitacion" name="habitacion" 
                           value="<?php echo isset($_POST['habitacion']) ? htmlspecialchars($_POST['habitacion']) : '1'; ?>" 
                           min="1" max="10" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-reservar">
                        Reservar Ahora
                    </button>
                    <a href="dashboardCliente.php" class="btn-cancelar">
                        Cancelar
                    </a>
                </div>
            </form>

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
            setTimeout(() => {
                const messages = document.querySelectorAll('.message');
                messages.forEach(msg => msg.style.display = 'none');
            }, 5000);
            
            <?php if ($success): ?>
                document.getElementById('reservaForm').reset();
            <?php endif; ?>
            
            //Validaciones
            const form = document.getElementById('reservaForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const nombre = document.getElementById('nombre').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const fechaEntrada = document.getElementById('fecha_entrada').value;
                    const fechaSalida = document.getElementById('fecha_salida').value;
                    const adultos = document.getElementById('adultos').value;
                    const ninos = document.getElementById('ninos').value;
                    const habitacion = document.getElementById('habitacion').value;

                    if (!nombre || !email || !fechaEntrada || !fechaSalida) {
                        e.preventDefault();
                        alert('Por favor completa todos los campos obligatorios');
                        return false;
                    }
                    
                    if (new Date(fechaEntrada) >= new Date(fechaSalida)) {
                        e.preventDefault();
                        alert('La fecha de salida debe ser posterior a la fecha de entrada');
                        return false;
                    }
                    
                    if (parseInt(adultos) < 1) {
                        e.preventDefault();
                        alert('Debe haber al menos 1 adulto');
                        return false;
                    }
                    
                    if (parseInt(ninos) < 0) {
                        e.preventDefault();
                        alert('El número de niños no puede ser negativo');
                        return false;
                    }
                    
                    if (parseInt(habitacion) < 1) {
                        e.preventDefault();
                        alert('Debe reservar al menos 1 habitación');
                        return false;
                    }
                    
                    return true;
                });
            }
            
            //Fecha de salida minima ante la fecha de entrada
            const fechaEntrada = document.getElementById('fecha_entrada');
            const fechaSalida = document.getElementById('fecha_salida');
            
            if (fechaEntrada && fechaSalida) {
                fechaEntrada.addEventListener('change', function() {
                    const fechaMin = new Date(this.value);
                    fechaMin.setDate(fechaMin.getDate() + 1);
                    fechaSalida.min = fechaMin.toISOString().split('T')[0];
                    
                    //evitar que fecha de salida sea antes de entrada
                    if (new Date(fechaSalida.value) < fechaMin) {
                        fechaSalida.value = fechaMin.toISOString().split('T')[0];
                    }
                });
            }
        });
    </script>
</body>

</html>