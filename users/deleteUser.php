<?php

$valor['borrado']=0;

include('../connect.php');
if(isset($_GET['userId'])){
    $userId=$_GET['userId'];
    var_dump("DENTRO");
}else{
    $userId="";
}
var_dump($userId);
if($userId!=""){
    $sql="DELETE FROM cliente WHERE dni=?";
    $res=$conexion->prepare($sql);
    $res->bind_param('s',$userId);
    $res->execute();
    $valor['borrado']=$res->affected_rows;
}

$datos = json_encode($valor);
echo $datos;