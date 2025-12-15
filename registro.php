<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-key="titulo">Registro - Villas Brenes</title>
    <link rel="stylesheet" href="Css/estiloRegistro.css">
</head>

<body>

    <!-- Selector de idioma -->
    <div style="text-align:right; padding: 10px;">
        <select id="language-selector">
            <option value="es">Español</option>
            <option value="en">English</option>
        </select>
    </div>


    <div class="contenedor">
        <div class="form-box" id="registro">

            <?php
            //registro
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $conexion = new mysqli("localhost", "root", "", "villasBrenes");
                
                if ($conexion->connect_error) {
                    echo '<div class="message error">Error de conexión a la base de datos</div>';
                } else {
                    $nombre = trim($_POST['nombre']);
                    $email = trim($_POST['email']);
                    $contraseña = $_POST['contraseña'];
                    
                    //confirmacion de contrasena y correo
                    if (empty($nombre) || empty($email) || empty($contraseña)) {
                        echo '<div class="message error">Todos los campos son obligatorios</div>';
                    } else if (strlen($contraseña) < 6) {
                        echo '<div class="message error">La contraseña requiere mínimo 6 caracteres</div>';
                    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo '<div class="message error">El formato del email no es válido</div>';
                    } else {

                        //Verificar existencia de email
                        $check_email = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
                        $check_email->bind_param("s", $email);
                        $check_email->execute();
                        $check_email->store_result();
                        
                        if ($check_email->num_rows > 0) {
                            echo '<div class="message error">El email ya está registrado</div>';
                        } else {
                            //Registrar usuario
                            $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);
                            $rol = 'cliente';
                            
                            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, contraseña, rol) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("ssss", $nombre, $email, $contraseña_hash, $rol);
                            
                            if ($stmt->execute()) {
                                echo '<div class="message exito">Cuenta creada correctamente. Redirigiendo...</div>';
                                echo '<script>setTimeout(function() { window.location.href = "login.php"; }, 2000);</script>';
                            } else {
                                echo '<div class="message error">Error en el registro</div>';
                            }
                        }
                    }
                }
            }
            ?>

            <form method="POST" action="registro.php">
                <h2 data-key="registro_titulo">Registro</h2>

                <input type="text" name="nombre" placeholder="Nombre completo" data-key="nombre_ph" required>
                <input type="email" name="email" placeholder="Correo electrónico" data-key="correo_ph" required>
                <input type="password" name="contraseña" placeholder="Contraseña (mínimo 6 caracteres)" data-key="contra_ph" required>

                <button type="submit" data-key="registrarme_btn">Registrarme</button>

                <p data-key="tienes_cuenta">¿Ya tienes cuenta?
                    <a href="login.php" data-key="iniciar_sesion_link">Iniciar sesión</a>
                </p>
            </form>

        </div>
    </div>

<script>
const translations = {
    es: {
        titulo: "Registro - Villas Brenes",
        registro_titulo: "Registro",
        nombre_ph: "Nombre completo",
        correo_ph: "Correo electrónico",
        contra_ph: "Contraseña (mínimo 6 caracteres)",
        registrarme_btn: "Registrarme",
        tienes_cuenta: "¿Ya tienes cuenta?",
        iniciar_sesion_link: "Iniciar sesión"
    },
    en: {
        titulo: "Register - Villas Brenes",
        registro_titulo: "Sign Up",
        nombre_ph: "Full name",
        correo_ph: "Email address",
        contra_ph: "Password (minimum 6 characters)",
        registrarme_btn: "Sign Up",
        tienes_cuenta: "Already have an account?",
        iniciar_sesion_link: "Log In"
    }
};


document.getElementById("language-selector").addEventListener("change", function () {
    const lang = this.value;

    
    document.querySelectorAll("[data-key]").forEach(element => {
        const key = element.getAttribute("data-key");


        if (element.placeholder !== undefined && element.hasAttribute("placeholder")) {
            element.placeholder = translations[lang][key];
        } else {
            element.textContent = translations[lang][key];
        }
    });
});
</script>

</body>
</html>
