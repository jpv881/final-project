<?php
session_start();
header("content-type: application/json");
include('conexion.php');

//if(is_null($_SESSION['user'])){
//       header('Location: index.html');
//       }


$nombre = $_GET['nombre'];
$primerApellido = $_GET['primerApellido'];
$segundoApellido = $_GET['segundoApellido'];
$direccion = $_GET['direccion'];
$dni = $_GET['dni'];
$ciudad = $_GET['ciudad'];
$codigoPostal = $_GET['codigoPostal'];
$telefono = $_GET['telefono'];
$email = $_GET['email'];
$repeatemail = $_GET['repeatemail'];
$pass = $_GET['pass'];
$repeatpass = $_GET['repeatpass'];
$ok = false;
$error = false;

$emailsIguales = true;
$passIguales = true;

$emailExistente = false;
$dniExistente = false;
$datos = array();

//comprueba que los datos introducidos en los input no excedan el limite
$nombreLargo = false;
$apellido1Largo = false;
$apellido2Largo = false;
$dniLargo = false;
$direccionLarga = false;
$ciudadLarga = false;
$codigoPostalLargo = false;
$telefonoLargo = false;
$passwordLarga = false;
$emailLargo = false;

if(strlen($nombre) > 50) $nombreLargo = true;
if(strlen($primerApellido) > 50) $apellido1Largo = true;
if(strlen($segundoApellido) > 50) $apellido2Largo = true;
if(strlen($direccion) > 50) $direccionLarga = true;
if(strlen($dni) > 10) $dniLargo = true;
if(strlen($ciudad) > 50) $ciudadLarga = true;
if(strlen($codigoPostal) > 5) $codigoPostalLargo = true;
if(strlen($telefono) > 20) $telefonoLargo = true;
if(strlen($email) > 50) $emailLargo = true;
if(strlen($pass) > 50) $passwordLarga = true;

if($nombreLargo || $apellido1Largo || $apellido2Largo || $direccionLarga || $dniLargo || $ciudadLarga || $codigoPostalLargo || $telefonoLargo || $emailLargo || $passwordLarga) $error = true;

//comprueba que el email no exista en la bd
$sqlEmail = "select * from usuario where email = '".$email."'";
$consultaEmail = mysqli_query($conexion, $sqlEmail);
$resultEmail = mysqli_fetch_array($consultaEmail);
if(sizeof($resultEmail)>0) $emailExistente= true;

//comprueba que el dni no exista en la bd
$sqlDni = "select * from usuario where dni = '".$dni."'";
$consultaDni = mysqli_query($conexion, $sqlDni);
$resultDni = mysqli_fetch_array($consultaDni);
if(sizeof($resultDni)>0) $dniExistente = true;

if($email != $repeatemail) $emailsIguales = false;
if($pass != $repeatpass) $passIguales = false;

$datos['email'] = $emailsIguales;
$datos['pass']= $passIguales;
$datos['emailRepetido'] = $emailExistente;
$datos['dniRepetido'] = $dniExistente;
$datos['nombreLargo'] = $nombreLargo;
$datos['apellido1Largo'] = $apellido1Largo;
$datos['apellido2Largo'] = $apellido2Largo;
$datos['direccionLarga'] = $direccionLarga;
$datos['dniLargo'] = $dniLargo;
$datos['ciudadLarga'] = $ciudadLarga;
$datos['codigoPostalLargo'] = $codigoPostalLargo;
$datos['telefonoLargo'] = $telefonoLargo;
$datos['emailLargo'] = $emailLargo;
$datos['passwordLarga'] = $passwordLarga;

if($emailsIguales && $passIguales && !$emailExistente && !$dniExistente && !$error){
    $sql = "insert into usuario (apellido1,apellido2,ciudad,codigo_postal,direccion,dni,email,fecha_alta,nombre,password,penalizado,rol,telefono) values 
    ('".$primerApellido."','".$segundoApellido."','".$ciudad."','".$codigoPostal."','".$direccion."','".$dni."','".$email."','".date('Y-m-d')."','".$nombre."','".md5($pass)."',false,1,'".$telefono."')";
    
    mysqli_query($conexion,$sql);
    $id = $conexion->insert_id;
    $_SESSION['user'] = $nombre;
    $_SESSION['id'] = $id;
    $_SESSION['rol'] = 1;
    $ok = true;
}
		
	$datos['ok'] = $ok;	
echo $_GET['callback']. '('. json_encode($datos) . ')';



?>