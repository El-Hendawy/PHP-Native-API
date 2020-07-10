<?php

namespace Core\Http\Classes;

use  Core\Http\Classes\API;

use  Core\Http\Interfaces\Request;

use Core\Http\Classes\JsonTransport;

//Child class JsonApi implemented the abstracted method getApiTransport

class JsonApi extends API {

    function getApiTransport(): Request
    {

        return new JsonTransport();

    }
}

?>