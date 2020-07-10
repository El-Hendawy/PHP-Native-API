<?php

namespace Core\Http\Classes;

class Validator {

    // isValidCard method Credit card number validation based on Luhn's algorithm

    public function isValidCard($card) {

        if (is_numeric($card) == false){

            return false;

        }

        $sum = 0;

        $alt = false;

        for($i = strlen($card) - 1; $i >= 0; $i--) 
        {

            if($alt)

            {

               $temp = $card[$i];

               $temp *= 2;

               $card[$i] = ($temp > 9) ? $temp = $temp - 9 : $temp;

            }

            $sum += $card[$i];

            $alt = !$alt;

        }
       
       

        return $sum % 10 == 0;
    }

    // isValidCVV method validating that CVV number is 3-4 numbers

    public function isValidCVV($CVV) {
      
        if (preg_match("/^[0-9]{3,4}$/", $CVV)) {

        return true;

        } 
       
        return false;
          
    }

    // isValidEmail method validating that email provided is a correct fromat
    public function isValidEmail($Email) {
      
        if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $Email)) {

        return true;

        } 
        
        return false;
          
    }

    // isValidExpiryDate method validate that expiry date in valid format like (01/2020|01/20|012020|0120)
    public function isValidExpiryDate($ExpiryDate) {
      
        if (preg_match("/^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/", $ExpiryDate)) {

        return true;

        }
        
        return false;
         
    }

    // isValidMobile method validate mobile number in international fromat like (002-01270-537832) in Egypt
    
    public function isValidMobile($Mobile) {
      
        if (preg_match("/^[0-9]{3}-[0-9]{5}-[0-9]{6}$/", $Mobile)) {

        return true;

        }
        
        return false;
            
    }

    // isValidHash Validate both received and generated hash

    public function isValidHash($Hash,$Expected) {
      
        if (hash_equals($Expected, $Hash) ) {

            return true;

        }
        
        return false;
        
    }
}

?>