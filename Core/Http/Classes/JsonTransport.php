<?php

namespace Core\Http\Classes;

use Core\Http\Interfaces\Request;

use Core\Http\Classes\Validator;

class JsonTransport implements Request{

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

    public function __construct(){

        //Assign $request with input params

        self::$request = json_decode(file_get_contents("php://input"));

        //Instiate Vaidator class to input params

        self::$Validator = new Validator();

    }

    //Implementing Request interface methods

    public function ready(): void{

        //Looping through JSON request element attributes

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

    private static function Init()
    {
        
        //Authorize_Request method Validate if the hash key is valid 

        self::Authorize_Request(self::$request->hash,self::$request->api_key);

        self::$CVV = (!empty(self::$request->cvv)) ? self::$request->cvv : null;

        self::$CARD = (!empty(self::$request->card)) ? self::$request->card : null;

        self::$EXPIRY = (!empty(self::$request->expiry)) ? self::$request->expiry : null;

        self::$EMAIL = (!empty(self::$request->email)) ? self::$request->email : null;

        self::$TYPE = (!empty(self::$request->type)) ? self::$request->type : null;

        self::$MOBILE = (!empty(self::$request->mobile_number)) ? self::$request->mobile_number : null;

    }

   //Validate all required fields and assign errors
 
    private static function Required(){

        if(self::$TYPE == "credit_card"){
  
            if(self::$Validator->isValidCard(self::$CARD) != true || is_null(self::$CARD)){
  
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

            return;

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

            return;

          } 
           //If request doesn't have type credit card or mobile then return error

           self::$ERR[] =  "Invalid Request Params Type not set";

           return self::$ERR;
          
    }

    //Printing results in xml format either it's error or success message

    private static function Printer(){
      Header('Content-type: text/json');
       
        if(is_array(self::$ERR) && count(self::$ERR) != 0){
        
            self::$DATA['status'] = "Failed";
        
            self::$DATA['errors'] = self::$ERR;               
        
        }
        else{
        
            self::$DATA['status'] = "Success";
        
            self::$DATA['msg'] = "Operation Done Successfuly";  
        
        }
        
        
        print(json_encode(self::$DATA));  
    }

    //Validate if request paramter Hash is valid

    private static function Authorize_Request($hash,$api_key){
        
        if(isset($hash) && isset($api_key)){
        
            self::$HASH = (!empty($hash)) ? $hash : null;
        
            self::$ApiKey = (!empty($api_key)) ? $api_key : null;
            
            //Hashing method returns expected hashed value to compare with request hash parameter
        
            self::$EXEPECTED = self::Hashing(self::$ApiKey);
        
           //If Hashes not matched block the request

           if(self::$Validator->isValidHash(self::$HASH,self::$EXEPECTED) != true){
        
                 //Un Authorized

                 self::UnAuthorized();

            }
        }
        else{
        
            //Un Authorized

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
    
    Header('Content-type: text/json');

    header("HTTP/1.1 401 Unauthorized");

    self::$ERR['msg'] =  "Hash Key Required Unauthorized Access (401)";
        
    print(json_encode(self::$ERR));  
        
    exit;

  }


}

?>