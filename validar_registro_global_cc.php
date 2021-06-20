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
// $id_parq = $objeto['id_parq'];


//Actualizar todos los parqueaderos a registrado = 0

$reiniciar_registro_sitios = "UPDATE sitios SET registrado = 0";
$conexion_bd ->query($reiniciar_registro_sitios);


// Creamos la sintaxis para el la consulta de Matriculas en Camara y Comercio
$sql_matriculas_cc = "SELECT matricula FROM camara_comercio";

// Ejecutamos el Query
$obtener_matriculas_cc = $conexion_bd->query($sql_matriculas_cc);

// Guardamos en un Array las matriculas obtenidas
if($obtener_matriculas_cc -> num_rows > 0){
    while($resultado = mysqli_fetch_array($obtener_matriculas_cc)){
    
        $matriculas_cc[]= $resultado['matricula'];

    }
}


// Creamos la sintaxis para el la consulta de Matriculas en Camara y Comercio
$sql_matriculas_sitios = "SELECT id,matricula FROM sitios ORDER BY id DESC";

// Ejecutamos el Query
$obtener_matriculas_sitios = $conexion_bd->query($sql_matriculas_sitios);

// Guardamos en un Array las matriculas obtenidas
if($obtener_matriculas_sitios -> num_rows > 0){
    while($resultado = mysqli_fetch_array($obtener_matriculas_sitios)){
    
        $id_sitio= $resultado['id'];
        $matricula_sitios= $resultado['matricula'];

        $hallazgo = array_search($matricula_sitios, $matriculas_cc);

        if($hallazgo != 0){
            $conexion_bd ->query("UPDATE sitios SET registrado = 1 WHERE id = '$id_sitio'");
        }
    }

    echo json_encode("Registros_Actualizados");
}



    




?>