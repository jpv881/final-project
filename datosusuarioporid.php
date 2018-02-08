<?php
session_start();
header("content-type: application/json");
include('conexion.php');

if(is_null($_SESSION['user'])){
       header('Location: index.html');
       }

$datos = array();
$sql = "select * from usuario where id =".$_SESSION['id'];
$consulta = mysqli_query($conexion, $sql);
$result = mysqli_fetch_array($consulta);

$datos['nombre'] = $result['nombre'];
$datos['apellido1']= $result['apellido1'];;
$datos['apellido2'] = $result['apellido2'];;
$datos['direccion'] = $result['direccion'];;
$datos['ciudad'] = $result['ciudad'];;
$datos['codigo_postal'] = $result['codigo_postal'];;
$datos['dni'] = $result['dni'];;
$datos['telefono'] = $result['telefono'];;
$datos['email'] = $result['email'];;
$datos['fecha_alta'] = $result['fecha_alta'];;
$datos['fecha_baja'] = $result['fecha_baja'];;
$datos['fin_penalizacion'] = $result['fin_penalizacion'];;
$datos['observaciones'] = $result['observaciones'];;
$datos['penalizado'] = $result['penalizado'];;
$datos['rol'] = $result['rol'];;
$datos['password'] = md5($result['password']);

echo $_GET['callback']. '('. json_encode($datos) . ')';


?>