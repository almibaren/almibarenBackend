<?php
$conexion = new mysqli('192.168.6.141', 'webservice', 'Almi123', 'almibar');
$conexion->set_charset("utf8");
$conexion2 = new mysqli('192.168.6.141', 'webservice', 'Almi123', 'almibar');
$conexion2->set_charset("utf8");
$conexion3 = new mysqli('192.168.6.141', 'webservice', 'Almi123', 'almibar');
if (mysqli_connect_error()) {
    die(mysqli_connect_errno());
}
