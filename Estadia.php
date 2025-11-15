<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $conexion = new mysqli("localhost", "root", "", "villasBrenes");
                
                if ($conexion->connect_error) {
                    echo '<div class="message error">Error de conexión a la base de datos</div>';
                } else {
                    $nombreReservante = $_POST['Nombre'];
                    $FechaEntrada = $_POST['Fecha de Entrada'];
                    $FechaSalida = $_POST['Fecha de Salida'];
                    $adultos = $_POST['Fecha de Salida'];
                    $niños = $_POST['Fecha de Salida'];
                    $habitaciones = $_POST['Fecha de Salida'];
                    $email = trim($_POST['email']);
                    
                    $stmt = $conexion->prepare("SELECT nombre_Reservante, fechaEntrada, fechaSalida, adultos, niños, habitación,email FROM reserva WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                }
            }
           
?>  