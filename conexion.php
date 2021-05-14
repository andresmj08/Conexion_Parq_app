<?php

function conectar(){
    $user_bd = 'root';
    $password_bd = '';
    $server_bd = 'localhost';
    $bd_name = 'parq_app';

    $conexion = mysqli_connect($server_bd , $user_bd , $password_bd , $bd_name) or die ('No es posible Conectarse a la Base de Datos ');
   //  $conexion = mysqli_connect( 'localhost' , 'root' , '' , 'gps_local');



    return $conexion;

}
?>