<?php

include('../../connect.php');

if(isset($_GET['callback'])){
    $callback=$_GET['callback'];
}
else{
    $callback=false;
}

//var_dump($_GET);

$dni="";


//TODO
if($id == "") {
    $sql = "SELECT * FROM producto INNER JOIN tipoProducto ON producto.idTipo = tipoProducto.id WHERE tipoProducto.nombre='consola'";
    $resultado1 = $conexion->prepare($sql);
    $resultado1->execute();

    $resultado1->bind_result($id,$nombre,$descripcion,$fechaLanzamiento,$idDesarrollador,$idTipo,$cantidad);

    $alumnos = array();
    while ($resultado1->fetch()) {
        $alumnos[] = array('id' => $idAlumno, 'nombre' => $nombreAlumno, 'apellido1' => $apellido1Alumno, 'apellido2' => $apellido2Alumno, 'fecha_nac' => $fechaAlumno, 'provincia' => $provinciaAlumno, 'DNI' => $dniAlumno);
    }

}