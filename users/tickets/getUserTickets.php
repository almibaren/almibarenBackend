<?php
include('../../connect.php');
if (isset($_GET['callback'])) {
    $callback = $_GET['callback'];
} else {
    $callback = false;
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
    $sqlDEF = "SELECT t.precio,t.fechatransaccion,t.fechaEntrada,t.fechaEstimada,p.nombre,p.descripcion,i.url
FROM transaccion t 
INNER JOIN productoTransaccion pt ON t.id = pt.idTransaccion 
INNER JOIN producto p ON pt.idProducto = p.id 
INNER JOIN productoImagen pi ON p.id = pi.idProducto
INNER  JOIN imagen i ON pi.idImagen = i.id
WHERE t.id=?";
}

$datos = json_encode($productos);
if ($callback) {
    echo sprintf('%s(%s)', $callback, $datos);
} else {
    echo $datos;
}