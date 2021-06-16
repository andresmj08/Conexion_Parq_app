<?php

    // Importar archivo de Cadena de Conexion a BD
    include("conexion.php");
    $conexion_bd = conectar();




    // Traer puntos extremos de Poligono

    $consultar_parq = "SELECT CONCAT(latitud,',',longitud)As Coordenadas FROM perimetro;";
    // Ejecutamos el Query
    $ejecucion = $conexion_bd->query($consultar_parq);

    if($ejecucion -> num_rows > 0){
        while($row = $ejecucion -> fetch_assoc()){
            $coordinates_bd[] = $row['Coordenadas'];
        }  
        
    }else{
        $registro_principal = "Vacio";
    }

  
    
    $max_position_array = count($coordinates_bd);

    $coordinates_bd[$max_position_array] = $coordinates_bd[0];

    // echo json_encode($coordinates_bd);




    // -------------------------------------------------------


    // Recibimos los datos en Json
    $json = file_get_contents('php://input');

    // Descomprimimos los datos del Objeto
    $objeto = json_decode($json, true);

    // Creamos Variables para los parametros recibidos


    $num_matricula = $objeto['matricula'];

    if(is_null($num_matricula) or empty($num_matricula)){
        $matricula = 0;
    }else{
        $matricula = $num_matricula;
    }
    
    $nombres = $objeto['nombres'];
    $nit = $objeto['nit'];
    $direccion = $objeto['direccion'];
    $latitud_map = $objeto['latitud_map'];
    $longitud_map = $objeto['longitud_map'];
    $valor_carro = $objeto['valor_carro'];
    $valor_moto = $objeto['valor_moto'];
    $apertura = $objeto['apertura'];
    $cierre = $objeto['cierre'];
    $hora = date('Y-m-d H:i:s');
    


    // Definimos variable con formato "lat,lon"
    $posicion_ingresada=  $latitud_map.",".$longitud_map;


    // Instanciamos la clase y definimos las variables para evaluar
    $pointLocation = new Validate_Point();
    $points = array($posicion_ingresada);
    $polygon = $coordinates_bd;





// Las últimas coordenadas tienen que ser las mismas que las primeras, para "cerrar el círculo"
    foreach($points as $key => $point) {
    //echo "El Punto " . ($key+1) . " ($point): " . $pointLocation->pointInPolygon($point, $polygon)


    // Iniciar validacion de punto sobre perimetro
    $definicion_punto = $pointLocation->pointInPolygon($point, $polygon);

    // Validacion de Punto dentro de Perimetro
    if($definicion_punto == 0){
        // Si esta Fuera del Perimetro
        echo json_encode("Fuera_Perimetro");
    }else{

        $servicios_adicionales = $objeto['servicios'];

        if(is_null($servicios_adicionales) or empty($servicios_adicionales)){
            $servicios = ' ';
        }else{
            $servicios = $servicios_adicionales;
        }


        $existe_matricula = 0;


        // Validacion de Camara y Comercio

        if($matricula != 0 ){

            if($existe_matricula == 0){

                $consutar_matricula = "SELECT id FROM sitios WHERE matricula = '$matricula'";
                    
                // Ejecutamos la cadena
                $validacion_existencia = $conexion_bd->query($consutar_matricula);

                if($validacion_existencia->num_rows > 0){
                    while($row[] = $validacion_existencia->fetch_assoc()){
                        $tem = $row;
                    }
                    $existe_matricula = 1;
                }
                else{   
                    
                    $consulta_cc = "SELECT matricula FROM camara_comercio WHERE matricula = '$matricula'";
                
                        // Ejecutamos la cadena
                        $validacion = $conexion_bd->query($consulta_cc);
                        
                        if($validacion->num_rows > 0){
                            while($row[] = $validacion->fetch_assoc()){
                                $tem = $row;
                            }
                            $estado = 1;
                        }
                        else{   
                            $estado = 0;
                        }; 
                    

                }; 

            };

            


        }

        


        // Validaciones de Campos Vacios
        if( (is_null($nombres) or empty($nombres)) or (is_null($nit) or empty($nit)) or (is_null($direccion) or empty($direccion)) or (is_null($latitud_map) or empty($latitud_map)) or (is_null($longitud_map) or empty($longitud_map)) or (is_null($valor_carro) or empty($valor_carro)) or (is_null($valor_moto) or empty($valor_moto))){
            echo json_encode("Vacios");
        }
        else{

            if($existe_matricula == 1){

                echo json_encode("ya_existe_registro");
            }
            else {
                    // Creamos la sintaxis para el Insert con los datos parseados
                    $registro = "INSERT INTO sitios VALUES (NULL,'$matricula','$nombres','$nit','$direccion','$latitud_map','$longitud_map',1,'$hora',1, $estado, '$apertura', '$cierre', '$servicios')";
        
                    // Ejecutamos el Query
                    $almacenar_registro = $conexion_bd->query($registro);
        
                    if($almacenar_registro){
        
                        $consultar_ultimo_id = "SELECT MAX(id) FROM sitios";
                        $consultar_id = $conexion_bd->query($consultar_ultimo_id);
        
                            if($consultar_id -> num_rows > 0){                        
                                while($resultado = mysqli_fetch_array($consultar_id)){
                                        $id_parq=$resultado[0];
        
                                        $insertar_valores= "INSERT INTO tarifas VALUES (NULL,'$id_parq', 1, 1, '$valor_carro'),(NULL,'$id_parq', 1, 2, '$valor_moto')";
                                        $insertar = $conexion_bd->query($insertar_valores);
        
                                        if($insertar){
                                            echo json_encode("Registrado");
                                        }
                                        else{
                                                echo json_encode("No pudo Crearse");
                                            } 
                                }
                            } else { 
                                echo json_encode("No devolvio ID"); 
                            }
                            
                        }
                        else{
                            echo json_encode("Ni se registro");
                    }

                }

        }
        




    }

}   


class Validate_Point {
    var $pointOnVertex = true; // Checar si el punto se encuentra exactamente en uno de los vértices?

    function pointLocation() {
    }

        function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        $this->pointOnVertex = $pointOnVertex;

        // Transformar la cadena de coordenadas en matrices con valores "x" e "y"
        $point = $this->pointStringToCoordinates($point);
        $vertices = array(); 
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex); 
        }

        // Checar si el punto se encuentra exactamente en un vértice
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return 0;
        }

        // Checar si el punto está adentro del poligono o en el borde
        $intersections = 0; 
        $vertices_count = count($vertices);

        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Checar si el punto está en un segmento horizontal
                return "boundary";
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $point['x']) { // Checar si el punto está en un segmento (otro que horizontal)
                    return "boundary";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++; 
                }
            } 
        } 
        // Si el número de intersecciones es impar, el punto está dentro del poligono. 
        if ($intersections % 2 != 0) {
            //return "Dentro";
            return 1;
        } else {
            // return "Fuera de Zona";
            return 0;
        }
    }

    function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }

    }

    function pointStringToCoordinates($pointString) {
        $coordinates = explode(",", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }

}


?>