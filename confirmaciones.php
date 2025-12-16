
 <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once __DIR__ . '/vendor/autoload.php';

function notificarReservaEstadia($email, $reservationId) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tt3948578@gmail.com';
        $mail->Password = 'ygudlehieeihleyn';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('tt3948578@gmail.com', 'Villas Brenes');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de Reserva';
        $mail->Body    = "Su reserva con ID $reservationId ha sido registrada correctamente.";

        $mail->send();
        return true;
    } catch (Exception $e) {
    error_log("MAIL ERROR: " . $mail->ErrorInfo);
    return false;
    }
}

function notificarReservaViaje($email, $viajeId, $destino, $fecha, $hora) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tt3948578@gmail.com';
        $mail->Password = 'ygudlehieeihleyn';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('tt3948578@gmail.com', 'Villas Brenes');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de Reserva';
        $mail->Body    = "<h3>Solicitud de Viaje Confirmada</h3>
            <p><strong>Viaje #$viajeId</strong></p>
            <p><strong>Destino:</strong> $destino</p>
            <p><strong>Fecha:</strong> $fecha</p>
            <p><strong>Hora:</strong> $hora</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
    error_log("MAIL ERROR: " . $mail->ErrorInfo);
    return false;
    }
}


 ?>