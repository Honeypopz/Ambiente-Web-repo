
 <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

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
        echo "Correo enviado correctamente.";
    } catch (Exception $e) {
        echo "Error al enviar correo: {$mail->ErrorInfo}";
    }
}


 ?>