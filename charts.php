<?php
/* charts */
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
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/estilos.css">
        <link rel="stylesheet" href="css/charts.css">

    </head>

    <body ng-app="charts">
        <?php include('menu.php'); ?>

            <div class="container" ng-controller="chartsController as chartsCtrl">
                <div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="pill" href="#tab1">DIARIO</a></li>
                        <li><a data-toggle="pill" href="#tab2">HISTÓRICO</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade in active">
                            <div class="row thumbnail" id="rowTartas">
                                <div class="col-md-6">
                                    <div id="piechart"></div>
                                </div>
                                <div class="col-md-6">
                                    <div id="piechartSemanal"></div>
                                </div>
                            </div>
                            <div class="row thumbnail">
                                <h4 id="franjasOcupadas">Franjas ocupadas hoy</h4>
                                <div class="col-md-12" id="divTimeLines"></div>
                            </div>
                            <div class="row thumbnail">
                                <div class="col-md-12" id="lineChart"></div>
                            </div>
                        </div>
                        <div id="tab2" class="tab-pane fade">

                        </div>
                    </div>
                </div>
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
            <script src="angular.min.js"></script>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script src="charts.js"></script>
            <script src="menu.js"></script>
    </body>

    </html>