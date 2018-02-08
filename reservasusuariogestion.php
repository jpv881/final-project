<?php

session_start();
header("content-type: application/json");
include('conexion.php');

if($_SESSION['user'] == null){
    header('Location: index.html');
}

$funcion = $_GET['funcion'];
$datos = array();

if($funcion =="dibujar"){

    $instalacion = $_GET['instalacion'];
    $fecha = $_GET['fecha'];
    $horas = array();
    
        $sql = "select * from franjas_horarias where activa = true";
        $consulta = mysqli_query($conexion,$sql);
        while($fila=mysqli_fetch_array($consulta)){
            $franjaOcupada =0;
            $sqlFranjaOcupada = "select * from reserva where instalacion=".$instalacion." and fecha_inicio='".$fecha." ".$fila['nombre_inicio'].":00'";
            $consultaFranjaOcupada = mysqli_query($conexion,$sqlFranjaOcupada);
            $filaFranjaOcupada=mysqli_fetch_array($consultaFranjaOcupada);
            if(sizeof($filaFranjaOcupada)>0){
                $franjaOcupada = 1;
            }
            $fila['franjaOcupada']=$franjaOcupada;
            $horas[]= $fila;
            
        }
        $datos['horas']=$horas;
   
}else if($funcion=="reservar"){
    $id = $_SESSION['id'];
    $instalacion = $_GET['instalacion'];
    $fecha_inicio = $_GET['fecha_inicio'];
    $fecha_fin = $_GET['fecha_fin'];
    $fecha = $_GET['fecha'];
    //el usuario solo puede tener activa una reserva para una misma instalacion
    $sql = "select * from reserva where socio =".$id." and instalacion= ".$instalacion." and pago_realizado = false";
    $consulta = mysqli_query($conexion,$sql);
    $fila=mysqli_fetch_array($consulta);
    
    $sqlUsuario = "select * from usuario where id=".$id;
    $consultaUsuario = mysqli_query($conexion,$sqlUsuario);
    $filaUsuario=mysqli_fetch_array($consultaUsuario);
    
    if(sizeof($fila)>0){
        $datos['yaTieneReserva']=true;
    }else if($filaUsuario['penalizado'] == 1){
        $datos['penalizado'] = true;
    }else{
         
        $inicio = $fecha." ".$fecha_inicio.":00";
        $fin = $fecha." ".$fecha_fin.":00";

        $sqlReservar = "insert into reserva (fecha_reserva,fecha_inicio,fecha_fin,pago_realizado,reserva_anulada,ha_acudido,socio,instalacion) values";
        $sqlReservar.="('".date('Y-m-d H:i:s')."','".$inicio."','".$fin."',false,false,false,".$id.",".$instalacion.")";
//        $consultaReservar = mysqli_query($conexion,$sqlReservar);
//        mysqli_fetch_array($consultaReservar);
        mysqli_query($conexion,$sqlReservar);
        $idCreado =   $conexion->insert_id;
        
        $sqlInstalacion = "select nombre from instalacion where id=".$instalacion;
        $consultaInstalacion = mysqli_query($conexion,$sqlInstalacion);
        $filaInstalacion = mysqli_fetch_array($consultaInstalacion);
            
        $reserva = array();
        $reserva['nombreInstalacion']= $filaInstalacion['nombre'];
        $reserva['fecha_inicioFormateada']= date("d-m-Y H:i:s", strtotime($inicio));
        $reserva['fecha_reserva'] = date('Y-m-d H:i:s');
        $reserva['fecha_inicio'] = $inicio;
        $reserva['fecha_fin'] = $fin;
        $reserva['pago_realizado'] = 0;
        $reserva['reserva_anulada'] = 0;
        $reserva['ha_acudido'] = 0;
        $reserva['socio'] = $id;
        $reserva['instalacion'] = $instalacion;
        $reserva['id'] = $idCreado;
        $datos['reserva'] = $reserva;
        $datos['ok']=true;  
        //$datos['sql']=$sqlReservar;
        
    }
}else if($funcion == "verReservas"){
    $id = $_SESSION['id'];
    $sql = "select * from reserva where socio =".$id." and pago_realizado = false order by fecha_inicio";
    $consulta = mysqli_query($conexion,$sql);
    while($fila=mysqli_fetch_array($consulta)){
        $fila['fecha_inicioFormateada']= date("d-m-Y H:i:s", strtotime($fila['fecha_inicio']));
        $fila['fecha_reservaFormateada']= date("d-m-Y H:i:s", strtotime($fila['fecha_reserva']));
        
        $sqlInstalacion = "select nombre from instalacion where id=".$fila['instalacion'];
        $consultaInstalacion = mysqli_query($conexion,$sqlInstalacion);
        $filaInstalacion = mysqli_fetch_array($consultaInstalacion);
        $fila['nombreInstalacion']= $filaInstalacion['nombre'];
        
        $datos[] = $fila;
    }
//    $datos['sql'] = $sql;
}else if($funcion == "cancelar"){
    $id = $_GET['id'];
    $fecha = $_GET['fecha'];
    $horasCancelar = $_SESSION['horas_cancelar'];
    
    $t1 = StrToTime ($fecha);
    $t2 = StrToTime (date('Y-m-d H:i:s'));
    $diff = $t1 - $t2;
    $hours = $diff / ( 60 * 60 );
    
    if($hours < (double)$horasCancelar){
        $datos['puedeCancelar'] = false;
    }else{
        $sql = "delete from reserva where id =".$id;
        $consulta = mysqli_query($conexion,$sql);
        mysqli_fetch_array($consulta);
    }
   
    //$datos['sql'] = $sql;
    $datos['horasCancelar'] = $horasCancelar;
}

echo $_GET['callback']. '('. json_encode($datos) . ')';

?>