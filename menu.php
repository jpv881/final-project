<!DOCTYPE html>
<html>

<head>
    <!--    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
    <style>
        .btnCerrar {
            float: right;
        }
        
        #nav {
            margin-right: 15px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- El logotipo y el icono que despliega el menú se agrupan
       para mostrarlos mejor en los dispositivos móviles -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
        <div id="nav" class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a class="aMenu" href="reservas.php" role="button">Reservas</a>
                </li>
                <li>
                    <a class="aMenu" href="usuarios.php" role="button">Usuarios</a>
                </li>
                <?php if($_SESSION['informes']){ ?>
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle aMenu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="aInformes">Informes <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="charts.php"><span class="fa fa-pie-chart">&nbsp;</span>Gráficos</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="informes.php"><span class="fa fa-file-pdf-o">&nbsp;</span>Informes</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                        <?php if($_SESSION['instalaciones']){ ?>
                            <li>
                                <a  class="aMenu" href="instalaciones.php" class="" role="button">Instalaciones</a>

                            </li>
                            <?php } ?>
                                <?php if($_SESSION['roles']){ ?>
                                    <li>
                                        <a class="aMenu" href="roles.php" class="" role="button">Roles</a>
                                    </li>
                                    <?php } ?>
            </ul>
            <form method="post" action="menuUsuario.php">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <p class="navbar-text">Conectado como:&nbsp;
                                <?php echo $_SESSION['user']; ?>
                            </p>
                            <button type="submit" class="btn btn-danger navbar-btn" id="btnCerrarSesion" data-toggle="tooltip" data-placement="bottom" title="Cerrar la sesión" name="cerrar"><span class="glyphicon glyphicon-off"></span> </button>
                        </li>

                    </ul>
                </form>
        </div>

    </nav>
    <?php
      if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['cerrar'])){
            header('location:cerrarsesion.php');
        }
    }

    ?>

</body>

</html>