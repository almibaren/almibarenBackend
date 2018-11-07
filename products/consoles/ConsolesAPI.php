<?php
class ConsolesAPI{
    public function API(){
        header('Content-Type:application/JSON');
        $method=$_SERVER['REQUEST_METHOD'];
        switch ($method){

            case 'GET':
                include('getProductsConsoles.php');
                break;

             default:
                echo "UNSUPPORTED METHOD";
                break;
        }
    }
}