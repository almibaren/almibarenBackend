<?php
$post = file_get_contents('php://input');

$request = json_decode($post);

include('../../connect.php');

$dni = $request->dni;
$nombre = $request->nombre;
$apel1 = $request->apellido1;
$apel2 = $request->apellido2;
$email = $request->email;
$user = $request->user;
$passwd = $request->passwd;
$img = $request->imagen;

$sqlCheck = "Select * from cliente WHERE usuario=?";
$resCheck = $conexion->prepare($sqlCheck);
$resCheck->bind_param('s', $user);
$resCheck->execute();

if ($resCheck->num_rows == 0) {

    if ($dni != "" && $user != "" && $passwd != "") {
        $sql = "INSERT INTO cliente VALUES(?,?,?,?,?,?,?)";
        $res = $conexion2->prepare($sql);
        $res->bind_param('sssssss', $dni, $nombre, $apel1, $apel2, $email, $user, $passwd, $img);
        $res->execute();

        $valor['insertado'] = $res->affected_rows;
        $datos = json_encode($valor);
        echo $datos;
    }
}