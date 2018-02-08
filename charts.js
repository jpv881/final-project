(function () {
    var v = [];
    var charts = angular.module('charts', []);

    charts.controller("chartsController", ['$http', function ($http) {
        controller = this;



        //tarta reservas diarias
        $http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/chartsgestion.php?callback=JSON_CALLBACK&funcion=charts'

        }).success(function (data, status, headers, config) {
            controller.dibujar(data);
            console.log(data);
        });


        this.dibujar = function (data) {


            google.charts.load('current', {
                'packages': ['corechart', "timeline", 'line']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var contA = 0;
                $.each(data.tartaDiaria, function (index, value) {

                    if (contA != 0) {
                        value[1] = parseInt(value[1], 10);
                    }
                    contA++;
                });

                var datosA = google.visualization.arrayToDataTable(data.tartaDiaria);

                var optionsA = {
                    title: 'Reservas por instalación para hoy.',
                    is3D: true,
                };

                var chartA = new google.visualization.PieChart(document.getElementById('piechart'));

                chartA.draw(datosA, optionsA);

                //tarta semanal
                var contB = 0;
                $.each(data.tartaSemanal, function (index, value) {

                    if (contB != 0) {
                        value[1] = parseInt(value[1], 10);
                    }
                    contB++;
                });

                var datosB = google.visualization.arrayToDataTable(data.tartaSemanal);

                var optionsB = {
                    title: 'Reservas por instalación para la semana.',
                    is3D: true,
                };

                var chartB = new google.visualization.PieChart(document.getElementById('piechartSemanal'));

                chartB.draw(datosB, optionsB);

                //timelines
                var dataTable = new google.visualization.DataTable();
                var reservas = [];
                dataTable.addColumn({
                    type: 'string',
                    id: 'Instalacion'
                });
                dataTable.addColumn({
                    type: 'string',
                    id: 'Nombre'
                });
                dataTable.addColumn({
                    type: 'date',
                    id: 'Inicio'
                });
                dataTable.addColumn({
                    type: 'date',
                    id: 'Fin'
                });

                var alto = 50;
                var continuar = false;
                $.each(data.reservas, function (index, reservasPorIns) {
                    //value[1] = parseInt(value[1], 10);
                    if (reservasPorIns.length > 0){
                       alto += 50; 
                        continuar = true;
                    } 
                    
                    if(continuar){
                    for (var i = 0; i < reservasPorIns.length; i++) {
                        var reserva = [];
                        reserva.push(reservasPorIns[i].nombreInstalacion);
                        var texto = reservasPorIns[i].hora_inicio + " - " + reservasPorIns[i].hora_fin;
                        reserva.push(texto);
                        var inicio = new Date(0, 0, 0, parseInt(reservasPorIns[i].hora_inicio,10), 0, 0);
                        var fin = new Date(0, 0, 0, parseInt(reservasPorIns[i].hora_fin,10), 0, 0);
                        reserva.push(inicio);
                        reserva.push(fin);

                        reservas.push(reserva);
                    }
                }
                });
                
                if(continuar){
                dataTable.addRows(reservas);
                var options = {
                    timeline: {
                        colorByRowLabel: true
                    }
                };
                $("#divTimeLines").css("height", alto + "px");
                var divTimeLines = document.getElementById('divTimeLines');
                var chartTimeLines = new google.visualization.Timeline(divTimeLines);
                chartTimeLines.draw(dataTable, options);
                }
                //line chart
                var dataLine = new google.visualization.DataTable();
                dataLine.addColumn('string', 'Nº Reservas');

                for (var k = 0; k < data.linechart.instalaciones.length; k++) {
                    dataLine.addColumn('number', data.linechart.instalaciones[k].nombre);
                }

                $.each(data.linechart.sumas, function (index, filaTabla) {
                    for(var l =0;l<filaTabla.length;l++){
                        if(l != 0){
                            filaTabla[l] = parseInt(filaTabla[l], 10);
                        }
                    }

                });
                
                dataLine.addRows(data.linechart.sumas);

                var optionsLineChart = {
                    chart: {
                        title: 'Nº de reservas por instalación y día de la semana'
                    },
                    width: 900,
                    height: 500
                };
                
                var lineChart = new google.charts.Line(document.getElementById('lineChart'));
                lineChart.draw(dataLine, optionsLineChart);
            } //fin draw


        };


    }]);

})()