<?php
class ProductsAPI{
    public function API(){
        header('Content-Type:application/JSON');
        $method=$_SERVER['REQUEST_METHOD'];
        switch ($method){

            case 'GET':
                include('getProductsMain.php');
                break;

             default:
                echo "UNSUPPORTED METHOD";
                break;
        }
    }
}