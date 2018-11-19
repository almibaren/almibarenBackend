<?php

$post = file_get_contents('php://input');

$request = json_decode($post);

include('../../connect.php');

$keyWords = $request->keywords;

$palabras = explode(":", $keyWords);

foreach ($palabras as $key){
    if(strlen($key)>1){

    }else{
        die();
    }
}

$whereSQL = "WHERE p.nombre='" . $palabras[0] . "'";
if (count($palabras) > 1) {
    for ($i = 1; $i < count($palabras) ; $i++) {
        $whereSQL = $whereSQL . " OR p.nombre='" . $palabras[$i] . "'";
    }
}
foreach ($palabras as $p) {
    if (strlen($p) > 2) {
        $whereSQL = $whereSQL . " OR p.nombre LIKE '%" . $p . "%' OR g.nombre ='" . $p . "'";
    }
}
$sqlID = "Select DISTINCT p.id from producto p INNER JOIN generoProducto gp ON p.id = gp.idProducto INNER JOIN genero g ON gp.idGenero = g.id " . $whereSQL;
//var_dump($sqlID);
$resID = $conexion->prepare($sqlID);
$resID->execute();
$resID->bind_result($id);
while ($resID->fetch()) {
    $ids[] = $id;
}

if (count($ids) > 0) {
    foreach ($ids as $item) {
        $sql = "SELECT DISTINCT p.id, p.nombre,i.url,hp.descuento,pr.precio
  FROM  producto p 
  INNER JOIN historicoPrecio hp ON p.id = hp.idProducto
  INNER JOIN precio pr ON hp.idPrecioProducto = pr.id
  INNER JOIN imagen i ON p.id = i.id
  WHERE p.id=?";
        $res = $conexion2->prepare($sql);
        $res->bind_param('i', $item);
        $res->execute();
        $res->bind_result($id, $nombre, $url, $descuento, $precio);
        $pPop = array();
        while ($res->fetch()) {
            $pr = array('id' => $id, 'nombre' => $nombre, 'url' => $url, 'descuento' => $descuento, 'precio' => $precio);
            $productos[] = $pr;
        }
    }

    $datos = json_encode($productos);
    echo $datos;
}
