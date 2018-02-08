<?php
session_start();
header("content-type: application/json");
include('conexion.php');
$loginEmail = $_GET['email'];
$loginPass = $_GET['pass'];
$datos = array();
$datos['result'] =false;

$sql ="select * from usuario where email = '".$loginEmail."' and password = '".md5($loginPass)."'";
$consulta = mysqli_query($conexion, $sql);
$result = mysqli_fetch_array($consulta);
$datos['tam']= sizeof($result);
if(sizeof($result) >0){
    
    if($result['baja'] == 1){
        $datos['baja'] = true;
    }else{
        $datos['result'] =true;
        $_SESSION['user'] = $result['nombre'];
        $GLOBALS['rol'] = $result['rol'];
        $_SESSION['rol'] = $result['rol'];
        $_SESSION['id'] = $result['id'];

        $sqlDias = "select valor from config where clave = 'dias_sancion'";
        $consultaDias = mysqli_query($conexion,$sqlDias);
        $filaDias = mysqli_fetch_array($consultaDias);

        $_SESSION['dias_sancion']= $filaDias['valor']; 

        $sqlHoras = "select valor from config where clave = 'horas_cancelar'";
        $consultaHoras = mysqli_query($conexion,$sqlHoras);
        $filaHoras = mysqli_fetch_array($consultaHoras);

        $_SESSION['horas_cancelar']= $filaHoras['valor']; 

        //comprueba si las fechas de sancion han caducado
        $sqlUsuariosSancionados = "SELECT * FROM usuario WHERE penalizado = 1 and fin_penalizacion <= date(now())";
        $consultaUsuariosSancionados = mysqli_query($conexion, $sqlUsuariosSancionados);
        while($resultUsuariosSancionados = mysqli_fetch_array($consultaUsuariosSancionados)){
            $sqlQuitarSancion = "update usuario set fin_penalizacion = null, penalizado = 0 where id =".$resultUsuariosSancionados['id'];
            $consultaQuitarSancion = mysqli_query($conexion, $sqlQuitarSancion);
            mysqli_fetch_array($consultaQuitarSancion);
        }

        $sqlPermisos = "SELECT permiso.id, permiso.nombre from permiso, rol, rolypermiso where permiso.id = rolypermiso.idpermiso and rolypermiso.idrol = rol.id and rol.id =".$result['rol'];
        $consultaPermisos = mysqli_query($conexion,$sqlPermisos);
        while($filaPermisos = mysqli_fetch_array($consultaPermisos)){
            switch($filaPermisos['id']){
                case 4:
                    $_SESSION['reservar'] = 1;
                    break;
                case 5:
                    $_SESSION['cancelar'] = 1;
                    break;
                case 6:
                    $_SESSION['usuarios'] = 1;
                    break;
                case 7:
                    $_SESSION['bajas'] = 1;
                    break;
                case 8:
                    $_SESSION['informes'] = 1;
                    break;
                case 9:
                    $_SESSION['roles'] = 1;
                    break;
                case 10:
                    $_SESSION['instalaciones'] = 1;
                    break;
            }
        } 
        }
$datos['ses'] = $_SESSION['user'];
    
}
//$datos['ses'] = $_SESSION;
echo $_GET['callback']. '('. json_encode($datos) . ')';

?>