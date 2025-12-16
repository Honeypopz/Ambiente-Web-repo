
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

//Conectar a bd
$conexion = new mysqli("localhost", "root", "", "villasBrenes");
$usuario = null;
$mensaje = '';
$tipo_mensaje = ''; 

if (!$conexion->connect_error) {
    //Obtener datos de usuario
    $stmt = $conexion->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id_usuario']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
    }
}

//Procesar actualizacion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_perfil'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $nuevoNombre = trim($_POST['nombre']);
    $nuevoEmail = trim($_POST['email']);
    
    //Validaciones
    $errores = [];
    
    if (empty($nuevoNombre)) {
        $errores[] = "El nombre completo es obligatorio";
    }
    
    if (empty($nuevoEmail)) {
        $errores[] = "El correo electrónico es obligatorio";
    } elseif (!filter_var($nuevoEmail, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido";
    }
    
    //Verificar email
    if (empty($errores)) {
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $nuevoEmail, $id_usuario);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errores[] = "Este correo electrónico ya está registrado por otro usuario";
        }
        $stmt->close();
    }
    
    //Actualizar perfil
    if (empty($errores)) {
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nuevoNombre, $nuevoEmail, $id_usuario);
        
        if ($stmt->execute()) {
            $mensaje = "Perfil actualizado correctamente";
            $tipo_mensaje = "success";
            
            $_SESSION['nombre_usuario'] = $nuevoNombre;
            $_SESSION['email_usuario'] = $nuevoEmail;
            
            $stmt = $conexion->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
            }
            
            $stmt->close();
        } else {
            $mensaje = "Error al actualizar el perfil: " . $conexion->error;
            $tipo_mensaje = "error";
        }
    } else {
        $mensaje = implode('<br>', $errores);
        $tipo_mensaje = "error";
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Villas Brenes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Css/estiloPerfilCliente.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">
            <h1>Villas Brenes</h1>
            <span class="subtitle">Mi Perfil</span>
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
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h2>Mi Información Personal</h2>
                <p>Actualiza tu nombre y correo establecidos</p>
            </div>
            
            <?php if ($mensaje): ?>
            <div class="message <?php echo $tipo_mensaje; ?>" id="message">
                <?php echo $mensaje; ?>
            </div>
            <?php endif; ?>
            
            <form id="profileForm" method="POST" action="MiPerfil.php">
                <input type="hidden" name="actualizar_perfil" value="1">
                
                <div class="form-group">
                    <label for="nombre">Nombre completo *</label>
                    <input type="text" id="nombre" name="nombre" 
                           value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" 
                           required placeholder="Ingresa tu nombre completo">
                </div>
                
                <div class="form-group">
                    <label for="email">Correo electrónico *</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" 
                           required placeholder="ejemplo@correo.com">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        Guardar Cambios
                    </button>
                    <a href="dashboardCliente.php" class="btn-cancel">
                        Cancelar
                    </a>
                </div>
            </form>

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
            // Ocultar mensajes después de 5 segundos
            setTimeout(() => {
                const message = document.getElementById('message');
                if (message) {
                    message.style.display = 'none';
                }
            }, 5000);
            
            //Validar formulario
            const form = document.getElementById('profileForm');
            form.addEventListener('submit', function(e) {
                const nombre = document.getElementById('nombre').value.trim();
                const email = document.getElementById('email').value.trim();
                
                if (!nombre || !email) {
                    e.preventDefault();
                    alert('Por favor completa todos los campos obligatorios');
                    return false;
                }
                
                if (!validateEmail(email)) {
                    e.preventDefault();
                    alert('Por favor ingresa un correo electrónico válido');
                    return false;
                }

                return true;
            });
            
            //Verificar cambios
            const nombreInput = document.getElementById('nombre');
            const emailInput = document.getElementById('email');
            const btnGuardar = document.querySelector('.btn-save');
            
            let nombreOriginal = nombreInput.value;
            let emailOriginal = emailInput.value;
            
            function checkChanges() {
                const nombreChanged = nombreInput.value !== nombreOriginal;
                const emailChanged = emailInput.value !== emailOriginal;
                
                if (nombreChanged || emailChanged) {
                    btnGuardar.style.background = '#ff9124eb';
                    btnGuardar.innerHTML = 'Guardar Cambios';
                } else {
                    btnGuardar.style.background = '#ccc';
                    btnGuardar.innerHTML = 'Sin cambios';
                }
            }
            
            nombreInput.addEventListener('input', checkChanges);
            emailInput.addEventListener('input', checkChanges);
            
            checkChanges();
        });
    </script>
</body>
</html>