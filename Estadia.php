<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $conexion = new mysqli("localhost", "root", "", "villasBrenes");
                
                if ($conexion->connect_error) {
                    echo '<div class="message error">Error de conexión a la base de datos</div>';
                } else {
                    $nombreReservante = $_POST['Nombre'];
                    $_POST['Fecha de Entrada']
                    $email = trim($_POST['email']);
                    
                    $stmt = $conexion->prepare("SELECT id_reserva, nombre_Reservante, fechaEntrada, fechaSalida, adultos, niños, habitación,email FROM reserva WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    
                }
            }
           
?>  