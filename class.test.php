<?php

 class titulo {

public $id= $_POST["id"];
public $que= $_POST["que"];

public function titulo($id,$que){


$conexion = mysql_connect('localhost', 'javimart_m6', 'nombreapellido.') or die('Could not connect: ' . mysql_error());

mysql_select_db('bd', $conexion);
$sql = mysql_query("SELECT $que from Prueba WHERE id==$id");
while($columna = mysql_fetch_array($sql)){
	echo $columna["Nombre"];
}
	return $columna["Nombre"];
}
    
}
?>
