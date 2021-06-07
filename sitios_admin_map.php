<?php

    // Importar archivo de Cadena de Conexion a BD
    include("conexion.php");
    $conexion_bd = conectar();

    //Definir Zona Horaria en Colombia
    date_default_timezone_set("America/Bogota");

    // Recibimos los datos en Json
    $json = file_get_contents('php://input');

    // Descomprimimos los datos del Objeto
    $objeto = json_decode($json, true);


    // Creamos Variables para los parametros recibidos
    $id_admin = $objeto['id_admin'];


    $consulta_simple = "SELECT id,nombre,latitud_map, longitud_map FROM sitios WHERE id_admin = '$id_admin'";
  
    $resultado_consulta_simple = $conexion_bd->query($consulta_simple);

    if($resultado_consulta_simple -> num_rows > 0){
        while($row[] = $resultado_consulta_simple -> fetch_assoc()){
            $sitios_admin = $row;
        }  
        echo json_encode($sitios_admin);
    }else {
        echo json_encode("Vacio");
    }
    
    
?>