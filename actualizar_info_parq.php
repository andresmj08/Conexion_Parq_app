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
    $nombre = $objeto['nombre'];
    $num_matricula = $objeto['matricula'];
    $nit = $objeto['nit'];
    $direccion = $objeto['direccion'];
    $servicios_adicionales = $objeto['servicios'];
    $estado = $objeto['estado'];
    $valor_carro = $objeto['valor_carro'];
    $valor_moto = $objeto['valor_moto'];
    $latitud_map = $objeto['latitud_map'];
    $longitud_map = $objeto['longitud_map'];


    // Validacion estado en app
    if($estado == false){
        $estado = 0;
    }else if($estado == 1 OR $estado == true){
        $estado = 1;
    }


    // Validacion de Matricula (Si vacio)
    $num_matricula = $objeto['matricula'];

    
    if(is_null($num_matricula) or empty($num_matricula)){
        $matricula = 0;
    }else{
        $matricula = $num_matricula;
    }
    
    // Validacion de Servicios

    if(is_null($servicios_adicionales) or empty($servicios_adicionales)){
        $servicios = ' ';
    }else{
        $servicios = $servicios_adicionales;
    }


    // Validacion de registro en Camara y Comercio
    $existe_matricula = 0;
    $registrado = 0;


    if($matricula != 0 ){

        if($existe_matricula == 0){

            $consultar_matricula = "SELECT id FROM sitios WHERE matricula = '$matricula' AND id <> '$id_parq'";
                
            // Ejecutamos la cadena
            $validacion_existencia = $conexion_bd->query($consultar_matricula);
            
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
                        $registrado = 1;
                    }
                    else{   
                        $registrado = 0;
                    }; 
                

            }; 

        };

    }


        // Validaciones de Campos Vacios
        if( (is_null($nombre) or empty($nombre)) or (is_null($nit) or empty($nit)) or (is_null($direccion) or empty($direccion)) or (is_null($valor_carro) or empty($valor_carro)) or (is_null($valor_moto) or empty($valor_moto))){
            echo json_encode("Vacios");
        }else{

            if($existe_matricula == 1){

                echo json_encode("ya_existe_registro");
            }else{

                $consultar_parq = "SELECT CONCAT(latitud,',',longitud)As Coordenadas FROM perimetro;";
                // Ejecutamos el Query
                $ejecucion = $conexion_bd->query($consultar_parq);
            
                if($ejecucion -> num_rows > 0){
                    while($row = $ejecucion -> fetch_assoc()){
                        $coordinates_bd[] = $row['Coordenadas'];
                    }  
                    
                }
                // Cerrar Perimetro
                $max_position_array = count($coordinates_bd);
                $coordinates_bd[$max_position_array] = $coordinates_bd[0];
        
                // Definimos variable con formato "lat,lon"
                $posicion_ingresada=  $latitud_map.",".$longitud_map;
        
        
                // Instanciamos la clase y definimos las variables para evaluar
                $pointLocation = new Validate_Point();
                $points = array($posicion_ingresada);
                $polygon = $coordinates_bd;

                
                foreach($points as $key => $point) {
                    $definicion_punto = $pointLocation->pointInPolygon($point, $polygon);
            
                    // Validacion de Punto dentro de Perimetro
                    if($definicion_punto == 0){
                        // Si esta Fuera del Perimetro
                        echo json_encode("Fuera_Perimetro");
                    }else{
                        // Creamos las sintaxis para actualizar el registro en la BD de Sitios y tarifas
                        $update_sitio = "UPDATE sitios SET matricula = '$matricula', nombre = '$nombre', nit= '$nit', direccion = '$direccion', id_estado = '$estado', servicios = '$servicios', registrado = '$registrado', latitud_map = '$latitud_map', longitud_map = '$longitud_map' WHERE id  = '$id_parq'";
                        $update_tarifa_carro = "UPDATE tarifas SET valor = '$valor_carro' WHERE id_sitio = '$id_parq' AND id_tipo_vehiculo = 1";                        
                        $update_tarifa_moto = "UPDATE tarifas SET valor = '$valor_moto' WHERE id_sitio = '$id_parq' AND id_tipo_vehiculo = 2";       

                        // Ejecutamos las cadenas
                        $aplicacion_sitio = $conexion_bd->query($update_sitio);
                        $aplicacion_tarifa_carro = $conexion_bd->query($update_tarifa_carro);
                        $aplicacion_tarifa_moto = $conexion_bd->query($update_tarifa_moto);

                            
                            echo json_encode("Actualizado");


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