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
        $sqlPop = "SELECT DISTINCT p.id, p.nombre,i.url,i.url2,i.url3,i.url4,hp.descuento,pr.precio
  FROM  producto p 
  INNER JOIN historicoPrecio hp ON p.id = hp.idProducto
  INNER JOIN precio pr ON hp.idPrecioProducto = pr.id
  INNER JOIN imagen i ON p.id = i.id
  WHERE p.id=?";
        $resPop = $conexion2->prepare($sqlPop);
        $resPop->bind_param('i', $item);
        $resPop->execute();
        $resPop->bind_result($id, $nombre, $url, $url2, $url3, $url4, $descuento, $precio);
        $pPop = array();
        while ($resPop->fetch()) {
            $pPop = array('id' => $id, 'nombre' => $nombre, 'url' => $url, 'url2' => $url2, 'url3' => $url3, 'url4' => $url4, 'descuento' => $descuento, 'precio' => $precio);
            $pPops[] = $pPop;
        }

    }
    $productosP = $pPops;
    //if there is no user the personal reccomendations are the popular items
    if ($userId == "") {
        $pRecs = $pPops;
    }
}

//Recommended for you
if ($userId != "") {
    $sqlRecG = "SELECT DISTINCT g.nombre, t.fechaTransaccion
FROM genero g
INNER JOIN generoProducto gp ON g.id = gp.idGenero
INNER JOIN producto p ON gp.idProducto = p.id
INNER JOIN productoTransaccion pt ON p.id = pt.idProducto
INNER JOIN transaccion t ON pt.idTransaccion = t.id
INNER JOIN tipoTransaccion tt ON t.idTipo = tt.id
WHERE tt.nombre='Compra' AND t.dniCliente=?
ORDER BY t.fechaTransaccion DESC
LIMIT 0,3";
    $resRecG = $conexion3->prepare($sqlRecG);
    $resRecG->bind_param('s', $userId);
    $resRecG->execute();
    $resRecG->bind_result($genero, $fec);
    while ($resRecG->fetch()) {
        $generoRec[] = $genero;
    }
    if (count($generoRec) > 0) {
        $sqlRecId = "
SELECT p.id 
FROM producto p 
INNER JOIN generoProducto gp ON p.id = gp.idProducto 
INNER JOIN genero g ON gp.idGenero = g.id 
WHERE p.id NOT IN (
    SELECT p.id FROM producto p 
	INNER JOIN productoTransaccion pt ON p.id = pt.idProducto 
	INNER JOIN transaccion t ON pt.idTransaccion = t.id
	WHERE t.dniCliente=? )
AND g.nombre=? OR g.nombre=? OR g.nombre=?";
        $resRecId = $conexion->prepare($sqlRecId);
        $resRecId->bind_param('ssss', $userId, $generoRec[0], $generoRec[1], $generoRec[2]);
        $resRecId->execute();
        $resRecId->bind_result($id);
        while ($resRecId->fetch()) {
            $pRecId[] = $id;
        }

        foreach ($pRecId as $item) {
            $sqlRec = "SELECT DISTINCT p.id, p.nombre,i.url,i.url2,i.url3,i.url4,hp.descuento,pr.precio
  FROM  producto p 
  INNER JOIN historicoPrecio hp ON p.id = hp.idProducto
  INNER JOIN precio pr ON hp.idPrecioProducto = pr.id
  INNER JOIN imagen i ON p.id = i.id
  WHERE p.id=?";
            $resRec = $conexion2->prepare($sqlRec);
            $resRec->bind_param('i', $item);
            $resRec->execute();
            $resRec->bind_result($id, $nombre, $url, $url2, $url3, $url4, $descuento, $precio);
            while ($resRec->fetch()) {
                $pRec = array('id' => $id, 'nombre' => $nombre, 'url' => $url, 'url2' => $url2, 'url3' => $url3, 'url4' => $url4, 'descuento' => $descuento, 'precio' => $precio);
            }
            $pRecs[] = $pRec;
        }
        $productos[] = $pRecs;

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
        $sqlRate = "SELECT DISTINCT p.id, p.nombre,i.url,i.url2,i.url3,i.url4,hp.descuento,pr.precio
  FROM  producto p 
  INNER JOIN historicoPrecio hp ON p.id = hp.idProducto
  INNER JOIN precio pr ON hp.idPrecioProducto = pr.id
  INNER JOIN imagen i ON p.id = i.id
  WHERE p.id=?";
        $resRate = $conexion2->prepare($sqlRate);
        $resRate->bind_param('i', $item);
        $resRate->execute();
        $resRate->bind_result($id, $nombre, $url, $url2, $url3, $url4, $descuento, $precio);
        while ($resRate->fetch()) {
            $pRate = array('id' => $id, 'nombre' => $nombre, 'url' => $url, 'url2' => $url2, 'url3' => $url3, 'url4' => $url4, 'descuento' => $descuento, 'precio' => $precio);
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
        $sqlDeal = "SELECT DISTINCT p.id, p.nombre,i.url,i.url2,i.url3,i.url4,hp.descuento,pr.precio
  FROM  producto p 
  INNER JOIN historicoPrecio hp ON p.id = hp.idProducto
  INNER JOIN precio pr ON hp.idPrecioProducto = pr.id
  INNER JOIN imagen i ON p.id = i.id
  WHERE p.id=?";
        $resDeal = $conexion2->prepare($sqlDeal);
        $resDeal->bind_param('i', $item);
        $resDeal->execute();
        $resDeal->bind_result($id, $nombre, $url, $url2, $url3, $url4, $descuento, $precio);
        while ($resDeal->fetch()) {
            $pDeal = array('id' => $id, 'nombre' => $nombre, 'url' => $url, 'url2' => $url2, 'url3' => $url3, 'url4' => $url4, 'descuento' => $descuento, 'precio' => $precio);
        }
        $pDeals[] = $pDeal;
    }
    $productos[] = $pDeals;
}


$datos = array("populares" => $pPops, "recomendados" => $pRecs, "valorados" => $pRates, "ofertas" => $pDeals);

$datos = json_encode($datos);
if ($callback) {
    echo sprintf('%s(%s)', $callback, $datos);
} else {
    echo $datos;
}