<?php


$post = file_get_contents('php://input');

$request = json_decode($post);

include('../connect.php');

$userId = $request->userId;
$products = $request->products;
$tipo = $request->tipo;
$precio = $request->precio;
if ($tipo == "reparacion") {
    $averia = $request->averia;
}

if ($userId != "" && (count($products) > 0) && $tipo != "" && $precio != "") {

    if ($tipo == "compra") {
        $idTipo = 1;
    } else if ($tipo == "alquiler") {
        $idTipo = 2;
    } else {
        $idTipo = 3;
    }

    if ($tipo == "compra") {
        $sqlInsTrans = "INSERT INTO transaccion (dniCliente,idTipo,precio,fechaTransaccion) VALUES (?,?,?,CURRENT_DATE )";
        $resInsTrans = $conexion->prepare($sqlInsTrans);
        $resInsTrans->bind_param('sid', $userId, $idTipo, $precio);
        $resInsTrans->execute();

        if ($resInsTrans->affected_rows > 0) {
            $sqlIDTrans = "SELECT t.id FROM transaccion t WHERE t.dniCliente=? AND idTipo=? AND precio=? and fechaTransaccion=CURRENT_DATE LIMIT 0,1";
            $resIDTrans = $conexion->prepare($sqlIDTrans);
            $resIDTrans->bind_param('sid', $userId, $idTipo, $precio);
            $resIDTrans->execute();
            $resIDTrans->bind_result($id);
            while ($resIDTrans->fetch()) {
                $idTransaccion = $id;
            }
            if ($idTransaccion != null) {
                foreach ($products as $pID) {
                    $sqlInsPT = "INSERT INTO productoTransaccion (idTransaccion,idProducto) VALUES (?,?)";
                    $resInsPT = $conexion->prepare($sqlInsPT);
                    $resInsPT->bind_param('ii', $idTransaccion, $pID);
                    $resInsPT->execute();
                }
            }
        }

    } else if ($tipo == "alquiler") {


        $dateDevolucion = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 5, date('Y')));

        $sqlInsTrans = "INSERT INTO transaccion (dniCliente,idTipo,precio,fechaTransaccion,fechaDevolucion) VALUES (?,?,?,CURRENT_DATE,? )";
        $resInsTrans = $conexion->prepare($sqlInsTrans);
        $resInsTrans->bind_param('sids', $userId, $idTipo, $precio, $dateDevolucion);
        $resInsTrans->execute();

        if ($resInsTrans->affected_rows > 0) {
            $sqlIDTrans = "SELECT t.id FROM transaccion t WHERE t.dniCliente=? AND idTipo=? AND precio=? and fechaTransaccion=CURRENT_DATE LIMIT 0,1";
            $resIDTrans = $conexion->prepare($sqlIDTrans);
            $resIDTrans->bind_param('sid', $userId, $idTipo, $precio);
            $resIDTrans->execute();
            $resIDTrans->bind_result($id);
            while ($resIDTrans->fetch()) {
                $idTransaccion = $id;
            }
            if ($idTransaccion != null) {
                foreach ($products as $pID) {
                    $sqlInsPT = "INSERT INTO productoTransaccion (idTransaccion,idProducto) VALUES (?,?)";
                    $resInsPT = $conexion->prepare($sqlInsPT);
                    $resInsPT->bind_param('ii', $idTransaccion, $pID);
                    $resInsPT->execute();
                }
            }
        }

    } else {


        $dateEntrada = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')));
        $dateEstimada = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 16, date('Y')));


        $sqlInsTrans = "INSERT INTO transaccion (dniCliente,idTipo,precio,fechaTransaccion,fechaEntrada,fechaEstimada) VALUES (?,?,?,CURRENT_DATE,?,? )";
        $resInsTrans = $conexion->prepare($sqlInsTrans);
        $resInsTrans->bind_param('sidss', $userId, $idTipo, $precio, $dateEntrada, $dateEstimada);
        $resInsTrans->execute();

        if ($resInsTrans->affected_rows > 0) {
            $sqlIDTrans = "SELECT t.id FROM transaccion t WHERE t.dniCliente=? AND idTipo=? AND precio=? and fechaTransaccion=CURRENT_DATE LIMIT 0,1";
            $resIDTrans = $conexion->prepare($sqlIDTrans);
            $resIDTrans->bind_param('sid', $userId, $idTipo, $precio);
            $resIDTrans->execute();
            $resIDTrans->bind_result($id);
            while ($resIDTrans->fetch()) {
                $idTransaccion = $id;
            }
            if ($idTransaccion != null) {
                foreach ($products as $pID) {
                    $sqlInsPT = "INSERT INTO productoTransaccion (idTransaccion,idProducto,descripcionAveria) VALUES (?,?,?)";
                    $resInsPT = $conexion->prepare($sqlInsPT);
                    $resInsPT->bind_param('iis', $idTransaccion, $pID, $averia);
                    $resInsPT->execute();
                }
            }
        }

    }
}