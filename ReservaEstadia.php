
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Estadía- Villas Brenes</title>
    <link rel="stylesheet" href="Css/estiloLogin.css">
</head>

<body>
    <?php
        session_start();
        require_once __DIR__ . '/configuracion/conexionBD.php';
        require_once 'confirmaciones.php';

        $database = new BaseDeDatos();
        $pdo = $database->obtenerConexion();

        if (isset($_POST['crear_reserva'])) {

            
            $usuarioId = (int) $_SESSION['id_usuario'];

            $nombre        = $_POST['nombre'];
            $fechaEntrada  = $_POST['fecha_entrada'];
            $fechaSalida   = $_POST['fecha_salida'];
            $personas      = (int) $_POST['personas'];
            $ninos         = (int) $_POST['ninos'];
            $habitaciones  = (int) $_POST['hab'];

            // reserva a la bd
            $sql = "
                INSERT INTO reservas
                (usuario_id, reservation_date, status, email_sent)
                VALUES (?, ?, 'COMPLETED', 0)
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $usuarioId,
                $fechaEntrada
            ]);

            //id de la ultima reserva
            $reservaId = $pdo->lastInsertId();

            //join para sacar email
            $sql = "
                SELECT u.email
                FROM reservas r
                JOIN usuarios u ON r.usuario_id = u.id
                WHERE r.id = ?
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$reservaId]);
            $email = $stmt->fetchColumn();

            // email de confirmacion
            if ($email) {
                notificarReservaEstadia($email, $reservaId);

                // email enviado en la bd
                $sql = "UPDATE reservas SET email_sent = 1 WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$reservaId]);
            }

            echo "Reserva creada y correo enviado correctamente.";
        }
    ?>

    <div class="contenedor">
        <div class="form-box" id="reservaViaje">
            <form method="post">
                <h2>Reserva tu Estadía</h2>
                <input type="text" name="nombre" placeholder="Nombre Reservante" required>

                <input type="date" name="fecha_entrada" required>
                <input type="date" name="fecha_salida" required>

                <input type="number" name="personas" value="1" required>
                <input type="number" name="ninos" value="0" required>
                <input type="number" name="hab" required>
                <button type="submit" name="crear_reserva">Reservar</button>
            </form>
            <div id="message" class="message"></div>
        </div>
    </div>
</body>

</html>