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
    

    $consultar_parq = "SELECT id, nombre, registrado, DATE_FORMAT(hora_apertura, '%h:%i %p') AS apertura, DATE_FORMAT(hora_cierre, '%h:%i %p') AS cierre,
                            (SELECT valor FROM tarifas WHERE id_tipo_vehiculo = 1 AND id_sitio =  '$id_parq') AS hora_carro,
                            (SELECT valor FROM tarifas WHERE id_tipo_vehiculo = 2 AND id_sitio =  '$id_parq') AS hora_moto
                        FROM  sitios WHERE id =  '$id_parq'";
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