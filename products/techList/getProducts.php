<?php
include('../../connect.php');
if (isset($_GET['callback'])) {
    $callback = $_GET['callback'];
} else {
    $callback = false;
}
$sql ="Select * from producto p where p.idTipo!=1";
$res = $conexion->prepare($sql);
$res->execute();
$res->bind_result($id,$nombre,$descripcion,$fechaLanzamiento,$idDesarrollador,$idTipo,$cantidad);

while($res->fetch()){
    $p[]=array('id'=>$id,'nombre'=>$nombre,'descripcion'=>$descripcion);

}
$productos[]=$p;

$datos = json_encode($productos);
if ($callback) {
    echo sprintf('%s(%s)', $callback, $datos);
} else {
    echo $datos;
}