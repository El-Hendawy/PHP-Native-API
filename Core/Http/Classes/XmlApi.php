<?php
namespace Core\Http\Classes;

use  Core\Http\Classes\API;

use  Core\Http\Interfaces\Request;

use Core\Http\Classes\XmlTransport;

//Child class XmlApi implemented the abstracted method getApiTransport

class XmlApi extends API {
   
    function getApiTransport(): Request
    {

        return new XmlTransport();

    }
    
}


?>