<?php

session_start();
header("content-type: application/json");
include('conexion.php');

if($_SESSION['user'] == null){
    header('Location: index.html');
}


$funcion = $_GET['funcion'];
$datos = array();

if($funcion == "crearRol"){
    $nombre = $_GET['nombre'];
    $descripcion = $_GET['descripcion'];
    $nombreLargo = false;
    $nombreExistente = false;
    $nuevoId = null; //id del rol creado
    $insertar = false;
    $realizarReserva = $_GET['realizarReserva'];
    $cancelarReserva = $_GET['cancelarReserva'];
    $cuentas = $_GET['cuentas'];
    $bajas = $_GET['bajas'];
    $informes = $_GET['informes'];
    $roles = $_GET['roles'];
    $instalaciones = $_GET['instalaciones'];
    
    if($realizarReserva=="true" || $cancelarReserva=="true" || $cuentas=="true" || $bajas=="true" || $informes=="true" || $roles=="true" || $instalaciones=="true") $insertar = true;
    
    if(sizeof($nombre)>50){
        $nombreLargo = true;
    }
    
    if(sizeof($descripcion)>250){
        $descripcionLarga = true;
    }
    
    $sqlNombre = "select * from rol where nombre='".$nombre."'";
    $consultaNombre = mysqli_query($conexion,$sqlNombre);
    $filaNombre = mysqli_fetch_array($consultaNombre);
    
    if(sizeof($filaNombre)>0){
        $nombreExistente = true;
    }
    
    if(!$nombreLargo && !$nombreExistente && $insertar){
        $sqlCrearRol = "insert into rol (nombre,descripcion) values ('".$nombre."','".$descripcion."')";
        mysqli_query($conexion,$sqlCrearRol);
        $nuevoId = mysqli_insert_id($conexion);
        
        $sql ="insert into rolypermiso (idrol,idpermiso) values";
        if($realizarReserva == "true"){
            $sql.="(".$nuevoId.",4),";
        }
        if($cancelarReserva == "true"){
            $sql.="(".$nuevoId.",5),";
        }
        if($cuentas == "true"){
            $sql.="(".$nuevoId.",6),";
        }
        if($bajas == "true"){
            $sql.="(".$nuevoId.",7),";
        }
        if($informes == "true"){
            $sql.="(".$nuevoId.",8),";
        }
        if($roles == "true"){
            $sql.="(".$nuevoId.",9),";
        }
        if($instalaciones == "true"){
            $sql.="(".$nuevoId.",10),";
        }
        
        $pos = strrpos($sql,",");
        $sql[$pos]=";";

        mysqli_query($conexion,$sql);
        
        $rol = array();
        $rol['id'] = $nuevoId;
        $rol['nombre'] = $nombre;
        $rol['descripcion'] = $descripcion;
        $datos['rol'] = $rol;
        $datos['ok']=true;

    }
    $datos['nombreLargo']=$nombreLargo;
    $datos['descripcionLarga']=$descripcionLarga;
    $datos['nombreExistente']=$nombreExistente;
    $datos['insertar']=$insertar;
}else if($funcion == 'dameRoles'){
    $sql="select * from rol";
    $consulta = mysqli_query($conexion,$sql);
    while($fila = mysqli_fetch_array($consulta)){    
        $datos[] = $fila;
    }
}else if($funcion == "damePermisos"){
    $id = $_GET['id'];
    
    $sql = "select permiso.nombre
            from permiso join rolypermiso
            on permiso.id = rolypermiso.idpermiso where rolypermiso.idrol =".$id;
    
    $consulta = mysqli_query($conexion,$sql);
    while($fila = mysqli_fetch_array($consulta)){    
        $datos[] = $fila;
    }
}else if($funcion == "eliminarRol"){
    $id = $_GET['id'];
    
    $sqlUsuario = "select usuario.id from usuario where rol =".$id;
    $consultaUsuario = mysqli_query($conexion,$sqlUsuario);
    $filaUsuario = mysqli_fetch_array($consultaUsuario);
    
    if(sizeof($filaUsuario)>0){
        $datos['rolConUsuarios']=true;
    }else{
        $sqlEliminarPermisos="delete from rolypermiso where idrol=".$id;
        $consultaEliminarPermisos = mysqli_query($conexion,$sqlEliminarPermisos);
        mysqli_fetch_array($consultaEliminarPermisos);
        
        $sql="delete from rol where id=".$id;
        $consulta = mysqli_query($conexion,$sql);
        mysqli_fetch_array($consulta);
        $datos['ok']=true;
        $datos['sql']=$sql;
    }
}else if($funcion =="damePermisosRol"){
    $id = $_GET['id'];
    $sql="select idpermiso from rolypermiso where idrol=".$id;
    $consulta = mysqli_query($conexion,$sql);
    
    while($fila = mysqli_fetch_array($consulta)){
        if($fila['idpermiso']==4)$realizarReserva = true;
        else if($fila['idpermiso']==5)$cancelarReserva = true;
        else if($fila['idpermiso']==6)$cuentas = true;
        else if($fila['idpermiso']==7)$bajas = true;
        else if($fila['idpermiso']==8)$informes = true;
        else if($fila['idpermiso']==9)$roles = true;
        else if($fila['idpermiso']==10)$instalaciones = true;
    }
   
    $datos['realizarReserva']=$realizarReserva;
    $datos['cancelarReserva']=$cancelarReserva;
    $datos['cuentas']=$cuentas;
    $datos['bajas']=$bajas;
    $datos['informes']=$informes;
    $datos['roles']=$roles;
    $datos['instalaciones']=$instalaciones;
}else if($funcion=="editarRol"){
    $id = $_GET['id'];
    $nombre = $_GET['nombre'];
    $descripcion = $_GET['descripcion'];
    $nombreLargo = false;
    $descripcionLarga = false;
    $nombreExistente = false;
    $editar = false;
    $realizarReserva = $_GET['realizarReserva'];
    $cancelarReserva = $_GET['cancelarReserva'];
    $cuentas = $_GET['cuentas'];
    $bajas = $_GET['bajas'];
    $informes = $_GET['informes'];
    $roles = $_GET['roles'];
    $instalaciones = $_GET['instalaciones'];
    $nombreAnterior = $_GET['nombreAnterior'];
    
    if($realizarReserva=="true" || $cancelarReserva=="true" || $cuentas=="true" || $bajas=="true" || $informes=="true" || $roles=="true" || $instalaciones=="true") $editar = true;
    
    if(sizeof($nombre)>50){
        $nombreLargo = true;
    }
    
    if(sizeof($descripcion)>250){
        $descripcionLarga = true;
    }
    
    if($nombre != $nombreAnterior){
        $sqlNombre = "select * from rol where nombre='".$nombre."'";
        $consultaNombre = mysqli_query($conexion,$sqlNombre);
        $filaNombre = mysqli_fetch_array($consultaNombre);
    
    if(sizeof($filaNombre)>0){
        $nombreExistente = true;
    }
    }
    
    
    if(!$nombreLargo && !$nombreExistente && $editar){
        $sqlEditarNombre = "update rol set nombre='".$nombre."', descripcion='".$descripcion."' where id=".$id;
        $consultaEditarNombre = mysqli_query($conexion,$sqlEditarNombre);
        mysqli_fetch_array($consultaEditarNombre);
        
        $sqlBorrarPermisos = "delete from rolypermiso where idrol=".$id;
        $consultaBorrarPermisos = mysqli_query($conexion,$sqlBorrarPermisos);
        mysqli_fetch_array($consultaBorrarPermisos);
        
        $sql ="insert into rolypermiso (idrol,idpermiso) values";
        if($realizarReserva == "true"){
            $sql.="(".$id.",4),";
        }
        if($cancelarReserva == "true"){
            $sql.="(".$id.",5),";
        }
        if($cuentas == "true"){
            $sql.="(".$id.",6),";
        }
        if($bajas == "true"){
            $sql.="(".$id.",7),";
        }
        if($informes == "true"){
            $sql.="(".$id.",8),";
        }
        if($roles == "true"){
            $sql.="(".$id.",9),";
        }
        if($instalaciones == "true"){
            $sql.="(".$id.",10),";
        }
        
        $pos = strrpos($sql,",");
        $sql[$pos]=";";
$datos['sql']=$sql;
        mysqli_query($conexion,$sql);
        $datos['ok']=true;
    }
    
    $datos['nombreLargo']=$nombreLargo;
    $datos['descripcionLarga']=$descripcionLarga;
    $datos['nombreExistente']=$nombreExistente;
    $datos['editar']=$editar;
}

echo $_GET['callback']. '('. json_encode($datos) . ')';

?>