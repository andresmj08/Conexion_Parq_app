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
    $valor_inicial = $objeto['valor_inicial'];
    $valor_final = $objeto['valor_final'];
    $tipo_vehiculo = $objeto['tipo_vehiculo'];

    
    // Creamos la sintaxis para el la consulta
    $consultar_parqs = "SELECT ST.id, ST.latitud_map, ST.longitud_map FROM sitios AS ST INNER JOIN tarifas AS TF ON TF.id_sitio = ST.id WHERE TF.id_tipo_vehiculo = '$tipo_vehiculo' AND (TF.valor BETWEEN '$valor_inicial' AND '$valor_final') AND ST.id_estado = 1";

    // Ejecutamos el Query
    $ejecucion = $conexion_bd->query($consultar_parqs);

    if($ejecucion -> num_rows > 0){
        while($row[] = $ejecucion -> fetch_assoc()){
            $registro = $row;
        }  
        
    }else{
        $registro = "Vacio";
    }

    echo json_encode($registro);
  
    
        
?>