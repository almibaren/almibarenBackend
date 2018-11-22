<?php
//tickets que ha mandado, sus transacciones , su cuenta entera otra vez
if (isset($_GET['callback'])) {
    $callback = $_GET['callback'];
} else {
    $callback = false;
}

include('../../connect.php');

$userId = $_GET['userId'];

if ($userId == "") {
    die();
} else {
    $sqlPago = "SELECT mp.userPayPal,mp.tarjeta FROM metodoPago mp INNER JOIN clienteMetodoPago cmp ON mp.id =cmp.idMetodoPago INNER JOIN cliente c ON cmp.dniCliente = c.dni WHERE c.dni=?";
    $resPago = $conexion2->prepare($sqlPago);
    $resPago->bind_param('s', $userId);
    $resPago->execute();
    $resPago->bind_result($paypal, $tarjeta);

    while ($resPago->fetch()) {
        $metodosPago[] = array('paypal' => $paypal, 'tarjeta' => $tarjeta);
    }


    $sqlTCID = "SELECT DISTINCT t.id FROM tipoTransaccion tt INNER JOIN transaccion t on tt.id=t.idTipo INNER JOIN productoTransaccion pt ON t.id = pt.idTransaccion INNER JOIN producto p ON pt.idProducto = p.id 
WHERE t.idTipo=1 AND t.dniCliente=?";
    $resTCID = $conexion->prepare($sqlTCID);
    $resTCID->bind_param('i', $userId);
    $resTCID->execute();
    $resTCID->bind_result($TCID);
    $TCIDs = null;
    while ($resTCID->fetch()) {
        $TCIDs[] = $TCID;
    }
    if (count($TCIDs) > 0) {
        foreach ($TCIDs as $tciden) {
            $sqlTC = "SELECT t.id,t.precio,t.fechaTransaccion,p.nombre,i.url from transaccion t INNER JOIN productoTransaccion pt ON t.id = pt.idTransaccion INNER JOIN producto p ON pt.idProducto = p.id INNER JOIN imagen i ON p.id = i.id WHERE t.id=?";
            $resTC = $conexion->prepare($sqlTC);
            $resTC->bind_param('i', $tciden);
            $resTC->execute();
            $resTC->bind_result($idTRC, $precioTRC, $fechaTRC, $nombreProdTRC, $imagenProdTRC);
            $productosTRC = null;
            while ($resTC->fetch()) {
                $productosTRC[] = array('nombre' => $nombreProdTRC, 'url' => $imagenProdTRC);
            }
            $compras[] = array('idTransaccion' => $idTRC, 'precio' => $precioTRC, 'fecha' => $fechaTRC, 'productos' => $productosTRC);
        }
    }

    $sqlTAID = "SELECT DISTINCT t.id FROM tipoTransaccion tt INNER JOIN transaccion t on tt.id=t.idTipo INNER JOIN productoTransaccion pt ON t.id = pt.idTransaccion INNER JOIN producto p ON pt.idProducto = p.id 
WHERE t.idTipo=2 AND t.dniCliente=?";
    $resTAID = $conexion3->prepare($sqlTAID);
    $resTAID->bind_param('s', $userId);
    $resTAID->execute();
    $resTAID->bind_result($TAID);
    $TAIDs = null;
    while ($resTAID->fetch()) {
        $TAIDs[] = $TAID;
    }

    if (count($TAIDs) > 0) {
        foreach ($TAIDs as $taiden) {
            $sqlTC = "SELECT t.id,t.precio,t.fechaTransaccion,p.nombre,i.url from transaccion t INNER JOIN productoTransaccion pt ON t.id = pt.idTransaccion INNER JOIN producto p ON pt.idProducto = p.id INNER JOIN imagen i ON p.id = i.id WHERE t.id=?";
            $resTA = $conexion->prepare($sqlTC);
            $resTA->bind_param('i', $taiden);
            $resTA->execute();
            $resTA->bind_result($idTRA, $precioTRA, $fechaTRA, $nombreProdTRA, $imagenProdTRA);
            $productosTRA = null;
            while ($resTA->fetch()) {
                $productosTRA[] = array('nombre' => $nombreProdTRA, 'url' => $imagenProdTRA);
            }
            $alquileres[] = array('idTransaccion' => $idTRA, 'precio' => $precioTRA, 'fecha' => $fechaTRA, 'productosTRA' => $productosTRA);
        }

    } else {
        $alquileres = null;
    }

    $TRIDs = array();
    $sqlTRID = "SELECT DISTINCT t.id FROM tipoTransaccion tt INNER JOIN transaccion t on tt.id=t.idTipo INNER JOIN productoTransaccion pt ON t.id = pt.idTransaccion INNER JOIN producto p ON pt.idProducto = p.id 
WHERE t.idTipo=3 AND t.dniCliente=?";
    $resTRID = $conexion4->prepare($sqlTRID);
    $resTRID->bind_param('s', $userId);
    $resTRID->execute();
    $resTRID->bind_result($TRID);
    $TRIDs = null;
    while ($resTRID->fetch()) {
        $TRIDs[] = $TRID;
    }

    if (count($TRIDs) > 0) {

        foreach ($TRIDs as $triden) {

            $sqlTR = "SELECT t.id,t.precio,t.fechaTransaccion,p.nombre,i.url 
from transaccion t INNER JOIN productoTransaccion pt ON t.id = pt.idTransaccion INNER JOIN producto p ON pt.idProducto = p.id INNER JOIN imagen i ON p.id = i.id WHERE t.id=?";
            $resTR = $conexion->prepare($sqlTR);
            $resTR->bind_param('i', $triden);
            $resTR->execute();
            $resTR->bind_result($idTRR, $precioTRR, $fechaTRR, $nombreProdTRR, $imagenProdTRR);
            $productosTRR = null;
            while ($resTR->fetch()) {
                $productosTRR[] = array('nombre' => $nombreProdTRR, 'url' => $imagenProdTRR);
            }
            $reparaciones[] = array('idTransaccion' => $idTRR, 'precio' => $precioTRR, 'fecha' => $fechaTRR, 'productos' => $productosTRR);
        }

    } else {
        $reparaciones = null;
    }

    $transacciones = array('compras' => $compras, 'alquileres' => $alquileres, 'reparaciones' => $reparaciones);

    $sqlRating = "SELECT p.nombre,i.url,o.valoracion,o.comentario FROM opinion o INNER JOIN cliente c ON o.user = c.usuario INNER JOIN producto p ON o.idProducto = p.id INNER JOIN imagen i ON p.id = i.id WHERE c.dni=?";
    $resRating = $conexion5->prepare($sqlRating);
    $resRating->bind_param('s', $userId);
    $resRating->execute();
    $resRating->bind_result($rateProd, $rateUrl, $rateVal, $rateComment);
    while ($resRating->fetch()) {
        $valoraciones[] = array('nombreProducto' => $rateProd, 'url' => $rateUrl, 'valoracion' => $rateVal, 'comentario' => $rateComment);
    }


    $datos = array('metodosPago' => $metodosPago, 'transacciones' => $transacciones, 'valoraciones' => $valoraciones);
    $datos = json_encode($datos);
    if ($callback) {
        echo sprintf('%s(%s)', $callback, $datos);
    } else {
        echo $datos;
    }
}