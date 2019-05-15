$("#user").keyup(function() {
    console.log($("#user").val());
});
$("#contrasenya").keyup(function() {
    console.log(("#contrasenya").val());
});


$("#enviar").click(function() {
    var username = $("#user").val();
    var password = $("#contrasenya").val();

    console.log(username + password);


    $.post("login.php", { user: $("#user").val(), contrasenya: $("#contrasenya").val() })
        .done(function(data) {
            if (data == "success") {
                console.log("success" + data);
            } else {
                console.log + ("error" + data);
            }

        })
        .fail(function() {
            alert("error fail");
        });


});
console.log("logintest.js");

//Escribir nombre y comprobar
//Escibir password y comprobar
// comprobar ambos  ok