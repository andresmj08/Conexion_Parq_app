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
    $matricula = $objeto['matricula'];
    $nombres = $objeto['nombres'];
    $nit = $objeto['nit'];
    $direccion = $objeto['direccion'];
    $latitud_map = $objeto['latitud_map'];
    $longitud_map = $objeto['longitud_map'];
    $valor_carro = $objeto['valor_carro'];
    $valor_moto = $objeto['valor_moto'];
    $estado = 1;
    $apertura = $objeto['apertura'];
    $cierre = $objeto['cierre'];
    $hora = date('Y-m-d H:i:s');

    

    echo $matricula;


    // Validaciones de CAmpos Vacios
    if((is_null($matricula) or empty($matricula)) or (is_null($nombres) or empty($nombres)) or (is_null($nit) or empty($nit)) or (is_null($direccion) or empty($direccion)) or (is_null($latitud_map) or empty($latitud_map)) or (is_null($longitud_map) or empty($longitud_map)) or (is_null($valor_carro) or empty($valor_carro)) or (is_null($valor_moto) or empty($valor_moto))){
        echo json_encode("Vacios");
    }
    else{
            // Creamos la sintaxis para el Insert con los datos parseados
            $registro = "INSERT INTO sitios VALUES (NULL,'$matricula','$nombres','$nit','$direccion','$latitud_map','$longitud_map',1,'$hora',1, 0, '$apertura', '$cierre')";
            
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
?>