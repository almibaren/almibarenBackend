<?php
include('../../connect.php');
if (isset($_GET['callback'])) {
    $callback = $_GET['callback'];
} else {
    $callback = false;
}

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];
} else {
    die();
}


$sql = "Select t.id from transaccion t INNER JOIN tipoTransaccion tt ON t.idTipo = tt.id where tt.nombre='reparacion' AND dniCliente=?";
$res = $conexion->prepare($sql);
$res->bind_param('s', $userId);
$res->execute();
$res->bind_result($id);
while ($res->fetch()) {
    $idTs[] = $id;
}

if (count($idTs) > 0) {
    foreach ($idTs as $idT) {

        $sqlDEF = "SELECT t.precio,t.fechatransaccion,t.fechaEntrada,t.fechaEstimada,pt.descripcionAveria,p.nombre,i.url
FROM transaccion t 
INNER JOIN productoTransaccion pt ON t.id = pt.idTransaccion 
INNER JOIN producto p ON pt.idProducto = p.id 
INNER  JOIN imagen i ON p.id = i.id
WHERE t.id=?";

        $resDEF = $conexion->prepare($sqlDEF);
        $resDEF->bind_param('i', $idT);
        $resDEF->execute();
        $resDEF->bind_result($precio, $fechaTransaccion, $fechaEntrada, $fechaEstimada, $descripcionAveria, $nombreProducto, $url);
        while ($resDEF->fetch()) {
            $regs = array('nombreProducto' => $nombreProducto, 'url' => $url, 'fechaTransaccion' => $fechaTransaccion, 'fechaEntrada' => $fechaEntrada, 'fechaEstimada' => $fechaEstimada, 'descripcionAveria' => $descripcionAveria, 'precio' => $precio);
        }
    }
} else {
    die();
}
$datos = json_encode($regs);
if ($callback) {
    echo sprintf('%s(%s)', $callback, $datos);
} else {
    echo $datos;
}