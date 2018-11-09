<?php
$post = file_get_contents('php://input');

$request = json_decode($post);

include('../../connect.php');

$dni = $request->dni;
$nombre = $request->nombre;
$apel1 = $request->apellido1;
$apel2 = $request->apellido2;
$user = $request->user;
$passwd = $request->passwd;
$img = $request->imagen;

if ($dni != "" && $user != "" && $passwd != "") {
    $sql = "INSERT INTO cliente VALUES(?,?,?,?,?,?,?)";
    $res = $conexion->prepare($sql);
    $res->bind_param('sssssss', $dni, $nombre, $apel1, $apel2, $user, $passwd, $img);
    $res->execute();

    $valor['insertado']=$res->affected_rows;
    $datos =json_encode($valor);
    echo $datos;
}