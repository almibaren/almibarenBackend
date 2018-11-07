<?php
class UsersAPI{
    public function API(){
        header('Content-Type:application/JSON');
        $method=$_SERVER['REQUEST_METHOD'];
        switch ($method){

            case 'PUT':
                include('updateUserData.php');
                break;

            case 'DELETE':
                include('deleteUser.php');
                break;


             default:
                echo "UNSUPPORTED METHOD";
                break;
        }
    }
}