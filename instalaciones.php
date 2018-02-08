<?php
/* instalaciones y tipos de instalacion. Creacion y edicion */
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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/estilos.css">
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
                //background: rgba(250, 120, 126, 0.5);
            }
            
            form.ng-submitted textarea.ng-invalid {
                border-color: #FA787E;
                //background: rgba(250, 120, 126, 0.5);
            }
            
            form.ng-submitted select.ng-invalid {
                border-color: #FA787E;
                //background: rgba(250, 120, 126, 0.5);
            }
            
            form .ng-invalid.ng-dirty {
                border-color: #FA787E;
                //background: rgba(250, 120, 126, 0.5);
            }
            
            .ng-valid.ng-dirty {
                border-color: #78FA89;
            }
            
            #divTabs {
                border: solid;
                padding: 20px;
                border-color: black;
                border-radius: 10px;
                margin-right: auto;
                margin-left: auto;
                width: 100%;
                margin-top: 40px;
                margin-bottom: 40px;
                min-height: 90%;
            }
            /*
            ul.nav.nav-tabs li.active a {
                background-color: rgba(49, 176, 213, 0.5);
            }
*/
            
            .container {
                height: 100%;
            }
            

            
            textarea {
                width: 100%;
            }
            
            #divModalTipo label {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            div a.btn.btn-default {
                float: right;
                border-radius: 5px;
                margin-bottom: 15px;
                margin-top: 15px;
                background-color: white;
                border: solid 2px;
                background-image: none;
            }
            
            div a.btn.btn-default:hover {
                text-decoration: none;
                background-color:#F1F1F1;
            }
            
            div h4 a:visited {
                text-decoration: none;
            }
            
            .glyphicon.glyphicon-trash {
                color: red;
            }
            
            #divModalEditarTipo {
                display: none;
            }
            
            #divModalEditarTipo label,
            #divModalEditarInstalacion label {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            #divModalInstalacion label {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            #formHorasCancelar button {
                margin-left: 20px;
            }
        </style>

    </head>

    <body ng-app="gestionInstalaciones">
        <?php include('menu.php'); ?>

            <div class="container">
                <div id="divTabs">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="pill" href="#tab1"><b>INSTALACIONES</b></a></li>
                        <li><a data-toggle="pill" href="#tab2"><b>TIPOS</b></a></li>
                        <li><a data-toggle="pill" href="#tab3"><b>HORARIOS</b></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade in active" ng-controller="instalacionesController as instalacionesCtrl" ng-cloak>
                            <div>
                                <a class="btn btn-default" href="" data-toggle="modal" data-target="#divModalInstalacion"><span class="glyphicon glyphicon-plus"></span>&nbsp;AÑADIR INSTALACIÓN</a></div>
                            <div id="divInstalaciones">
                                <table class="table table-hover">
                                    <tr class="active">
                                        <td>
                                            <h4>Nombre</h4>
                                        </td>
                                        <td>
                                            <h4>Tipo</h4>
                                        </td>
                                        <td>
                                            <h4>Descripción</h4>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr ng-repeat="instalacion in instalacionesCtrl.instalaciones">
                                        <td>{{instalacion.nombre}}</td>
                                        <td>{{instalacion.nombreTipo}}</td>
                                        <td>{{instalacion.descripcion}}</td>
                                        <td>
                                            <button ng-click="instalacionesCtrl.abrirEditarInstalacion(instalacion)"><span class="glyphicon glyphicon-pencil"></span></button>
                                            <button ng-click="instalacionesCtrl.eliminarInstalacion(instalacion)"><span class="glyphicon glyphicon-trash"></span></button>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                            <!-- Ventana modal form crear instalacion-->
                            <div id="divModalInstalacion" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <form name="formCrearInstalacion" class="form-horizontal" ng-submit="formCrearInstalacion.$valid && instalacionesCtrl.crearInstalacion(instalacionesCtrl.instalacion.nombre,instalacionesCtrl.instalacion.id,instalacionesCtrl.instalacion.descripcion)" novalidate>
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Crear Instalación</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div>
                                                    <div class="form-group">
                                                        <label class="control-label">Tipo de Instalación</label>
                                                        <div class="col-md-12">
                                                            <select class="form-control" ng-model="instalacionesCtrl.instalacion.id" ng-options="+(tipo.id) as tipo.nombre for tipo in instalacionesCtrl.tipos" required>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Nombre</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" ng-model="instalacionesCtrl.instalacion.nombre" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Descripción</label>
                                                        <div class="col-md-12">
                                                            <textarea rows="5" required ng-model="instalacionesCtrl.instalacion.descripcion"></textarea>
                                                        </div>
                                                    </div>
                                                    *Todos los campos son obligatorios
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-info" type="submit" id="btnAcceder">Crear</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Ventana modal form crear instalacion-->
                            <!-- Ventana modal form editar instalacion-->
                            <div id="divModalEditarInstalacion" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <form name="formEditarInstalacion" class="form-horizontal" ng-submit="formEditarInstalacion.$valid && 
                                                        instalacionesCtrl.editarInstalacion(instalacionesCtrl.instalacionEditar.nombre,
                                                        instalacionesCtrl.instalacionEditar.descripcion)">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Editar Instalacion</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div>
                                                    <div class="form-group">
                                                        <label class="control-label">Nombre</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" ng-model="instalacionesCtrl.instalacionEditar.nombre" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Descripción</label>
                                                        <div class="col-md-12">
                                                            <textarea rows="5" required ng-model="instalacionesCtrl.instalacionEditar.descripcion"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-info" type="submit" id="btnAcceder">Editar</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="tiposCtrl.limpiarInstalacion()">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Ventana modal form editar instalacion-->
                        </div>
                        <div id="tab2" class="tab-pane fade" ng-controller="tiposController as tiposCtrl" ng-cloak>
                            <div>
                                <a class="btn btn-default" href="" data-toggle="modal" data-target="#divModalTipo"><span class="glyphicon glyphicon-plus"></span>&nbsp;AÑADIR TIPO</a></div>
                            <!-- div tipos de instalacion-->
                            <div id="divTipos">
                                <table class="table table-hover">
                                    <tr class="active">
                                        <td>
                                            <h4>Nombre</h4></td>
                                        <td>
                                            <h4>Descripción</h4></td>
                                        <td></td>
                                    </tr>
                                    <tr ng-repeat="tipo in tiposCtrl.tipos">
                                        <td>{{tipo.nombre}}</td>
                                        <td>{{tipo.descripcion}}</td>
                                        <td>
                                            <button ng-click="tiposCtrl.abrirEditarTipo(tipo)"><span class="glyphicon glyphicon-pencil"></span></button>
                                            <button ng-click="tiposCtrl.eliminarTipo(tipo)"><span class="glyphicon glyphicon-trash"></span></button>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                            <!-- fin div tipos de instalacion-->
                            <!-- Ventana modal form crear tipo-->
                            <div id="divModalTipo" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <form name="formCrearTipo" class="form-horizontal" ng-submit="formCrearTipo.$valid && 
                                                        tiposCtrl.crearTipo(tiposCtrl.tipo.nombre,
                                                        tiposCtrl.tipo.descripcion)">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Crear Tipo</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div>
                                                    <div class="form-group">
                                                        <label class="control-label">Nombre</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" ng-model="tiposCtrl.tipo.nombre" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Descripción</label>
                                                        <div class="col-md-12">
                                                            <textarea rows="5" required ng-model="tiposCtrl.tipo.descripcion"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-info" type="submit" id="btnAcceder">Crear</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Ventana modal form crear tipo-->

                            <!-- Ventana modal form editar tipo-->
                            <div id="divModalEditarTipo" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <form name="formEditarTipo" class="form-horizontal" ng-submit="formEditarTipo.$valid && 
                                                        tiposCtrl.editarTipo(tiposCtrl.tipoEditar.nombre,
                                                        tiposCtrl.tipoEditar.descripcion)">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Editar Tipo</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div>
                                                    <div class="form-group">
                                                        <label class="control-label">Nombre</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" ng-model="tiposCtrl.tipoEditar.nombre" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Descripción</label>
                                                        <div class="col-md-12">
                                                            <textarea rows="5" required ng-model="tiposCtrl.tipoEditar.descripcion"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-info" type="submit" id="btnAcceder">Editar</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="tiposCtrl.limpiarTipo()">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Ventana modal form editar tipo-->
                        </div>
                        <div id="tab3" class="tab-pane fade" ng-controller="horariosController as horariosCtrl" ng-cloak>
                            <div class="row">
                                <form id="formFranjas" class="col-md-6" name="formFranjas" ng-submit="horariosCtrl.guardarFranjas(
                                                                                                      horariosCtrl.horario.ocho,
                                                                                                      horariosCtrl.horario.nueve,
                                                                                                      horariosCtrl.horario.diez,
                                                                                                      horariosCtrl.horario.once,
                                                                                                      horariosCtrl.horario.doce,
                                                                                                      horariosCtrl.horario.trece,
                                                                                                      horariosCtrl.horario.catorce,
                                                                                                      horariosCtrl.horario.quince,
                                                                                                      horariosCtrl.horario.dieciseis,
                                                                                                      horariosCtrl.horario.diecisiete,
                                                                                                      horariosCtrl.horario.dieciocho,
                                                                                                      horariosCtrl.horario.diecinueve,
                                                                                                      horariosCtrl.horario.veinte,
                                                                                                      horariosCtrl.horario.veintiuno,
                                                                                                      horariosCtrl.horario.veintidos,
                                                                                                      horariosCtrl.horario.veintitres
                                                                                                      )">
                                    <h4>Franjas horarias disponibles</h4>
                                    <table class="table table-bordered">
                                        <tr class="active">
                                            <td>Activa</td>
                                            <td>Franja</td>
                                            <td>Activa</td>
                                            <td>Franja</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.ocho">
                                            </td>
                                            <td>08:00-09:00</td>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.dieciseis">
                                            </td>
                                            <td>16:00-17:00</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.nueve">
                                            </td>
                                            <td>09:00-10:00</td>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.diecisiete">
                                            </td>
                                            <td>17:00-18:00</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.diez">
                                            </td>
                                            <td>10:00-11:00</td>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.dieciocho">
                                            </td>
                                            <td>18:00-19:00</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.once">
                                            </td>
                                            <td>11:00-12:00</td>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.diecinueve">
                                            </td>
                                            <td>19:00-20:00</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.doce">
                                            </td>
                                            <td>12:00-13:00</td>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.veinte">
                                            </td>
                                            <td>20:00-21:00</td>
                                        </tr>
                                        <tr></tr>
                                        <td>
                                            <input type="checkbox" ng-model="horariosCtrl.horario.trece">
                                        </td>
                                        <td>13:00-14:00</td>
                                        <td>
                                            <input type="checkbox" ng-model="horariosCtrl.horario.veintiuno">
                                        </td>
                                        <td>21:00-22:00</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.catorce">
                                            </td>
                                            <td>14:00-15:00</td>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.veintidos">
                                            </td>
                                            <td>22:00-23:00</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.quince">
                                            </td>
                                            <td>15:00-16:00</td>
                                            <td>
                                                <input type="checkbox" ng-model="horariosCtrl.horario.veintitres">
                                            </td>
                                            <td>23:00-24:00</td>
                                        </tr>
                                    </table>
                                    <button class="btn btn-info" type="submit">Guardar</button>
                                </form>
                                <div class="col-md-6" id="colSanciones">
                                    <div class="row">
                                        <h4>Horas permitidas para cancelar una reserva</h4>
                                            <select data-ng-options="opcion.valor for opcion in horariosCtrl.horasCancelarOptions" ng-model="horariosCtrl.horasCancelar">

                                            </select>
                                            <button class="btn btn-info" ng-click="horariosCtrl.guardarHorasCancelar(horariosCtrl.horasCancelar)">Guardar</button>
                                        <br>
                                        <br>
                                        <p>Horas de antelación con las que se permite a un usuario cancelar una reserva.
<!--                                            <br> El valor 0 permite cancelar las reservas en cualquier momento.</p>-->
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <h4>Días de sanción.</h4>
                                            <select data-ng-options="opcion.valor for opcion in horariosCtrl.diasSancionOptions" ng-model="horariosCtrl.diasSancion">
                                                
                                            </select>
                                            <button class="btn btn-info" ng-click="horariosCtrl.guardarDiasSancion(horariosCtrl.diasSancion)">Guardar</button>
                                        <br>
                                        <br>
                                        <p>Número de días en los que un usuario sancionado no podrá realizar reservas.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
            <script src="angular.min.js"></script>
            <script src="principal.js"></script>
            <script src="menu.js"></script>
    </body>

    </html>