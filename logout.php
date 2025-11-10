<?php
session_start();
session_destroy();
header('Location: inicioCuenta.html');
exit;
?>