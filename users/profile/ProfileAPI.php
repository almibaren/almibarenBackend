<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 7/11/18
 * Time: 10:59
 */

class ProfileAPI{
    public function API(){
        header('Content-Type:application/JSON');
        $method=$_SERVER['REQUEST_METHOD'];
        switch ($method){

            case 'GET':
                include('userProfileInfo.php');
                break;


            default:
                echo "UNSUPPORTED METHOD";
                break;
        }
    }
}