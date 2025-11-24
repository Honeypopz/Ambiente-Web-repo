<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conexion = new mysqli("localhost", "root", "", "villasBrenes");
    
    if ($conexion->connect_error) {
    echo '<div class="message error">Error de conexión a la base de datos</div>';
    } else {
     //buscar conductor por vehiculo
     
     $sql = ("SELECT cantidad, destino,vehiculo,ubicacion,nombre_conductor FROM viaje v 
     INNER JOIN conductor c ON v.nombre_conductor = c.nombre_conductor");
     $viaje= $conexion->query($sql);       
      while($row = $sql->fetch_array()){
        echo $row['nombre_conductor']."<br>". $row['conductor']."<br>""<br>";
      }
    }
}
  
?>