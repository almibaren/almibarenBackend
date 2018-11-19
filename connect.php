<?php
$host='192.168.6.141';
$username = 'webservice';
$passwd = 'Almi123';
$dbname='almibar';


$conexion = new mysqli($host, $username, $passwd, $dbname);
$conexion->set_charset("utf8");
$conexion2 = new mysqli($host, $username, $passwd, $dbname);
$conexion2->set_charset("utf8");
$conexion3 = new mysqli($host, $username, $passwd, $dbname);
$conexion3->set_charset("utf8");
$conexion4 = new mysqli($host, $username, $passwd, $dbname);
$conexion4->set_charset("utf8");
$conexion5 = new mysqli($host, $username, $passwd, $dbname);
$conexion5->set_charset("utf8");
if (mysqli_connect_error()) {
    die(mysqli_connect_errno());
}
