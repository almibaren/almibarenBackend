<?php

$post = file_get_contents('php://input');

$request = json_decode($post);

include('../../connect.php');

$user = $request->user;
$passwd = $request->passwd;

if ($user != "" && $passwd != "") {
    $sql = "SELECT * FROM cliente WHERE usuario=? AND passwd=?";
    $res = $conexion->prepare($sql);
    $res->bind_param('ss', $user, $passwd);
    $res->execute();
    $res->bind_result($dni, $nombre, $apel1, $apel2,$email, $user, $passwd, $imagen);
    $cliente = array();
    while ($res->fetch()) {
        $cliente = array('dni' => $dni, 'nombre' => $nombre, 'apellido1' => $apel1, 'apellido2' => $apel2,'email'=>$email, 'user' => $user, 'passwd' => $passwd, 'imagen' => $imagen);
    }
    $datos = json_encode($cliente);
    echo $datos;
}