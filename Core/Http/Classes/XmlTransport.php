<?php

namespace Core\Http\Classes;

use Core\Http\Interfaces\Request;

use Core\Http\Classes\Validator;

class XmlTransport implements Request{

  //Instiate Param Vars

  static $CVV;

  static $CARD;

  static $EXPIRY;

  static $EMAIL;

  static $ERR;

  static $TYPE;

  static $MOBILE;

  static $DATA;

  static $HASH;

  static $EXEPECTED;

  static $ApiKey;

  static $request;

  static $Validator;

  static $XmlHolder;

  public function __construct(){

    //Assign $request with input params

    self::$request = file_get_contents("php://input");

    //Instiate Vaidator class to input params

    self::$Validator = new Validator();

    //Parse $request as XML elements and assign it to $XmlHolder

    self::$XmlHolder = new \SimpleXMLElement(self::$request); 

  }

  //Implementing Request interface methods

  public function ready(): void{

    //Looping through xml request element attributes

    self::Init(); 

  }

  public function dispatch(): void{

    //Validate all assigned variables 

    self::Required();
    
  }

  public function respond(): void{

    //Print either validation errors or success message

    self::Printer();

  }
   
  // Assign all request params and validate if the hash key and timestamp are valid
  protected static function Init(){

    // Assign Xml request element to $items variable

    $items = self::$XmlHolder->xpath('*/request');

    //Looping through Xml Request element attributes

    foreach($items as $item) {
         
      //Authorize_Request method Validate if the hash key is valid 

      self::Authorize_Request($item['hash'],$item['api_key']);

      //Check if payment method type is credit card

      if(isset($item['type']) && $item['type'] == 'credit_card'){

          //Assign Credit Card Params to variables

          self::$CVV =(!empty($item['cvv'])) ? (string) $item['cvv'] : null;

          self::$CARD = (!empty($item['card'])) ? (string) $item['card'] : null;

          self::$EXPIRY =(!empty($item['expiry'])) ? (string) $item['expiry'] : null;

          self::$EMAIL = (!empty($item['email'])) ? (string) $item['email'] : null;

          self::$TYPE = (!empty($item['type'])) ? (string) $item['type'] : null;

          return;


      }

      //Check if Payment method is mobile number

      elseif(isset($item['type']) && $item['type'] == 'mobile'){

        //Assign Mobile number Params to variables

        self::$TYPE = (!empty($item['type'])) ? (string) $item['type'] : null;

        self::$MOBILE = (!empty($item['mobile_number'])) ? (string) $item['mobile_number'] : null;
        
        return;

      }

          //If request doesn't have type credit card or mobile then return error

          self::$ERR[] =  "Invalid Request Params Type not set";

          return self::$ERR;

    }

  }

  //Validate all required fields and assign errors

  private static function Required(){

    if(self::$TYPE == "credit_card"){

      if(self::$Validator->isValidCard(self::$CARD) != true ){

          self::$ERR[] =  "Invalid/Required Card Number";

      }

      if(self::$Validator->isValidCVV(self::$CVV) != true || is_null(self::$CVV)){

          self::$ERR[] =  "Invalid/Required CVV Number";

      }

      if(self::$Validator->isValidEmail(self::$EMAIL) != true || is_null(self::$EMAIL)){

          self::$ERR[] =  "Invalid/Required Email Address";

      }

      if(self::$Validator->isValidExpiryDate(self::$EXPIRY) != true || is_null(self::$EXPIRY)){

          self::$ERR[] =  "Invalid/Required EXPIRY Date";

      }

  }
      elseif(self::$TYPE == "mobile"){

      if(!empty(self::$MOBILE)){

          if(self::$Validator->isValidMobile(self::$MOBILE) != true || is_null(self::$MOBILE)){

              self::$ERR[] =  "Invalid/Required Mobile Number";  

          }

      }
      else{

          self::$ERR[] =  "Mobile Number Required";   

      }

    }
     elseif(is_null(self::$TYPE)){

      self::$ERR[] =  "Payment Type Required";   

    }       

  }


  //Printing results in xml format either it's error or success message

  private static function Printer(){

    Header('Content-type: text/xml');

    if(is_array(self::$ERR) && count(self::$ERR) != 0){

      $xml = new \SimpleXMLElement('<xml/>');

       for ($i = 0; $i <= count(self::$ERR); $i++) {

         if(isset(self::$ERR[$i])){

         $error = $xml->addChild('msg');

         $error->addChild('type', "error");

         $error->addChild('message', self::$ERR[$i]);

          }

        }
        

        print($xml->asXML());   
    
    }

    else{
     
      self::Success();

    }

  }

  //Validate if request paramter Hash is valid

  private static function Authorize_Request($hash,$api_key){
  
    if(isset($hash) && isset($api_key)){

      self::$HASH = (!empty($hash)) ? (string) $hash : null;

      self::$ApiKey = (!empty($api_key)) ? (string) $api_key : null;

      //Hashing method returns expected hashed value to compare with request hash parameter

      self::$EXEPECTED = self::Hashing(self::$ApiKey);

      //If Hashes not matched block the request
      if(self::$Validator->isValidHash(self::$HASH,self::$EXEPECTED) != true){
        
        //Un Authorized

        self::UnAuthorized();
        
      }
    }
    else{

    //Un Authorized if there weren't hash and api key params send

    self::UnAuthorized();
      
    }  

  }

  //Return the hashed value 

  private static function Hashing($ApiKey){
 
    $time = time();

    $time =  date("Y-m-d h:i",$time);

    return hash_hmac('sha256', $time, $ApiKey);

  }

  //UnAuthorized method in case of un matched hash

  private static function UnAuthorized(){
    
    header("HTTP/1.1 401 Unauthorized");

    Header('Content-type: text/xml');

    $xml = new \SimpleXMLElement('<xml/>');

    $error = $xml->addChild('msg');

    $error->addChild('type', "error");

    $error->addChild('message',  "Invalid Hash Key Unauthorized Access (401)");

    print($xml->asXML());  

    exit;

  }

  //Success method in case of success

  private static function Success(){

    $xml = new \SimpleXMLElement('<xml/>');

    $response = $xml->addChild('msg');

    $response->addChild('type', "success");

    $response->addChild('message', "Operation Done Successfully");

    print($xml->asXML());   

  }

}

?>