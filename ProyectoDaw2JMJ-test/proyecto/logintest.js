$(".login").click(function() {

    var username = $("#inputUser").val();
    var password = $("#inputContraseña").val();

    console.log(username + password);


    $.post("login.php", { username: username, password: password })
        .done(function(data) {
            if (data == "success") {
                console.log("success" + data);
            } else {
                console.log + ("error" + data);
            }

        });


});
console.log("logintest.js");

//Escribir nombre y comprobar
//Escibir password y comprobar
// comprobar ambos  ok