<?php
    // Importar archivo de Cadena de Conexion a BD
    include("conexion.php");
    $conexion_bd = conectar();

    //Definir Zona Horaria en Colombia
    date_default_timezone_set("America/Bogota");

    
    // Creamos la sintaxis para el la consulta
    

    $datos_estado = "SELECT 
    (SELECT COUNT(*) FROM sitios WHERE id_estado = 0) AS Inactivos,
    (SELECT COUNT(*) FROM sitios WHERE id_estado = 1) AS Activos,
    (SELECT COUNT(*) FROM sitios WHERE registrado = 0) AS No_Registrado,
    (SELECT COUNT(*) FROM sitios WHERE registrado = 1) AS Registrado,
    (SELECT COUNT(*) FROM admin_sitios) AS Admins,
    (SELECT COUNT(*) FROM actividad) AS Actividad,
    (SELECT COUNT(*) FROM calificaciones GROUP BY id_sitio) AS Calificaciones";
    // Ejecutamos el Query
    $ejecucion = $conexion_bd->query($datos_estado);

    if($ejecucion -> num_rows > 0){
        while($row = $ejecucion -> fetch_assoc()){
            $parqs_por_estado = $row;
        }  
        
    }else{
        $parqs_por_estado = "Vacio";
    }

  
    echo json_encode($parqs_por_estado);
  

    
        
?>