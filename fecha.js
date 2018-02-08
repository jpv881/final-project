(function(){
 
    var t = new Date();
    var dd = t.getDate();
    var mm = t.getMonth()+1; //January is 0!
    var yyyy = t.getFullYear();
    
    var today = yyyy+"-"+mm+"-"+dd;
    
    $("#inputFecha").attr("min",today);
    
})();