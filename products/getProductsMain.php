<?php
include('../connect.php');
if (isset($_GET['callback'])) {
    $callback = $_GET['callback'];
} else {
    $callback = false;
}
$userId = "";

$productos = array();
$pPops = array();
if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];
}

//Popular items
$sqlPopId = " SELECT p.id, COUNT(p.id) as num
  FROM producto p
  inner join productoTransaccion pt on p.id=pt.idProducto
  inner join transaccion t on pt.idTransaccion=t.id
  inner join tipoTransaccion tt on t.idTipo=tt.id
  inner join desarrollador d ON p.idDesarrollador = d.id
  WHERE tt.nombre='Compra'
  GROUP BY p.id
  ORDER BY num DESC
  LIMIT 0,10";
$resPopId = $conexion->prepare($sqlPopId);
$resPopId->execute();
$resPopId->bind_result($id, $num);
while ($resPopId->fetch()) {
    $pPopId[] = $id;
}
if (count($pPopId) > 0) {
    foreach ($pPopId as $item) {
        $sqlPop = "SELECT p.nombre,i.url,hp.descuento,pr.precio
  FROM  producto p 
  INNER JOIN historicoPrecio hp ON p.id = hp.idProducto
  INNER JOIN precio pr ON hp.idPrecioProducto = pr.id
  INNER JOIN productoImagen pi ON p.id = pi.idProducto
  INNER JOIN imagen i ON pi.idImagen = i.id
  WHERE p.id=?";
        $resPop = $conexion2->prepare($sqlPop);
        $resPop->bind_param('i', $item);
        $resPop->execute();
        $resPop->bind_result($nombre, $url, $descuento, $precio);
        $pPop = array();
        while ($resPop->fetch()) {
            $pPop = array('nombre' => $nombre, 'url' => $url, 'descuento' => $descuento, 'precio' => $precio);
            $pPops[] = $pPop;
        }

    }
    $productos[] = $pPops;
    //if there is no user the personal reccomendations are the popular items
    if ($userId == "") {
        $productos[] = $pPops;
    }
}

//Recommended for you
if ($userId != "") {
    $sqlRecG = "SELECT DISTINCT g.nombre
FROM genero g
INNER JOIN generoProducto gp ON g.id = gp.idGenero
INNER JOIN producto p ON gp.idProducto = p.id
INNER JOIN productoTransaccion pt ON p.id = pt.idProducto
INNER JOIN transaccion t ON pt.idTransaccion = t.id
INNER JOIN tipoTransaccion tt ON t.idTipo = tt.id
WHERE tt.nombre='Compra' AND t.dniCliente=?
ORDER BY t.fechaTransaccion DESC
LIMIT 0,3";
    $resRecG = $conexion->prepare($sqlRecG);
    $resRecG->bind_param(s, $userId);
    $resRecG->execute();
    $resRecG->bind_result($genero);
    while ($resRecG->fetch()) {
        $generoRec[] = $genero;
    }
    if (count($generoRec) > 0) {
        $sqlRecId = "SELECT p.id
FROM genero g
INNER JOIN generoProducto gp ON g.id = gp.idGenero
INNER JOIN producto p ON gp.idProducto = p.id
INNER JOIN productoTransaccion pt ON p.id = pt.idProducto
INNER JOIN transaccion t ON pt.idTransaccion = t.id
INNER JOIN tipoTransaccion tt ON t.idTipo = tt.id
WHERE g.nombre=? OR g.nombre=? OR g.nombre=?";
        $resRecId = $conexion->prepare($sqlRecId);
        $resRecId->bind_param('sss', $generoRec[0], $generoRec[1], $generoRec[2]);
        $resRecId->execute();
        $resRecId->bind_result($id);
        while ($resRecId->fetch()) {
            $pRecId[] = $id;
        }



    }
}


