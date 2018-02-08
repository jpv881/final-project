(function () {

    var reservas = angular.module('reservas', []);

    reservas.controller("reservarController", ['$http', function ($http) {
        var controller = this;
        this.instalaciones = [];
        this.instalacion = {};
        this.horas = [];
        this.fecha = {};
        this.fechaFormateada;
        this.fechaEuropea;
        this.nombreIns;
        this.reservas = [];


        $http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=dameInstalaciones'

        }).success(function (data, status, headers, config) {
            controller.instalaciones = data;
        });

        this.buscar = function (inst, fecha) {

            var mensaje = "";

            if (inst == undefined) {
                mensaje += "No has seleccionado una instalación.\n";
            }

            if (fecha == undefined) {
                mensaje += "No has seleccionado una fecha."
            }

            if (mensaje != "") {
                swal("ERROR", mensaje, "error");
            } else {
                var day = fecha.getDate();
                var month = fecha.getMonth() + 1;
                var year = fecha.getFullYear();
                var fechaReserva = year + "-" + month + "-" + day;
                controller.fechaEuropea = day + "-" + month + "-" + year;
                controller.fechaFormateada = fechaReserva;

                var today = new Date();
                var actualDay = today.getDate();
                var actualMonth = today.getMonth() + 1;
                var actualYear = today.getFullYear();
                var fechaActual = year + "-" + month + "-" + day;

                var today2 = new Date("'" + actualYear + "," + actualMonth + "," + actualDay + "'"); //date sin horas
                var fechaReserva2 = new Date("'" + year + "," + month + "," + day + "'"); //date sin horas


                var oneDay = 24 * 60 * 60 * 1000;
                var diffDays = Math.round(Math.abs((fechaReserva2.getTime() - today2.getTime()) / (oneDay)));

                if (diffDays > 15) {
                    swal("ERROR", "No puedes reservar una instalación\n con más de 15 días de antelación.", "error");
                } else if (fechaReserva2 < today2) {
                    swal("ERROR", "No puedes reservar una fecha anterior a la actual.", "error");
                } else {
                    $http({
                        method: 'JSONP',
                        url: 'http://javierperez.tk/proyecto/reservasusuariogestion.php?callback=JSON_CALLBACK&funcion=dibujar&instalacion=' + inst + '&fecha=' + fechaReserva

                    }).success(function (data, status, headers, config) {
                        controller.horas = data.horas;
                    });
                }

            }
        }

        this.reservar = function (franja) {

            $.each(controller.instalaciones, function (index, value) {
                if (value.id == controller.instalacion.id) {
                    controller.nombreIns = value.nombre;
                }
            });

            swal({
                    title: "",
                    text: controller.nombreIns + "\n\n" + controller.fechaEuropea + " " + franja.nombre_inicio + "\n\n¿Reservar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#8CD4F5",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                },
                function () {
                    $http({
                        method: 'JSONP',
                        url: 'http://javierperez.tk/proyecto/reservasusuariogestion.php?callback=JSON_CALLBACK&funcion=reservar&instalacion=' + controller.instalacion.id + '&fecha=' + controller.fechaFormateada + '&fecha_inicio=' + franja.nombre_inicio + '&fecha_fin=' + franja.nombre_fin

                    }).success(function (data, status, headers, config) {
                        
                        if (data.yaTieneReserva == true) {
                            swal("ERROR","No puedes tener más de una reserva\nen una misma instalación.","error");
                        }else if(data.penalizado == true){
                            swal("ERROR","Un usuario sancionado no puede realizar reservas","error");
                        } else {
                            controller.reservas.push(data.reserva);console.log(data.reserva);
                            swal("","Reserva realizada","success");
                            $("ul.nav.nav-tabs li:nth-child(1)").removeClass("active");
                            $("ul.nav.nav-tabs li:nth-child(2)").addClass("active");

                            $("#tab1").removeClass("active in");
                            $("#tab2").addClass("active in");
                        }
                        controller.limpiarFranjas();

                    }).error(function (data, status, headers, config) {
                        console.log("Some error ocurred");
                    });
                });
        }

        this.limpiarFranjas = function () {
            $("#tablaFranjas > tbody > tr:nth-child(1)").siblings().remove();
        }



        $http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/reservasusuariogestion.php?callback=JSON_CALLBACK&funcion=verReservas'

        }).success(function (data, status, headers, config) {
            controller.reservas = data;
        });


        this.cancelarReserva = function (reserva) {
            swal({
                    title: "",
                    text: reserva.nombreInstalacion+"\n\n"+reserva.fecha_inicioFormateada+"\n\n¿ELIMINAR?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#8CD4F5",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                },
                function () {
                   
                    $http({
                        method: 'JSONP',
                        url: 'http://javierperez.tk/proyecto/reservasusuariogestion.php?callback=JSON_CALLBACK&funcion=cancelar&fecha=' + reserva.fecha_inicio + '&id='+
                        reserva.id

                    }).success(function (data, status, headers, config) {
                        //console.log(data);
                        if (data.puedeCancelar == false) {
                            swal("ERROR", "El tiempo mínimo para cancelar una reserva\nson " + data.horasCancelar + " hora(s) de antelación.", "error");
                        }else{
                            var index = controller.reservas.indexOf(reserva);
                                controller.reservas.splice(index, 1);
                            swal("","Reserva cancelada.","success");
                        }
                    });
                });

        }

    }]);

})();