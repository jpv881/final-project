<?php

session_start();
header("content-type: application/json");
include('conexion.php');

if($_SESSION['user'] == null){
    header('Location: index.html');
}

$funcion = $_GET['funcion'];
$datos = array();
$instalacion = false;

if($funcion =="dibujar"){
    $instalacion = $_GET['instalacion'];
    $fecha = $_GET['fecha'];
    $horas = array();
    
    if(!empty($instalacion)){
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
    }
    
    $datos['instalacion']=$instalacion; //si la instalacion es null se mostrara un mensaje de alerta
    
}else if($funcion=="reservar"){
    $usuario = $_GET['usuario'];
    $instalacion = $_GET['instalacion'];
    $fecha_inicio = $_GET['fecha_inicio'];
    $fecha_fin = $_GET['fecha_fin'];
    $today = date("Y-m-d H:i:s");
    $fecha = $_GET['fecha'];
    //el usuario solo puede tener activa una reserva para una misma instalacion
    $sql = "select * from reserva where socio =".$usuario." and instalacion= ".$instalacion." and pago_realizado = false";
    $consulta = mysqli_query($conexion,$sql);
    $fila=mysqli_fetch_array($consulta);
    
    if(sizeof($fila)>0){
        $datos['yaTieneReserva']=true;
    }else{
         
        $inicio = $fecha." ".$fecha_inicio.":00";
        $fin = $fecha." ".$fecha_fin.":00";
        $datos['tod']=$today;
        $datos['ini']=$inicio;
        $datos['fin']=$fin;
        
        if(strtotime($today) > strtotime($inicio)){
            $datos['fechaPasada']=true;
        }else{
          
            $sqlReservar = "insert into reserva (fecha_reserva,fecha_inicio,fecha_fin,pago_realizado,reserva_anulada,ha_acudido,socio,instalacion) values";
            $sqlReservar.="('".date('Y-m-d H:i:s')."','".$inicio."','".$fin."',false,false,false,".$usuario.",".$instalacion.")";
            
            mysqli_query($conexion,$sqlReservar);
            $idReserva =   $conexion->insert_id;
            $reserva = array();
            $reserva['id'] = $idReserva;
            $reserva['fecha_reserva'] = date('Y-m-d H:i:s');
            $reserva['fecha_inicio'] = $inicio;
            $reserva['fecha_fin'] = $fin;
            $reserva['pago_realizado'] = false;
            $reserva['reserva_anulada'] = false;
            $reserva['ha_acudido'] = false;
            $reserva['socio'] = $usuario;
            $reserva['instalacion'] = $instalacion;
            $datos['reserva'] = $reserva;
            
            $datos['ok']=true;  
            //$datos['sql']=$sqlReservar;
        }
        
    }
}else if($funcion=="verReservas"){
    $filtroFecha = $_GET['filtroFecha'];
    $filtroDosFechas = $_GET['filtroDosFechas'];
    $seleccion = $_GET['seleccion'];
    $fecha = null;
    $fecha1 = null;
    $fecha2 = null;
    $reserva = $_GET['reserva'];
    
    
    $fecha = $_GET['fecha'];
    $fecha1 = $_GET['fecha1'];
    $fecha2 = $_GET['fecha2'];
    
    
     $sql = "select * from reserva";
    
    if($reserva=="hoy"){
        $sql.=" where date(fecha_inicio) = '".date('Y-m-d')."'";
    }else if($reserva=="todas"){
        $sql = "select * from reserva";
    }else if($reserva=="pagadas"){
        $sql.=" where pago_realizado=1";
    }else if($reserva=="noPagadas"){
        $sql.=" where pago_realizado=0";
    }else if($reserva=="anuladas"){
        $sql.=" where reserva_anulada=1";
    }else if($reserva=="noAnuladas"){
        $sql.=" where reserva_anulada=0";
    }else if($reserva=="acudio"){
        $sql.=" where ha_acudido=1";
    }else if($reserva=="noAcudio"){
        $sql.=" where ha_acudido=0";
    }
    
    if($fecha !='null'){
        if($reserva == "todas"){
            $sql.=" where date(fecha_inicio) = '".$fecha."'";
        }else{
            $sql.=" and date(fecha_inicio) = '".$fecha."'";
        }
    }
    
    if($fecha1 != 'null' && $fecha2 != 'null'){
        if($reserva == "todas"){
            $sql.=" where date(fecha_inicio) >= '".$fecha1."' and date(fecha_inicio) <= '".$fecha2."'";
        }else{
            $sql.=" and date(fecha_inicio) >= '".$fecha1."' and date(fecha_inicio) <= '".$fecha2."'";
        }  
    }
    
    $sql.=" order by fecha_inicio";
    
    $consulta = mysqli_query($conexion,$sql);
    while($fila=mysqli_fetch_array($consulta)){
        $sqlUsuario = "select * from usuario where id =".$fila['socio'];
        $consultaUsuario = mysqli_query($conexion,$sqlUsuario);
        $filaUsuario = mysqli_fetch_array($consultaUsuario);
        $fila['nombre']= $filaUsuario['nombre'];
        $fila['apellido1']= $filaUsuario['apellido1'];
        $fila['apellido2']= $filaUsuario['apellido2'];
        $fila['fecha_inicioFormateada']= date("d-m-Y H:i:s", strtotime($fila['fecha_inicio']));
        $fila['fecha_reservaFormateada']= date("d-m-Y H:i:s", strtotime($fila['fecha_reserva']));
        
        if($fila['pago_realizado'] == 0){
            $fila['pago_realizadoB'] = false;
        }else if($fila['pago_realizado'] == 1){
            $fila['pago_realizadoB'] = true;
        }
        
        if($fila['ha_acudido'] == 0){
            $fila['ha_acudidoB'] = false;
        }else if($fila['ha_acudido'] == 1){
            $fila['ha_acudidoB'] = true;
        }
        
        if($fila['reserva_anulada'] == 0){
            $fila['reserva_anuladaB'] = false;
        }else if($fila['reserva_anulada'] == 1){
            $fila['reserva_anuladaB'] = true;
        }
        
        $sqlInstalacion = "select nombre from instalacion where id=".$fila['instalacion'];
        $consultaInstalacion = mysqli_query($conexion,$sqlInstalacion);
        $filaInstalacion = mysqli_fetch_array($consultaInstalacion);
        $fila['nombreInstalacion']= $filaInstalacion['nombre'];
        
        $datos[]=$fila;
        
    }
//    $datos['sql']=$sql;
}else if($funcion == "editarReserva"){
    $pagada = $_GET['pagada'];
    $acudio = $_GET['acudio'];
    $anulada = $_GET['anulada'];
    $id = $_GET['id'];
    
    $sql = "update reserva set pago_realizado=".$pagada.", reserva_anulada=".$anulada.", ha_acudido=".$acudio." where id=".$id;
    $consulta = mysqli_query($conexion,$sql);
    mysqli_fetch_array($consulta);
    $datos['ok']= true;
    
    if($pagada == 'true'){
        $datos['pagada']= 1;
    }else if($pagada == 'false'){
        $datos['pagada']= 0;
    }
    
    if($acudio == 'true'){
        $datos['acudio']= 1;
    }else if($acudio == 'false'){
        $datos['acudio']= 0;
    }
    
    if($anulada == 'true'){
        $datos['anulada']= 1;
    }else if($anulada == 'false'){
        $datos['anulada']= 0;
    }
    
    $datos['pagadaB'] = $pagada;
    $datos['acudioB']= $acudio;
    $datos['anuladaB']= $anulada;
}else if($funcion=="eliminarReserva"){
    $id = $_GET['id'];
    $sqlReserva = "select * from reserva where id=".$id;
    $consultaReserva = mysqli_query($conexion,$sqlReserva);
    $filaReserva = mysqli_fetch_array($consultaReserva);
    
    if($filaReserva['pago_realizado'] == 1){
        $datos['pagada']==true;
    }else {
       $sql = "delete from reserva where id=".$id;
        $consulta = mysqli_query($conexion,$sql);
        mysqli_fetch_array($consulta); 
    }
    
}

echo $_GET['callback']. '('. json_encode($datos) . ')';

?>