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

    
    // Creamos la sintaxis para el la consulta
    $consulta_id = "SELECT registrado FROM sitios WHERE id = '$id_parq'";

    // Ejecutamos el Query
    $consultar_parqs = $conexion_bd->query($consulta_id);

    if($consultar_parqs -> num_rows > 0){
        while($resultado = mysqli_fetch_array($consultar_parqs)){
            $registro=$resultado[0];
        }
    
    }else{
        $registro = "Vacio";
    }

        echo json_encode($registro);
  
    
        
?>