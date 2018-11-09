<?php
$valor['actualizado'] = 0;
include('../connect.php');

$put = file_get_contents('php://input');
$request = json_decode($put);

$dni = $request->dni;
$nombre = $request->nombre;
$apellido1 = $request->apellido1;
$apellido2 = $request->apellido2;
$user = $request->user;
$passwd = $request->passwd;
$img = $request->imagen;

if ($dni != "") {
    $sql = "update cliente set nombre=?,apellido1=?,apellido2=?,usuario=?,passwd=?,imagen=? where dni=?";
    $resultado = $conexion->prepare($sql);
    $resultado->bind_param('sssssss', $nombre, $apellido1, $apellido2, $user, $passwd, $img, $dni);
    $resultado->execute();
    $valor['actualizado'] = $resultado->affected_rows;
}

$datos = json_encode($valor);
echo $datos;
