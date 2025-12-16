
<?php
session_start();
// Español por defecto
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

include __DIR__ . "/lang/" . $_SESSION['lang'] . ".php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conexion = new mysqli("localhost", "root", "", "villasBrenes");
    
    if ($conexion->connect_error) {
        echo '<div class="message error">'.$lang['db_connection_error'].'</div>';
    } else {
        $nombreReservante = $_POST['Nombre'];
        $FechaEntrada = $_POST['Fecha de Entrada'];
        $FechaSalida = $_POST['Fecha de Salida'];
        $adultos = $_POST['adultos']; 
        $niños = $_POST['ninos'];
        $habitaciones = $_POST['habitacion'];
        $email = trim($_POST['email']);
        $id_usuario = $_SESSION['id_usuario'] ?? 0;

        $stmt = $conexion->prepare("SELECT id_reserva, nombre_Reservante, fechaEntrada, fechaSalida, adultos, ninos, habitacion, email FROM reservas WHERE email = ? AND id_usuario = ?");
        $stmt->bind_param("si", $email, $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo '<div class="message success">'.$lang['reservas_found'].':</div>';
            while($row = $result->fetch_assoc()) {
                echo '<div class="reserva-item">';
                echo '<p>'.$lang['reserva'].' #' . $row['id_reserva'] . '</p>';
                echo '<p>'.$lang['nombre'].': ' . htmlspecialchars($row['nombre_Reservante']) . '</p>';
                echo '<p>'.$lang['entrada'].' ' . $row['fechaEntrada'] . ' - '.$lang['salida'].': ' . $row['fechaSalida'] . '</p>';
                echo '<p>'.$lang['adultos'].': ' . $row['adultos'] . ', '.$lang['ninos'].': ' . $row['ninos'] . '</p>';
                echo '<p>'.$lang['habitaciones'].': ' . $row['habitacion'] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<div class="message error">'.$lang['no_data_found'].'</div>';
        }
        
        $stmt->close();
        $conexion->close();
    }
}
?>
