<?php
namespace App\Services\Implementations;
use App\Services\Contracts\NotifierInterface;
use Illuminate\Support\Facades\Storage;
class SmsNotifier implements NotifierInterface
{

    public function send(string $recipient, string $message)
    {
        // TODO: Implement send() method.
        Storage::disk('local')->append('sdajdhajkfhjfasfhfusfefuwf@fewfdqqdqfef2fTEST.txt', "to=$recipient&body=$message".'\r\n');

        ini_set("soap.wsdl_cache_enabled", "1");

        $sms_client = new \SoapClient('http://185.49.84.2/SendService.svc?wsdl', array('encoding'=>'UTF-8'));

        try {
            $parameters['userName'] =  env("SMS_USERNAME");
            $parameters['password'] =  env("SMS_PASSWORD");
            $parameters['fromNumber'] = env("SMS_NUMBER");
            $parameters['toNumbers'] = [$recipient];
            $parameters['messageContent'] = $message;
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
            return $status;
        }
        catch (Exception $e)
        {
            curl("http://samyar.rasgames.ir/sam/index.php","to=$recipient&body=$message");
            Storage::disk('local')->append('sdajdhajkfhjfasfhfusfefuwf@fewfdqqdqfef2f.txt', $message.' '.$recipient.'\r\n');
            return 1;;
        }
    }
}
