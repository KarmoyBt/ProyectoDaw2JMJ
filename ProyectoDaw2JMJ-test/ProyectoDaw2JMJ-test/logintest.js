$("#user").keyup(function() {
    console.log($("#user").val());
});
$("#contrasenya").keyup(function() {
    console.log($("#contrasenya").val());
});


$("#enviar").click(function() {
    var username = $("#user").val();
    var password = $("#contrasenya").val();

    console.log(username + password);

    $.ajax({
        type: "POST",
        url: "login.php",
        data: { username: username, password: password }, //capturo array     
        success: function(data) {
            if (data == "success") {
                console.log("success" + data);
            } else {
                console.log + ("error" + data);
            }

        }

    })

});

console.log("logintest.js");

//Escribir nombre y comprobar
//Escibir password y comprobar
// comprobar ambos  ok