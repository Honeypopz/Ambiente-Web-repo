
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Villas Brenes</title>
    <link rel="stylesheet" href="Css/estiloLogin.css">
</head>

<body>
    <div class="contenedor">
        <div class="form-box" id="sesion">
            <?php
            session_start();
            //español por defecto
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

include __DIR__ . "/lang/" . $_SESSION['lang'] . ".php";
            
            //login
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $conexion = new mysqli("localhost", "root", "", "villasBrenes");
                
                if ($conexion->connect_error) {
                    echo '<div class="message error">Error de conexión a la base de datos</div>';
                } else {
                    $email = trim($_POST['email']);
                    $contraseña = $_POST['contraseña'];
                    
                    $stmt = $conexion->prepare("SELECT id, nombre, email, contraseña, rol FROM usuarios WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows === 1) {
                        $usuario = $result->fetch_assoc();
                        
                        if (password_verify($contraseña, $usuario['contraseña'])) {
                            // Login correcto
                            $_SESSION['id_usuario'] = $usuario['id'];
                            $_SESSION['nombre_usuario'] = $usuario['nombre'];
                            $_SESSION['email_usuario'] = $usuario['email'];
                            $_SESSION['rol_usuario'] = $usuario['rol'];
                            
                            echo '<div class="message exito">Inicio de sesión exitoso. Un momento...</div>';
                            echo '<script>setTimeout(function() { window.location.href = "';
                            echo ($usuario['rol'] === 'administrador') ? 'dashboardAdmin.php' : 'dashboardCliente.php';
                            echo '"; }, 1500);</script>';
                        } else {
                            echo '<div class="message error">Credenciales incorrectas</div>';
                        }
                    } else {
                        echo '<div class="message error">Credenciales incorrectas</div>';
                    }
                }
            }
            ?>
            
            <form method="POST" action="login.php">
                <h2>Iniciar Sesión</h2>
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <input type="password" name="contraseña" placeholder="Contraseña" required>
                <button type="submit">Iniciar Sesión</button>
                <p>¿No tienes cuenta? <a href="registro.php">Registrarme</a></p>
            </form>
            <div id="message" class="message"></div>
        </div>
    </div>
</body>
</html>