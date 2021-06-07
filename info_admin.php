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


    
    // Creamos la sintaxis para el la consulta
    

    $consultar_parq = "SELECT id, nombres, apellidos, documento, telefono, email, password FROM  admin_sitios WHERE id =  '$id_admin'";
    // Ejecutamos el Query
    $ejecucion = $conexion_bd->query($consultar_parq);

    if($ejecucion -> num_rows > 0){
        while($row = $ejecucion -> fetch_assoc()){
            $registro_principal = $row;
        }  
        
    }else{
        $registro_principal = "Vacio";
    }

  
    echo json_encode($registro_principal);
  

    
        
?>