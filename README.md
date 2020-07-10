# Native-PHP-API-
Native PHP API that can handle request types like JSON or XML


Hashing Code : 

$time = time();
$time = date("Y-m-d h:i",$time);
$api_key = 'secret';
$hash = hash_hmac('sha256', $time,  $api_key);
echo "$hash";


JSON Requests Example : 

1) Credit Card Request Example : 

{
	"card": "5594444687606435",
	"expiry": "01/2020",
	"cvv": "1234",
	"email": "omar@omar.net",
	"type":"credit_card",
	"hash":"44c9593e92ac1772728e94e9612b92f28268957f7a61ae2099509ccc469fe35e",
	"api_key":"secret"
}	

2) Mobile Request Example : 

{
	"mobile_number": "002-01270-537832",
	"type":"mobile",
	"hash":"618a029ed69357a20aad2c685d98e4c83e0544a19ac2a17e722081a865831e3a",
	"api_key":"secret"
}
	


XML Requests Example : 

1) Credit Card Request Example : 

<?xml version="1.0" encoding="UTF-8"?>
<xml>
    <payment>
        <request card="5594444687606435" cvv="1234" expiry="01/2020" email="omar@omar.net" type="credit_card" api_key="secret" hash="06f34daf94800cab52c36bdc357c60377937dc72fe06130e30ef8dfc1005e5f7"/>
    </payment>
</xml>


2) Mobile Request Example : 

<?xml version="1.0" encoding="UTF-8"?>
<xml>
    <payment>
        <request mobile_number="002-01270-537832"  type="mobile" api_key="secret" hash="79a175a18dc1abad8bc65196ec8dff40b61a25d40372a35e0fb1ecd3a9ad3991"/>
    </payment>
</xml>



PLease Consider Checking DB.php for singleton example 