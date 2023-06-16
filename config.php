<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$namedb = 'filesphp';

$conexion = mysqli_connect($host, $user, $pass, $namedb);

if(!$conexion){
    echo 'ERROR DE CONEXION'.mysqli_connect_error();
}


?>