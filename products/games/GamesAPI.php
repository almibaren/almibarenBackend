<?php
class GamesAPI{
    public function API(){
        header('Content-Type:application/JSON');
        $method=$_SERVER['REQUEST_METHOD'];
        switch ($method){

            case 'GET':
                include('getProductsGames.php');
                break;

             default:
                echo "UNSUPPORTED METHOD";
                break;
        }
    }
}