$(document).ready(function(){
    servicios_todos();

});



//////BUSCADOR DINAMICO
function servicios_todos(){
    console.log("servicios");
    var servicios="todos";

    $.ajax({
        type: "GET",
        url: "contenido.php",
        data: {
            'servicios' : servicios,
        dataType:"JSON",
        } ,
        success: function(data){
            var cont = 0;
            var tarjeta ="";
             for(var i = 1; i <= Object.keys(data).length; i++){
                console.log("for1");
                tarjeta += "<div class='card-deck'>";
                i++;
                for(x=0;x<3;x++){

                    console.log("for2");
                    i++;
                    tarjeta += "<div class='card'> <img class='card-img-top' src=" +data[cont]+ " alt='Card image cap'>";
                    cont++;

                    tarjeta += "<div class='card-body'> <h5 class='card-title'>" +data[cont]+ "</h5>";
                    cont++;

                    tarjeta += "<p class='card-text'>" +data[cont]+ ".</p> </div></div>";
                    cont++;

                    i++;
                    if(data[cont]==undefined){
                        x=3;
                        tarjeta += "<div class='card offset 3' style='border:none; box-shadow:none;'></div>";
                    }
                }
                i++;
                tarjeta += "</div>";
                $("#tarjetas_servicios").html(tarjeta);
            }
            //$("#tarjetas_servicios").html(tarjeta);
        }
    });
}