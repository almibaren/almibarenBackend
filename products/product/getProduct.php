<?php

include('../../connect.php');

if (isset($_GET['callback'])) {
    $callback = $_GET['callback'];
} else {
    $callback = false;
}

if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];
} else {
    die();
}


$sqlRatings = "SELECT opinion.user,opinion.valoracion,opinion.comentario from opinion where opinion.idProducto=?";
$resRatings = $conexion->prepare($sqlRatings);
$resRatings->bind_param('i', $productId);
$resRatings->execute();
$resRatings->bind_result($userOpinion, $ValoracionOpinion, $commentOpinion);
while ($resRatings->fetch()){
    $opiniones[] = array('user'=>$userOpinion,'valoracion'=>$ValoracionOpinion,'comentario'=>$commentOpinion);
}


$sql = "SELECT p.nombre,p.descripcion,p.fechaLanzamiento,d.nombre,i.url,i.url2,i.url3,i.url4,pr.precio,hp.descuento
FROM precio pr 
INNER JOIN historicoPrecio hp ON pr.id = hp.idPrecioProducto 
INNER JOIN producto p ON hp.idProducto = p.id 
INNER JOIN desarrollador d ON d.id = p.idDesarrollador 
INNER JOIN imagen i ON i.id = p.id 
WHERE p.id=?";
$res = $conexion->prepare($sql);
$res->bind_param('i', $productId);
$res->execute();
$res->bind_result($nombre, $descripcion, $fechaLanzamiento, $desarrollador, $url, $url2, $url3, $url4, $precio,$descuento);
while ($res->fetch()) {
$datos = array('nombre'=>$nombre,'descripcion'=>$descripcion,'fechaLanzamiento'=>$fechaLanzamiento,'desarrollador'=>$desarrollador,'url'=>$url,'url2'=>$url2,'url3'=>$url3,'url4'=>$url4,'precio'=>$precio,'descuento'=>$descuento,'opiniones'=>$opiniones);
}

$datos = json_encode($datos);
if ($callback) {
    echo sprintf('%s(%s)', $callback, $datos);
} else {
    echo $datos;
}
