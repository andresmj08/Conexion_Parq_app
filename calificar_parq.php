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
    $id_parq = $objeto['id_parq'];
    $valor = $objeto['valor'];
    $hora = date('Y-m-d H:i:s');

    // Creamos la sintaxis para el Insert con los datos parseados
             $calificacion = "INSERT INTO calificaciones VALUES (NULL,'$valor','$id_parq','$hora')";

             // Ejecutamos el Query
            $almacenar_registro = $conexion_bd->query($calificacion);

            if($almacenar_registro){
                echo json_encode("Registro_Calificacion");
            }
            else{
                echo json_encode("No pudo Crearse");
            } 
        
?>