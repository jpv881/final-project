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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

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
        </style>

    </head>

    <body ng-app="gestionUsuarios">
        <?php include('menu.php'); ?>
            <div class="container" ng-controller="usuariosController as usuariosCtrl" ng-cloak>
                <div id="divTabs">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="pill" href="#tab1"><b>USUARIOS</b></a></li>
                        <li><a data-toggle="pill" href="#tab2"><b>CREAR USUARIO</b></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade in active">
                            <div id="divBusquedaUsuario" class="col-md-6">

                                <input ng-model="buscar" class="form-control" placeholder="Búsqueda">
                            </div>
                            <div>
                                <table id="tablaUsuarios" class="table table-hover">
                                    <tr class="active">
                                        <td></td>
                                        <td>
                                            <h4>Nombre</h4>
                                        </td>
                                        <td>
                                            <h4>Apellido&nbsp;1</h4>
                                        </td>
                                        <td>
                                            <h4>Apellido&nbsp;2</h4>
                                        </td>
                                        <td>
                                            <h4>DNI</h4>
                                        </td>
                                        <td>
                                            <h4>Teléfono</h4>
                                        </td>
                                        <td>
                                            <h4>Email</h4>
                                        </td>
                                        <td>
                                            <h4>Tipo&nbsp;Usuario</h4>
                                        </td>
                                        <td>
                                            <h4>Sancionado</h4>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr ng-repeat="usuario in usuariosCtrl.usuarios | filter:buscar" ng-class="{true: 'table-danger'}[usuario.baja == 1]">
                                        <td>
                                            <button ng-click="usuariosCtrl.verUsuario(usuario)">
                                                <spaN class="glyphicon glyphicon-eye-open"></span>
                                            </button>
                                        </td>
                                        <td>{{usuario.nombre}}</td>
                                        <td>{{usuario.apellido1}}</td>
                                        <td>{{usuario.apellido2}}</td>
                                        <td>{{usuario.dni}}</td>
                                        <td>{{usuario.telefono}}</td>
                                        <td>{{usuario.email}}</td>
                                        <td>{{usuario.nombreRol}}</td>
                                        <td><span ng-show="usuariosCtrl.isPenalizado(usuario.penalizado)" class="fa fa-thumbs-o-down"></span>
                                            <span ng-show="!usuariosCtrl.isPenalizado(usuario.penalizado)" class="fa fa-thumbs-o-up"></span>
                                        </td>
                                        <td>
                                            <?php if($_SESSION['usuarios']){ ?> <span class="fa fa-edit fa-2x" ng-click="usuariosCtrl.abrirEditarUsuario(usuario)"></span> <?php } ?>
                                            <?php if($_SESSION['bajas']){ ?> <span class="fa fa-cogs fa-2x orange" ng-click="usuariosCtrl.abrirGestionarUsuario(usuario)"></span> <?php } ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Ventana modal detalles usuario-->
                            <div id="divModalVerUsuario" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    Modal content
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">DETALLES USUARIO</h4>
                                            <?php if($_SESSION['bajas']){ ?> <span class="fa fa-cogs" ng-click="usuariosCtrl.abrirGestionarUsuario(null)"></span> <?php } ?>
                                            <?php if($_SESSION['usuarios']){ ?> <span class="fa fa-edit" ng-click="usuariosCtrl.abrirEditarUsuario(usuariosCtrl.usuario)"></span> <?php } ?>
                                        </div>
                                        <div>
                                            <table class="table">
                                                <tr>
                                                    <td><b>Nombre</b></td>
                                                    <td>{{usuariosCtrl.usuario.nombre}} {{usuariosCtrl.usuario.apellido1}} {{usuariosCtrl.usuario.apellido2}}</td>
                                                    <td><b>Tipo</b></td>
                                                    <td>{{usuariosCtrl.usuario.nombreRol}}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Email</b></td>
                                                    <td>{{usuariosCtrl.usuario.email}}</td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td><b>DNI</b></td>
                                                    <td>{{usuariosCtrl.usuario.dni}}</td>
                                                    <td><b>Teléfono</b></td>
                                                    <td>{{usuariosCtrl.usuario.telefono}}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Ciudad</b></td>
                                                    <td>{{usuariosCtrl.usuario.ciudad}}</td>
                                                    <td><b>Código Postal</b></td>
                                                    <td>{{usuariosCtrl.usuario.codigo_postal}}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Alta</b></td>
                                                    <td>{{usuariosCtrl.usuario.fecha_alta}}</td>
                                                    <td><b>Baja</b></td>
                                                    <td><span ng_show="usuariosCtrl.returnEstado(usuariosCtrl.usuario.baja)">{{usuariosCtrl.usuario.fecha_baja}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Sancionado</b></td>
                                                    <td><span ng-show="usuariosCtrl.isPenalizado(usuariosCtrl.usuario.penalizado)" class="fa fa-thumbs-o-down"></span>
                                                        <span ng-show="!usuariosCtrl.isPenalizado(usuariosCtrl.usuario.penalizado)" class="fa fa-thumbs-o-up"></span>
                                                    </td>
                                                    <td><b>Fin Sanción</b></td>
                                                    <td><span ng_show="usuariosCtrl.returnEstado(usuariosCtrl.usuario.penalizado)">{{usuariosCtrl.usuario.fin_penalizacion}}</span></td>
                                                </tr>
                                            </table>
                                            <hr>
                                            <h4>RESERVAS</h4>
                                            <hr>
                                            <h4 ng-repeat="reserva in usuariosCtrl.usuario.reservas">{{reserva.nombre_instalacion}}, {{reserva.fecha_inicio}}</h4>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-info" data-dismiss="modal">Aceptar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin Ventana modal ver detalles usuario-->

                            <!-- Ventana modal editar usuario-->
                            <div id="divModalEditarUsuario" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    Modal content
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">EDICIÓN DE USUARIO</h4>
                                        </div>
                                        <div id="divTabsEditar">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a data-toggle="pill" href="#tab3"><b>DATOS USUARIO</b></a></li>
                                                <li><a data-toggle="pill" href="#tab4"><b>CONTRASEÑA USUARIO</b></a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div id="tab3" class="tab-pane fade in active">
                                                    <form class="form-horizontal" role="form" name="formEditarUsuario" id="formEditarUsuario" ng-submit="formEditarUsuario.$valid && 
                                                    usuariosCtrl.editarUsuario(
                                                     usuariosCtrl.usuarioEditar.id,
                                                     usuariosCtrl.usuarioEditar.nombre,
                                                     usuariosCtrl.usuarioEditar.apellido1,
                                                     usuariosCtrl.usuarioEditar.apellido2,
                                                     usuariosCtrl.usuarioEditar.direccion,
                                                     usuariosCtrl.usuarioEditar.ciudad,
                                                     usuariosCtrl.usuarioEditar.codigo_postal,
                                                     usuariosCtrl.usuarioEditar.dni,
                                                     usuariosCtrl.usuarioEditar.telefono,
                                                     usuariosCtrl.usuarioEditar.email,
                                                     usuariosCtrl.usuarioEditar.repeatemail,
                                                     usuariosCtrl.usuarioEditar.rol)" novalidate>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Nombre*</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control" id="nombre" required ng-model="usuariosCtrl.usuarioEditar.nombre">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Primer Apellido*</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control" id="primerApellido" required ng-model="usuariosCtrl.usuarioEditar.apellido1">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Segundo Apellido*</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control" id="segundoApellido" required ng-model="usuariosCtrl.usuarioEditar.apellido2">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Dirección*</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control" id="direccion" required ng-model="usuariosCtrl.usuarioEditar.direccion">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Ciudad*</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control" id="ciudad" required ng-model="usuariosCtrl.usuarioEditar.ciudad">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Código Postal*</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control" id="codigoPostal" required ng-model="usuariosCtrl.usuarioEditar.codigo_postal">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">DNI*</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control" id="dni" required ng-model="usuariosCtrl.usuarioEditar.dni">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Teléfono*</label>
                                                            <div class="col-md-9">
                                                                <input type="text" class="form-control" id="telefono" required ng-model="usuariosCtrl.usuarioEditar.telefono">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Email*</label>
                                                            <div class="col-md-9">
                                                                <input type="email" class="form-control" id="email" required ng-model="usuariosCtrl.usuarioEditar.email">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Repetir Email*</label>
                                                            <div class="col-md-9">
                                                                <input type="email" class="form-control" id="email" required ng-model="usuariosCtrl.usuarioEditar.repeatemail">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Rol*</label>
                                                            <div class="col-md-9">
                                                                <select type="password" class="form-control" ng-model="usuariosCtrl.usuarioEditar.rol" ng-init="usuariosCtrl.usuarioEditar.rol" ng-options="rol.id as rol.nombre for rol in usuariosCtrl.roles" required>
                                                                </select>
                                                                <p style="color:black;"><b>* Todos los campos son obligatorios</b></p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-info pull-right" type="submit">Editar</button>
                                                            <button class="btn btn-danger pull-right" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div id="tab4" class="tab-pane fade in">
                                                    <div>
                                                        <form class="form-horizontal" name="formEditarPass" id="formEditarPass" ng-submit="formEditarUsuario.$valid && 
                                                            usuariosCtrl.editarPass(
                                                            usuariosCtrl.pass.pass1,
                                                            usuariosCtrl.pass.pass2)">
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Nueva Contraseña</label>
                                                                <div class="col-md-9">
                                                                    <input type="password" class="form-control" id="nombre" required ng-model="usuariosCtrl.pass.pass1">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Repetir Contraseña</label>
                                                                <div class="col-md-9">
                                                                    <input type="password" class="form-control" id="primerApellido" required ng-model="usuariosCtrl.pass.pass2">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-info pull-right" type="submit">Editar</button>
                                                                <button class="btn btn-danger pull-right" data-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin Ventana modal editar usuario-->

                        <!-- Ventana modal gestionar usuario-->
                        <div id="divModalGestionarUsuario" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                Modal content
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">{{usuariosCtrl.usuario.nombre | uppercase}} {{usuariosCtrl.usuario.apellido1 | uppercase}} {{usuariosCtrl.usuario.apellido2 | uppercase}}</h4>
                                    </div>
                                    <div>
                                        <table class="table">
                                            <tr ng-show="usuariosCtrl.usuario.penalizado==0" class="bgreen">
                                                <td>
                                                    <button id="btnSancionar" class="btn btn-danger" ng-click="usuariosCtrl.penalizar()"><span><b>SANCIONAR</b></span><span class="fa fa-long-arrow-right fa-2x"></span></button>
                                                </td>
                                                <td><h4>Usuario no sancionado</h4></td>
                                            </tr>
                                            <tr ng-show="usuariosCtrl.usuario.penalizado==1" class="bred">
                                                <td>
                                                    <button id="btnEliminarSancion" class="btn btn-success" ng-click="usuariosCtrl.quitarPenalizacion()"><span><b>ELIMINAR SANCIÓN</b></span><span class="fa fa-long-arrow-right fa-2x"></span></button>
                                                </td>
                                                <td><h4>Sancionado hasta: {{usuariosCtrl.usuario.fin_penalizacion}}</h4></td>
                                            </tr>
                                            <tr ng-show="usuariosCtrl.usuario.baja==0" class="bgreen">
                                                <td>
                                                    <button class="btn btn-danger" ng-click="usuariosCtrl.darBaja()"><span><b>CURSAR BAJA</b></span><span class="fa fa-long-arrow-right fa-2x"></button>
                                                </td>
                                                <td><h4>Alta desde: {{usuariosCtrl.usuario.fecha_alta}}</h4></td>
                                            </tr>
                                            <tr ng-show="usuariosCtrl.usuario.baja==1" class="bred">
                                                <td>
                                                    <button class="btn btn-success" ng-click="usuariosCtrl.darAlta()"><span>CURSAR ALTA</span><span class="fa fa-long-arrow-right fa-2x"></span></button>
                                                </td>
                                                <td><h4>Baja desde: {{usuariosCtrl.usuario.fecha_baja}}</h4></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin Ventana modal gestionar usuario-->

                        <div id="tab2" class="tab-pane fade in">
                            <?php if($_SESSION['usuarios']){ ?>
                            <div>
                                <form class="form-horizontal" role="form" name="formCrearUsuario" id="formCrearUsuario" ng-submit="formCrearUsuario.$valid && 
                                                    usuariosCtrl.crearUsuario(
                                                     usuariosCtrl.usuarioCrear.nombre,
                                                     usuariosCtrl.usuarioCrear.apellido1,
                                                     usuariosCtrl.usuarioCrear.apellido2,
                                                     usuariosCtrl.usuarioCrear.direccion,
                                                     usuariosCtrl.usuarioCrear.ciudad,
                                                     usuariosCtrl.usuarioCrear.codigoPostal,
                                                     usuariosCtrl.usuarioCrear.dni,
                                                     usuariosCtrl.usuarioCrear.telefono,
                                                     usuariosCtrl.usuarioCrear.email,
                                                     usuariosCtrl.usuarioCrear.repeatemail,
                                                     usuariosCtrl.usuarioCrear.pass,
                                                     usuariosCtrl.usuarioCrear.repeatpass,
                                                     usuariosCtrl.usuarioCrear.rol)" novalidate>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nombre*</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="nombre" required ng-model="usuariosCtrl.usuarioCrear.nombre">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Primer Apellido*</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="primerApellido" required ng-model="usuariosCtrl.usuarioCrear.apellido1">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Segundo Apellido*</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="segundoApellido" required ng-model="usuariosCtrl.usuarioCrear.apellido2">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Dirección*</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="direccion" required ng-model="usuariosCtrl.usuarioCrear.direccion">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Ciudad*</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="ciudad" required ng-model="usuariosCtrl.usuarioCrear.ciudad">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Código Postal*</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="codigoPostal" required ng-model="usuariosCtrl.usuarioCrear.codigoPostal">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">DNI*</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="dni" required ng-model="usuariosCtrl.usuarioCrear.dni">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Teléfono*</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="telefono" required ng-model="usuariosCtrl.usuarioCrear.telefono">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Email*</label>
                                        <div class="col-md-9">
                                            <input type="email" class="form-control" id="email" required ng-model="usuariosCtrl.usuarioCrear.email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Repetir Email*</label>
                                        <div class="col-md-9">
                                            <input type="email" class="form-control" id="email" required ng-model="usuariosCtrl.usuarioCrear.repeatemail">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Contraseña*</label>
                                        <div class="col-md-9">
                                            <input type="password" class="form-control" id="pass" required ng-model="usuariosCtrl.usuarioCrear.pass">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Repetir Contraseña*</label>
                                        <div class="col-md-9">
                                            <input type="password" class="form-control" id="pass" required ng-model="usuariosCtrl.usuarioCrear.repeatpass">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Rol*</label>
                                        <div class="col-md-9">
                                            <select type="password" class="form-control" id="pass" required ng-model="usuariosCtrl.usuarioCrear.rol" ng-options="+(rol.id) as rol.nombre for rol in usuariosCtrl.roles" required>
                                            </select>
                                            <p style="color:black;"><b>* Todos los campos son obligatorios</b></p>
                                        </div>
                                    </div>              
                                    <button class="btn btn-info pull-right" type="submit">Completar Registro</button>
                                    <button class="btn btn-danger pull-right">Cancelar</button>
                                </form>
                            </div>
                            <?php } ?>
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