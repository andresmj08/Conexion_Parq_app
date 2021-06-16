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
    $nombres = $objeto['nombres'];
    $apellidos = $objeto['apellidos'];
    $documento = $objeto['documento'];
    $telefono = $objeto['telefono'];
    $correo = $objeto['correo'];
    $pass = $objeto['pass'];
    $hora = date('Y-m-d H:i:s');


    $pass_encrypt = md5($pass);
    
    // Validaciones de Campos vacios
    if((is_null($nombres) or empty($nombres)) OR (is_null($apellidos) or empty($apellidos)) OR (is_null($documento) or empty($documento)) OR (is_null($telefono) or empty($telefono)) OR (is_null($correo) or empty($correo)) OR (is_null($pass) or empty($pass))){
        echo json_encode("Vacios");
    }
    else{
            // Creamos la sintaxis para el Insert con los datos parseados
             $registro = "INSERT INTO admin_sitios VALUES (NULL,'$nombres','$apellidos','$documento','$telefono','$correo','$pass_encrypt','$hora')";

             // Ejecutamos el Query
            $almacenar_registro = $conexion_bd->query($registro);

            if($almacenar_registro){
                echo json_encode("Registrado");
            }
        else{
            echo json_encode("No pudo Crearse");
        } 
    }
?>