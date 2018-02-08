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
                padding: 40px;
                text-align: center;
            }
            
            form.ng-submitted input.ng-invalid {
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
        </style>

    </head>

    <body ng-app="gestionRoles">
        <?php include('menu.php'); ?>
            <div class="container" ng-controller="rolesController as rolesCtrl" ng-cloak>
                <div id="divTabs">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="pill" href="#tab1"><b>ROLES</b></a></li>
                        <li><a data-toggle="pill" href="#tab2"><b>CREAR ROL</b></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade in active">
                            <div>
                                <table id="tablaRoles" class="table table-hover">
                                    <tr class="active">
                                        <td>
                                        </td>
                                        <td>
                                            <h4>Nombre</h4>
                                        </td>
                                        <td>
                                            <h4>Descripcion</h4>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr ng-repeat="rol in rolesCtrl.roles">
                                        <td>
                                            <button ng-click="rolesCtrl.verRol(rol)" >
                                                <span class="glyphicon glyphicon-eye-open"></span>
                                            </button>
                                        </td>
                                        <td>
                                            {{rol.nombre}}
                                        </td>
                                        <td>
                                            {{rol.descripcion}}
                                        </td>
                                        <td>
                                            <button ng-hide="rolesCtrl.ocultarBoton(rol.nombre)" ng-click="rolesCtrl.abrirEditarRol(rol)"><span class="glyphicon glyphicon-pencil"></span></button>
                                            <button ng-hide="rolesCtrl.ocultarBoton(rol.nombre)" ng-click="rolesCtrl.eliminarRol(rol)"><span class="glyphicon glyphicon-trash"></span></button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div id="tab2" class="tab-pane fade">
                            <div>
                                <form id="formCrearRol" class="form-horizontal" name="formCrearRol" ng-submit="formCrearRol.$valid && rolesCtrl.crearRol(
                                                 rolesCtrl.rol.nombre,
                                                 rolesCtrl.rol.descripcion,
                                                 rolesCtrl.rol.realizarReserva,
                                                 rolesCtrl.rol.cancelarReserva,
                                                 rolesCtrl.rol.cuentas,
                                                 rolesCtrl.rol.bajas,
                                                 rolesCtrl.rol.informes,
                                                 rolesCtrl.rol.roles,
                                                 rolesCtrl.rol.instalaciones
                                                 )">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <div>
                                            <input type="text" class="form-control" ng-model="rolesCtrl.rol.nombre" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Descripción</label>
                                        <div>
                                            <textarea class="form-control" ng-model="rolesCtrl.rol.descripcion">
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rol.realizarReserva">
                                        <label>Realizar reservas</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rol.cancelarReserva">
                                        <label>Cancelar reservas</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rol.cuentas">
                                        <label>Alta y modificación de cuentas</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rol.bajas">
                                        <label>Baja de socios</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rol.informes">
                                        <label>Ver informes</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rol.roles">
                                        <label>Crear roles</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rol.instalaciones">
                                        <label>Administrar instalaciones</label>
                                    </div>
                                    <div class="from-group">
                                        <button type="button" class="btn btn-danger" ng-click="rolesCtrl.limpiarChecks()">Cancelar</button>
                                        <button class="btn btn-info" type="submit" id="btnAcceder">Crear</button>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ventana modal ver permisos de un rol-->
                <div id="divModalVerPermisos" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{rolesCtrl.rolVer.nombre}}</h4>
                            </div>
                            <div>
                                <table class="table">
                                    <tr ng-repeat="permiso in rolesCtrl.permisos">
                                        <td><span class="glyphicon glyphicon-ok"></span></td>
                                        <td>{{permiso.nombre}}</td>
                                        <td>{{permiso.descripcion}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" data-dismiss="modal">Aceptar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Ventana modal ver permisos de un rol-->

                <!-- Ventana modal editar un rol-->
                <div id="divModalEditarRol" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <form id="formEditarRol" class="form-horizontal" name="formEditarRol" ng-submit="formEditarRol.$valid && rolesCtrl.editarRol(
                                                 rolesCtrl.rolEditar.nombre,
                                                 rolesCtrl.rolEditar.descripcion,
                                                 rolesCtrl.rolEditar.realizarReserva,
                                                 rolesCtrl.rolEditar.cancelarReserva,
                                                 rolesCtrl.rolEditar.cuentas,
                                                 rolesCtrl.rolEditar.bajas,
                                                 rolesCtrl.rolEditar.informes,
                                                 rolesCtrl.rolEditar.roles,
                                                 rolesCtrl.rolEditar.instalaciones
                                                 )">
                                <div class="modal-header">
                                    <h4 class="modal-title">Editar Rol</h4>
                                </div>
                                <div>

                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <div>
                                            <input type="text" class="form-control" ng-model="rolesCtrl.rolEditar.nombre" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Descripción</label>
                                        <div>
                                            <textarea class="form-control" ng-model="rolesCtrl.rolEditar.descripcion">
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rolEditar.realizarReserva">
                                        <label>Realizar reservas</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rolEditar.cancelarReserva">
                                        <label>Cancelar reservas</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rolEditar.cuentas">
                                        <label>Alta y modificación de cuentas</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rolEditar.bajas">
                                        <label>Baja de socios</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rolEditar.informes">
                                        <label>Ver informes</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rolEditar.roles">
                                        <label>Crear roles</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" ng-model="rolesCtrl.rolEditar.instalaciones">
                                        <label>Administrar instalaciones</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="from-group">
                                        <button type="button" class="btn btn-danger" ng-click="rolesCtrl.limpiarChecksEditar()">Cancelar</button>
                                        <button class="btn btn-info" type="submit" id="btnAcceder">Editar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Fin Ventana modal editar un rol-->
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
            <script src="angular.min.js"></script>
            <script src="principal.js"></script>
            <script src="menu.js"></script>
    </body>

    </html>