(function () {
    
    $(document.body).on("click","#linkEntrada",function(){
        if ($("#formRegistro:visible")) $("#formRegistro").hide();
        $("#formEntrada").fadeIn(2000);
    });
    
    $(document.body).on("click","#linkRegistro",function(){
        if ($("#formEntrada:visible")) $("#formEntrada").hide();
        $("#formRegistro").fadeIn(2000);
    });
    
    $(document.body).on("click","#cancelarLogin",function(){
        $("#formEntrada").hide();
    });
    
    $(document.body).on("click","#cancelarRegistro",function(){
        $("#formRegistro").hide();
    });
    
    
    var app = angular.module('instalaciones', []);
    
    /* login */
    app.controller("FormularioEntradaController", ['$http', function ($http) {
        this.submitEntrada = function (loginEmail,loginPass) {
            var miUrl = 'http://javierperez.tk/proyecto/login.php?callback=JSON_CALLBACK&email=' +loginEmail + '&pass='+ loginPass;

            $http({
                method: 'JSONP',
                url: miUrl
                
            }).success(function (data, status, headers, config) {
                console.log(data);
                if(data.baja == true){
                    $("#formEntrada").fadeOut(2000);
                    swal("Error", "Usuario inactivo.", "error");
                }else if(data.result == true){
                    window.location.href = 'principal.php';
                }else{
                    $("#formEntrada").fadeOut(2000);
                    swal("Error", "Login incorrecto.", "error");
                }
                // data contains the response
                // status is the HTTP status
                // headers is the header getter function
                // config is the object that was used to create the HTTP request
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");

            });         
        };
            }]);
    
    /* creacion de usuarios */
    app.controller("FormularioRegistroController", ['$http', function ($http) {
        this.submitRegistro = function (nombre,primerApellido,segundoApellido,direccion,ciudad,codigoPostal,dni,telefono,email,repeatEmail,pass,repeatPass) {

            var miUrl = 'http://javierperez.tk/proyecto/crearusuario.php?callback=JSON_CALLBACK&nombre=' + nombre + '&primerApellido=' + primerApellido + '&segundoApellido=' + segundoApellido + '&direccion=' + direccion + '&ciudad='+ ciudad + '&codigoPostal=' + codigoPostal + '&dni=' + dni + '&telefono=' + telefono + '&email=' + email + '&repeatemail=' + repeatEmail + '&pass=' + pass + '&repeatpass='+ repeatPass;
            console.log(miUrl);
            $http({
                method: 'JSONP',
                url: miUrl
                
                
            }).success(function (data, status, headers, config) {
                mensaje = "";
                if(data.email == false) mensaje+= "Los emails no coinciden.\n";
                if(data.pass == false) mensaje+= "Las contraseñas no coinciden.\n";
                if(data.emailRepetido == true) mensaje+= "El email ya existe en la base de datos.\n";
                if(data.dniRepetido == true) mensaje+= "El dni ya existe en la base de datos.\n";
                if(data.nombreLargo == true) mensaje+= "El nombre excede los caracteres permitidos.\n";
                if(data.apellido1Largo == true) mensaje += "El primer apellido excede los caracteres permitidos\n";
                if(data.apellido2Largo == true) mensaje += "El segundo apellido excede los caracteres permitidos.\n.";
                if(data.direccionLarga == true) mensaje += "La dirección excede los caracteres permitidos.\n";
                if(data.dniLargo == true) mensaje += "El DNI excede los caracteres permitidos.\n";
                if(data.ciudadLarga == true) mensaje += "La ciudad excede los caracteres permitidos.\n";
                if(data.codigoPostalLargo == true) mensaje += "El código postal excede los caracteres permitidos.\n";
                if(data.telefonoLargo == true) mensaje += "El teléfono excede los caracteres permitidos.\n";
                if(data.emailLargo == true) mensaje += "El email excede los caracteres permitidos.\n";
                if(data.passwordLarga == true) mensaje += "La contraseña excede los caracteres permitidos.\n";
                //$("#spanErrorRegistro").text(mensaje);
                if(mensaje != "")swal("Error", mensaje, "error");
                else if(data.ok = true) location.href = "principal.php";
                
                // data contains the response
                // status is the HTTP status
                // headers is the header getter function
                // config is the object that was used to create the HTTP request
            }).error(function (data, status, headers, config) {
                console.log("Some error ocurred");

            });         
        };
            }]);
    
})();