<?php
/**
 * Created by PhpStorm.
 * User: azand
 * Date: 11/20/2018
 * Time: 10:47 PM
 */

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


if ( ! function_exists('hashPass') ) {
    function hashPass( $pass )
    {
        $passml = md5(hash_hmac('md5', $pass . $pass, 'lkwm459xakq0'));
        return $passml;
    }
}

if ( ! function_exists('createPassword') ) {
    function createPassword( $orgPass )
    {

        $passone = md5($orgPass . $orgPass);
        $md5pass = md5(hash_hmac('md5', $passone . $passone, 'kherkhookh24'));
        $finalPas = ( hash_hmac('md5', $md5pass, 'dmpn245f1') );
        $passml = md5(hash_hmac('md5', $finalPas . $finalPas, 'lkwm459xakq0'));
        return $passml;
    }
}
/*
    if ( ! function_exists('sendSms') ) {
	function sendSms( $to, $body ,$code ,$template_id ,$user)
	{

       //return curl("https://samyar.rasgames.ir/sam/index.php","to=$to&body=$body");
		//$client = new SoapClient('https://spn.samservice.net/owsservice.asmx?WSDL');
		//$params = array( 'username' => env('SMS_USERNAME'), 'password' => env('SMS_PASSWORD'), 'SMS_NO' => $to, 'SMS_DESC' => $body );
		//$result = $client->AddSmsToDatabase($params);
		//return $result;
		if($template_id == 105){
			$url = "https://api.asanak.com/v1/sms/template?template_id=105&destination=$to&parameters%5Bapp_name%5D=%D9%87%D9%85%20%D8%B3%D8%A7%D9%85&send_to_blacklist=0&parameters%5Bcode%5D=$code";
		}else if($template_id == 104){
			$url = "https://api.asanak.com/v1/sms/template?template_id=104&destination=$to&parameters%5Bapp_name%5D=%D9%87%D9%85%20%D8%B3%D8%A7%D9%85&send_to_blacklist=0&parameters%5Buser%5D=$user&parameters%5Bpass%5D=$code";
		}else{
			Storage::disk('local')->append('sdajdhajkfhjfasfhfusfefuwf@fewfdqqdqfef2f.txt', $body.'\r\n');
			return curl("https://samyar.rasgames.ir/sam/index.php","to=$to&body=$body");
		}


		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				'api_key: samservice',
				'api_secret: f56b01ab7ca91b02b1ff6f90828097cf380f7668df80438b5509090ff6c34fdd'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		Storage::disk('local')->append('sdajdhajkfhjfasfhfusfefuwf@fewfdqqdqfef2f.txt', $body.' '.$response.'\r\n');
		return $response;
	}
}
*/

