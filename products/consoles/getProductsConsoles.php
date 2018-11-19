<?php

include('../../connect.php');

if (isset($_GET['callback'])) {
    $callback = $_GET['callback'];
} else {
    $callback = false;
}

//var_dump($_GET);

$dni = "";


//TODO

    $sqlID = "SELECT p.id FROM producto p INNER JOIN tipoProducto tp ON p.idTipo = tp.id WHERE tp.nombre='consola'";
    $resultado1 = $conexion->prepare($sqlID);
    $resultado1->execute();
    $resultado1->bind_result($id);

    while ($resultado1->fetch()) {
        $prodID[] = $id;
    }
    if (count($prodID) > 0) {
        foreach ($prodID as $item) {
            $sql = "SELECT DISTINCT p.id, p.nombre,i.url,hp.descuento,pr.precio
  FROM  producto p 
  INNER JOIN historicoPrecio hp ON p.id = hp.idProducto
  INNER JOIN precio pr ON hp.idPrecioProducto = pr.id
  INNER JOIN imagen i ON p.id = i.id
  WHERE p.id=?";
            $resP = $conexion2->prepare($sql);
            $resP->bind_param('i', $item);
            $resP->execute();
            $resP->bind_result($id, $nombre, $url, $descuento, $precio);
            $pPop = array();
            while ($resP->fetch()) {
                $p = array('id' => $id, 'nombre' => $nombre, 'url' => $url, 'descuento' => $descuento, 'precio' => $precio);
                $prod[] = $p;
            }

        }
        $datos = json_encode($prod);
        if ($callback) {
            echo sprintf('%s(%s)', $callback, $datos);
        } else {
            echo $datos;
        }
    }