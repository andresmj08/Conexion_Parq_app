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
    $nombres = $objeto['nombres'];
    $apellidos = $objeto['apellidos'];
    $documento = $objeto['documento'];
    $telefono = $objeto['telefono'];
    $email = $objeto['email'];
    $pass = $objeto['pass'];


    if(is_null($pass)){
            // Creamos la sintaxis para actualizar el registro en la BD sin pass
            $update_admin_info = "UPDATE admin_sitios SET nombres = '$nombres', apellidos = '$apellidos', documento= '$documento', telefono = '$telefono', email = '$email' WHERE id  = '$id_admin'";
                        
            // Ejecutamos la cadena
            $validacion = $conexion_bd->query($update_admin_info);
            
    }else{
        
        $pass_encrypt = md5($pass);

        // Creamos la sintaxis para actualizar el registro en la BD con pass
        $update_admin_info = "UPDATE admin_sitios SET nombres = '$nombres', apellidos = '$apellidos', documento= '$documento', telefono = '$telefono', email = '$email', password = '$pass_encrypt' WHERE id  = '$id_admin'";
                                
        // Ejecutamos la cadena
        $validacion = $conexion_bd->query($update_admin_info);
    }


            
            echo json_encode("Actualizado");
          
    

?>