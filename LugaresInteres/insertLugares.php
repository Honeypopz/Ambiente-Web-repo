<?php
include "../configuracion/conexionBD.php";

try {
    $db = new BaseDeDatos();
    $conn = $db->obtenerConexion();

$lugares = [
    ["Catarata La Fortuna", "Una espectacular catarata ideal para caminatas.", "media/catarata.jpg"],
    ["Volcán Arenal", "Un volcán icónico con aguas termales cercanas.", "media/arenal.jpg"],
    ["Lago Arenal", "Perfecto para tours acuáticos y paisajes hermosos.", "media/lago.jpg"],
    ["Puentes Colgantes", "Caminata elevada en medio del bosque tropical.", "media/puentes.jpg"],
    ["Rio Celeste", "Famoso mundialmente por su color azul intenso.", "media/rio.jpg"]
];

    $stmt = $conn->prepare("INSERT INTO lugares_interes (nombre, descripcion, imagen) VALUES (?, ?, ?)");

    foreach ($lugares as $lugar) {
        $stmt->execute($lugar);
    }

    echo "Lugares insertados correctamente.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
