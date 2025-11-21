<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conexion = new mysqli("localhost", "root", "", "villasBrenes");
    
    if ($conexion->connect_error) {
    echo '<div class="message error">Error de conexión a la base de datos</div>';
    } else {
      //datos de solicitud
      $cantidad = $_POST['cantidad'];
     $destino = $_POST['destino'];
     $vehiculo = $_POST['vehiculo'];
     $ubicacion = $_POST['ubicacion'];
     
     //buscar conductor por vehiculo
     
    }
}
  
?>
    


