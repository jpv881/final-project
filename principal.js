(function () {
    //Inicializa los tooltips

    $('[data-toggle="tooltip"]').tooltip();
    /*$(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });*/

    //añade el nombre de la instalacion seleccionada a #spanInst
    var inst;
    $(document.body).on("change", ".selectIns", function () {
        inst = $(".selectIns option:selected").text();
        $("#spanInst").text(inst);
    });
    
    function formatearFecha(fecha){
            var dd = fecha.getDate();
            var mm = fecha.getMonth() + 1; //January is 0!
            var yyyy = fecha.getFullYear();

            var today = yyyy + "-" + mm + "-" + dd;
        return today;
    }
    
    
       
    var appFormEditar = angular.module('formEditarCuentaPropia', []);

    /* Edicion de una cuenta */
    appFormEditar.controller("formularioEditarCuentaController", ['$http', function ($http) {
        var controller = this;
        this.usuario = {};

        /* carga los datos en el formulario de edicion*/
        $http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/datosusuarioporid.php?callback=JSON_CALLBACK'


        }).success(function (data, status, headers, config) {
            controller.usuario.nombre = data.nombre;
            controller.usuario.apellido1 = data.apellido1;
            controller.usuario.apellido2 = data.apellido2;
            controller.usuario.direccion = data.direccion;
            controller.usuario.ciudad = data.ciudad;
            controller.usuario.codigo_postal = data.codigo_postal;
            controller.usuario.dni = data.dni;
            controller.usuario.email = data.email;
            controller.usuario.repeatemail = data.email;
            controller.usuario.telefono = data.telefono;


            //                if(data.ok = true) location.href = "principal.php";
            // data contains the response
            // status is the HTTP status
            // headers is the header getter function
            // config is the object that was used to create the HTTP request
        }).error(function (data, status, headers, config) {
            console.log("Some error ocurred");

        });
        /* fin carga de datos */

        /* submit form edicion datos cuenta*/
        this.submitEdicion = function (nombre, primerApellido, segundoApellido, direccion, ciudad, codigoPostal, dni, telefono, email, repeatEmail) {

            var miUrl = 'http://javierperez.tk/proyecto/editarcuenta.php?callback=JSON_CALLBACK&nombre=' + nombre + '&primerApellido=' + primerApellido + '&segundoApellido=' + segundoApellido + '&direccion=' + direccion + '&ciudad=' + ciudad + '&codigoPostal=' + codigoPostal + '&dni=' + dni + '&telefono=' + telefono + '&email=' + email + '&repeatemail=' + repeatEmail + '&formPassword=false';

            $http({
                method: 'JSONP',
                url: miUrl


            }).success(function (data, status, headers, config) {
                if (data.ok == true) {
                    swal({
                            title: "",
                            text: "Datos Modificados",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#8CD4F5",
                            confirmButtonText: "Aceptar",
                            closeOnConfirm: true
                        },
                        function () {
                            location.href = "principal.php"
                        });
                } else {
                    mensaje = "";
                    if (data.email == false) mensaje += "Los emails no coinciden.\n";
                    if (data.pass == false) mensaje += "Las contraseñas no coinciden.\n";
                    if (data.emailRepetido == true) mensaje += "El email ya existe en la base de datos.\n";
                    if (data.dniRepetido == true) mensaje += "El dni ya existe en la base de datos.\n";
                    if (data.nombreLargo == true) mensaje += "El nombre excede los caracteres permitidos.\n";
                    if (data.apellido1Largo == true) mensaje += "El primer apellido excede los caracteres permitidos\n";
                    if (data.apellido2Largo == true) mensaje += "El segundo apellido excede los caracteres permitidos.\n.";
                    if (data.direccionLarga == true) mensaje += "La dirección excede los caracteres permitidos.\n";
                    if (data.dniLargo == true) mensaje += "El DNI excede los caracteres permitidos.\n";
                    if (data.ciudadLarga == true) mensaje += "La ciudad excede los caracteres permitidos.\n";
                    if (data.codigoPostalLargo == true) mensaje += "El código postal excede los caracteres permitidos.\n";
                    if (data.telefonoLargo == true) mensaje += "El teléfono excede los caracteres permitidos.\n";
                    if (data.emailLargo == true) mensaje += "El email excede los caracteres permitidos.\n";

                    //$("#spanErrorRegistro").text(mensaje);
                    if (mensaje != "") swal("Error", mensaje, "error");
                }


                //                if(data.ok = true) location.href = "principal.php";
                // data contains the response
                // status is the HTTP status
                // headers is the header getter function
                // config is the object that was used to create the HTTP request
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");

            });
        };
        /* fin submit form edicion datos cuenta*/
            }]);
    /* fin edicion datos cuenta*/

    /* edicion contraseña */
    appFormEditar.controller("formularioEditarPasswordController", ['$http', function ($http) {
        this.usuario = {};

        /* submit form editar contraseña*/
        this.submitPassForm = function (pass, newPass, confirmPass) {
            var url = 'http://javierperez.tk/proyecto/editarcuenta.php?callback=JSON_CALLBACK&pass=' + pass + '&newPass=' + newPass + '&confirmPass=' + confirmPass + '&formPassword=true';

            $http({
                method: 'JSONP',
                url: url

            }).success(function (data, status, headers, config) {
                if (data.ok == true) {
                    swal({
                            title: "",
                            text: "Contraseña Modificada",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#8CD4F5",
                            confirmButtonText: "Aceptar",
                            closeOnConfirm: true
                        },
                        function () {
                            location.href = "principal.php"
                        });
                } else if (data.ok == false) {
                    mensaje = "";
                    if (data.passIguales == false) mensaje += "Las contraseñas no coinciden.\n";
                    if (data.passwordLarga == true) mensaje += "La contraseña excede los caracteres permitidos.\n";
                    if (data.newPasswordLarga == true) mensaje += "La nueva contraseña excede los caracteres permitidos.\n";
                    if (data.passErronea == true) mensaje += "Contraseña no válida.\n";

                    if (mensaje != "") swal("Error", mensaje, "error");
                }

            });
        };
        /* fin submit form editar contraseña*/
    }]);
    /* fin edicion contraseña */

    /*-------------INSTALACIONES----------*/

    var appGestionInstalaciones = angular.module('gestionInstalaciones', []);


    /* tipos instalaciones*/
    appGestionInstalaciones.controller("tiposController", ['$http', function ($http) {
        var controller = this;
        this.tipo = {};
        this.tipoEditar = {};
        this.tipos = [];

        /* vista tipos */
        $http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=dameTipos'

        }).success(function (data, status, headers, config) {
            controller.tipos = data;

        }).error(function (data, status, headers, config) {
            console.log("Some error ocurred");
        });
        /* fin vista tipos*/

        /* creacion tipos*/
        this.crearTipo = function (nombre, descripcion) {
            controller.tipo = {};
            var url = 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&nombre=' + nombre + '&descripcion=' + descripcion + '&funcion=crearTipo';

            controller.tipo.nombre = nombre;
            controller.tipo.descripcion = descripcion;

            $http({
                method: 'JSONP',
                url: url

            }).success(function (data, status, headers, config) {
                if (data.ok == true) {
                    $("#divModalTipo").modal("hide");
                    swal({
                            title: "",
                            text: "Tipo Creado",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#8CD4F5",
                            confirmButtonText: "Aceptar",
                            closeOnConfirm: true
                        },
                        function () {
                    
                        });
                    controller.tipo.id = data.id;
                    controller.tipos.push(controller.tipo);
                    controller.tipo = {};

                } else {
                    mensaje = "";
                    if (data.nombreLargo == true) mensaje += "El nombre excede los caracteres permitidos.\n";
                    if (data.descripcionLarga == true) mensaje += "La descripción excede los caracteres permitidos.\n";
                    if (mensaje != "") swal("Error", mensaje, "error");
                }

            });
        };
        /* fin creacion tipos */

        /* eliminacion tipos */
        this.eliminarTipo = function (tipo) {

                swal({
                        title: "ATENCIÓN",
                        text: "¿Eliminar " + tipo.nombre + "?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#8CD4F5",
                        confirmButtonText: "Aceptar",
                        closeOnConfirm: false
                    },
                    function () {
                        $http({
                            method: 'JSONP',
                            url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=eliminarTipo&id=' + tipo.id

                        }).success(function (data, status, headers, config) {
                            if(data.tieneInstalaciones == true){
                                swal("ERROR","No puedes elimiar un tipo que ya tenga instalaciones.","error");
                            }else if (data.ok == true) {
                                var index = controller.tipos.indexOf(tipo);
                                controller.tipos.splice(index, 1);
                                swal("","Tipo eliminado.","success");
                            }


                        }).error(function (data, status, headers, config) {
                            console.log("Some error ocurred");
                        });
                    });
            }
            /* fin eliminacion tipos */

        /* edicion tipos*/
        this.abrirEditarTipo = function (tipo) {
            $("#divModalEditarTipo").modal("show");
            controller.tipoEditar.nombre = tipo.nombre;
            controller.tipoEditar.descripcion = tipo.descripcion;
            controller.tipo = tipo;
        };

        this.editarTipo = function (nombre, descripcion) {

            $http({
                method: 'JSONP',
                url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=editarTipo&id=' + controller.tipo.id + '&nombre=' + nombre + '&descripcion=' + descripcion

            }).success(function (data, status, headers, config) {
                if (data.ok == true) {
                    $("#divModalEditarTipo").modal("hide");
                    swal({
                        title: "",
                        text: "Tipo Editado",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#8CD4F5",
                        confirmButtonText: "Aceptar",
                        closeOnConfirm: true
                    }, function () {});
                    var index = controller.tipos.indexOf(controller.tipo);
                    controller.tipos[index].nombre = nombre;
                    controller.tipos[index].descripcion = descripcion;
                    controller.tipoEditar = {};
                    controller.tipo = {};
                }

            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });

        };
        /* fin edicion tipos*/

        /* tipo = null en caso de cancelar una edicion */
        this.limpiarTipo = function () {
            controller.tipo = {};
        };

    }]);
    /* fin  tipos instalaciones*/

    /* controlador horarios */
    appGestionInstalaciones.controller("horariosController", ['$http', function ($http) {
        var controller = this;
        this.diasAlmacenados = 0;
        this.posicionDias = 0;
        this.contDias = 0;
        this.posicionHoras = 0;
        this.contHoras = 0;

        this.horario = {};
        this.diasSancion = {};
        this.diasSancionOptions = [{ valor: '1'}, { valor: '2' }, { valor: '3' }, { valor: '5' }, { valor: '7' }, { valor: '15' }, { valor: '20' }, { valor: '30' }];
        this.horasCancelar = {};
        this.horasCancelarOptions = [{ valor: '0'}, { valor: '3' }, { valor: '6' }, { valor: '12' }, { valor: '24' }, { valor: '48' }];
        
        //selecciona en el select los dias almacenados en la bd
        this.posicionSelectDias = function(){
        
            $http({
                method: 'JSONP',
                url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=dameDiasSancion'


            }).success(function (data, status, headers, config) {


                $.each(controller.diasSancionOptions, function (index, value) {

                    if(value.valor == data[0].valor){
                        controller.posicionDias = controller.contDias;
                    } 
                    controller.contDias++;                  
                });
                    controller.diasSancion = controller.diasSancionOptions[controller.posicionDias]; 
                
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
         
        }
        
        this.posicionSelectDias();
        
         //selecciona en el select las horas almacenadas en la bd
        this.posicionSelectHoras = function(){
        
            $http({
                method: 'JSONP',
                url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=dameHorasCancelar'


            }).success(function (data, status, headers, config) {


                $.each(controller.horasCancelarOptions, function (index, value) {

                    if(value.valor == data[0].valor){
                        controller.posicionHoras = controller.contHoras;
                    } 
                    controller.contHoras++;                  
                });
                    controller.horasCancelar = controller.horasCancelarOptions[controller.posicionHoras]; 
                
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
         
        }
        
        this.posicionSelectHoras();

        $http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=dameFranjas'


        }).success(function (data, status, headers, config) {
            if (data.ok == true) {
                controller.horario.ocho = data.ocho;
                controller.horario.nueve = data.nueve;
                controller.horario.diez = data.diez;
                controller.horario.once = data.once;
                controller.horario.doce = data.doce;
                controller.horario.trece = data.trece;
                controller.horario.catorce = data.catorce;
                controller.horario.quince = data.quince;
                controller.horario.dieciseis = data.dieciseis;
                controller.horario.diecisiete = data.diecisiete;
                controller.horario.dieciocho = data.dieciocho;
                controller.horario.diecinueve = data.diecinueve;
                controller.horario.veinte = data.veinte;
                controller.horario.veintiuno = data.veintiuno;
                controller.horario.veintidos = data.veintidos;
                controller.horario.veintitres = data.veintitres;
            }

        }).error(function (data, status, headers, config) {
            console.log("Some error ocurred");
        });

        /* GUARDADO DE LAS FRANJAS HORARIAS AL EDITAR*/
        this.guardarFranjas = function (ocho, nueve, diez, once, doce, trece, catorce, quince, dieciseis, diecisiete, dieciocho, diecinueve, veinte, veintiuno, veintidos, veintitres) {

            var url = 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=guardarFranjas&ocho=' + ocho + '&nueve=' + nueve + '&diez=' + diez + '&once=' + once + '&doce=' + doce + '&trece=' + trece + '&catorce=' + catorce + '&quince=' + quince + '&dieciseis=' + dieciseis + '&diecisiete=' + diecisiete +
                '&dieciocho=' + dieciocho + '&diecinueve=' + diecinueve + '&veinte=' + veinte + '&veintiuno=' + veintiuno + '&veintidos=' + veintidos + '&veintitres=' + veintitres;

            $http({
                method: 'JSONP',

                url: url

            }).success(function (data, status, headers, config) {
                if (data.ok == true) {
                    swal({
                        title: "",
                        text: "Horarios Modificados",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#8CD4F5",
                        confirmButtonText: "Aceptar",
                        closeOnConfirm: true
                    }, function () {});
                }

            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
        };
        /* FIN GUARDADO DE LAS FRANJAS HORARIAS AL EDITAR*/
        
        this.guardarDiasSancion = function(dias){
            var url = 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=guardarDiasSancion&dias='+dias.valor;

        $http({
            method: 'JSONP',
                url: url

            }).success(function (data, status, headers, config) {
                if(data.ok == true)  swal("", "Dias almacenados.", "info");
                
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
        }
        
        this.guardarHorasCancelar = function(horas){
            var url = 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=guardarHorasCancelar&horas='+horas.valor;

        $http({
            method: 'JSONP',
                url: url

            }).success(function (data, status, headers, config) {
                if(data.ok == true)  swal("", "Horas almacenadas.", "info");
                
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
        }
        
    }]);
    /* fin controlador horarios */

    /* CONTROLADOR INSTALACIONES*/
    appGestionInstalaciones.controller("instalacionesController", ['$http', function ($http) {
        var controller = this;
        this.tipos = [];
        this.instalacion = {};
        this.instalacionEditar = {};
        this.instalaciones = [];

        /* VISTA INSTALACIONES*/
        $http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=dameInstalaciones'

        }).success(function (data, status, headers, config) {
            controller.instalaciones = data;
        }).error(function (data, status, headers, config) {
            console.log("Some error ocurred");
        });
        /* FIN VISTA INSTALACIONES*/

        /* vista tipos */
        $http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=dameTipos'

        }).success(function (data, status, headers, config) {
            controller.tipos = data;
        }).error(function (data, status, headers, config) {
            console.log("Some error ocurred");
        });

        this.parseId = function (id) {
            controller.tipo.id = parseInt(id);
        };
        /* fin vista tipos*/

        this.crearInstalacion = function (nombre, tipo, descripcion) {

            var url = 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=crearInstalacion&nombre=' + nombre + '&tipo=' + tipo + '&descripcion=' + descripcion;

            $http({
                method: 'JSONP',
                url: url

            }).success(function (data, status, headers, config) {
                if (data.ok == true) {
                    controller.instalacion.nombreTipo = data.nombreTipo;
                    controller.instalacion.id = data.id;

                    $("#divModalInstalacion").modal("hide");
                    swal({
                            title: "",
                            text: "Instalación Creada",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#8CD4F5",
                            confirmButtonText: "Aceptar",
                            closeOnConfirm: true
                        },
                        function () {

                        });
                    controller.instalaciones.push(controller.instalacion);
                    controller.instalacion = {};
                } else {
                    var mensaje = "";
                    if (data.nombreLargo == true) mensaje += "El nombre excede los caracteres permitidos\n";
                    if (data.descripcionLarga == true) mensaje += "La descripción excede los caracteres permitidos";

                    swal("Error", mensaje, "error");
                }
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            })
        };

        this.eliminarInstalacion = function (instalacion) {
            swal({
                    title: "ATENCIÓN",
                    text: "¿Eliminar " + instalacion.nombre + "?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#8CD4F5",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                },
                function () {
                    $http({
                        method: 'JSONP',
                        url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=eliminarInstalacion&id=' + instalacion.id

                    }).success(function (data, status, headers, config) {
                        if(data.tieneReservas == true){
                            swal("ERROR","No puedes eliminar una instalación en la que\nhay reservas.","error");
                        }else if (data.ok == true) {
                            var index = controller.instalaciones.indexOf(instalacion);
                            controller.instalaciones.splice(index, 1);
                            swal("","Instalación eliminada.","success");
                        }


                    }).error(function (data, status, headers, config) {
                        console.log("Some error ocurred");
                    });
                });
        }

        this.abrirEditarInstalacion = function (instalacion) {

            $("#divModalEditarInstalacion").modal("show");
            controller.instalacionEditar.nombre = instalacion.nombre;
            controller.instalacionEditar.descripcion = instalacion.descripcion;
            controller.instalacion = instalacion;
        }

        /* instalacion = null en caso de cancelar una edicion */
        this.limpiarInstalacion = function () {
            controller.instalacion = {};
        };

        this.editarInstalacion = function (nombre, descripcion) {

            $http({
                method: 'JSONP',
                url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=editarInstalacion&id=' + controller.instalacion.id + '&nombre=' + nombre + '&descripcion=' + descripcion

            }).success(function (data, status, headers, config) {
                if (data.ok == true) {
                    $("#divModalEditarInstalacion").modal("hide");
                    swal({
                        title: "",
                        text: "Instalacion Editada",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#8CD4F5",
                        confirmButtonText: "Aceptar",
                        closeOnConfirm: true
                    }, function () {});
                    var index = controller.instalaciones.indexOf(controller.instalacion);
                    controller.instalaciones[index].nombre = nombre;
                    controller.instalaciones[index].descripcion = descripcion;
                    controller.instalacionEditar = {};
                    controller.instalacion = {};
                }

            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });


        }

    }]);
    /* FIN CONTROLADOR INSTALACIONES*/


    /*-------------FIN INSTALACIONES----------*/


    /*-------------ROLES----------*/

    var appGestionRoles = angular.module('gestionRoles', []);

    appGestionRoles.controller("rolesController", ['$http', function ($http) {
        var controller = this;
        this.rol = {};
        this.rolVer = {};
        this.rolEditar = {};
        this.roles = [];

        this.rol.realizarReserva = false;
        this.rol.cancelarReserva = false;
        this.rol.cuentas = false;
        this.rol.bajas = false;
        this.rol.informes = false;
        this.rol.roles = false;
        this.rol.instalaciones = false;

        this.rolEditar.realizarReserva = false;
        this.rolEditar.cancelarReserva = false;
        this.rolEditar.cuentas = false;
        this.rolEditar.bajas = false;
        this.rolEditar.informes = false;
        this.rolEditar.roles = false;
        this.rolEditar.instalaciones = false;
        this.permisos = [];
        this.nombreRol = null; //guarda el nombre del rol antes de editarlo para saber si ha cambiado

        $http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/rolesgestion.php?callback=JSON_CALLBACK&funcion=dameRoles'

        }).success(function (data, status, headers, config) {
            controller.roles = data;

        }).error(function (data, status, headers, config) {
            console.log("Some error ocurred");
        });

        this.crearRol = function (nombre, descripcion, realizarReserva, cancelarReserva, cuentas, bajas, informes, roles, instalaciones) {
            var url = 'http://javierperez.tk/proyecto/rolesgestion.php?callback=JSON_CALLBACK&funcion=crearRol&nombre=' + nombre + '&descripcion=' + descripcion + '&realizarReserva=' + realizarReserva + '&cancelarReserva=' + cancelarReserva + '&cuentas=' + cuentas + '&bajas=' + bajas + '&informes=' + informes + '&roles=' + roles + '&instalaciones=' + instalaciones;

            $http({
                method: 'JSONP',
                url: url

            }).success(function (data, status, headers, config) {

                mensaje = "";
                if (data.insertar == false) {
                    mensaje += "Debes asignar algún permiso.\n"
                }

                if (data.nombreLargo == true) {
                    mensaje += "El nombre excede los caracteres permitidos.\n";
                }

                if (data.descripcionLarga == true) {
                    mensaje += "La descripcion excede los caracteres permitidos.\n";
                }

                if (data.nombreExistente == true) {
                    mensaje += "El nombre ya existe en la base de datos.\n";
                }

                if (mensaje != "") swal("", mensaje, "warning");

                if (data.ok == true) {
                    controller.rol = {};
                    controller.roles.push(data.rol);
                    swal("", "Rol Creado", "info");
                }
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });

        };

        this.limpiarChecks = function () {
            controller.rol = {};
        }
        this.limpiarChecksEditar = function () {
            controller.rolEditar = {};
            $("#divModalEditarRol").modal("hide");
        }

        /* oculta editar y borrar en los roles predefinidos*/
        this.ocultarBoton = function (nombre) {
            if (nombre == "usuario" || nombre == "administrador") return true;
            return false;
        }

        this.verRol = function (rol) {
            controller.rolVer = rol;
            $http({
                method: 'JSONP',
                url: 'http://javierperez.tk/proyecto/rolesgestion.php?callback=JSON_CALLBACK&funcion=damePermisos&id=' + rol.id

            }).success(function (data, status, headers, config) {
                controller.permisos = data;
                $("#divModalVerPermisos").modal("show");
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
        }

        /*abre una ventana modal con los datos del rol*/
        this.abrirEditarRol = function (rol) {
            controller.nombreRol = rol.nombre;
            controller.rol = rol;
            controller.rolEditar.nombre = rol.nombre;
            controller.rolEditar.descripcion = rol.descripcion;
            controller.rolEditar.id = rol.id;
            $("#divModalEditarRol").modal("show");

            $http({
                method: 'JSONP',
                url: 'http://javierperez.tk/proyecto/rolesgestion.php?callback=JSON_CALLBACK&funcion=damePermisosRol&id=' + rol.id

            }).success(function (data, status, headers, config) {
                if (data.realizarReserva == true) controller.rolEditar.realizarReserva = true;
                if (data.cancelarReserva == true) controller.rolEditar.cancelarReserva = true;
                if (data.cuentas == true) controller.rolEditar.cuentas = true;
                if (data.bajas == true) controller.rolEditar.bajas = true;
                if (data.informes == true) controller.rolEditar.informes = true;
                if (data.roles == true) controller.rolEditar.roles = true;
                if (data.instalaciones == true) controller.rolEditar.instalaciones = true;


            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
        }

        this.editarRol = function (nombre, descripcion, realizarReserva, cancelarReserva, cuentas, bajas, informes, roles, instalaciones) {

            var url = 'http://javierperez.tk/proyecto/rolesgestion.php?callback=JSON_CALLBACK&funcion=editarRol&id=' + controller.rolEditar.id + '&nombre=' + nombre + '&descripcion=' + descripcion + '&realizarReserva=' + realizarReserva + '&cancelarReserva=' + cancelarReserva + '&cuentas=' + cuentas + '&bajas=' + bajas + '&informes=' + informes + '&roles=' + roles + '&instalaciones=' + instalaciones + '&nombreAnterior=' + controller.nombreRol;

            $http({
                method: 'JSONP',
                url: url

            }).success(function (data, status, headers, config) {
                mensaje = "";
                if (data.editar == false) {
                    mensaje += "Debes asignar algún permiso.\n"
                }

                if (data.nombreLargo == true) {
                    mensaje += "El nombre excede los caracteres permitidos.\n";
                }

                if (data.descripcionLarga == true) {
                    mensaje += "La descripcion excede los caracteres permitidos.\n";
                }

                if (data.nombreExistente == true) {
                    mensaje += "El nombre ya existe en la base de datos.\n";
                }

                if (mensaje != "") swal("", mensaje, "warning");

                if (data.ok == true) {

                    var index = controller.roles.indexOf(controller.rol);
                    controller.roles[index].nombre = nombre;
                    controller.roles[index].descripcion = descripcion;
                    if (data.realizarReserva == true) controller.roles[index].realizarReserva = true;
                    if (data.cancelarReserva == true) controller.roles[index].cancelarReserva = true;
                    if (data.cuentas == true) controller.roles[index].cuentas = true;
                    if (data.bajas == true) controller.roles[index].bajas = true;
                    if (data.informes == true) controller.roles[index].informes = true;
                    if (data.roles == true) controller.roles[index].roles = true;
                    if (data.instalaciones == true) controller.roles[index].instalaciones = true;
                    controller.rolEditar = {};
                    $("#divModalEditarRol").modal("hide");
                    swal("", "Rol Editado", "info");
                }
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
        }

        this.eliminarRol = function (rol) {
            swal({
                    title: "ATENCIÓN",
                    text: "¿Eliminar " + rol.nombre + "?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#8CD4F5",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                },
                function () {
                    $http({
                        method: 'JSONP',
                        url: 'http://javierperez.tk/proyecto/rolesgestion.php?callback=JSON_CALLBACK&funcion=eliminarRol&id=' + rol.id

                    }).success(function (data, status, headers, config) {

                        if (data.rolConUsuarios == true) {

                            swal("Error", "No puedes eliminar un rol con usuarios asignados", "warning");
                        }

                        if (data.ok == true) {
                            var index = controller.roles.indexOf(rol);
                            controller.roles.splice(index, 1);
                            swal("","Rol eliminado.","success");
                        }


                    }).error(function (data, status, headers, config) {
                        console.log("Some error ocurred");
                    });
                });
        }

    }]);
    /*-------------FIN ROLES----------*/

    /*--------------USUARIOS-----------------*/

    var appGestionUsuarios = angular.module('gestionUsuarios', []);

    appGestionUsuarios.controller("usuariosController", ['$http', function ($http) {
        var controller = this;
        this.usuarios = [];
        this.usuario = {};
        this.usuarioCrear = {}; //usuario para separar el usuario a crear del modelo vista usuarios
        this.roles = [];
        this.usuarioEditar = {};
        this.pass = {};
        this.usu = {};
        
        //vista de los usuarios de la bd
        $http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/usuariosgestion.php?callback=JSON_CALLBACK&funcion=dameUsuarios'

        }).success(function (data, status, headers, config) {
            controller.usuarios = data;

        }).error(function (data, status, headers, config) {
            console.log("Some error ocurred");
        });

        //carga los roles en el select para la creacion de un usuario
        $http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/usuariosgestion.php?callback=JSON_CALLBACK&funcion=dameRoles'

        }).success(function (data, status, headers, config) {
            controller.roles = data;
        }).error(function (data, status, headers, config) {
            console.log("Some error ocurred");
        });

        this.isPenalizado = function (penalizado) {

            if (penalizado == 0) return false;
            else return true;
        }

        this.verUsuario = function (usuario) {
            controller.usuario = usuario;
            $("#divModalVerUsuario").modal("show");
        }

        this.crearUsuario = function (nombre, primerApellido, segundoApellido, direccion, ciudad, codigoPostal, dni, telefono, email, repeatEmail, pass, repeatPass, rol) {

            var miUrl = 'http://javierperez.tk/proyecto/usuariosgestion.php?callback=JSON_CALLBACK&funcion=crearUsuario&nombre=' + nombre + '&primerApellido=' + primerApellido + '&segundoApellido=' + segundoApellido + '&direccion=' + direccion + '&ciudad=' + ciudad + '&codigoPostal=' + codigoPostal + '&dni=' + dni + '&telefono=' + telefono + '&email=' + email + '&repeatemail=' + repeatEmail + '&pass=' + pass + '&repeatpass=' + repeatPass + '&rol=' + rol;

            $http({
                method: 'JSONP',
                url: miUrl


            }).success(function (data, status, headers, config) {
                if(data.usuario != null){
                    controller.usuarioCrear = data.usuario;
                }
                
                //controller.usuarioCrear.penalizado = data.penalizado;
                
                //controller.usuarioCrear.baja = 0;
                mensaje = "";
                if (data.email == false) mensaje += "Los emails no coinciden.\n";
                if (data.pass == false) mensaje += "Las contraseñas no coinciden.\n";
                if (data.emailRepetido == true) mensaje += "El email ya existe en la base de datos.\n";
                if (data.dniRepetido == true) mensaje += "El dni ya existe en la base de datos.\n";
                if (data.nombreLargo == true) mensaje += "El nombre excede los caracteres permitidos.\n";
                if (data.apellido1Largo == true) mensaje += "El primer apellido excede los caracteres permitidos\n";
                if (data.apellido2Largo == true) mensaje += "El segundo apellido excede los caracteres permitidos.\n.";
                if (data.direccionLarga == true) mensaje += "La dirección excede los caracteres permitidos.\n";
                if (data.dniLargo == true) mensaje += "El DNI excede los caracteres permitidos.\n";
                if (data.ciudadLarga == true) mensaje += "La ciudad excede los caracteres permitidos.\n";
                if (data.codigoPostalLargo == true) mensaje += "El código postal excede los caracteres permitidos.\n";
                if (data.telefonoLargo == true) mensaje += "El teléfono excede los caracteres permitidos.\n";
                if (data.emailLargo == true) mensaje += "El email excede los caracteres permitidos.\n";
                if (data.passwordLarga == true) mensaje += "La contraseña excede los caracteres permitidos.\n";
                //$("#spanErrorRegistro").text(mensaje);
                if (mensaje != "") swal("Error", mensaje, "error");
                if (data.ok == true) {
                    controller.usuarioCrear.nombreRol = data.nombreRol;
                    controller.usuarios.push(controller.usuarioCrear);
                    $("#formCrearUsuario").removeClass("form-horizontal ng-valid-email ng-submitted ng-pristine ng-untouched ng-invalid ng-invalid-required");
                    $("#formCrearUsuario").addClass("form-horizontal ng-pristine ng-invalid ng-invalid-required ng-valid-email");
                    $("#formCrearUsuario input").removeClass("ng-touched ng-dirty");
                    $("#formCrearUsuario select").removeClass("ng-touched ng-dirty");
                    $("#formCrearUsuario input").addClass("ng-pristine ng-untouched");
                    $("#formCrearUsuario input").addClass("ng-pristine ng-untouched");
                    controller.usuarioCrear = {};
                    console.log(controller.usuarios);
                    swal("", "Usuario Creado", "info");
                }

                // data contains the response
                // status is the HTTP status
                // headers is the header getter function
                // config is the object that was used to create the HTTP request
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");

            });

        }

        this.returnEstado = function (estado) {

            if (estado == "0") return false;
            else return true;
        }

        this.abrirEditarUsuario = function (usuario) {
            //controller.usuarioEditar = usuario;
            controller.usu = usuario;
            var id = usuario.id;
            var nombre = usuario.nombre;
            var apellido1 = usuario.apellido1;
            var apellido2 = usuario.apellido2;
            var direccion = usuario.direccion;
            var ciudad = usuario.ciudad;
            var codigo_postal = usuario.codigo_postal;
            var dni = usuario.dni;
            var telefono = usuario.telefono;
            var email = usuario.email;
            var repeatemail = usuario.email;
            var rol = usuario.rol;
            //controller.usuarioEditar.repeatemail = usuario.email;
            
            controller.usuarioEditar.id = id;
            controller.usuarioEditar.nombre = nombre;
            controller.usuarioEditar.apellido1 = apellido1;
            controller.usuarioEditar.apellido2 = apellido2;
            controller.usuarioEditar.direccion = direccion;
            controller.usuarioEditar.ciudad = ciudad;
            controller.usuarioEditar.codigo_postal = codigo_postal;
            controller.usuarioEditar.dni = dni;
            controller.usuarioEditar.telefono = telefono;
            controller.usuarioEditar.email = email;
            controller.usuarioEditar.repeatemail = repeatemail;
            controller.usuarioEditar.rol = rol;
            
            $("#divModalVerUsuario").modal("hide");
            //$("#divModalEditarUsuario").modal("show");
            
            $("#divModalEditarUsuario").modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });

        }

        this.editarPass = function (pass1, pass2) {

            var url = 'http://javierperez.tk/proyecto/usuariosgestion.php?callback=JSON_CALLBACK&funcion=editarPass&id=' + controller.usuarioEditar.id + '&pass1=' + pass1 + '&pass2=' + pass2;

            swal({
                    title: "ATENCIÓN",
                    text: "¿Editar Contraseña?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#8CD4F5",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                },
                function () {
                    $http({
                        method: 'JSONP',
                        url: url

                    }).success(function (data, status, headers, config) {
                        var mensaje = "";
                        if (data.passLarga == true) mensaje += "La contraseña excede los caracteres permitidos";
                        if (data.passIguales == false) mensaje += "Las contraseñas no coinciden";
                        if (mensaje != "") swal("ERROR", mensaje, "warning");
                        else swal("","Contraseña editada","success");
                        controller.pass = {};
                        $("#divModalEditarUsuario").modal("hide");

                    }).error(function (data, status, headers, config) {
                        console.log("Some error ocurred");
                    });
                });
        }
        
        this.editarUsuario = function (id, nombre, apellido1, apellido2, direccion, ciudad, codigo_postal, dni, telefono, email, repeatemail, rol) {
            var url = 'http://javierperez.tk/proyecto/usuariosgestion.php?callback=JSON_CALLBACK&funcion=editarUsuario&id=' + id + '&nombre=' + nombre + '&apellido1=' + apellido1 + '&apellido2=' + apellido2 + '&direccion=' + direccion + '&ciudad=' + ciudad + '&codigo_postal=' + codigo_postal + '&dni=' + dni + '&telefono=' + telefono + '&email=' + email + '&repeatemail=' + repeatemail + '&rol=' + rol;

            swal({
                    title: "ATENCIÓN",
                    text: "¿Editar Usuario?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#8CD4F5",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function (isConfirm) {
                    if(isConfirm){
                        
                        $http({
                        method: 'JSONP',
                        url: url

                    }).success(function (data, status, headers, config) {
                        var mensaje = "";

                            if (data.ok == true) {
                                controller.usuarioEditar.nombreRol = data.nombreRol;
                                $("#divModalEditarUsuario").modal("hide");
                                swal("","Usuario editado.","success");
                                
                                var index = controller.usuarios.indexOf(controller.usu);console.log("index"+index);
                                console.log(controller.usu);
                                console.log(controller.usuarioEditar);
                                controller.usuarios[index] = controller.usuarioEditar;
                                
                            } else {
                                if (data.emailsIguales == false) mensaje += "Los emails no coinciden.\n";
                                if (data.dniExistente == true) mensaje += "El DNI introducido ya existe.\n";
                                if (data.emailExistente == true) mensaje += "El email introducido ya existe.\n";
                                if (data.nombreLargo == true) mensaje += "El nombre excede los caracteres permitidos.\n";
                                if (data.apellido1Largo == true) mensaje += "El primer apellido excede los caracteres permitidos.\n";
                                if (data.apellido2Largo == true) mensaje += "El segundo apellido excede los caracteres permitidos.\n";
                                if (data.direccionLarga == true) mensaje += "La dirección excede los caracteres permitidos.\n";
                                if (data.dniLargo == true) mensaje += "El DNI excede los caracteres permitidos.\n";
                                if (data.ciudadLarga == true) mensaje += "La ciudad excede los caracteres permitidos.\n";
                                if (data.codigoPostalLargo == true) mensaje += "El código postal excede los caracteres permitidos.\n";
                                if (data.telefonoLargo == true) mensaje += "El teléfono excede los caracteres permitidos.\n";
                                if (data.emailLargo == true) mensaje += "El email excede los caracteres permitidos.\n";

                                if (mensaje != "") swal("ERROR", mensaje, "warning");
                                //controller.usuarioEditar = {};
                                
                                
                                //console.log(data.pagada==true);
                                
                            }

                        }).error(function (data, status, headers, config) {
                            console.log("Some error ocurred");
                        });
                    }else{
                        controller.usuarioEditar = {};
                        swal("","Edición cancelada","success");
                    }
                    
                });
        }

        this.abrirGestionarUsuario = function (usuario) {
            if (usuario != null) {
                controller.usuario = usuario;
            }
            $("#divModalVerUsuario").modal("hide");
            $("#divModalGestionarUsuario").modal("show");
        }

        this.quitarPenalizacion = function () {

        }

        this.penalizar = function () {
            $http({
                method: 'JSONP',
                url: 'http://javierperez.tk/proyecto/usuariosgestion.php?callback=JSON_CALLBACK&funcion=penalizar&id=' + controller.usuario.id

            }).success(function (data, status, headers, config) {
                if (data.ok = true) {
                    var index = controller.usuarios.indexOf(controller.usuario);
                    controller.usuarios[index].penalizado = true;
                    controller.usuarios[index].fin_penalizacion = data.fin_penalizacion;
                    $("#divModalGestionarUsuario").modal("hide");
                    swal("", "Usuario sancionado.", "info");
                }
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
        }

        this.quitarPenalizacion = function () {
            $http({
                method: 'JSONP',
                url: 'http://javierperez.tk/proyecto/usuariosgestion.php?callback=JSON_CALLBACK&funcion=quitarPenalizacion&id=' + controller.usuario.id

            }).success(function (data, status, headers, config) {
                if (data.ok = true) {
                    var index = controller.usuarios.indexOf(controller.usuario);
                    controller.usuarios[index].penalizado = false;
                    controller.usuarios[index].fin_penalizacion = null;
                    $("#divModalGestionarUsuario").modal("hide");
                    swal("", "Sanción eliminada.", "info");
                }
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
        }

        this.darBaja = function () {
            $http({
                method: 'JSONP',
                url: 'http://javierperez.tk/proyecto/usuariosgestion.php?callback=JSON_CALLBACK&funcion=darBaja&id=' + controller.usuario.id

            }).success(function (data, status, headers, config) {
                if (data.ok = true) {
                    var index = controller.usuarios.indexOf(controller.usuario);
                    controller.usuarios[index].baja = true;
                    controller.usuarios[index].fecha_baja = data.fecha_baja;
                    $("#divModalGestionarUsuario").modal("hide");
                    swal("", "Baja Cursada.", "info");
                }
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
        }

        this.darAlta = function () {
            $http({
                method: 'JSONP',
                url: 'http://javierperez.tk/proyecto/usuariosgestion.php?callback=JSON_CALLBACK&funcion=darAlta&id=' + controller.usuario.id

            }).success(function (data, status, headers, config) {
                if (data.ok = true) {
                    var index = controller.usuarios.indexOf(controller.usuario);
                    controller.usuarios[index].baja = false;
                    controller.usuarios[index].fecha_alta = data.fecha_alta;
                    $("#divModalGestionarUsuario").modal("hide");
                    swal("", "Baja Cursada.", "info");
                }
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
        }


    }]);
    /*--------------FIN USUARIOS-------------*/


    /*--------------GESTIÓN RESERVAS USUARIOS-------------*/

    var appGestionReservas = angular.module('gestionReservas', []);

    appGestionReservas.controller("reservasController", ['$http', function ($http) {
        controller = this;
        this.usuario = {};
        this.instalacion = {};
        this.reserva = {};
        this.reservas = [];
        this.usuarios = [];
        this.horas = [];
        this.fecha = {};
        this.fechaSeleccionada; //fecha en formato y-m-d para usar en la sql
        this.instalaciones = [];


        $("#seleccion").css("display", "initial");

        $http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=dameInstalaciones'

        }).success(function (data, status, headers, config) {
            controller.instalaciones = data;
        }).error(function (data, status, headers, config) {
            console.log("Some error ocurred");
        });

        this.abrirSelUsuario = function () {
            //$("#divSelUsuario").css("visibility","hidden");
            $("#seleccion > div > table > tbody > tr:nth-child(1)").siblings().remove();
            $("#divModalSelUsuario").modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });

            $http({
                method: 'JSONP',
                url: 'http://javierperez.tk/proyecto/usuariosgestion.php?callback=JSON_CALLBACK&funcion=dameUsuarios'

            }).success(function (data, status, headers, config) {
                controller.usuarios = data;

            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");
            });
        }

        this.cancelarSelUsuario = function () {
            //$("#divSelUsuario").css("visibility","visible");
        }

        this.seleccionarUsuario = function (usuario) {
            controller.usuario = usuario;
            $("#divModalSelUsuario").modal("hide");
        }

        this.selInstalacion = function (id) {
            controller.instalacion.id = id;
            $("#seleccion > div > table > tbody > tr:nth-child(1)").siblings().remove();
            if (!(Object.getOwnPropertyNames(controller.fecha).length === 0)) { //cambio de fecha con una instalacion ya seleccionada
                controller.selFecha(controller.fecha);
            }
        }

        this.selFecha = function (f) {
            $("#seleccion > div > table > tbody > tr:nth-child(1)").siblings().remove();
            var t = new Date();
            var dd = t.getDate();
            var mm = t.getMonth() + 1; //January is 0!
            var yyyy = t.getFullYear();

            var today = yyyy + "-" + mm + "-" + dd;

            var day = controller.fecha.f.getDate();
            var month = controller.fecha.f.getMonth() + 1;
            var year = controller.fecha.f.getFullYear();
            var fecha = year + "-" + month + "-" + day;


            if (fecha < today) {
                swal("ERROR", "No puedes reservar en una fecha anterior al dia actual.", "warning");
                controller.fecha = {};
            } else {
                controller.fechaSeleccionada = fecha; 
            }

        }
        this.buscar = function () {
            var mensaje = "";
            if (controller.usuario.id == null) {
                mensaje += "No hay ningún usuario seleccionado.\n";
            }
            if (controller.instalacion.id == null) {
                mensaje += "No hay ningúna instalación seleccionada.\n";
            } 
            if (controller.fechaSeleccionada == null) {
                mensaje += "No hay ningúna fecha seleccionada.\n";
            }

            if (mensaje != "") {
                swal("ERROR", mensaje, "warning");
            } else {
                var url = 'http://javierperez.tk/proyecto/reservasgestion.php?callback=JSON_CALLBACK&funcion=dibujar&usuario=' + controller.usuario.id + '&instalacion=' + controller.instalacion.id + '&fecha=' + controller.fechaSeleccionada;

                $http({
                    method: 'JSONP',
                    url: url

                }).success(function (data, status, headers, config) {
                    controller.horas = data.horas;
                    
                    if (data.instalacion == "null") {
                        controller.fecha = {};
                        swal("ERROR", "No hay instalaciones seleccionadas.", "warning");

                    }
                }).error(function (data, status, headers, config) {
                    console.log("Some error ocurred");
                });
            }
        }

        this.reservar = function (franja) {

            var url = 'http://javierperez.tk/proyecto/reservasgestion.php?callback=JSON_CALLBACK&funcion=reservar&usuario=' + controller.usuario.id + '&instalacion=' + controller.instalacion.id + '&fecha_inicio=' + franja.nombre_inicio + '&fecha_fin=' + franja.nombre_fin + '&fecha=' + controller.fechaSeleccionada;
            
            swal({
                    title: "RESERVA",
                    text: controller.usuario.nombre + " " + controller.usuario.apellido1 + " " + controller.usuario.apellido2 + "\n" + inst + "\n" + franja.nombre_inicio + " - " + franja.nombre_fin,
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#8CD4F5",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                },
                function () {
                    $http({
                        method: 'JSONP',
                        url: url

                    }).success(function (data, status, headers, config) {
                        if (data.yaTieneReserva == true) {
                            swal("ERROR", "Un usuario sólo puede tener activa una reserva\n para una misma instalación.", "warning");
                        }else if(data.fechaPasada == true){
                            swal("ERROR", "No puedes reservar una fecha anterior a la actual.", "warning");
                        }else{
                            $("#seleccion > div > table > tbody > tr:nth-child(1)").siblings().remove()
                            controller.fechaSeleccionada = null;
                            controller.usuario = {};
                            controller.instalacion={};
                            controller.fecha = {};
                            $("#spanInst").text("");
                            controller.reservas.push(data.reserva);
                            swal("","Reserva realizada.","success");
                        }
                    }).error(function (data, status, headers, config) {
                        console.log("Some error ocurred");
                    });
                });

        }

    }]);

    appGestionReservas.controller("verReservasController", ['$http', function ($http) {
        var controller = this;
        this.reservas = [];
        this.reserva = {};
        this.reservaEditar = {};
        this.fecha = {};
        this.fecha1 = {};
        this.fecha2 = {};
        this.filtro = {};
        this.urlActual = null;
        controller.options = [{ name: "No", id: 1 }, { name: "Filtrar una fecha", id: 2 }, { name: "Filtrar entre dos fechas", id: 3 }];
        controller.filtro = controller.options[0];
        
        $("#tabVerReservas").on("click",function(){
            if(controller.urlActual == null){
                pintarReservas('http://javierperez.tk/proyecto/reservasgestion.php?callback=JSON_CALLBACK&funcion=verReservas&reserva=hoy&fecha=null&fecha1=null&fecha2=null');
            }else{
                pintarReservas(controller.urlActual);
            }
        });
        
    $("#selectFiltrarReservas").attr("disabled", true);
    
    $("#selectVistaReservas").on("change",function(){
        if($("#selectVistaReservas").val()=="hoy"){
            $("#selectFiltrarReservas").attr("disabled", true);
            controller.filtro = controller.options[0];
            
            if(!$("#filtroFecha").hasClass("ocultar")) {
                    $("#filtroFecha").addClass("ocultar");
                 }
                if(!$("#filtroFechaUno").hasClass("ocultar")) {
                    $("#filtroFechaUno").addClass("ocultar");
                 }
                if(!$("#filtroFechaDos").hasClass("ocultar")) {
                    $("#filtroFechaDos").addClass("ocultar");
                }
        }else{
            $("#selectFiltrarReservas").attr("disabled", false);
        }
    });
        
        //pintarReservas('http://javierperez.tk/proyecto/reservasgestion.php?callback=JSON_CALLBACK&funcion=verReservas&reserva=hoy&fecha=null&fecha1=null&fecha2=null');
        
        
        //muestra los input date en funcion del tipo de filtrado
        this.selFiltrosOnChange = function(){

            if(controller.filtro.name =="No"){
                if(!$("#filtroFecha").hasClass("ocultar")) {
                    $("#filtroFecha").addClass("ocultar");
                 }
                if(!$("#filtroFechaUno").hasClass("ocultar")) {
                    $("#filtroFechaUno").addClass("ocultar");
                 }
                if(!$("#filtroFechaDos").hasClass("ocultar")) {
                    $("#filtroFechaDos").addClass("ocultar");
                }
            }else if(controller.filtro.name =="Filtrar una fecha"){
                if($("#filtroFecha").hasClass("ocultar")) {
                    $("#filtroFecha").removeClass("ocultar");
                }
                if(!$("#filtroFechaUno").hasClass("ocultar")) {
                    $("#filtroFechaUno").addClass("ocultar");
                }
                if(!$("#filtroFechaDos").hasClass("ocultar")) {
                    $("#filtroFechaDos").addClass("ocultar");
                }
            }else if(controller.filtro.name =="Filtrar entre dos fechas"){
                if(!$("#filtroFecha").hasClass("ocultar")) {
                    $("#filtroFecha").addClass("ocultar");
                 }
                if($("#filtroFechaUno").hasClass("ocultar")) {
                    $("#filtroFechaUno").removeClass("ocultar");
                }
                if($("#filtroFechaDos").hasClass("ocultar")) {
                    $("#filtroFechaDos").removeClass("ocultar");
                }
            }
        }

        this.filtrarReservas = function(filtrado,fecha,fecha1,fecha2){
            
            var tipoReserva = $("#selectVistaReservas").val();

            var url;
            if(controller.filtro.name =="No"){
                url = 'http://javierperez.tk/proyecto/reservasgestion.php?callback=JSON_CALLBACK&funcion=verReservas&reserva='+tipoReserva+'&fecha=null&fecha1=null&fecha2=null';
                pintarReservas(url);
                controller.urlActual = url;
            }else if(controller.filtro.name =="Filtrar una fecha"){
                if(fecha == undefined){
                    swal("Error", "Debes seleccionar una fecha para el filtrado.", "error");
                }else{
                    url = 'http://javierperez.tk/proyecto/reservasgestion.php?callback=JSON_CALLBACK&funcion=verReservas&reserva='+tipoReserva+'&fecha='+formatearFecha(fecha)+'&fecha1=null&fecha2=null';
                    pintarReservas(url);
                    controller.urlActual = url;
                }  
            }else if(controller.filtro.name =="Filtrar entre dos fechas"){
                if(fecha1==undefined || fecha2 == undefined){
                    swal("Error", "Debes seleccionar las fechas para el filtrado.", "error");
                }else{
                   url = 'http://javierperez.tk/proyecto/reservasgestion.php?callback=JSON_CALLBACK&funcion=verReservas&reserva='+tipoReserva+'&fecha=null&fecha1='+formatearFecha(fecha1)+'&fecha2='+formatearFecha(fecha2);
                    pintarReservas(url);
                    controller.urlActual = url;
                }   
            }

        }
        
        function pintarReservas(url){
            
            $http({
            method: 'JSONP',
            url: url

        }).success(function (data, status, headers, config) {
            controller.reservas = data;
             
        }).error(function (data, status, headers, config) {
            console.log("Some error ocurred");
        });
        }
        
        this.abrirEdicionReserva = function(reserva){
            controller.reservaEditar = reserva;
            controller.reserva = reserva;
            $("#divModalEditarReserva").modal("show");
            
        }
        
        this.editarReserva = function(pagada,acudio,anulada){
            
            swal({
                        title: "ATENCIÓN",
                        text: "¿Editar " + controller.reservaEditar.nombreInstalacion + " "+controller.reservaEditar.fecha_inicioFormateada+"?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#8CD4F5",
                        confirmButtonText: "Aceptar",
                        closeOnConfirm: true
                    },
                    function () {
                        $http({
                            method: 'JSONP',
                            url: 'http://javierperez.tk/proyecto/reservasgestion.php?callback=JSON_CALLBACK&funcion=editarReserva&id=' + controller.reservaEditar.id +'&pagada='+pagada+'&acudio='+acudio+'&anulada='+anulada

                        }).success(function (data, status, headers, config) {
                            
                            if (data.ok == true) {
                                                                
                                  var index = controller.reservas.indexOf(controller.reserva);
                                
                                //console.log(data.pagada==true);
                                controller.reservas[index].pago_realizado = data.pagada;
                                controller.reservas[index].ha_acudido = data.acudio;
                                controller.reservas[index].reserva_anulada = data.anulada;
//                                controller.reservas[index].pago_realizadoB = data.pagadaB;
//                                controller.reservas[index].ha_acudidoB = data.acudioB;
//                                controller.reservas[index].reserva_anuladaB = data.anuladaB;
                               
                                controller.reservaEditar = {};
                                controller.reserva = {};
                                $("#divModalEditarReserva").modal("hide");
                            }


                        }).error(function (data, status, headers, config) {
                            console.log("Some error ocurred");
                        });
                    });
        }
        
        this.eliminarReserva = function(){
            swal({
                        title: "ATENCIÓN",
                        text: "¿ELIMINAR?\n\n " +controller.reserva.nombre+" "+controller.reserva.apellido1+" "+controller.reserva.apellido2+"\n\n"+ controller.reserva.nombreInstalacion + " "+controller.reserva.fecha_inicioFormateada,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#8CD4F5",
                        confirmButtonText: "Aceptar",
                        closeOnConfirm: false
                    },
                    function () {
                        $http({
                            method: 'JSONP',
                            url: 'http://javierperez.tk/proyecto/reservasgestion.php?callback=JSON_CALLBACK&funcion=eliminarReserva&id=' + controller.reserva.id

                        }).success(function (data, status, headers, config) {
                            
                            if(data.pagada == true) {
                                swal("ERROR", "No puede eliminarse una reserva que haya sido pagada.", "warning");
                            }else{                                 
                                var index = controller.reservas.indexOf(controller.reserva);
                                controller.reservas.splice(index, 1);
                                controller.reserva = {};
                                $("#divModalEditarReserva").modal("hide");
                                swal("","Reserva eliminada.","success");
                            }


                        }).error(function (data, status, headers, config) {
                            console.log("Some error ocurred");
                        });
                    });
        }
        
    }]);
    
    

    /*--------------FIN GESTIÓN RESERVAS USUARIOS-------------*/

})();

/*
$http({
            method: 'JSONP',
            url: 'http://javierperez.tk/proyecto/instalacionesgestion.php?callback=JSON_CALLBACK&funcion=dameTipos'

        }).success(function (data, status, headers, config) {
            controller.tipos = data;
        }).error(function (data, status, headers, config) {
            console.log("Some error ocurred");
        });

*/