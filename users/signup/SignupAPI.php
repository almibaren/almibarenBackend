<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 7/11/18
 * Time: 11:01
 */

class SignupAPI{
    public function API(){
        header('Content-Type:application/JSON');
        $method=$_SERVER['REQUEST_METHOD'];
        switch ($method){

            case 'POST':
                include('signup.php');
                break;


            default:
                echo "UNSUPPORTED METHOD";
                break;
        }
    }
}