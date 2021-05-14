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
    $matricula = $objeto['matricula'];
    $nombres = $objeto['nombres'];
    $nit = $objeto['nit'];
    $direccion = $objeto['direccion'];
    $latitud_map = $objeto['latitud_map'];
    $longitud_map = $objeto['longitud_map'];
    $estado = 1;
    $hora = date('Y-m-d H:i:s');

    //id,matricula, nombre, nit, direccion, latitud_map, longitud_map, id_estado, creacion, id_admin, registrado

    if((is_null($matricula) or empty($matricula)) or (is_null($nombres) or empty($nombres)) or (is_null($nit) or empty($nit)) or (is_null($direccion) or empty($direccion)) or (is_null($latitud_map) or empty($latitud_map)) or (is_null($longitud_map) or empty($longitud_map))){
        echo json_encode("Vacios");
    }
    else{
            // Creamos la sintaxis para el Insert con los datos parseados
            $registro = "INSERT INTO sitios VALUES (NULL,'$matricula','$nombres','$nit','$direccion','$latitud_map','$longitud_map',1,'$hora',1, 0)";
            
            
            $almacenar_registro = $conexion_bd->query($registro);

            if($almacenar_registro){
                echo json_encode("Registrado");
            }
        else{
            echo json_encode("No pudo Crearse");
        } 
    }   
?>