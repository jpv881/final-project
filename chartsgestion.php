<?php

session_start();
header("content-type: application/json");
include('conexion.php');

if($_SESSION['user'] == null){
    header('Location: index.html');
}

$funcion = $_GET['funcion'];
$datos = array();
$result = array();

if($funcion == "charts"){
    //tarta diaria
    $textos = array("Instalación","Nº reservas");
    $result[] = $textos;
    $sql = "select instalacion.nombre, count(reserva.id) as nums from reserva join instalacion on instalacion.id = reserva.instalacion where date(reserva.fecha_inicio) = curdate() group by instalacion";
    $consulta = mysqli_query($conexion,$sql);
    while($fila=mysqli_fetch_array($consulta)){
        $datos2 = array();
        $datos2[] = $fila['nombre'];
        $datos2[] = $fila['nums'];
        
        $result[] = $datos2;
    }
    
    $datos['tartaDiaria'] = $result;
    $result = array();
    $textos = array();
    
    //tarta semanal
    $textos = array("Instalación","Nº reservas");
    $result[] = $textos;
    $sqlTartaSemanal = "select instalacion.nombre, count(reserva.id) as nums from reserva join instalacion on instalacion.id = reserva.instalacion where weekofyear(reserva.fecha_inicio) = weekofyear(curdate()) group by instalacion";
    $consultaTartaSemanal = mysqli_query($conexion,$sqlTartaSemanal);
    while($fila=mysqli_fetch_array($consultaTartaSemanal)){
        $datos2 = array();
        $datos2[] = $fila['nombre'];
        $datos2[] = $fila['nums'];
        
        $result[] = $datos2;
    }
    $datos['tartaSemanal'] = $result;
    
    //charts franjas horarias
    $reservas = array();
    $instalaciones = array();
    $sqlInstalaciones = "select distinct instalacion from reserva";
    $consultaInstalaciones = mysqli_query($conexion,$sqlInstalaciones);
    //una fila por cada instalacion
    while($filaInstalaciones=mysqli_fetch_array($consultaInstalaciones)){
       
        $sqlNombreInstalacion = "select nombre from instalacion where id = ".$filaInstalaciones['instalacion']; 
        $consultaNombreInstalacion = mysqli_query($conexion,$sqlNombreInstalacion);
        $filaNombreInstalacion=mysqli_fetch_array($consultaNombreInstalacion);
        $instalacion = array();
        $instalacion['id'] = $filaInstalaciones['instalacion'];
        $instalacion['nombre'] = $filaNombreInstalacion['nombre'];
        $instalaciones[] = $instalacion;
        
        $sqlReservas = "select fecha_inicio,fecha_fin, date_format(fecha_inicio,'%H:%i') as nombre_inicio,
        date_format(fecha_fin,'%H:%i') as nombre_fin,
        date_format(fecha_inicio,'%H') as hora_inicio,
        date_format(fecha_fin,'%H') as hora_fin  from reserva where instalacion = ".$filaInstalaciones['instalacion']." and date(fecha_inicio) ='".date('Y-m-d')."'";
        $consultaReservas = mysqli_query($conexion,$sqlReservas);
        
        $reservasPorInstalacion = array();
        while($filaReservas=mysqli_fetch_array($consultaReservas)){
            $filaReservas['nombreInstalacion'] = $filaNombreInstalacion['nombre'];
            $reservasPorInstalacion[] = $filaReservas;
        }
        
        $reservas[] = $reservasPorInstalacion;
    }
    
    $datos['reservas'] = $reservas;
    
    //linechart
    $datos['linechart']['instalaciones'] = $instalaciones;
//    $sqlDiaSemana = "select weekday(now()) as diaSemana";
//    $consultaDiaSemana = mysqli_query($conexion,$sqlDiaSemana);
//    $filaDiaSemana=mysqli_fetch_array($consultaDiaSemana);
//    $diaSemana = $filaDiaSemana['diaSemana'];
    
    $diaSemana = date("w");
    
    //cambia el orden de los dias para que el lunes sea 0 y el domingo 6
    if($diaSemana == 0){
        $diaSemana = 6;
    }else{
        $diaSemana--;
    }
    //$datos['dia'] = $diaSemana;
    $hoy = date('Y-m-d');
    $lunes = strtotime ( '-'.$diaSemana.' day' , strtotime ( $hoy ) ) ;
    $lunes = date ( 'Y-m-d' , $lunes );
    
    $reservasPordia = array();
    
    for($i=0;$i<7;$i++){
        $dia = strtotime ( '+'.$i.' day' , strtotime ( $lunes ) ) ;
        $dia = date ( 'Y-m-d' , $dia );
        
        if($i == 0){
            $textoDiaSemana = "Lunes";
        }else if($i == 1){
            $textoDiaSemana = "Martes";
        }else if($i == 2){
            $textoDiaSemana = "Miercoles";
        }else if($i == 3){
            $textoDiaSemana = "Jueves";
        }else if($i == 4){
            $textoDiaSemana = "Viernes";
        }else if($i == 5){
            $textoDiaSemana = "Sábado";
        }else if($i == 6){
            $textoDiaSemana = "Domingo";
        }
        
        $sumaReservas = array();
        $sumaReservas[] = $textoDiaSemana;
        
        for($k=0;$k<sizeof($instalaciones);$k++){
            $sqlReservasDiariasPorInstalacion = "SELECT count(id) as suma FROM `reserva` WHERE date(fecha_inicio) = '".$dia."' and instalacion = ".$instalaciones[$k]['id'];
            $consultaReservasDiariasPorInstalacion = mysqli_query($conexion,$sqlReservasDiariasPorInstalacion);
            $filaReservasDiariasPorInstalacion=mysqli_fetch_array($consultaReservasDiariasPorInstalacion);
            
            $sumaReservas[] = $filaReservasDiariasPorInstalacion['suma'];
        }
        $reservasPordia[] = $sumaReservas;        
    }
    
    //$datos['dia'] = $lunes;
    $datos['linechart']['sumas'] = $reservasPordia;
}

echo $_GET['callback']. '('. json_encode($datos) . ')';

?>