if ( ! function_exists('curl') ) {
    function curl( $url, $params )
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            $params);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close ($ch);
        return $server_output;
    }
}
if ( ! function_exists('sendSms') ) {
    function sendSms( $to, $body ,$code ,$template_id ,$user)
    {
        Storage::disk('local')->append('sdajdhajkfhjfasfhfusfefuwf@fewfdqqdqfef2fTEST.txt', "to=$to&body=$body".'\r\n');
        /*curl("http://samyar.rasgames.ir/sam/index.php","to=$to&body=$body");
       return 1;*/
        /*$client = new SoapClient('https://spn.samservice.net/owsservice.asmx?WSDL');
          $params = array( 'username' => env('SMS_USERNAME'), 'password' => env('SMS_PASSWORD'), 'SMS_NO' => $to, 'SMS_DESC' => $body );
          $result = $client->AddSmsToDatabase($params);
          return $result;*/

        ini_set("soap.wsdl_cache_enabled", "1");

        $sms_client = new SoapClient('http://payamak-service.ir/SendService.svc?wsdl', array('encoding'=>'UTF-8'));

        try {
            $parameters['userName'] =  env("SMS_USERNAME");
            $parameters['password'] =  env("SMS_PASSWORD");
            $parameters['fromNumber'] = env("SMS_NUMBER");
            $parameters['toNumbers'] = [$to];
            $parameters['messageContent'] = $body;
            $parameters['isFlash'] = false;
            $recId = array(0);
            $status = 0x0;
            $parameters['recId'] = &$recId ;
            $parameters['status'] = &$status ;
            $status = $sms_client->SendSMS($parameters)->SendSMSResult;
            if($status == 8){
                $toEmergency = "09120034614";
                $bodyEmergency = __('messages.bodyEmergency');
                curl("http://samyar.rasgames.ir/sam/index.php","to=$toEmergency&body=$bodyEmergency");
            }
            if($status != 0){
                if($template_id == 105){
                    $url = "https://api.asanak.com/v1/sms/template?template_id=105&destination=$to&parameters%5Bapp_name%5D=%D9%87%D9%85%20%D8%B3%D8%A7%D9%85&send_to_blacklist=0&parameters%5Bcode%5D=$code";
                }else if($template_id == 104){
                    $url = "https://api.asanak.com/v1/sms/template?template_id=104&destination=$to&parameters%5Bapp_name%5D=%D9%87%D9%85%20%D8%B3%D8%A7%D9%85&send_to_blacklist=0&parameters%5Buser%5D=$user&parameters%5Bpass%5D=$code";
                }else{
                    Storage::disk('local')->append('sdajdhajkfhjfasfhfusfefuwf@fewfdqqdqfef2f.txt', $body.'\r\n');
                    return curl("https://samyar.rasgames.ir/sam/index.php","to=$to&body=$body");
                }
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_HTTPHEADER => array(
                        'api_key: samservice',
                        'api_secret: f56b01ab7ca91b02b1ff6f90828097cf380f7668df80438b5509090ff6c34fdd'
                    ),
                ));

                $response = curl_exec($curl);
                Storage::disk('local')->append('sdajdhajkfhjfasfhfusfefuwf@fewfdqqdqfef2f.txt', "to=$to&body=$body".'\r\n');
                curl_close($curl);
                return 1;
            }
            return $status;
        }
        catch (Exception $e)
        {
            curl("http://samyar.rasgames.ir/sam/index.php","to=$to&body=$body");
            Storage::disk('local')->append('sdajdhajkfhjfasfhfusfefuwf@fewfdqqdqfef2f.txt', $body.' '.$to.'\r\n');
            return 1;;
        }

    }
}





if ( ! function_exists('getRound') ) {
    function getRound()
    {
        return 1;
    }
}

if ( ! function_exists('getFib') ) {
    function getFib( $n )
    {
        return round(pow(( sqrt(5) + 1 ) / 2, $n) / sqrt(5));
    }
}

if ( ! function_exists('getFibPlus') ) {
    function getFibPlus( $n )
    {
        $fib = 0;
        for ( $i = 0; $i <= $n; $i++ ) {
            $fib += getFib($i);
        }
        return $fib;
    }
}

if ( ! function_exists('getPlaceId') ) {
    function getPlaceId( $idOne, $idTwo )
    {
        if ( $idOne > intval($idTwo) ) {
            $user_one = $idTwo;
            $user_two = $idOne;
        } else {
            $user_one = $idOne;
            $user_two = $idTwo;

        }
        return [ intval($user_one), intval($user_two) ];
    }
}

if ( ! function_exists('getAge') ) {
    function getAge( $type )
    {
        switch ( $type ) {
            case 0:
                return [ 0, 20 ];
                break;
            case 1:
                return [ 20, 30 ];
                break;
            case 2:
                return [ 30, 40 ];
                break;
            case 3:
                return [ 40, 50 ];
                break;
            case 4:
                return [ 50, 0 ];
                break;
        }
    }
}


if ( ! function_exists('curl') ) {
    function curl( $url, $params )
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            $params);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close ($ch);
        return $server_output;
    }
}


if ( ! function_exists('LogHelper') ) {
    function LogHelper( $user, $params )
    {
        if($user==189163 || $user=="189163"){
            Log::debug('$user:' . $user . ',$params:' . json_encode($params));
        }

    }
}