//By rating
$sqlRateId = "Select p.id,AVG(o.valoracion) as media
from producto p inner join opinion o ON p.id = o.idProducto
group by p.id
order by media DESC
Limit 0,10";
$resRateId = $conexion->prepare($sqlRateId);
$resRateId->execute();
$resRateId->bind_result($id, $num);
while ($resRateId->fetch()) {
    $pRateId[] = $id;
}
if (count($pRateId) > 0) {
    foreach ($pRateId as $item) {
        $sqlRate = "SELECT p.nombre,i.url,hp.descuento,pr.precio
  FROM  producto p 
  INNER JOIN historicoPrecio hp ON p.id = hp.idProducto
  INNER JOIN precio pr ON hp.idPrecioProducto = pr.id
  INNER JOIN productoImagen pi ON p.id = pi.idProducto
  INNER JOIN imagen i ON pi.idImagen = i.id
  WHERE p.id=?";
        $resRate = $conexion2->prepare($sqlRate);
        $resRate->bind_param('i', $item);
        $resRate->execute();
        $resRate->bind_result($nombre, $url, $descuento, $precio);
        while ($resRate->fetch()) {
            $pRate = array('nombre' => $nombre, 'url' => $url, 'descuento' => $descuento, 'precio' => $precio);
        }
        $pRates[] = $pRate;
    }
    $productos[] = $pRates;
}


//By Deals
$sqlDealId = "Select p.id 
from producto p 
inner join historicoPrecio hp ON p.id = hp.idProducto 
inner join precio pr ON hp.idPrecioProducto = pr.id
Where hp.descuento!=0
Order by hp.descuento DESC
Limit 0,10";
$resDealId = $conexion->prepare($sqlDealId);
$resDealId->execute();
$resDealId->bind_result($id);
while ($resDealId->fetch()) {
    $pDealId[] = $id;
}
if (count($pDealId) > 0) {
    foreach ($pDealId as $item) {
        $sqlDeal = "SELECT p.nombre,i.url,hp.descuento,pr.precio
  FROM  producto p 
  INNER JOIN historicoPrecio hp ON p.id = hp.idProducto
  INNER JOIN precio pr ON hp.idPrecioProducto = pr.id
  INNER JOIN productoImagen pi ON p.id = pi.idProducto
  INNER JOIN imagen i ON pi.idImagen = i.id
  WHERE p.id=?";
        $resDeal = $conexion2->prepare($sqlDeal);
        $resDeal->bind_param('i', $item);
        $resDeal->execute();
        $resDeal->bind_result($nombre, $url, $descuento, $precio);
        while ($resDeal->fetch()) {
            $pDeal = array('nombre' => $nombre, 'url' => $url, 'descuento' => $descuento, 'precio' => $precio);
        }
        $pDeals[] = $pDeal;
    }
    $productos[] = $pDeals;
}


$datos = json_encode($productos);
if ($callback) {
    echo sprintf('%s(%s)', $callback, $datos);
} else {
    echo $datos;
}


/* PARA SELECIONAR EL GENERO PARA LAS RECOMENACIONES
 SELECT DISTINCT g.nombre
FROM genero g
INNER JOIN generoProducto gp ON g.id = gp.idGenero
INNER JOIN producto p ON gp.idProducto = p.id
INNER JOIN productoTransaccion pt ON p.id = pt.idProducto
INNER JOIN transaccion t ON pt.idTransaccion = t.id
INNER JOIN tipoTransaccion tt ON t.idTipo = tt.id
WHERE tt.nombre="Compra" AND t.dniCliente="123456987D"
ORDER BY t.fechaTransaccion DESC
LIMIT 0,3;*/

/*PARA SELECIONAR LOS IDS DEPENDIENDO DE LOS GEENROS DE LA CONSULTA ANTERIOR
 * SELECT p.id
FROM genero g
INNER JOIN generoProducto gp ON g.id = gp.idGenero
INNER JOIN producto p ON gp.idProducto = p.id
INNER JOIN productoTransaccion pt ON p.id = pt.idProducto
INNER JOIN transaccion t ON pt.idTransaccion = t.id
INNER JOIN tipoTransaccion tt ON t.idTipo = tt.id
WHERE g.nombre="TCG" OR g.nombre="" OR g.nombre=""*/

/*PARA SELECIIONAR LOS PRODUCTOS A MOSTRAR
 * SELECT p.id,p.nombre,i.url
FROM genero g
INNER JOIN generoProducto gp ON g.id = gp.idGenero
INNER JOIN producto p ON gp.idProducto = p.id
INNER JOIN desarrollador d ON p.idDesarrollador= d.id
INNER JOIN productoImagen pi ON p.id = pi.idProducto
INNER JOIN imagen i ON pi.idImagen = i.id
WHERE g.nombre="TCG" OR g.nombre="" OR g.nombre=""*/