<?php

session_start();
header("content-type: application/json");
include('conexion.php');

if($_SESSION['user'] == null){
    header('Location: index.html');
}

$funcion = $_GET['funcion'];
$datos = array();

if($funcion =="dameUsuarios"){
    $sql = "select * from usuario where nombre != 'admin' order by apellido1";
    $consulta = mysqli_query($conexion,$sql);
    while($fila = mysqli_fetch_array($consulta)){
        $idRol = $fila['rol'];
        $sqlRol = "select nombre from rol where id =".$idRol;
        $consultaRol = mysqli_query($conexion,$sqlRol);
        $filaRol = mysqli_fetch_array($consultaRol);
        
        $fila['fecha_alta'] = date("d-m-Y", strtotime($fila['fecha_alta']));
        $fila['fecha_baja'] = date("d-m-Y", strtotime($fila['fecha_baja']));
        $fila['fin_penalizacion'] = date("d-m-Y", strtotime($fila['fin_penalizacion']));
        $fila['nombreRol']=$filaRol['nombre'];
        $fila['password']=null;
        $reservas = array();
        $sqlReservas = "SELECT reserva.fecha_inicio,instalacion.nombre as nombre_instalacion FROM reserva, instalacion WHERE reserva.instalacion = instalacion.id and reserva.socio= ".$fila['id']." and fecha_inicio >='".date('Y-m-d')."'";
        $consultaReservas = mysqli_query($conexion,$sqlReservas);
        while($filaReservas = mysqli_fetch_array($consultaReservas)){
            $filaReservas['fecha_inicio'] = date("d-m-Y H:i:s", strtotime($filaReservas['fecha_inicio']));
            $reservas[]=$filaReservas;
        }
        $fila['reservas']=$reservas;
        $datos[]=$fila;
    }
    
}else if($funcion =="crearUsuario"){
    
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
    $rol = $_GET['rol'];
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
    
    if($nombreLargo || $apellido1Largo || $apellido2Largo || $direccionLarga || $dniLargo || $ciudadLarga || $codigoPostalLargo || $telefonoLargo || $emailLargo){
            $error = true;
        }

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
        $datos['error'] = $error;
        $sql = "insert into usuario (apellido1,apellido2,ciudad,codigo_postal,direccion,dni,email,fecha_alta,nombre,password,penalizado,rol,telefono,baja) values 
        ('".$primerApellido."','".$segundoApellido."','".$ciudad."','".$codigoPostal."','".$direccion."','".$dni."','".$email."','".date('Y-m-d')."','".$nombre."','".md5($pass)."',false,".$rol.",'".$telefono."',false)";
        mysqli_query($conexion,$sql);
        $idUsuario =   $conexion->insert_id;
          
        $sqlRol = "select nombre from rol where id =".$rol;
        $consultaRol = mysqli_query($conexion, $sqlRol);
        $filaRol = mysqli_fetch_array($consultaRol);
        
        $ok = true;
        $usuario = array();
        $usuario['id']= $idUsuario;
        $usuario['nombre']= $nombre;
        $usuario['apellido1']= $primerApellido;
        $usuario['apellido2']= $segundoApellido;
        $usuario['dni']= $dni;
        $usuario['direccion']= $direccion;
        $usuario['ciudad']= $ciudad;
        $usuario['codigo_postal']= $codigoPostal;
        $usuario['telefono']= $telefono;
        $usuario['email']= $email;
        $usuario['fecha_alta']= date('Y-m-d');
        $usuario['penalizado']= 0;
        $usuario['rol']= $rol;
        $usuario['baja']= 0;
        
        $datos['usuario'] = $usuario;
        $datos['nombreRol'] = $filaRol['nombre'];
        $datos['penalizado'] = 0;
        $datos['ok']=$ok;
    }
    
}else if($funcion =="dameRoles"){
    $sql ="select * from rol";
    $consulta = mysqli_query($conexion,$sql);
    while($fila = mysqli_fetch_array($consulta)){
        $datos[]=$fila;
    }
}else if($funcion == "editarPass"){
    $id = $_GET['id'];
    $pass1 = $_GET['pass1'];
    $pass2 = $_GET['pass2'];
    $passIguales = false;
    $passLarga = false;
    
    if($pass1 == $pass2) $passIguales = true;
    if(sizeof($pass1)>50 || sizeof($pass2)>50) $passLarga = true;
    
    if($passIguales && !$passLarga){
        $sql = "update usuario set password ='".md5($pass1)."' where id=".$id;
        $consulta = mysqli_query($conexion,$sql);
         mysqli_fetch_array($consulta);
    }
    $datos['sql']=$sql;
    $datos['passIguales']=$passIguales;
    $datos['passLarga']=$passLarga;
    
}else if($funcion=="editarUsuario"){
    $datos['ok']= false;
    $id=$_GET['id'];
    $nombre = $_GET['nombre'];
    $apellido1 = $_GET['apellido1'];
    $apellido2 = $_GET['apellido2'];
    $direccion = $_GET['direccion'];
    $dni = $_GET['dni'];
    $ciudad = $_GET['ciudad'];
    $codigoPostal = $_GET['codigo_postal'];
    $telefono = $_GET['telefono'];
    $email = $_GET['email'];
    $repeatemail = $_GET['repeatemail'];
    $rol = $_GET['rol'];
    $dniExistente = false;
    $emailExistente = false;
    $emailsIguales = false;
    $error = false;
    
    $sqlUsuario = "select * from usuario where id=".$id;
    $consultaUsuario = mysqli_query($conexion,$sqlUsuario);
    $filaUsuario = mysqli_fetch_array($consultaUsuario);
    
    $dniAntiguo=$filaUsuario['dni'];
    $emailAntiguo=$filaUsuario['email'];
    
    if($email==$repeatemail){
        $emailsIguales=true;
    }
    
    if($dni != $dniAntiguo){
        $sqlDni = "select * from usuario where dni ='".$dni."'";
        $consultaDni = mysqli_query($conexion,$sqlDni);
        $filaDni = mysqli_fetch_array($consultaDni);
        
        if(sizeof($filaDni)>0){
            $dniExistente=true;
        }
    }
    
    if($email != $emailAntiguo){
        $sqlEmail = "select * from usuario where email ='".$email."'";
        $consultaEmail = mysqli_query($conexion,$sqlEmail);
        $filaEmail = mysqli_fetch_array($consultaEmail);
        
        if(sizeof($filaEmail)>0){
            $emailExistente=true;
        }
    }
    
    if(!$dniExistente && !$emailExistente && $emailsIguales){
        
    if(strlen($nombre) > 50) $nombreLargo = true;
    if(strlen($primerApellido) > 50) $apellido1Largo = true;
    if(strlen($segundoApellido) > 50) $apellido2Largo = true;
    if(strlen($direccion) > 50) $direccionLarga = true;
    if(strlen($dni) > 10) $dniLargo = true;
    if(strlen($ciudad) > 50) $ciudadLarga = true;
    if(strlen($codigoPostal) > 5) $codigoPostalLargo = true;
    if(strlen($telefono) > 20) $telefonoLargo = true;
    if(strlen($email) > 50) $emailLargo = true;
        
        if($nombreLargo || $apellido1Largo || $apellido2Largo || $direccionLarga || $dniLargo || $ciudadLarga || $codigoPostalLargo || $telefonoLargo || $emailLargo){
            $error = true;
        }
        
        if(!$error){
             $sql = "update usuario set nombre = '".$nombre."',apellido1='".$apellido1."',apellido2='".$apellido2."',direccion='".$direccion."',dni='".$dni."',ciudad='".$ciudad."',codigo_postal='".$codigoPostal."',telefono='".$telefono."',email='".$email."',rol=".$rol." where id=".$id;
            $consulta = mysqli_query($conexion,$sql);
            mysqli_fetch_array($consulta);
            
            $sqlNombreRol = "select nombre from rol where id = ".$rol;
            $consultaNombreRol = mysqli_query($conexion,$sqlNombreRol);
            $filaNombreRol = mysqli_fetch_array($consultaNombreRol);
            
            $datos['nombreRol'] = $filaNombreRol['nombre'];
            $datos['ok']=true;
        }

    }
    $datos['sqlEmail']=$sqlEmail;
    $datos['emailsIguales']=$emailsIguales;
    $datos['dniExistente']=$dniExistente;
    $datos['emailExistente']=$emailExistente;
    $datos['nombreLargo']=$nombreLargo;
    $datos['apellido1Largo']=$apellido1Largo;
    $datos['apellido2Largo']=$apellido2Largo;
    $datos['direccionLarga']=$direccionLarga;
    $datos['dniLargo']=$dniLargo;
    $datos['ciudadLarga']=$ciudadLarga;
    $datos['codigoPostalLargo']=$codigoPostalLargo;
    $datos['telefonoLargo']=$telefonoLargo;
    $datos['emailLargo']=$emailLargo;

}else if($funcion=="penalizar"){
    $id = $_GET['id'];

    $fin_penalizacion =Date('y-m-d', strtotime("+".$_SESSION['dias_sancion']." days"));
    
    $sql = "update usuario set penalizado=true,fin_penalizacion='".$fin_penalizacion."' where id=".$id;
    $consulta = mysqli_query($conexion,$sql);
    mysqli_fetch_array($consulta);
    
    $datos['fin_penalizacion']= date("d-m-Y", strtotime($fin_penalizacion));
    $datos['ok']= true;

}else if($funcion=="quitarPenalizacion"){
    $id = $_GET['id'];
 
    $sql = "update usuario set penalizado=false,fin_penalizacion=null where id=".$id;
    $consulta = mysqli_query($conexion,$sql);
    mysqli_fetch_array($consulta);
    $datos['ok']= true;

}else if($funcion=="darAlta"){
    $id = $_GET['id'];
    $sql = "update usuario set baja=false,fecha_alta='".date('Y-m-d')."' where id=".$id;
    $consulta = mysqli_query($conexion,$sql);
    mysqli_fetch_array($consulta);
    $datos['fecha_alta']=date('d-m-Y');
    $datos['ok']= true;
    
}else if($funcion=="darBaja"){
    $id = $_GET['id'];
    $sql = "update usuario set baja=true,fecha_baja='".date('Y-m-d')."' where id=".$id;
    $consulta = mysqli_query($conexion,$sql);
    mysqli_fetch_array($consulta);
    $datos['fecha_baja']=date('d-m-Y');
    $datos['ok']= true;
}

echo $_GET['callback']. '('. json_encode($datos) . ')';

?>