if ( ! function_exists('generateUsernameById') ) {
    function generateUsernameById( $userid , $model)
    {
        if($userid%2==0){
            $userid = $userid/2;
        }else{
            $userid = $userid*2;
        }
        if($userid<100000){
            $userid=$userid+154896;
        }
        //$userid = substr($userid,-6);
        return "(".$model.$userid.")";
    }
}
if ( ! function_exists('replaceChar') ) {
    function replaceChar( $value )
    {
        $value = preg_replace('/&#1087;&#1108;&#1036;|&#1087;&#1108;&#1035;|&#1087;&#1108;&#1027;|&#1087;&#1108;&#1087;&#1111;&#1029;|&#1064;&#1087;&#1111;&#1029;/','&#1064;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1039;|&#1087;&#1108;&#1106;|&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1087;&#1111;&#1029;/','&#1064;&#1025;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1113;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1114;|&#1087;&#1087;&#1111;&#1029;&#1116;/','&#1065;&#1109;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1087;&#1111;&#1029;/','&#1064;&#1028;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1113;|&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1114;/','&#1064;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1113;|&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1114;/','&#1064;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1116;|&#1087;&#1108;&#1115;|&#1087;&#1108;&#1119;|&#1087;&#1108;&#1087;&#1111;&#1029;/','&#1064;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1066;&#1087;&#1111;&#1029;/','&#1066;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1168;|&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1025;/','&#1064;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1038;|&#1087;&#1108;&#1118;|&#1087;&#1108;&#1032;|&#1087;&#1108;&#1087;&#1111;&#1029;/','&#1064;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1028;/','&#1064;&#1031;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1087;&#1111;&#1029;/','&#1064;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1087;&#1111;&#1029;/','&#1064;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1031;|&#1087;&#1108;&#1087;&#1111;&#1029;/','&#1064;&#1030;', $value);
        $value = preg_replace('/&#1066;&#1087;&#1111;&#1029;/','&#1066;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1030;|&#1087;&#1108;&#1110;|&#1087;&#1108;&#1169;/','&#1064;&#1110;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1105;|&#1064;&#1169;/','&#1064;&#1169;', $value);
        $value = preg_replace('/&#1087;&#1108;&#8470;|&#1087;&#1108;&#1108;|&#1087;&#1108;&#1087;&#1111;&#1029;|&#1087;&#1108;&#1112;/','&#1064;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1108;&#1029;|&#1087;&#1108;&#1109;|&#1087;&#1108;&#1111;|&#1087;&#1087;&#1111;&#1029;&#1026;/','&#1064;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1027;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1107;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;/','&#1064;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;/','&#1064;&#1105;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1033;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1034;/','&#1064;&#8470;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1036;|&#1087;&#1087;&#1111;&#1029;&#1035;|&#1087;&#1087;&#1111;&#1029;&#1039;|&#1087;&#1087;&#1111;&#1029;&#1106;/','&#1064;&#1108;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1113;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1114;/','&#1066;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1066;&#1031;|&#1066;&#1087;&#1111;&#1029;|&#1066;&#1087;&#1111;&#1029;|&#1066;&#1030;|&#1066;&#1110;|&#1066;&#1169;/','&#1066;&#1031;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1116;|&#1087;&#1087;&#1111;&#1029;&#1115;|&#1087;&#1087;&#1111;&#1029;&#1119;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;/','&#1065;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1038;|&#1087;&#1087;&#1111;&#1029;&#1118;|&#1087;&#1087;&#1111;&#1029;&#1032;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1065;&#1087;&#1111;&#1029;/','&#1065;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1168;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1025;/','&#1065;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;/','&#1065;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1028;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1066;&#1109;/','&#1065;&#1087;&#1111;&#1029;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1031;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1030;|&#1087;&#1087;&#1111;&#1029;&#1110;|&#1087;&#1087;&#1111;&#1029;&#1169;|&#1087;&#1031;&#1109;|&#1065;&#1087;&#1111;&#1029;/','&#1067;&#1034;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;/','&#1065;&#1027;', $value);
        $value = preg_replace('/&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;|&#1087;&#1087;&#1111;&#1029;&#1087;&#1111;&#1029;/','&#1065;&#1087;&#1111;&#1029;', $value);
        return $value;

    }
}
if ( ! function_exists('tvInfo') ) {
    function tvInfo( $year,$old )
    {
        if($old == 0){
            $old = 0;//tizen
        }
        elseif($old == 1){
            $old=1;//orsay
        }
        elseif($old == 2){
            $old = 2;//samsung mobile
        }
        elseif($old == 3){
            $old = 3;
        }

        elseif($old == 4){
            $old = 4;//sam
        }
        elseif($old == 5){
            $old = 5;//sam mobile
        }
        $result = [];
        $result[0] = $year;
        $result[1]= $old;
        return $result;

    }
}


