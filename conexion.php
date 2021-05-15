<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");

    $method = $_SERVER['REQUEST_METHOD'];
    if($method == "OPTIONS"){
        die();
    }

    function conectar(){
        $user_bd = 'root';
        $password_bd = '';
        $server_bd = 'localhost';
        $bd_name = 'parq_app';

        $conexion = mysqli_connect($server_bd , $user_bd , $password_bd , $bd_name) or die ('No es posible Conectarse a la Base de Datos ');




        return $conexion;

    }
?>