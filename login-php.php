<?php
class Users {
public $username = null;
public $password = null;

public function __construct( $data = array() ) {
    if( isset( $data['username'] ) ) $this->username = stripslashes(        strip_tags( $data['username'] ) );
    if( isset( $data['password'] ) ) $this->password = stripslashes( strip_tags( $data['password'] ) );
}

public function storeFormValues( $params ) {
   $this->__construct( $params ); 
}

public function Login() {
    $success = false;
    try{
       $con = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD ); 
       $con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
       $sql = "SELECT * FROM users WHERE username = :username AND password = :password LIMIT 1";
                   $user = username;

       $stmt = $con->prepare( $sql );
       $stmt->bindValue( "username", $this->username, PDO::PARAM_STR );
       $stmt->bindValue( "password", md5($this->password), PDO::PARAM_STR );
       $stmt->execute();

       $valid = $stmt->fetchColumn();

       if( $valid ) {
       $success = true;
                   session_start();


       session_regenerate_id();
       $_SESSION['user'] = $user['user'];
       session_write_close();
       echo ('Login');
       exit();

       }

       $con = null;
       return $success;
       }catch (PDOException $e) {
       echo $e->getMessage();
       return $success;
    }

}


?>