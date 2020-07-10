<?php
namespace Views;

use  Core\Http\Classes\JsonApi;

use  Core\Http\Classes\API;

use  Core\Http\Classes\XmlApi;

class Home {

    function __construct(){
        
        //Setting the right headers

        header("Content-Type: application/json,application/xml; charset=UTF-8");

        header("Access-Control-Allow-Methods: POST");

        header("Access-Control-Max-Age: 3600");

        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        //Assing request params to $request

        $request = file_get_contents("php://input");

        //Prepare the request to validate if it's in JSON format

        $json_request = (json_decode($request) != NULL) ? true : false;

        //If request in json format send it to child class JsonApi

        if($json_request){

            $this->deliverAPI(new JsonApi());

        }

        //Else if request isn't json format check if it's in XML format

        else{
            
            //Validate if it's XML string

             $xml = simplexml_load_string($request);

             //If it's not XML string respond wit bad request error

            if (!$xml) {

               $this->Bad_request();

            }

          //If it's XML format string send it to child class XmlApi

          $this->deliverAPI(new XmlApi());

        }

}

    //Deliver  request to API parent abstracted class  

    function deliverAPI(API $API){

        $API->sendAPI();

    }

    function Bad_request(){

        header("HTTP/1.1 400 Bad Request");

        header('Content-type: application/xml');

        $xml = new \SimpleXMLElement('<xml/>');

        $error = $xml->addChild('msg');

        $error->addChild('type', "Bad Request");

        $error->addChild('message', ' Unprocessable entity please provide valid JSON/XML Request');

        print($xml->asXML());     

        exit;

    }

}