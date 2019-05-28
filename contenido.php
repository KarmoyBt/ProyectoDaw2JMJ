<?php
require ("php.php");

$servidor = "localhost";
$usuari = "javimart_json";
$contrasenya = "nombreapellido.";
$bd = "javimart_proyecto";

$connexio = connectar_BD ($servidor, $usuari, $contrasenya, $bd);




$carga = $_GET["carga"];

$jsondata = array();


      $dades = consulta_SQL ($connexio, "select * from Prueba ");
    while ($todos = consulta_fila($dades)){
        
        $prueba_id = consulta_dada($todos, 'prueba_id');
        $titulo = consulta_dada($todos, 'titulo');
        $descripcion = consulta_dada($todos, 'descripcion');
        $imagen = consulta_dada($todos, 'imagen');
        $link = consulta_dada($todos, 'link');

        
        $hash = consulta_dada($todos, 'hash');
      
        array_push($jsondata, $prueba_id);
        array_push($jsondata, $titulo);
        array_push($jsondata, $descripcion);
        array_push($jsondata, $imagen);
        array_push($jsondata, $link);



        array_push($jsondata, $hash);
    }


$json_string = json_encode($jsondata,JSON_FORCE_OBJECT);
echo $json_string ;


tancar_consulta ($dades);
desconnectar_BD ($connexio);
?>