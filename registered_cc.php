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


    
            // Creamos la sintaxis para la consulta en la BD
            $consulta = "SELECT matricula FROM camara_comercio WHERE matricula = '$matricula'";
            
            // Ejecutamos la cadena
            $validacion = $conexion_bd->query($consulta);
            
            if($validacion->num_rows > 0){
                while($row[] = $validacion->fetch_assoc()){
                    $tem = $row;
                }
                echo json_encode("Existe");
            }
            else{   
                echo json_encode("Nada");
            };  
            
       

?>