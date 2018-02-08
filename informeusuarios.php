<?php
	session_start();
   include('conexion.php');
	 require_once 'lib/pdf/dompdf_config.inc.php';
	 
      if($_SESSION['user'] == null){
       header('Location: index.html');
	}
	
	$sql = "select * from usuario where baja = 0 order by apellido1";
    $consulta = mysqli_query($conexion, $sql);
    
    
    
$html =
'<html>
<head>
	<meta http-equiv="Content-Type" content="text/html" charset=utf-8" />
    <style>
    td{
    border:solid;
    }
    body {
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 14px;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
    }
    table {
    border-spacing: 0;
    border-collapse: collapse;
    }
    td{
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;
    }
    </style>
</head>
<body>
<div " style="margin-top:20px">
	<div " style="padding-top:20px">
    <h4>Usuarios activos</h4>
	<table style="border:solid">
        <tr>
            <td>Nombre</td>

            <td>DNI</td>
            <td>Dirección</td>
            <td>Ciudad</td>
            <td>Código Postal</td>
            <td>Teléfono</td>
            <td>Email</td>
        </tr>';

        while($fila = mysqli_fetch_array($consulta)){
            $nombre = $fila['nombre'];
            $apellido1 = $fila['apellido1'];
            $apellido2 = $fila['apellido2'];
            $dni = $fila['dni'];
            $direccion = $fila['direccion'];
            $ciudad = $fila['ciudad'];
            $codigoPostal = $fila['codigo_postal'];
            $telefono = $fila['telefono'];
            $email = $fila['email'];
            
            $html.='
            <tr>
                <td>'.$apellido1.' '.$apellido2.', '.$nombre.'</td>
                <td>'.$dni.'</td>
                <td>'.$direccion.'</td>
                <td>'.$ciudad.'</td>
                <td>'.$codigoPostal.'</td>
                <td>'.$telefono.'</td>
                <td>'.$email.'</td>
            </tr>';
            
        }

$html.=
        '</table>
    </div>
</body>
</html>';


$mipdf = new DOMPDF();
$mipdf ->set_paper("A4", "portrait");
$mipdf ->load_html(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
$mipdf ->render();
$mipdf ->stream('Usuarios.pdf');

?>