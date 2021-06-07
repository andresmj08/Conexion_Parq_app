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
    

    $consultar_parqs = "SELECT  ST.id,  ST.nombre,  ST.registrado, DATE_FORMAT( ST.hora_apertura, '%h:%i %p') AS apertura, DATE_FORMAT( ST.hora_cierre, '%h:%i %p') AS cierre,  ST.id_estado AS estado, TFC.valor AS hora_carro, TFM.valor AS hora_moto
    FROM  
        sitios AS ST
            INNER JOIN
        (SELECT id_sitio,valor FROM tarifas WHERE id_tipo_vehiculo = 1) AS TFC ON TFC.id_sitio = ST.id
            INNER JOIN
        (SELECT id_sitio,valor FROM tarifas WHERE id_tipo_vehiculo = 2) AS TFM ON TFM.id_sitio = ST.id";
    // Ejecutamos el Query
    $ejecucion = $conexion_bd->query($consultar_parqs);

    if($ejecucion -> num_rows > 0){
        while($row[] = $ejecucion -> fetch_assoc()){
            $registro_principal = $row;
        }  
        
    }else{
        $registro_principal = "Vacio";
    }

  
    echo json_encode($registro_principal);
  

    
        
?>