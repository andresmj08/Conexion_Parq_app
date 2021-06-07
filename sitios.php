<?php
    include("conexion.php");
    $conexion_bd = conectar();


    $consulta_simple = "SELECT id,nombre,latitud_map, longitud_map FROM sitios WHERE id_estado";

    $resultado_consulta_simple = $conexion_bd->query($consulta_simple);

    if($resultado_consulta_simple -> num_rows > 0){
        while($row[] = $resultado_consulta_simple -> fetch_assoc()){
            $sitios_temporal = $row;
        }  
        echo json_encode($sitios_temporal);
    }else {
        echo json_encode("Vacio");
    }
    
    
?>