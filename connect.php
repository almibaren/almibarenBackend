<?php
$conexion=new mysqli('192.168.6.141','root','Almi123','almibaren');
//$conexion2=new mysqli('192.168.6.141','webservice','Almi123','almibaren');
//$conexion3=new mysqli('192.168.6.141','webservice','Almi123','almibaren');
if(mysqli_connect_error()){
  die(mysqli_connect_errno());
}