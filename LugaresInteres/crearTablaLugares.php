<?php
include "../configuracion/conexionBD.php";

try {
    $db = new BaseDeDatos();
    $conn = $db->obtenerConexion();

    $sql = "
        CREATE TABLE IF NOT EXISTS lugares_interes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            descripcion TEXT NOT NULL,
            imagen VARCHAR(255) NOT NULL
        );
    ";

    $conn->exec($sql);
    echo "Tabla creada correctamente.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
