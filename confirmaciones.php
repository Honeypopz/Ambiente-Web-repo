
 <?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';
include __DIR__ . "/lang/" . ($_SESSION['lang'] ?? 'es') . ".php";

function notificarReservaEstadia($email, $reservationId) {
    global $lang;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tt3948578@gmail.com';
        $mail->Password = 'ygudlehieeihleyn';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('tt3948578@gmail.com', $lang['brand']);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = $lang['mail_subject_reserva'];
        $mail->Body    = sprintf($lang['mail_body_reserva'], $reservationId);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("MAIL ERROR: " . $mail->ErrorInfo);
        return false;
    }
}

function notificarReservaViaje($email, $viajeId, $destino, $fecha, $hora) {
    global $lang;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tt3948578@gmail.com';
        $mail->Password = 'ygudlehieeihleyn';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('tt3948578@gmail.com', $lang['brand']);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = $lang['mail_subject_viaje'];
        $mail->Body    = sprintf($lang['mail_body_viaje'], $viajeId, $destino, $fecha, $hora);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("MAIL ERROR: " . $mail->ErrorInfo);
        return false;
    }
}
?>
