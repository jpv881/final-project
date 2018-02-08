<!DOCTYPE html>
<html>

<head>

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

    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <!-- El logotipo y el icono que despliega el menú se agrupan
       para mostrarlos mejor en los dispositivos móviles -->
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
            <div id="nav" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="reservasusuario.php" role="button" class="aMenu"><span class="glyphicon glyphicon-calendar"> </span> Reservas</a>
                    </li>
                    <li >
                        <a href="formeditarcuenta.php"  role="button" class="aMenu"><span class="glyphicon glyphicon-cog"></span> Mi Cuenta</a>
                    </li>
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