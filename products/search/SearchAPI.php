<?php
class SearchAPI{
    public function API(){
        header('Content-Type:application/JSON');
        $method=$_SERVER['REQUEST_METHOD'];
        switch ($method){

            case 'POST':
                include('getProductsSearch.php');
                break;

             default:
                echo "UNSUPPORTED METHOD";
                break;
        }
    }
}