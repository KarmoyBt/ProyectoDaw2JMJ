<?php


$id=$_POST["id"];
$que=$_POST["que"];

public function titulo($id,$que)
{


$conexion = mysql_connect('localhost', 'usuario', 'contraseña') or die('Could not connect: ' . mysql_error());
mysql_select_db('bd', $conexion);
$sql = mysql_query("SELECT * from usuarios");
while($columna = mysql_fetch_array($sql)){
	echo $columna["Nombre"];
}
	return $columna["Nombre"]
}


public function hash($id)
{

try{
	$conn = new PDO('mysql:host=localhost;dbname=basededatos', $usuario, $contra);

	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = $conn->prepare('SELECT titulo FROM usuarios WHERE nombre = :Nombre');
	$sql->execute(array('Nombre' => $id));
	$resultado = $sql->fetchAll();

	foreach ($resultado as $row) {
		echo $row["Id"];
	}
}catch(PDOException $e){
	echo "ERROR: " . $e->getMessage();
}


return $row["Id"];

}

?>