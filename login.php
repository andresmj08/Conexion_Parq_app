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
    $correo = $objeto['email'];
    $clave = $objeto['pass'];


    $clave_encrypt = md5($clave);


    // Validaciones de Campos vacios
    if((is_null($correo) or empty($correo)) OR (is_null($clave) or empty($clave))){
        echo json_encode("Vacios");
    }
    else{

    
            // Creamos la sintaxis para la consulta en la BD
            $consulta = "SELECT id, nombres FROM admin_sitios WHERE email = '$correo' AND password = '$clave_encrypt'";
            
            // Ejecutamos la cadena
            $validacion = $conexion_bd->query($consulta);
            
            if($validacion->num_rows > 0){
                while($row = $validacion->fetch_assoc()){
                    $info_admin = $row;
                }
                echo json_encode($info_admin);
            }
            else{   
                echo json_encode("Incorrecto");
            };  
            
        };
    

?>