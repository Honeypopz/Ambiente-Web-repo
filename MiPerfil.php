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

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "villasBrenes");
$usuario = null;

if (!$conexion->connect_error) {
    // Obtener datos del usuario
    $stmt = $conexion->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id_usuario']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
    }
}
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
                <p>Actualiza tus datos de contacto</p>
            </div>
            
            <?php if (isset($_GET['success'])): ?>
            <div class="message success" id="successMessage">
             Perfil actualizado correctamente
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
            <div class="message error" id="errorMessage">
             Error al actualizar el perfil
            </div>
            <?php endif; ?>
            
            <form id="profileForm" method="POST" action="actualizarPerfil.php">
                <div class="form-group">
                    <label for="nombre">
                     Nombre completo
                    </label>
                    <input type="text" id="nombre" name="nombre" 
                           value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="email">
                     Correo electrónico
                    </label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" 
                           required>
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

            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000);
            }
            
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
            
            // Validar del formulario
            const form = document.getElementById('profileForm');
            form.addEventListener('submit', function(e) {
                const nombre = document.getElementById('nombre').value.trim();
                const email = document.getElementById('email').value.trim();
                
                if (!nombre || !email) {
                    e.preventDefault();
                    alert('Completar todos los campos obligatorios');
                    return false;
                }
                
                if (!validateEmail(email)) {
                    e.preventDefault();
                    alert('Ingresar un correo electrónico válido');
                    return false;
                }
                
                return true;
            });
   
        });
    </script>
</body>
</html>