<?php 
if(isset($_POST['user'])){
    echo "Falta Usuario";
}

if(isset($_POST['contrasenya'])){
    echo "Falta contraseña";
}

if(isset($_POST['user']) && isset($_POST['contraseña'])){

    $user = $_POST['user'];   
    $contra = $_POST['contrasenya'];

    //meterle db
    $connection = mysqli_connect("localhost", "javimart_json", "nombreapellido.", "javimart_m6");

    $data = mysqli_query($connection, "SELECT 'uid' FROM users WHERE  username = '{$user}' AND  contrasenya = '{$contra}'");

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



?>
