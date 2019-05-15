<?php 

if(isset($_POST['inputUser']) && isset($_POST['inputContraseña'])){

    $user = $_POST['inputUser'];   
    $password = $_POST['inputContraseña'];

    //echo $user.''.$password;

    //meterle db
    $connection = mysqli_connect("localhost", "projecto_admin", "nombreapellido.", "projecto_discografica");

    $data = mysqli_query($connection, "SELECT * FROM usuari WHERE email = '{$user}' AND  contrasenya = '{$password}'");

    $row_cnt = mysqli_num_rows($data);

    if($row_cnt == 1){
        $row = mysqli_fetch_array($data);
        $id = $row['ID'];
        session_start();
        $_SESSION['user_id'] = $id;
        echo "success";

    }else{
        echo "No se ha encontrado datos";
    }





}
if(isset($_POST['user'])){
    echo "Falta Usuario";
}
if(isset($_POST['password'])){
    echo "Falta contraseña";
}



?>
