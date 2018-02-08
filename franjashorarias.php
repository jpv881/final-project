<?php

session_start();
header("content-type: application/json");
include('conexion.php');

if($_SESSION['user'] == null){
    header('Location: index.html');
}


$datos = array();
$sql = "select * from franjas_horarias where activa = 1";
$consulta = mysqli_query($conexion,$sql);
while($fila=mysqli_fetch_array($consulta)){
    $arr = array();
    $arr['nombre']=$fila['nombre'];
    $arr['inicio']=$fila['inicio'];
    $arr['fin']=$fila['fin'];
    $datos[]=$arr;
}

echo $_GET['callback']. '('. json_encode($datos) . ')';

?>