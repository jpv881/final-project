<?php

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
        <link rel="stylesheet" type="text/css" href="css/estilos.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet">


        <style>
            html,
            body {
                height: 100%;
            }
            
            body {
                padding-top: 50px;
            }
            
            .starter-template {
                padding: 40px 15px;
                text-align: center;
            }
            
            form.ng-submitted input.ng-invalid {
                border-color: #FA787E;
                background: rgba(250, 120, 126, 0.5);
            }
            
            form .ng-invalid.ng-dirty {
                border-color: #FA787E;
                background: rgba(250, 120, 126, 0.5);
            }
            
            .ng-valid.ng-dirty {
                border-color: #78FA89;
            }
            
            .container {
                height: 100%;
            }
        </style>

    </head>

    <body ng-app="gestionReservas">
        <?php include('menu.php'); ?>

            <div class="container">
                <div class="divTabs">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="pill" href="#tab1"><b>CREAR RESERVA</b></a></li>
                        <li><a data-toggle="pill" href="#tab2" id="tabVerReservas"><b>VER RESERVAS</b></a></li>

                    </ul>
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade in active" ng-controller="reservasController as reservasCtrl" ng-cloak>
                            <div id="divSelUsuario">
                                <!--                                <span><b>Seleccionar Usuario</b></span>-->
                                <div class="row">
                                    <div class="col-md-8">
                                        <div id="seleccion">
                                            <div>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td>
                                                            <h4>RESERVAR</h4></td>
                                                        <td>
                                                            <h4><span class="fa fa-hourglass-start">&nbsp;</span>HORA INICIO</h4></td>
                                                        <td>
                                                            <h4><span class="fa fa-hourglass-end">&nbsp;</span>HORA FIN</h4></td>
                                                    </tr>
                                                    <tr ng-repeat="franja in reservasCtrl.horas" ng-class="{true: 'table-danger'}[franja.franjaOcupada==1]">
                                                        <td>
                                                            <button  ng-click="reservasCtrl.reservar(franja)" ng-show="franja.franjaOcupada==0" class="btn btn-default btnReservar"><span class="fa fa-calendar-check-o span15em"></span></button>
                                                        </td>
                                                        <td><span class="spanHora">{{franja.nombre_inicio}}</span></td>
                                                        <td><span class="spanHora">{{franja.nombre_fin}}</span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-10">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="fa fa-male fa-2x"></span>
                                                    <button class="btn btn-default" ng-click="reservasCtrl.abrirSelUsuario()">Usuario</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="fa fa-futbol-o fa-2x"></span>
                                                    <select ng-change="reservasCtrl.selInstalacion(reservasCtrl.instalacion.id)" class="form-control selectIns" ng-model="reservasCtrl.instalacion.id" ng-options="+(instalacion.id) as instalacion.nombre for instalacion in reservasCtrl.instalaciones">
                                                        <option value="" disabled selected hidden>Instalación a reservar</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="fa fa-calendar fa-2x"></span>
                                                    <input ui-date-format="yy-mm-dd" type="date" id="inputFecha" name="fecha" class="form-control" ng-model="reservasCtrl.fecha.f" ng-change="reservasCtrl.selFecha(reservasCtrl.fecha.f)">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span><h4>{{reservasCtrl.usuario.nombre}} {{reservasCtrl.usuario.apellido1}} {{reservasCtrl.usuario.apellido2}}</h4></span>
                                                    <h4><span id="spanInst"></span></h4>
                                                    <h4><span>{{reservasCtrl.fechaSeleccionada}}</span></h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4><?php if($_SESSION['reservar']){ ?><button id="btnBuscarUsuario" class="btn btn-info" ng-click="reservasCtrl.buscar()">BUSCAR</button><?php } ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Ventana modal seleccionar usuario-->
                            <div id="divModalSelUsuario" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">SELECCIÓN DE USUARIO</h4>
                                        </div>
                                        <div id="divBusquedaUsuario">
                                            <input ng-model="buscar" class="form-control" placeholder="Búsqueda">
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-hover">
                                                <tr ng-repeat="usuario in reservasCtrl.usuarios | filter: buscar" ng-class="{true: 'table-danger'}[usuario.penalizado == 1]"  ng-show="usuario.penalizado==0 && usuario.baja == 0">
                                                    <td>
                                                        <button class="btn btn-default"  ng-click="reservasCtrl.seleccionarUsuario(usuario)"><span class="fa fa-long-arrow-right"></span></button>
                                                    </td>
                                                    <td>{{usuario.apellido1}} {{usuario.apellido2}}, {{usuario.nombre}}</td>
                                                    <td>{{usuario.dni}}</td>
                                                    <td>{{usuario.email}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="reservasCtrl.cancelarSelUsuario()">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Ventana modal seleccionar usuario-->
                        </div>
                        <div id="tab2" class="tab-pane fade" ng-controller="verReservasController as verReservasCtrl" ng-cloak>
                            <form class="form-horizontal" id="formVerReservas">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <span class="fa fa-search" id="spanBusquedaReserva"></span>
                                        <input type="text" class="form-control" id="inputBuscarReserva" ng-model="filtroReservas">
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" id="selectVistaReservas">
                                            <option value="hoy" selected>Hoy</option>
                                            <option value="todas">Todas</option>
                                            <option value="pagadas">Pagadas</option>
                                            <option value="noPagadas">No Pagadas</option>
                                            <option value="anuladas">Anuladas</option>
                                            <option value="noAnuladas">No Anuladas</option>
                                            <option value="acudio">Acudió</option>
                                            <option value="noAcudio">No Acudió</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <select ng-change="verReservasCtrl.selFiltrosOnChange()" data-ng-options="o.name for o in verReservasCtrl.options" class="form-control" id="selectFiltrarReservas" ng-model="verReservasCtrl.filtro" ng-change="verReservasCtrl.selFiltrosOnChange()">
                                            <!--
                                            <option selected value="no">No</option>
                                            <option value="uno">Filtrar una fecha</option>
                                            <option value="dos">Filtrar entre dos fechas</option>
-->
                                        </select>
                                    </div>
                                    <div class="col-md-2 ocultar" id="filtroFecha">
                                        <input ui-date-format="yy-mm-dd" type="date" class="form-control" ng-model="verReservasCtrl.fecha.fechaSinFormato">
                                    </div>
                                    <div class="col-md-2 ocultar" id="filtroFechaUno">
                                        <input ui-date-format="yy-mm-dd" type="date" class="form-control" ng-model="verReservasCtrl.fecha1.fechaSinFormato">
                                    </div>
                                    <div class="col-md-2 ocultar" id="filtroFechaDos">
                                        <input ui-date-format="yy-mm-dd" type="date" class="form-control" ng-model="verReservasCtrl.fecha2.fechaSinFormato">
                                    </div>
                                    <div class="col-md-1" id="divRefresh">
                                        <button class="btn btn-default" ng-click="verReservasCtrl.filtrarReservas(
                                                                                  verReservasCtrl.filtro,
                                                                                  verReservasCtrl.fecha.fechaSinFormato,
                                                                                  verReservasCtrl.fecha1.fechaSinFormato,
                                                                                  verReservasCtrl.fecha2.fechaSinFormato
                                                                                  )">
                                            <span class="fa fa-refresh fa-2x"></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-bordered" id="tablaVerReservas">
                                <tr>
                                    <td>FECHA RESERVA</td>
                                    <td>INSTALACIÓN</td>
                                    <td>USUARIO</td>
                                    <td>RESERVADA EL</td>
                                    <td>PAGADA</td>
                                    <td>ACUDIÓ</td>
                                    <td>ANULADA</td>
                                    <td>EDITAR</td>
                                </tr>
                                <tr ng-repeat="reserva in verReservasCtrl.reservas | filter:filtroReservas">
                                    <td>{{reserva.fecha_inicioFormateada}}</td>
                                    <td>{{reserva.nombreInstalacion}}</td>
                                    <td>{{reserva.nombre}} {{reserva.apellido1}} {{reserva.apellido2}}</td>
                                    <td>{{reserva.fecha_reservaFormateada}}</td>
                                    <td align="center" ng-show="reserva.pago_realizado == 1"><span class="fa fa-check-circle green"></span></td>
                                    <td align="center" ng-show="reserva.pago_realizado == 0"><span class="fa fa-times-circle red"></span></td>
                                    <td align="center" ng-show="reserva.ha_acudido == 1"><span class="fa fa-check-circle green"></span></td>
                                    <td align="center" ng-show="reserva.ha_acudido == 0"><span class="fa fa-times-circle red"></span></td>
                                    <td align="center" ng-show="reserva.reserva_anulada == 1"><span class="fa fa-check-circle green"></span></td>
                                    <td align="center" ng-show="reserva.reserva_anulada == 0"><span class="fa fa-times-circle red"></span></td>
                                    <td align="center"><span ng-click="verReservasCtrl.abrirEdicionReserva(reserva)" class="fa fa-cogs"></span></td>
                                </tr>
                            </table>
                            <!-- Ventana modal editar reserva-->
                            <div id="divModalEditarReserva" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">EDICIÓN DE RESERVA</h4>
                                        </div>
                                        <div>
                                            <table class="table table-bordered"> 
                                                <tr>
                                                    <td>{{verReservasCtrl.reservaEditar.apellido1}} {{verReservasCtrl.reservaEditar.apellido2}}, {{verReservasCtrl.reservaEditar.nombre}}</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                   <td>{{verReservasCtrl.reservaEditar.nombreInstalacion}}</td> 
                                                    <td>{{verReservasCtrl.reservaEditar.fecha_inicioFormateada}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Pagada</td>
                                                    <td align="center"><input type="checkbox" ng-model="verReservasCtrl.reservaEditar.pago_realizadoB"></td>
                                                </tr>
                                                <tr>
                                                    <td>Acudió</td>
                                                    <td align="center"><input type="checkbox" ng-model="verReservasCtrl.reservaEditar.ha_acudidoB"></td>
                                                </tr>
                                                <tr>
                                                    <td>Anulada</td>
                                                    <td align="center"><input type="checkbox" ng-model="verReservasCtrl.reservaEditar.reserva_anuladaB"></td>
                                                </tr>
                                                <tr ng-show="verReservasCtrl.reservaEditar.pago_realizado == 0">
                                                    <td>CANCELAR RESERVA</td>
                                                    <td align="center"><?php if($_SESSION['cancelar']){ ?><button class="btn btn-default" ng-click="verReservasCtrl.eliminarReserva()"><span class="fa fa-trash-o"></span></button><?php } ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-info" ng-click="verReservasCtrl.editarReserva(
                                                                                                                      verReservasCtrl.reservaEditar.pago_realizadoB,
                                                                                                                      verReservasCtrl.reservaEditar.ha_acudidoB,
                                                                                                                      verReservasCtrl.reservaEditar.reserva_anuladaB
                                                                                                                        )">Editar</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Ventana modal editar reserva-->
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
            <script src="angular.min.js"></script>
            <script src="fecha.js"></script>
            <script src="principal.js"></script>
            <script src="menu.js"></script>
    </body>

    </html>