<?php
session_start();

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    if ($lang === 'es' || $lang === 'en') {
        $_SESSION['lang'] = $lang;
    }
}

// Regresa a la página anterior (si no hay referrer, ir a inicio)
$back = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'inicioCuenta.php';
header("Location: " . $back);
exit;
