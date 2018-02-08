<?php
/* Reservas realizadas por un usuario*/
session_start();
include('conexion.php');

if($_SESSION['user'] == null){
    header('Location: index.html');
}

?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Reservas</title>
        <link rel="shortcut icon" href="">
        <script src="lib/sweetalert/sweetalert.min.js"></script>
        <link rel="stylesheet" type="text/css" href="lib/sweetalert/sweetalert.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="estilos/cyborg.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/estilos_usuario.css">
        
        <style>
            a.aMenu.active {
              color:#ff8800 !important;
            }
        </style>
        
        <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-67906875-2', 'auto');
  ga('send', 'pageview');

</script>

    </head>

    <body ng-app="reservas">
        <?php include('menuUsuario.php'); ?>

            <div class="container" ng-controller="reservarController as reservarCtrl" ng-cloak>
                <div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="pill" href="#tab1">RESERVAR</a></li>
                        <li><a data-toggle="pill" href="#tab2" id="aTab2">RESERVAS ACTUALES</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade in active">
                            <div class="row">
                                <div class="col-md-3">
                                    <span class="fa fa-futbol-o fa-2x"></span>
                                    <select ng-options="+(instalacion.id) as instalacion.nombre for instalacion in reservarCtrl.instalaciones" ng-model="reservarCtrl.instalacion.id" 
                                            ng-change="reservarCtrl.limpiarFranjas()">
                                        <option value="" disabled selected hidden>Instalación a reservar</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <span class="fa fa-calendar fa-2x"></span>
                                    <input type="date" ui-date-format="yy-mm-dd" ng-model="reservarCtrl.fecha.f" ng-change="reservarCtrl.limpiarFranjas()">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-warning" ng-click="reservarCtrl.buscar(reservarCtrl.instalacion.id,reservarCtrl.fecha.f)">Buscar</button>
                                </div>
                            </div>
                            <div class="row">
                                <div id="seleccion" class="col-md-8">
                                    <table class="table table-bordered" id="tablaFranjas">
                                        <tr>
                                            <td>
                                                <span class="textoTabla"><span class="fa fa-check"></span>Reservar</span>
                                            </td>
                                            <td>
                                                <span class="textoTabla"><span class="fa fa-hourglass-start"></span>Hora Inicio</span>
                                            </td>
                                            <td>
                                                <span class="textoTabla"><span class="fa fa-hourglass-end"></span>Hora Fin</span>
                                            </td>
                                        </tr>
                                        <tr ng-repeat="franja in reservarCtrl.horas" ng-class="{true: 'fondoRojo'}[franja.franjaOcupada==1]">
                                            <td>
                                                <button ng-show="franja.franjaOcupada==0" class="btn btn-default" ng-click="reservarCtrl.reservar(franja)"><span class="fa fa-calendar-check-o"></span></button>
                                            </td>
                                            <td><span class="spanHora">{{franja.nombre_inicio}}</span></td>
                                            <td><span class="spanHora">{{franja.nombre_fin}}</span></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                        </div>
                        <div id="tab2" class="tab-pane fade">
                            <div>
                                <table class="table table-bordered">
                                    <tr>
                                        <td><span class="textoTabla">FECHA RESERVA</span></td>
                                        <td><span class="textoTabla">INSTALACIÓN</span></td>
                                        <td><span class="textoTabla">PAGADA</span></td>
                                        <td><span class="textoTabla">ANULADA</span></td>
                                        <td><span class="textoTabla">CANCELAR</span></td>
                                    </tr>
                                    <tr ng-repeat="reserva in reservarCtrl.reservas" ng-class="{true: 'fondoRojo'}[reserva.reserva_anulada==1]">
                                        <td>{{reserva.fecha_inicioFormateada}}</td>
                                        <td>{{reserva.nombreInstalacion}}</td>
                                        <td align="center" ng-show="reserva.pago_realizado == 1"><span class="fa fa-check-circle"></span></td>
                                        <td align="center" ng-show="reserva.pago_realizado == 0"><span class="fa fa-times-circle"></span></td>
                                        <td align="center"><span  ng-show="reserva.reserva_anulada == 1" class="fa fa-check-circle"></span></td>
                                        <td align="center"><button class="btn btn-default" ng-click="reservarCtrl.cancelarReserva(reserva)" ><span class="fa fa-trash"></span></button></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
            <script src="angular.min.js"></script>
            <script src="reservasusuario.js"></script>
            <script src="menu.js"></script>
    </body>

    </html>