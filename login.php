<?php 

if(isset($_POST['user']) && isset($_POST['password'])){

    $user = $_POST['user'];   
    $password = $_POST['password'];

    //echo $user.''.$password;

    //meterle db
    $connection = mysqli_connect("localhost", "projecto_admin", "P@ssw0rd", "projecto_discografica");

    $data = mysqli_query($connection, "SELECT * FROM usuari WHERE email = '{$user}' AND  contrasenya = '{$password}'");

    $row_cnt = mysqli_num_rows($data);

    if($row_cnt == 1){
        $row = mysqli_fetch_array($data);
        $id = $row['ID'];
        session_start();
        $_SESSION['user_id'] = $id;
        echo "success";

    }else{
        echo "failed";
    }





}


?>
