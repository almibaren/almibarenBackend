<?php


class TicketsAPI{
    public function API(){
        header('Content-Type:application/JSON');
        $method=$_SERVER['REQUEST_METHOD'];
        switch ($method){

            case 'GET':
                include('getUserTickets.php');
                break;


            default:
                echo "UNSUPPORTED METHOD";
                break;
        }
    }
}