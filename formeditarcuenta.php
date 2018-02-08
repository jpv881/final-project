<?php
/* Crea el formulario para editar una cuenta propia con los datos del usuario conectado*/
session_start();
include('conexion.php');

if($_SESSION['user'] == null){
    header('Location: index.html');
}

$sql = "select * from usuario where id =".$_SESSION['id'];
$consulta = mysqli_query($conexion, $sql);
$result = mysqli_fetch_array($consulta);
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
        <?php

        if($_SESSION['rol']== 1){ ?>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
            <link rel="stylesheet" href="estilos/cyborg.css">
            <?php  }else{ ?>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
                <?php }
    ?>

                    <style>
                        body {
                            padding-top: 50px;
                        }
                        
                        .starter-template {
                            padding: 40px 15px;
                            text-align: center;
                        }
                        
                        #formEditar {
                            border: solid;
                            padding: 20px;
                            border-color: white;
                            border-radius: 10px;
                            margin-right: auto;
                            margin-left: auto;
                            width: 75%;
                            margin-top: 40px;
                        }
                        
                        .btn.btn-warning {
                            margin-right: 20px;
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
                        
                        ul {
                            margin-bottom: 20px !important;
                        }
                        
                        .navbar-collapse.collapse {
                            font-size: 18px;
                        }
                        
                        a.aMenu.active {
                            color:#ff8800 !important;
                        }
                        
                    </style>

                    <!--[if IE]>
								<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
								<script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
				<![endif]-->
        
        <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-67906875-2', 'auto');
  ga('send', 'pageview');

</script>
        
    </head>

    <body ng-app="formEditarCuentaPropia">
        <?php 
    if($_SESSION['rol'] == 1){
        include('menuUsuario.php');
    }else{
        include('menu.php');
    } ?>

            <div class="container">
                <div id="formEditar">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="pill" href="#tab1">Modificar Datos</a></li>
                        <li><a data-toggle="pill" href="#tab2">Modificar Contraseña</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade in active" ng-controller="formularioEditarCuentaController as frmEdCuentaCtrl" ng-cloak>
                            <form class="form-horizontal" role="form" name="formCrearUsuario" 
                                  ng-submit="formCrearUsuario.$valid && 
                                             frmEdCuentaCtrl.submitEdicion(
                                             frmEdCuentaCtrl.usuario.nombre,
                                             frmEdCuentaCtrl.usuario.apellido1,
                                             frmEdCuentaCtrl.usuario.apellido2,
                                             frmEdCuentaCtrl.usuario.direccion,
                                             frmEdCuentaCtrl.usuario.ciudad,
                                             frmEdCuentaCtrl.usuario.codigo_postal,
                                             frmEdCuentaCtrl.usuario.dni,
                                             frmEdCuentaCtrl.usuario.telefono,
                                             frmEdCuentaCtrl.usuario.email,
                                             frmEdCuentaCtrl.usuario.repeatemail
                                             )" novalidate>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="nombre" required ng-model="frmEdCuentaCtrl.usuario.nombre" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Primer Apellido</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="primerApellido" required ng-model="frmEdCuentaCtrl.usuario.apellido1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Segundo Apellido</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="segundoApellido" required ng-model="frmEdCuentaCtrl.usuario.apellido2">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="direccion" required ng-model="frmEdCuentaCtrl.usuario.direccion">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Ciudad</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="ciudad" required ng-model="frmEdCuentaCtrl.usuario.ciudad">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Código Postal</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="codigoPostal" required ng-model="frmEdCuentaCtrl.usuario.codigo_postal">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">DNI</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="dni" required ng-model="frmEdCuentaCtrl.usuario.dni">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="telefono" required ng-model="frmEdCuentaCtrl.usuario.telefono">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" id="email" required ng-model="frmEdCuentaCtrl.usuario.email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Repetir Email</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" id="email" required ng-model="frmEdCuentaCtrl.usuario.repeatemail">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-warning pull-right" type="submit">Guardar</button>
                                    <!--                <button class="btn btn-danger pull-right">Cancelar</button>-->
                                </div>
                            </form>
                        </div>
                        <div id="tab2" class="tab-pane fade" ng-controller="formularioEditarPasswordController as frmEdPasswordCtrl">
                            <form class="form-horizontal" name="formEditarPass"
                                  ng-submit="formEditarPass.$valid && 
                                             frmEdPasswordCtrl.submitPassForm(
                                             frmEdPasswordCtrl.usuario.pass,
                                             frmEdPasswordCtrl.usuario.newPass,
                                             frmEdPasswordCtrl.usuario.confirmPass)" novalidate>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Contraseña</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" id="pass" required ng-model="frmEdPasswordCtrl.usuario.pass">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-9">
                                        <hr>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Nueva Contraseña</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" id="pass" required ng-model="frmEdPasswordCtrl.usuario.newPass">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Confirmar Contraseña</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" id="pass" required ng-model="frmEdPasswordCtrl.usuario.confirmPass">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-warning pull-right" type="submit">Guardar</button>
                                </div>
                            </form>
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