<?php

namespace Core\Http\Classes;

use  Core\Http\Interfaces\Request;

abstract class API {

    //Setting abstracted method getApiTransport implementing Request interface

    abstract function getApiTransport() : Request;

    public function sendAPI(){

        $transport = $this->getApiTransport();

        //The below methods implemented from Request interface Core\Http\Interfaces\Request

        $transport->ready();

        $transport->dispatch();

        $transport->respond();

    }

}

?>