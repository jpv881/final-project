<?php
/* gestion de las instalaciones y tipos de instalacion. Creacion y edicion */
session_start();
header("content-type: application/json");
include('conexion.php');

if($_SESSION['user'] == null){
    header('Location: index.html');
}

$funcion = $_GET['funcion'];
$datos = array();

if($funcion == "crearTipo"){
    $nombre = $_GET['nombre'];
    $descripcion = $_GET['descripcion'];
    $nombreLargo = false;
    $descripcionLarga = false;
    $ok = false;
    
    if(strlen($nombre)>50) $nombreLargo = true;
    if(strlen($descripcion)>250) $descripcionLarga = true;
    
    if(!$nombreLargo && !$descripcionLarga){
        $sql = "insert into tipoinstalacion (nombre, descripcion) values ('".$nombre."','".$descripcion."')";
        mysqli_query($conexion,$sql);
        $idCreado =   $conexion->insert_id;
        $datos['id'] = $idCreado;
        $ok = true;
    }
    $datos['nombreLargo']= $nombreLargo;
    $datos['descripcionLarga']= $descripcionLarga;
    $datos['ok'] = $ok;
    
}else if($funcion == "dameTipos"){
    $sql = "select * from tipoinstalacion order by tipoinstalacion.nombre";
    $consulta = mysqli_query($conexion,$sql);
    while($fila = mysqli_fetch_array($consulta)){
        $array = array();
        $array['id'] = $fila['id'];
        $array['nombre'] = $fila['nombre'];
        $array['descripcion'] = $fila['descripcion'];
        $datos[] = $array;
    }
    
}else if($funcion == "eliminarTipo"){
    $id = $_GET['id'];
    $sqlIns = "select * from instalacion where tipo=".$id;
    $consultaIns = mysqli_query($conexion,$sqlIns);
    $filaIns = mysqli_fetch_array($consultaIns);
    if(sizeof($filaIns) > 0){
        $datos['tieneInstalaciones'] = true;
    }else{
        $sql = "delete from tipoinstalacion where id=".$id;
        mysqli_query($conexion,$sql);
        $ok = true;
        $datos['ok'] = $ok;
    }
    
}else if($funcion == "editarTipo"){
    $id = $_GET['id'];
    $nombre = $_GET['nombre'];
    $descripcion = $_GET['descripcion'];
    $nombreLargo = false;
    $descripcionLarga = false;
    $ok = false;
    
    if(strlen($nombre)>50) $nombreLargo = true;
    if(strlen($descripcion)>250) $descripcionLarga = true;
    
    if(!$nombreLargo && !$descripcionLarga){
        $sql = "update tipoinstalacion set nombre ='".$nombre."', descripcion = '".$descripcion."' where id =".$id;
        $consulta = mysqli_query($conexion,$sql);
        mysqli_fetch_array($consulta);
        $ok = true;
    }
    $datos['nombreLargo']= $nombreLargo;
    $datos['descripcionLarga']= $descripcionLarga;
    $datos['ok'] = $ok;
    
}else if($funcion == 'dameFranjas'){
    $sql="select * from franjas_horarias";
    $consulta = mysqli_query($conexion,$sql);
    while($result = mysqli_fetch_array($consulta)){
        
        if($result['nombre']=="08"){
            if($result['activa']==0) $ocho = false;
            else $ocho = true;
        }
        if($result['nombre']=="09"){
            if($result['activa']==0) $nueve = false;
            else $nueve = true;
        }
        if($result['nombre']=="10"){
            if($result['activa']==0) $diez = false;
            else $diez = true;
        }
        if($result['nombre']=="11"){
            if($result['activa']==0) $once = false;
            else $once = true;
        }
        if($result['nombre']=="12"){
            if($result['activa']==0) $doce = false;
            else $doce = true;
        }
        if($result['nombre']=="13"){
            if($result['activa']==0) $trece = false;
            else $trece = true;
        }
        if($result['nombre']=="14"){
            if($result['activa']==0) $catorce = false;
            else $catorce = true;
        }
        if($result['nombre']=="15"){
            if($result['activa']==0) $quince = false;
            else $quince = true;
        }
        if($result['nombre']=="16"){
            if($result['activa']==0) $dieciseis = false;
            else $dieciseis = true;
        }
        if($result['nombre']=="17"){
            if($result['activa']==0) $diecisiete = false;
            else $diecisiete = true;
        }
        if($result['nombre']=="18"){
            if($result['activa']==0) $dieciocho = false;
            else $dieciocho = true;
        }
        if($result['nombre']=="19"){
            if($result['activa']==0) $diecinueve = false;
            else $diecinueve = true;
        }
        if($result['nombre']=="20"){
            if($result['activa']==0) $veinte = false;
            else $veinte = true;
        }
        if($result['nombre']=="21"){
            if($result['activa']==0) $veintiuno = false;
            else $veintiuno = true;
        }
        if($result['nombre']=="22"){
            if($result['activa']==0) $veintidos = false;
            else $veintidos = true;
        }
        if($result['nombre']=="23"){
            if($result['activa']==0) $veintitres = false;
            else $veintitres = true;
        }
    }
    $datos['ocho'] = $ocho;
    $datos['nueve'] = $nueve;
    $datos['diez'] = $diez;
    $datos['once'] = $once;
    $datos['doce'] = $doce;
    $datos['trece'] = $trece;
    $datos['catorce'] = $catorce;
    $datos['quince'] = $quince;
    $datos['dieciseis'] = $dieciseis;
    $datos['diecisiete'] = $diecisiete;
    $datos['dieciocho'] = $dieciocho;
    $datos['diecinueve'] = $diecinueve;
    $datos['veinte'] = $veinte;
    $datos['veintiuno'] = $veintiuno;
    $datos['veintidos'] = $veintidos;
    $datos['veintitres'] = $veintitres;
    $datos['ok'] = true;

}else if($funcion == "guardarFranjas"){
    $ocho = $_GET['ocho'];
    $nueve = $_GET['nueve'];
    $diez = $_GET['diez'];
    $once = $_GET['once'];
    $doce = $_GET['doce'];
    $trece = $_GET['trece'];
    $catorce = $_GET['catorce'];
    $quince = $_GET['quince'];
    $dieciseis = $_GET['dieciseis'];
    $diecisiete = $_GET['diecisiete'];
    $dieciocho = $_GET['dieciocho'];
    $diecinueve = $_GET['diecinueve'];
    $veinte = $_GET['veinte'];
    $veintiuno = $_GET['veintiuno'];
    $veintidos = $_GET['veintidos'];
    $veintitres = $_GET['veintitres'];
    
    $sql = "update franjas_horarias set activa = ( case ".
                                                 "when nombre = '08' then ".$ocho.
                                                " when nombre = '09' then ".$nueve.
                                                " when nombre = '10' then ".$diez.
                                                " when nombre = '11' then ".$once.
                                                " when nombre = '12' then ".$doce.
                                                " when nombre = '13' then ".$trece.
                                                " when nombre = '14' then ".$catorce.
                                                " when nombre = '15' then ".$quince.
                                                " when nombre = '16' then ".$dieciseis.
                                                " when nombre = '17' then ".$diecisiete.
                                                " when nombre = '18' then ".$dieciocho.
                                                " when nombre = '19' then ".$diecinueve.
                                                " when nombre = '20' then ".$veinte.
                                                " when nombre = '21' then ".$veintiuno.
                                                " when nombre = '22' then ".$veintidos.
                                                " when nombre = '23' then ".$veintitres.
                                                " end)";
   
    $consulta = mysqli_query($conexion,$sql);
    mysqli_fetch_array($consulta);
    $datos['ok'] = true;

} else if($funcion == 'crearInstalacion'){
    $nombre = $_GET['nombre'];
    $tipo = $_GET['tipo'];
    $descripcion = $_GET['descripcion'];
    
    $nombreLargo = false;
    $descripcionLarga = false;
    
    if(sizeof($nombre)>50) $nombreLargo = true;
    if(sizeof($descripcion)>250) $descripcionLarga = true;
    
    $datos['descripcionLarga']=$descripcionLarga;
    $datos['nombreLargo']=$nombreLargo;
    
   if(!$nombreLargo && !$descripcionLarga){
        $sql = "insert into instalacion (nombre,tipo,descripcion) values ('".$nombre."',".$tipo.",'".$descripcion."')";
        mysqli_query($conexion,$sql);
        $idCreado =   $conexion->insert_id;
       
        $sqlNombreTipo = "select nombre from tipoinstalacion where id =".$tipo;
        $consultaNombreTipo = mysqli_query($conexion,$sqlNombreTipo);
        $filaNombreTipo = mysqli_fetch_array($consultaNombreTipo);
        
       $datos['id'] = $idCreado;
        $datos['nombreTipo']=$filaNombreTipo['nombre'];
        $datos['ok'] = true;
    }
    
}else if($funcion == "dameInstalaciones"){
    $sql = "select * from instalacion order by instalacion.nombre";
    $consulta = mysqli_query($conexion,$sql);
    while($fila = mysqli_fetch_array($consulta)){
        $array = array();
        $array['id'] = $fila['id'];
        $array['nombre'] = $fila['nombre'];
        $array['descripcion'] = $fila['descripcion'];
        $array['tipo'] = $fila['tipo'];
        
        $tipo = $fila['tipo'];
        $sqlNombreTipo = "select nombre from tipoinstalacion where id =".$tipo;
        $consultaNombreTipo = mysqli_query($conexion,$sqlNombreTipo);
        $filaNombreTipo = mysqli_fetch_array($consultaNombreTipo);
        
        $array['nombreTipo']=$filaNombreTipo['nombre'];
        
        $datos[] = $array;
    }
    
}else if($funcion == "eliminarInstalacion"){
    $id = $_GET['id'];
    
    $sqlReservas = "select * from reserva where instalacion=".$id;
    $consultaReservas = mysqli_query($conexion,$sqlReservas);
    $filaReservas = mysqli_fetch_array($consultaReservas);
    
    if(sizeof($filaReservas) > 0){
        $datos['tieneReservas'] = true;
    }else{
       $sql = "delete from instalacion where id=".$id;
        $consulta = mysqli_query($conexion,$sql);
        mysqli_fetch_array($consulta);
        $datos['ok']= true; 
    }
    
    
}else if($funcion == "editarInstalacion"){
    $id = $_GET['id'];
    $nombre = $_GET['nombre'];
    $descripcion = $_GET['descripcion'];
    $nombreLargo = false;
    $descripcionLarga = false;
    $ok = false;
    
    if(strlen($nombre)>50) $nombreLargo = true;
    if(strlen($descripcion)>250) $descripcionLarga = true;
    
    if(!$nombreLargo && !$descripcionLarga){
        $sql = "update instalacion set nombre ='".$nombre."', descripcion = '".$descripcion."' where id =".$id;
        $consulta = mysqli_query($conexion,$sql);
        mysqli_fetch_array($consulta);
        $ok = true;
    }
    $datos['nombreLargo']= $nombreLargo;
    $datos['descripcionLarga']= $descripcionLarga;
    $datos['ok'] = $ok;
    
}else if($funcion=="dameDiasSancion"){
    $sql="select valor from config where clave = 'dias_sancion'";
    $consulta = mysqli_query($conexion,$sql);
    $fila = mysqli_fetch_array($consulta);
    
    $datos[] = $fila;
    
}else if($funcion == "guardarDiasSancion"){
    $dias = $_GET['dias'];
    $sql = "update config set valor = '".$dias."' where clave = 'dias_sancion'";
    $consulta = mysqli_query($conexion,$sql);
    mysqli_fetch_array($consulta);
    $_SESSION['dias_sancion'] = $dias;
    $datos['ok'] = true;
//    $datos['sql'] = $sql;

}else if($funcion=="dameHorasCancelar"){
    $sql="select valor from config where clave = 'horas_cancelar'";
    $consulta = mysqli_query($conexion,$sql);
    $fila = mysqli_fetch_array($consulta);
    
    $datos[] = $fila;
    
}else if($funcion == "guardarHorasCancelar"){
    $horas = $_GET['horas'];
    $sql = "update config set valor = '".$horas."' where clave = 'horas_cancelar'";
    $consulta = mysqli_query($conexion,$sql);
    mysqli_fetch_array($consulta);
    $_SESSION['horas_cancelar'] = $horas;
    $datos['ok'] = true;
//    $datos['sql'] = $sql;

}

echo $_GET['callback']. '('. json_encode($datos) . ')';
?>