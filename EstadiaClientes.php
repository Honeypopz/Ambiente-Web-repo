
<?php
session_start();
//español por defecto
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

include __DIR__ . "/lang/" . $_SESSION['lang'] . ".php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conexion = new mysqli("localhost", "root", "", "villasBrenes");
    
    if ($conexion->connect_error) {
        echo '<div class="message error">Error de conexión a la base de datos</div>';
    } else {
        $nombreReservante = $_POST['Nombre'];
        $FechaEntrada = $_POST['Fecha de Entrada'];
        $FechaSalida = $_POST['Fecha de Salida'];
        $adultos = $_POST['adultos']; 
        $niños = $_POST['ninos'];
        $habitaciones = $_POST['habitacion'];
        $email = trim($_POST['email']);
            $stmt = $conexion->prepare("SELECT id_reserva, nombre_Reservante, fechaEntrada, fechaSalida, adultos, ninos, habitacion, email FROM reservas WHERE email = ? AND id_usuario = ?");
            $stmt->bind_param("si", $email, $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            
            //Resultados
            if ($result->num_rows > 0) {
                echo '<div class="message success">Reservas encontradas:</div>';
                while($row = $result->fetch_assoc()) {
                    echo '<div class="reserva-item">';
                    echo '<p>Reserva #' . $row['id_reserva'] . '</p>';
                    echo '<p>Nombre: ' . htmlspecialchars($row['nombre_Reservante']) . '</p>';
                    echo '<p>Entrada: ' . $row['fechaEntrada'] . ' - Salida: ' . $row['fechaSalida'] . '</p>';
                    echo '<p>Adultos: ' . $row['adultos'] . ', Niños: ' . $row['ninos'] . '</p>';
                    echo '<p>Habitaciones: ' . $row['habitacion'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<div class="message error">No se encontraron datos para este email</div>';
            }
            
            $stmt->close();
        }
        $conexion->close();
    }
}
?>