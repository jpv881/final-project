var ruta1 = "imagenes/1.jpg";
var ruta2 = "imagenes/2.jpg";
var ruta3 = "imagenes/3.jpg";
var ruta4 = "imagenes/4.jpg";
var ruta5 = "imagenes/5.jpg";
var ruta6 = "imagenes/6.jpg";
var ruta7 = "imagenes/7.jpg";
var ruta8 = "imagenes/8.jpg";
var ruta9 = "imagenes/9.jpg";
var numeros = [];

var rutas = ["imagenes/1.jpg", "imagenes/2.jpg", "imagenes/3.jpg", "imagenes/4.jpg", "imagenes/5.jpg", "imagenes/6.jpg", "imagenes/7.jpg", "imagenes/8.jpg", "imagenes/9.jpg"];
console.log("ok");

setInterval(function(){ 
    $('img').fadeOut(1500);
    //setTimeout(function(){ $('img').fadeOut(1000); }, 2000);
      
     
    setTimeout(function(){ 
        asignarImagenes();
        $('img').fadeIn(1500); }, 4000);
    
    /*$("#im1").fadeIn(1000);
    $("#im2").fadeIn(1000);
    $("#im3").fadeIn(1000);
    $("#im4").fadeIn(1000);
    $("#im5").fadeIn(1000);
    $("#im6").fadeIn(1000);
    $("#im7").fadeIn(1000);
    $("#im8").fadeIn(1000);
    $("#im9").fadeIn(1000);*/
    
}, 10000);

function asignarImagenes() {

    numeros = [];
    
    while (numeros.length <9) {

        var num = Math.floor(Math.random() * (10 -1)+1);
        
        if (numeros.length == 0) {
            numeros.push(num);
        } else {
            var enc = false;
            
            for (var i = 0; i < numeros.length && !enc; i++) {
                if (numeros[i] == num) {
                    enc = true;
                }
            }
            if (!enc) {
                numeros.push(num);
            }
        }

    }
    
    
    
    $("#im1").attr("src",rutas[numeros[0]-1]);
    $("#im2").attr("src",rutas[numeros[1]-1]);
    $("#im3").attr("src",rutas[numeros[2]-1]);
    $("#im4").attr("src",rutas[numeros[3]-1]);
    $("#im5").attr("src",rutas[numeros[4]-1]);
    $("#im6").attr("src",rutas[numeros[5]-1]);
    $("#im7").attr("src",rutas[numeros[6]-1]);
    $("#im8").attr("src",rutas[numeros[7]-1]);
    $("#im9").attr("src",rutas[numeros[8]-1]);
    
}

