<?php
class LoginAPI{
    public function API(){
        header('Content-Type:application/JSON');
        $method=$_SERVER['REQUEST_METHOD'];
        switch ($method){

            case 'POST':
                include('login.php');
                break;


             default:
                echo "UNSUPPORTED METHOD";
                break;
        }
    }
}