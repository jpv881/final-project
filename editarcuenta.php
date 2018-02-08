<?php
session_start();
header("content-type: application/json");
include('conexion.php');

if(is_null($_SESSION['user'])){
       header('Location: index.html');
    }

$datos = array();
$ok = false;

/* si es el submit del formulario de contraseñas*/
if($_GET['formPassword'] == 'true'){

    $pass = $_GET['pass'];
    $newPass = $_GET['newPass'];
    $confirmPass = $_GET['confirmPass'];
    $passErronea = false;
    $passIguales = true;
    $passwordLarga = false;
    $newPasswordLarga = false;
    if(strlen($pass) > 50) $passwordLarga = true;
    
    $sqlPass ="select password from usuario where id=".$_SESSION['id'];
    $consultaPass = mysqli_query($conexion, $sqlPass);
    $resultPass = mysqli_fetch_array($consultaPass);
    
    if(md5($pass) != $resultPass['password']) $passErronea = true;
    
    if($newPass != $confirmPass) $passIguales = false;
    if(strlen($newPass)>50 || strlen($confirmPass)>50){
        $newPasswordLarga = true;
    }
    $datos['passIguales']= $passIguales;
    $datos['passwordLarga'] = $passwordLarga;
    $datos['newPasswordLarga'] = $newPasswordLarga;
    $datos['passErronea'] = $passErronea;
    
    if($passIguales && !$passwordLarga && !$newPasswordLarga && !$passErronea){
        $sql = "update usuario set password = '".md5($newPass). "' where id=".$_SESSION['id'];
        $consulta = mysqli_query($conexion, $sql);
        mysqli_fetch_array($consulta);

        $ok = true;
    }
    /* si es el formulario de datos */
}else if($_GET['formPassword'] == 'false'){
    $nuevoEmailOcupado = false;
    /* Consulta el email y dni existentes antes de editar los datos por si hay cambios*/
    $sqlCambioEmail = "select dni, email from usuario where id = ".$_SESSION['id'];
    $consultaCambioEmail = mysqli_query($conexion, $sqlCambioEmail);
    $resultCambioEmail = mysqli_fetch_array($consultaCambioEmail);
    if(sizeof($resultCambioEmail >0)){
        $nuevoEmailExistente = true;
    }
    
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
    $emailsIguales = true;
    $emailExistente = false;
    $dniExistente = false;
    
    //comprueba que los datos introducidos en los input no excedan el limite
    $nombreLargo = false;
    $apellido1Largo = false;
    $apellido2Largo = false;
    $dniLargo = false;
    $direccionLarga = false;
    $ciudadLarga = false;
    $codigoPostalLargo = false;
    $telefonoLargo = false;
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
    
    /* si el email cambia comprueba que el nuevo no exista en la bd*/
if($resultCambioEmail['email'] != $_GET['email']){
    //comprueba que el email no exista en la bd
    $sqlEmail = "select * from usuario where email = '".$email."'";
    $consultaEmail = mysqli_query($conexion, $sqlEmail);
    $resultEmail = mysqli_fetch_array($consultaEmail);
    if(sizeof($resultEmail)>0) $emailExistente= true;
}

/* si el dni cambia comprueba que el nuevo no exista en la bd*/
if($resultCambioEmail['dni'] != $_GET['dni']){
    //comprueba que el dni no exista en la bd
    $sqlDni = "select * from usuario where dni = '".$dni."'";
    $consultaDni = mysqli_query($conexion, $sqlDni);
    $resultDni = mysqli_fetch_array($consultaDni);
    if(sizeof($resultDni)>0) $dniExistente = true;
}
    
    if($email != $repeatemail) $emailsIguales = false;
    $datos['email'] = $emailsIguales;
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
    $datos['nuevoEmailOcupado'] = $nuevoEmailOcupado;
    
    if($emailsIguales && !$emailExistente && !$dniExistente){
        $sql = "update usuario set nombre ='".$_GET['nombre']."',apellido1='".$primerApellido."',apellido2='".$segundoApellido."',dni='".$_GET['dni']."',direccion='".$_GET['direccion']."',ciudad='".$_GET['ciudad']."',
        codigo_postal='".$codigoPostal."',telefono='".$_GET['telefono']."',email='".$_GET['email']."' where id=".$_SESSION['id'];

        $consulta = mysqli_query($conexion, $sql);
        mysqli_fetch_array($consulta);
        $_SESSION['user'] = $nombre;
        $ok = true;
    }
    
}


	$datos['ok'] = $ok;	
    echo $_GET['callback']. '('. json_encode($datos) . ')';


?>